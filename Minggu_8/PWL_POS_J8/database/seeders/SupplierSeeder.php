<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_kode' => 'SUP001',
                'supplier_nama' => 'PT. Elektronik Maju',
                'supplier_alamat' => 'Jl. Raya Elektronik No. 12'
            ],
            [
                'supplier_kode' => 'SUP002',
                'supplier_nama' => 'CV. Pakaian Fashion',
                'supplier_alamat' => 'Jl. Pakaian Indah No. 34'
            ],
            [
                'supplier_kode' => 'SUP003',
                'supplier_nama' => 'UD. Makanan Segar',
                'supplier_alamat' => 'Jl. Makanan Lezat No. 56'
            ],
        ];

        DB::table('m_supplier')->insert($data);
    }
}
