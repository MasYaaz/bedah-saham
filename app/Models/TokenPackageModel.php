<?php

namespace App\Models;

use CodeIgniter\Model;

class TokenPackageModel extends Model
{
    protected $table = 'token_packages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $allowedFields = ['package_name', 'token_amount', 'price'];

    protected $useTimestamps = true;
}