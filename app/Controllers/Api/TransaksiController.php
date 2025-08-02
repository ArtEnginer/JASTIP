<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApi;
use App\Models\DetailTransaksiModel;
use App\Models\ProdukModel;
use App\Models\TransaksiModel;

class TransaksiController extends BaseApi
{
    protected $modelName = TransaksiModel::class;
    protected $load = ['user'];

    public function saveTransaksi()
    {

        $data = $this->request->getJSON(true);

        $user_id = auth()->user()->id;
        $dataTransaksi['user_id'] = $user_id;
        $dataTransaksi['status'] = 'pending'; // Set default status to pending
        $dataTransaksi['total_harga'] = $data['total'] ?? null;
        $dataTransaksi['nama_penerima'] = $data['customerName'] ?? null;
        $dataTransaksi['alamat_penerima'] = $data['shippingAddress'] ?? null;
        $dataTransaksi['email_penerima'] = $data['customerEmail'] ?? null;
        $dataTransaksi['nomor_telepon_penerima'] = $data['customerPhone'] ?? null;

        // created_at and updated_at will be handled by Eloquent automatically
        $dataTransaksi['created_at'] = Date('Y-m-d H:i:s');
        $dataTransaksi['updated_at'] = Date('Y-m-d H:i:s');

        // Mengambil ID transaksi yang baru dibuat
        $transaksiId = TransaksiModel::insertGetId($dataTransaksi);

        // Menyimpan items ke detail transaksi
        foreach ($data['items'] as $item) {
            $itemData = [
                'transaksi_id' => $transaksiId,
                'produk_id' => $item['productId'],
                'jumlah' => $item['quantity'],
                'harga' => $item['price'],
            ];
            DetailTransaksiModel::insert($itemData);
        }
        // Mengurangi stok produk
        foreach ($data['items'] as $item) {
            $produk = ProdukModel::find($item['productId']);
            if ($produk) {
                $produk->stok -= $item['quantity'];
                $produk->save();
            }
        }
        return $this->respondCreated([
            'message' => 'Transaksi berhasil dibuat',
            'transaksi_id' => $transaksiId,
        ]);
    }
}
