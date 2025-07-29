<?php

namespace App\Database\Seeds;

use App\Models\PenggunaModel;
use CodeIgniter\Database\Seeder;

class InitSeeder extends Seeder
{
    public function run()
    {

        PenggunaModel::create([
            'username' => 'admin',
            'name' => 'Admin',
        ])->setEmailIdentity([
            'email' => 'admin@gmail.com',
            'password' => "password",
        ])->addGroup('admin')->activate();
        PenggunaModel::create([
            'username' => 'user',
            'name' => 'user',
        ])->setEmailIdentity([
            'email' => 'user@gmail.com',
            'password' => "password",
        ])->addGroup('user')->activate();


        //   insert data produk
        $this->db->table('kategori')->insertBatch([
            [
                'kode' => 'KTG1',
                'nama' => 'PAKAIAN BAYI',
                'deskripsi' => 'Produk pakaian seperti baju, celana, dll.'
            ],
            [
                'kode' => 'KTG2',
                'nama' => 'PERALATAN BAYI',
                'deskripsi' => 'Produk peralatan seperti stroller, car seat, dll.'
            ],
            [
                'kode' => 'KTG3',
                'nama' => 'MAINAN BAYI',
                'deskripsi' => 'Produk mainan untuk bayi dan anak-anak.'
            ],
            [
                'kode' => 'KTG4',
                'nama' => 'KESEHATAN BAYI',
                'deskripsi' => 'Produk kesehatan seperti vitamin, obat-obatan, dll.'
            ],
        ]);

        $this->db->table('produk')->insertBatch([
            [
                'kode' => 'PRD1',
                'nama' => 'Baju Bayi Lengan Pendek',
                'deskripsi' => 'Baju bayi lengan pendek yang nyaman dan stylish.',
                'gambar' => 'baju_bayi_lengan_pendek.jpg',
                'harga' => 50000,
                'stok' => 100,
                'kategori_kode' => 'KTG1'
            ],
            [
                'kode' => 'PRD2',
                'nama' => 'Stroller Bayi',
                'deskripsi' => 'Stroller bayi yang aman dan mudah digunakan.',
                'gambar' => 'stroller_bayi.jpg',
                'harga' => 1500000,
                'stok' => 50,
                'kategori_kode' => 'KTG2'
            ],
            [
                'kode' => 'PRD3',
                'nama' => 'Mainan Edukasi Bayi',
                'deskripsi' => 'Mainan edukasi yang membantu perkembangan bayi.',
                'gambar' => 'mainan_edukasi_bayi.jpg',
                'harga' => 75000,
                'stok' => 200,
                'kategori_kode' => 'KTG3'
            ],
        ]);
    }
}
