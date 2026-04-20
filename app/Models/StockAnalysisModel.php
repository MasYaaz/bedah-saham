<?php

namespace App\Models;

use CodeIgniter\Model;

class StockAnalysisModel extends Model
{
    protected $table = 'stock_analyses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $allowedFields = ['user_id', 'ticker', 'analysis_content', 'sentiment', 'token_spent', 'created_at'];

    // Dates
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = ''; // Kita tidak butuh updated_at untuk log

    /**
     * Cek apakah user sudah menganalisis ticker ini dalam 24 jam terakhir
     */
    public function checkRecentAnalysis($userId, $ticker)
    {
        return $this->where('user_id', $userId)
            ->where('ticker', $ticker)
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')))
            ->orderBy('created_at', 'DESC')
            ->first();
    }
}