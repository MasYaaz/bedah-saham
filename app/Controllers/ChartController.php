<?php
namespace App\Controllers;

use App\Models\EmitenModel;
use App\Models\StockDataModel;

class ChartController extends BaseController
{
    public function get_history($symbol, $range = '1d')
    {
        // 1. Set Timezone di awal agar sinkron
        date_default_timezone_set('Asia/Jakarta');

        $ticker = ($symbol === 'IHSG') ? '^JKSE' : strtoupper($symbol) . ".JK";
        $cacheKey = "stock_history_{$symbol}_{$range}";
        $today = date('Y-m-d'); // Tanggal hari ini di Jakarta

        // Hapus baris cache ini saat testing agar perubahan kode langsung terasa
        if ($cachedData = cache($cacheKey)) {
            return $this->response->setJSON($cachedData);
        }

        $end = time();
        // Tentukan rentang waktu & interval
        // Tambahkan padding sekitar 20-30 unit data ekstra di setiap range
        switch ($range) {
            case '1d':
                // Untuk 1D, kita tarik 5 hari agar 20 titik pertama (5m) terpenuhi
                $start = strtotime('-5 days', $end);
                $interval = '5m';
                break;
            case '1w':
                // Untuk 1W, tarik 20 hari agar SMA 20 (30m) punya data awal
                $start = strtotime('-20 days', $end);
                $interval = '30m';
                break;
            case '1m':
                // Tarik 60 hari agar data 1 bulan (1d) terhitung penuh sejak awal
                $start = strtotime('-60 days', $end);
                $interval = '1d';
                break;
            case '6m':
                $start = strtotime('-210 days', $end);
                $interval = '1d';
                break;
            default:
                $start = strtotime('-400 days', $end);
                $interval = '1d';
                break;
        }

        $url = "https://query1.finance.yahoo.com/v8/finance/chart/{$ticker}?period1={$start}&period2={$end}&interval={$interval}";
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->request('GET', $url, [
                'headers' => ['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/122.0.0.0 Safari/537.36'],
                'verify' => false,
                'timeout' => 15
            ]);

            $body = json_decode($response->getBody(), true);
            $result = $body['chart']['result'][0] ?? null;

            if (!$result || !isset($result['timestamp'])) {
                return $this->response->setJSON(['labels' => [], 'prices' => [], 'msg' => 'Data tidak tersedia']);
            }

            // Ambil penutupan terakhir & tanggal data terakhir
            $timestamps = $result['timestamp'];
            $prices = $result['indicators']['quote'][0]['close'] ?? [];

            $allData = [];
            foreach ($timestamps as $key => $ts) {
                if (isset($prices[$key]) && $prices[$key] !== null) {
                    $allData[] = [
                        'ts' => (int) $ts,
                        'price' => (float) $prices[$key],
                        'date_key' => date('Y-m-d', $ts)
                    ];
                }
            }

            if (empty($allData))
                return $this->response->setJSON(['labels' => [], 'prices' => []]);

            $latestEntry = end($allData);
            $latestDateInData = $latestEntry['date_key'];
            $lastPrice = (float) $latestEntry['price'];

            // Filter label dan harga
            $cleanLabels = [];
            $cleanPrices = [];
            if ($range == '1d') {
                foreach ($allData as $data) {
                    if ($data['date_key'] === $latestDateInData) {
                        $cleanLabels[] = date('H:i', $data['ts']);
                        $cleanPrices[] = $data['price'];
                    }
                }
            } else {
                foreach ($allData as $data) {
                    $cleanLabels[] = ($range == '1w') ? date('d M H:i', $data['ts']) : date('d M y', $data['ts']);
                    $cleanPrices[] = $data['price'];
                }
            }

            // LOGIKA PENENTUAN REFERENCE
            $stockDataModel = new StockDataModel();
            $emitenModel = new EmitenModel();
            $referencePrice = 0;

            if ($range == '1d') {
                // CEK APAKAH MARKET LIBUR (Tanggal data terakhir bukan hari ini)
                if ($latestDateInData !== $today) {
                    // MARKET LIBUR: Reference disamakan dengan Last Price agar Change = 0
                    $referencePrice = $lastPrice;
                } else {
                    // MARKET BUKA: Ambil Previous Close dari DB
                    $emiten = $emitenModel->where('code', $symbol)->first();
                    $prevCloseFromDb = 0;
                    if ($emiten) {
                        $stock = $stockDataModel->where('emiten_id', $emiten['id'])->first();
                        $prevCloseFromDb = (float) ($stock['previous_close'] ?? 0);
                    }
                    $referencePrice = ($prevCloseFromDb > 0) ? $prevCloseFromDb : (float) $cleanPrices[0];
                }
            } else {
                // RANGE 1W, 1M, dst: Pakai harga pertama di dataset
                $referencePrice = (float) $cleanPrices[0];
            }

            $change = $lastPrice - $referencePrice;
            $changePct = ($referencePrice > 0) ? ($change / $referencePrice) * 100 : 0;

            $finalResponse = [
                'symbol' => $symbol,
                'last_price' => $lastPrice,
                'reference_price' => $referencePrice,
                'change_raw' => round($change, 2),
                'change_pct' => round($changePct, 2),
                'last_date' => $latestDateInData,
                'today_server' => $today, // Untuk debug
                'is_market_open' => ($latestDateInData === $today),
                'labels' => $cleanLabels,
                'prices' => $cleanPrices,
            ];

            cache()->save($cacheKey, $finalResponse, 120);
            return $this->response->setJSON($finalResponse);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
        }
    }
}