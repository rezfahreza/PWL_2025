<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Penjualan 1
            ['detail_id' => 1, 'penjualan_id' => 1, 'barang_id' => 1, 'harga' => 8000000, 'jumlah' => 1], // Laptop Asus
            ['detail_id' => 2, 'penjualan_id' => 1, 'barang_id' => 2, 'harga' => 5500000, 'jumlah' => 1], // Smart TV Samsung
            ['detail_id' => 3, 'penjualan_id' => 1, 'barang_id' => 3, 'harga' => 75000, 'jumlah' => 2], // Kaos Polo

            // Penjualan 2
            ['detail_id' => 4, 'penjualan_id' => 2, 'barang_id' => 4, 'harga' => 150000, 'jumlah' => 1], // Celana Jeans
            ['detail_id' => 5, 'penjualan_id' => 2, 'barang_id' => 5, 'harga' => 3000, 'jumlah' => 5], // Indomie Goreng
            ['detail_id' => 6, 'penjualan_id' => 2, 'barang_id' => 6, 'harga' => 12000, 'jumlah' => 3], // Susu UHT

            // Penjualan 3
            ['detail_id' => 7, 'penjualan_id' => 3, 'barang_id' => 7, 'harga' => 350000, 'jumlah' => 1], // Meja Kayu
            ['detail_id' => 8, 'penjualan_id' => 3, 'barang_id' => 8, 'harga' => 800000, 'jumlah' => 1], // Lemari Pakaian
            ['detail_id' => 9, 'penjualan_id' => 3, 'barang_id' => 9, 'harga' => 150000, 'jumlah' => 1], // Bola Futsal

            // Penjualan 4
            ['detail_id' => 10, 'penjualan_id' => 4, 'barang_id' => 10, 'harga' => 350000, 'jumlah' => 1], // Sepatu Lari
            ['detail_id' => 11, 'penjualan_id' => 4, 'barang_id' => 1, 'harga' => 8000000, 'jumlah' => 1], // Laptop Asus
            ['detail_id' => 12, 'penjualan_id' => 4, 'barang_id' => 3, 'harga' => 75000, 'jumlah' => 2], // Kaos Polo

            // Penjualan 5
            ['detail_id' => 13, 'penjualan_id' => 5, 'barang_id' => 2, 'harga' => 5500000, 'jumlah' => 1], // Smart TV Samsung
            ['detail_id' => 14, 'penjualan_id' => 5, 'barang_id' => 4, 'harga' => 150000, 'jumlah' => 2], // Celana Jeans
            ['detail_id' => 15, 'penjualan_id' => 5, 'barang_id' => 5, 'harga' => 3000, 'jumlah' => 10], // Indomie Goreng

            // Penjualan 6
            ['detail_id' => 16, 'penjualan_id' => 6, 'barang_id' => 6, 'harga' => 12000, 'jumlah' => 5], // Susu UHT
            ['detail_id' => 17, 'penjualan_id' => 6, 'barang_id' => 7, 'harga' => 350000, 'jumlah' => 1], // Meja Kayu
            ['detail_id' => 18, 'penjualan_id' => 6, 'barang_id' => 8, 'harga' => 800000, 'jumlah' => 2], // Lemari Pakaian

            // Penjualan 7
            ['detail_id' => 19, 'penjualan_id' => 7, 'barang_id' => 9, 'harga' => 150000, 'jumlah' => 1], // Bola Futsal
            ['detail_id' => 20, 'penjualan_id' => 7, 'barang_id' => 10, 'harga' => 350000, 'jumlah' => 2], // Sepatu Lari
            ['detail_id' => 21, 'penjualan_id' => 7, 'barang_id' => 1, 'harga' => 8000000, 'jumlah' => 1], // Laptop Asus

            // Penjualan 8
            ['detail_id' => 22, 'penjualan_id' => 8, 'barang_id' => 3, 'harga' => 75000, 'jumlah' => 3], // Kaos Polo
            ['detail_id' => 23, 'penjualan_id' => 8, 'barang_id' => 5, 'harga' => 3000, 'jumlah' => 7], // Indomie Goreng
            ['detail_id' => 24, 'penjualan_id' => 8, 'barang_id' => 6, 'harga' => 12000, 'jumlah' => 2], // Susu UHT

            // Penjualan 9
            ['detail_id' => 25, 'penjualan_id' => 9, 'barang_id' => 7, 'harga' => 350000, 'jumlah' => 2], // Meja Kayu
            ['detail_id' => 26, 'penjualan_id' => 9, 'barang_id' => 8, 'harga' => 800000, 'jumlah' => 1], // Lemari Pakaian
            ['detail_id' => 27, 'penjualan_id' => 9, 'barang_id' => 9, 'harga' => 150000, 'jumlah' => 1], // Bola Futsal

            // Penjualan 10
            ['detail_id' => 28, 'penjualan_id' => 10, 'barang_id' => 10, 'harga' => 350000, 'jumlah' => 2], // Sepatu Lari
            ['detail_id' => 29, 'penjualan_id' => 10, 'barang_id' => 1, 'harga' => 8000000, 'jumlah' => 1], // Laptop Asus
            ['detail_id' => 30, 'penjualan_id' => 10, 'barang_id' => 2, 'harga' => 5500000, 'jumlah' => 1], // Smart TV Samsung
        ];

        DB::table('t_penjualan_detail')->insert($data);
    }
}
