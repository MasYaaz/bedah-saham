<?php
namespace App\Controllers;

use App\Models\EmitenModel;
use App\Models\StockDataModel;
use App\Models\StockHistoryModel;

class StockDetailController extends BaseController
{
    public function detail($code)
    {
        $stockDataModel = new StockDataModel();
        $emitenModel = new EmitenModel();
        $historyModel = new StockHistoryModel();
        $analysisModel = new \App\Models\StockAnalysisModel(); // Tambahkan model analisis

        // 1. Ambil data awal dari DB
        // CATATAN: ai_analysis dan last_ai_update sudah dihapus dari select karena pindah tabel
        $stock = $stockDataModel->select('stock_data.*, emiten.code, emiten.name, emiten.sector, emiten.notation, emiten.description, emiten.image')
            ->join('emiten', 'emiten.id = stock_data.emiten_id')
            ->where('emiten.code', $code)
            ->first();

        if (!$stock) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $symbol = $code . ".JK";
        $apiKey = env('FMP_API_KEY');
        $baseUrl = env('FMP_BASE_URL');
        $client = \Config\Services::curlrequest();
        $now = time();
        $needsRefresh = false;

        // --- LOGIKA 1: FUNDAMENTAL (FMP + SCRAPING IPOT) ---
        $lastFundamentalUpdate = strtotime($stock['fundamental_updated_at'] ?? '2000-01-01');

        if (($now - $lastFundamentalUpdate) > 86400) {
            try {
                // A. Fetch FMP (Profile)
                $fmpUrl = "{$baseUrl}/profile?symbol={$symbol}&apikey={$apiKey}";
                $resFmp = $client->request('GET', $fmpUrl, ['verify' => false]);
                $bodyFmp = json_decode($resFmp->getBody(), true);
                $fmpData = $bodyFmp[0] ?? null;

                // B. Fetch IndoPremier Scraping
                $scrapedData = $this->scrapeFundamentalIPOT($code);

                if ($fmpData && $scrapedData) {
                    // Hitung Yield
                    $divYield = ($fmpData['lastDividend'] ?? 0) > 0 && ($fmpData['price'] ?? 0) > 0
                        ? ($fmpData['lastDividend'] / $fmpData['price']) * 100
                        : 0;

                    // Update Emiten (Statis)
                    $emitenModel->update($stock['emiten_id'], [
                        'description' => $fmpData['description'] ?? $stock['description'],
                        'image' => $fmpData['image'] ?? $stock['image']
                    ]);

                    // Update Stock Data (Fundamental)
                    $stockDataModel->update($stock['id'], [
                        'market_cap' => $fmpData['marketCap'] ?? $stock['market_cap'],
                        'beta' => $fmpData['beta'] ?? $stock['beta'],
                        'dividend_yield' => $divYield,
                        'employees' => $fmpData['fullTimeEmployees'] ?? $stock['employees'],
                        'pbv' => $scrapedData['current']['pbv'] ?? null,
                        'per' => $scrapedData['current']['per'] ?? null,
                        'roe' => $scrapedData['current']['roe'] ?? null,
                        'der' => $scrapedData['current']['der'] ?? null,
                        'dividend' => $fmpData['lastDividend'] ?? null,
                        'fundamental_updated_at' => date('Y-m-d H:i:s')
                    ]);

                    // Update Sejarah Multi-Tahun
                    if (isset($scrapedData['history']) && is_array($scrapedData['history'])) {
                        foreach ($scrapedData['history'] as $year => $values) {
                            $historyData = [
                                'emiten_id' => $stock['emiten_id'],
                                'year' => $year,
                                'period' => 'FY',
                                'revenue' => (string) ($values['revenue'] ?? '0'),
                                'net_profit' => (string) ($values['net_profit'] ?? '0'),
                                'eps' => $values['eps'] ?? 0,
                                'roe' => $values['roe'] ?? 0,
                                'der' => $values['der'] ?? 0,
                                'pbv' => $values['pbv'] ?? 0,
                                'per' => $values['per'] ?? 0,
                            ];

                            $existing = $historyModel->where([
                                'emiten_id' => $stock['emiten_id'],
                                'year' => $year,
                                'period' => 'FY'
                            ])->first();

                            $existing ? $historyModel->update($existing['id'], $historyData) : $historyModel->insert($historyData);
                        }
                    }
                    $needsRefresh = true;
                }
            } catch (\Exception $e) {
                log_message('error', "Fundamental Update Error [{$code}]: " . $e->getMessage());
            }
        }

        // 2. Refresh data jika fundamental baru saja di-update
        if ($needsRefresh) {
            $stock = $stockDataModel->select('stock_data.*, emiten.code, emiten.name, emiten.sector, emiten.notation, emiten.description, emiten.image')
                ->join('emiten', 'emiten.id = stock_data.emiten_id')
                ->where('emiten.code', $code)
                ->first();
        }

        // 3. Ambil 5 riwayat tahunan terakhir
        $histories = $historyModel->where('emiten_id', $stock['emiten_id'])
            ->orderBy('year', 'DESC')
            ->findAll(5);

        // 4. Cek Analisis AI Terakhir (Optional: Menampilkan analisis terakhir milik user tersebut)
        $lastAnalysis = null;
        if (session()->get('user_id')) {
            $lastAnalysis = $analysisModel->where('user_id', session()->get('user_id'))
                ->where('ticker', $code)
                ->orderBy('created_at', 'DESC')
                ->first();
        }

        return view('stock_detail', [
            'title' => 'Detail ' . $code,
            'stock' => $stock,
            'histories' => $histories,
            'last_analysis' => $lastAnalysis // Kirim hasil AI ke view
        ]);
    }

    private function scrapeFundamentalIPOT($code)
    {
        $client = \Config\Services::curlrequest();
        $url = "https://www.indopremier.com/module/saham/include/fundamental.php?code=" . strtoupper($code);

        try {
            $response = $client->request('GET', $url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/123.0.0.0',
                ],
                'timeout' => 15,
                'verify' => false
            ]);

            $html = $response->getBody();
            if (empty($html))
                return null;

            $results = ['current' => [], 'history' => []];

            // Definisi pattern: Revenue & Net Profit sekarang menangkap seluruh row <tr>
            $metrics = [
                'pbv' => '/>PBV<\/td>(.*?)<\/tr>/is',
                'per' => '/>PER<\/td>(.*?)<\/tr>/is',
                'roe' => '/>ROE<\/td>(.*?)<\/tr>/is',
                'der' => '/>Debt\/Equity<\/td>(.*?)<\/tr>/is',
                'revenue' => '/>Revenue<\/td>(.*?)<\/tr>/is',
                'net_profit' => '/>Net\.?Profit<\/td>(.*?)<\/tr>/is',
                'eps' => '/>EPS<\/td>(.*?)<\/tr>/is',
            ];

            foreach ($metrics as $key => $pattern) {
                if (preg_match($pattern, $html, $matches)) {
                    $rowHtml = $matches[1];

                    // Tangkap semua angka/string data di dalam <td>
                    preg_match_all('/<td[^>]*>\s*((?:(?!<\/td>).)*)/i', $rowHtml, $values);
                    $dataArr = array_map('trim', $values[1] ?? []);

                    if (count($dataArr) >= 4) {
                        // Logic pemisahan: String untuk lapkeu, Float untuk rasio
                        $isString = in_array($key, ['revenue', 'net_profit']);

                        // 1. Ambil Data Current (Index 0)
                        $results['current'][$key] = $isString
                            ? $dataArr[0]
                            : (float) str_replace(',', '.', $dataArr[0]);

                        // 2. Mapping History (Index 2 ke atas)
                        // Index mapping: [2]=2024, [3]=2023, [4]=2022, [5]=2021, [6]=2020
                        $years = [2024, 2023, 2022, 2021, 2020];
                        foreach ($years as $i => $year) {
                            $dataIdx = $i + 2;
                            if (isset($dataArr[$dataIdx])) {
                                $results['history'][$year][$key] = $isString
                                    ? $dataArr[$dataIdx]
                                    : (float) str_replace(',', '.', $dataArr[$dataIdx]);
                            }
                        }
                    }
                }
            }

            return $results;

        } catch (\Exception $e) {
            log_message('error', "IPOT Scrape Error [{$code}]: " . $e->getMessage());
            return null;
        }
    }
}