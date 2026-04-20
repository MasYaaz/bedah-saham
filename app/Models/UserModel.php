<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $allowedFields = ['username', 'email', 'password', 'token_balance'];

    protected $useTimestamps = true;

    /**
     * Memotong saldo token user
     */
    public function deductToken($userId, $amount = 1)
    {
        return $this->db->table($this->table)
            ->where('id', $userId)
            ->set('token_balance', 'token_balance - ' . (int) $amount, false)
            ->update();
    }

    /**
     * Menambah saldo token (saat top up)
     */
    public function addToken($userId, $amount)
    {
        return $this->db->table($this->table)
            ->where('id', $userId)
            ->set('token_balance', 'token_balance + ' . (int) $amount, false)
            ->update();
    }
}