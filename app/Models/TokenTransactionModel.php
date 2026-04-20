<?php

namespace App\Models;

use CodeIgniter\Model;

class TokenTransactionModel extends Model
{
    protected $table = 'token_transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $allowedFields = ['user_id', 'package_id', 'amount_paid', 'status'];

    protected $useTimestamps = true;

    /**
     * Mendapatkan riwayat transaksi user beserta nama paketnya
     */
    public function getHistory($userId)
    {
        return $this->select('token_transactions.*, token_packages.package_name')
            ->join('token_packages', 'token_packages.id = token_transactions.package_id')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}