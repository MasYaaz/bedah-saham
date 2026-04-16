<?php

namespace App\Models;

use CodeIgniter\Model;

class StockDataModel extends Model
{
    protected $table = 'stock_data';
    protected $primaryKey = 'id';

    // Daftarkan semua kolom baru agar bisa di-insert/update
    protected $allowedFields = [
        'emiten_id',
        'last_price',
        'previous_close',
        'day_high',
        'day_low',
        'market_cap',
        'pbv',
        'dividend_yield',
        'beta',
        'employees',
        'price_updated_at',
        'fundamental_updated_at'
    ];

    // Dates - Aktifkan otomatisasi timestamp bawaan CI4 untuk audit record
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';

    /**
     * Mengambil ringkasan stok untuk halaman dashboard utama.
     * Kita urutkan berdasarkan harga yang paling baru di-update.
     */
    public function getStockSummary()
    {
        return $this->select('emiten.code, emiten.name, emiten.sector, emiten.image, stock_data.*')
            ->join('emiten', 'emiten.id = stock_data.emiten_id')
            ->orderBy('stock_data.price_updated_at', 'DESC')
            ->findAll();
    }

    /**
     * Mempermudah pencarian data stok berdasarkan ID emiten
     */
    public function getByEmitenId($emitenId)
    {
        return $this->where('emiten_id', $emitenId)->first();
    }
}