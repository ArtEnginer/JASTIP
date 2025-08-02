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
        $dataTransaksi['status'] = 'pending';
        $dataTransaksi['total_harga'] = $data['total'] ?? null;
        $dataTransaksi['nama_penerima'] = $data['customerName'] ?? null;
        $dataTransaksi['alamat_penerima'] = $data['shippingAddress'] ?? null;
        $dataTransaksi['email_penerima'] = $data['customerEmail'] ?? null;
        $dataTransaksi['nomor_telepon_penerima'] = $data['customerPhone'] ?? null;
        $dataTransaksi['created_at'] = Date('Y-m-d H:i:s');
        $dataTransaksi['updated_at'] = Date('Y-m-d H:i:s');

        $transaksiId = TransaksiModel::insertGetId($dataTransaksi);

        foreach ($data['items'] as $item) {
            $itemData = [
                'transaksi_id' => $transaksiId,
                'produk_id' => $item['productId'],
                'jumlah' => $item['quantity'],
                'harga' => $item['price'],
            ];
            DetailTransaksiModel::insert($itemData);
        }

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

    public function updateStatus()
    {

        $data = $this->request->getPost();
        $transaksi = TransaksiModel::find($data['id']);
        if (!$transaksi) {
            return $this->failNotFound('Transaksi tidak ditemukan');
        }
        $status = $data['status'] ?? null;
        if (!$status) {
            return $this->failValidationError('Status tidak boleh kosong');
        }
        $transaksi->status = $status;
        $transaksi->updated_at = date('Y-m-d H:i:s');
        if ($transaksi->save()) {
            return $this->respond([
                'message' => 'Status transaksi berhasil diperbarui',
                'transaksi' => $transaksi,
            ]);
        }
        return $this->fail('Gagal memperbarui status transaksi');
    }
}
