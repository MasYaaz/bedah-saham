<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TokenPackageSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'package_name' => 'Paket Retail (Starter)',
                'token_amount' => 10,
                'price' => 15000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'package_name' => 'Paket Investor (Best Value)',
                'token_amount' => 50,
                'price' => 50000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'package_name' => 'Paket Institusi (Pro)',
                'token_amount' => 200,
                'price' => 150000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Masukkan data ke tabel token_packages
        $this->db->table('token_packages')->insertBatch($data);
    }
}