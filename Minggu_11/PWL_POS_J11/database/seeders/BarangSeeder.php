<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            //Elektronik
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'ELK01', 'barang_nama' => 'Laptop Asus', 'harga_beli' => 7500000, 'harga_jual' => 8000000],
            ['barang_id' => 2, 'kategori_id' => 1, 'barang_kode' => 'ELK02', 'barang_nama' => 'Smart TV Samsung', 'harga_beli' => 5000000, 'harga_jual' => 5500000],

            //Pakaian
            ['barang_id' => 3, 'kategori_id' => 2, 'barang_kode' => 'PKN01', 'barang_nama' => 'Kaos Polo', 'harga_beli' => 50000, 'harga_jual' => 75000],
            ['barang_id' => 4, 'kategori_id' => 2, 'barang_kode' => 'PKN02', 'barang_nama' => 'Celana Jeans', 'harga_beli' => 100000, 'harga_jual' => 150000],

            //Makanan
            ['barang_id' => 5, 'kategori_id' => 3, 'barang_kode' => 'MKN01', 'barang_nama' => 'Indomie Goreng', 'harga_beli' => 2500, 'harga_jual' => 3000],
            ['barang_id' => 6, 'kategori_id' => 3, 'barang_kode' => 'MKN02', 'barang_nama' => 'Susu UHT', 'harga_beli' => 10000, 'harga_jual' => 12000],

            //Perabotan
            ['barang_id' => 7, 'kategori_id' => 4, 'barang_kode' => 'PRB01', 'barang_nama' => 'Meja Kayu', 'harga_beli' => 300000, 'harga_jual' => 350000],
            ['barang_id' => 8, 'kategori_id' => 4, 'barang_kode' => 'PRB02', 'barang_nama' => 'Lemari Pakaian', 'harga_beli' => 750000, 'harga_jual' => 800000],

            //Olahraga
            ['barang_id' => 9, 'kategori_id' => 5, 'barang_kode' => 'OLG01', 'barang_nama' => 'Bola Futsal', 'harga_beli' => 125000, 'harga_jual' => 150000],
            ['barang_id' => 10, 'kategori_id' => 5, 'barang_kode' => 'OLG02', 'barang_nama' => 'Sepatu Lari', 'harga_beli' => 300000, 'harga_jual' => 350000],
        ];

        DB::table('m_barang')->insert($data);
    }
}
