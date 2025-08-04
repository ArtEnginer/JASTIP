<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApi;
use App\Models\DetailTransaksiModel;
use App\Models\TransaksiModel;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransController extends BaseApi
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = 'SB-Mid-server-6dbkHjrTRxKMtO62L_WDUcrn'; // Ganti dengan server key sandbox Anda
        Config::$isProduction = false; // Sandbox mode
        Config::$isSanitized = true;
        Config::$is3ds = true; // Aktifkan 3DS (jika diperlukan)
    }

    public function createPayment()
    {
        $data = $this->request->getJSON(true);
        $user = auth()->user();

        $orderId = 'ORDER-' . uniqid();

        $transaksiData = [
            'user_id' => $user->id,
            'status' => 'pending',
            'total_harga' => $data['gross_amount'],
            'nama_penerima' => $data['customer_details']['first_name'] . ' ' . $data['customer_details']['last_name'],
            'alamat_penerima' => $data['customer_details']['shipping_address']['address'] ?? '',
            'email_penerima' => $data['customer_details']['email'],
            'nomor_telepon_penerima' => $data['customer_details']['phone'],
            'order_id' => $orderId,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $transaksiId = TransaksiModel::insertGetId($transaksiData);

        // Buat parameter transaksi Midtrans
        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => $data['gross_amount'],
        ];

        $customerDetails = [
            'first_name' => $data['customer_details']['first_name'],
            'last_name' => $data['customer_details']['last_name'],
            'email' => $data['customer_details']['email'],
            'phone' => $data['customer_details']['phone'],
            'shipping_address' => $data['customer_details']['shipping_address'] ?? null
        ];

        $itemDetails = [];
        foreach ($data['item_details'] as $item) {
            $itemDetails[] = [
                'id' => $item['id'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'name' => $item['name']
            ];
            $itemDetail = [
                'transaksi_id' => $transaksiId,
                'produk_id' => $item['id'],
                'jumlah' => $item['quantity'],
                'harga' => $item['price'],
            ];
            // Simpan detail transaksi
            DetailTransaksiModel::insert($itemDetail);
        }


        $transactionData = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails
        ];

        try {
            $snapToken = Snap::getSnapToken($transactionData);

            return $this->respond([
                'status' => 'success',
                'snap_token' => $snapToken,
                'transaksi_id' => $transaksiId,
                'order_id' => $orderId
            ]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function handleNotification()
    {
        $notification = $this->request->getJSON();

        $transaksi = TransaksiModel::findWhere('order_id', $notification->order_id)->first();

        if (!$transaksi) {
            return $this->failNotFound('Transaksi tidak ditemukan');
        }

        // Update status transaksi berdasarkan notifikasi Midtrans
        $statusMapping = [
            'capture' => 'paid',
            'settlement' => 'paid',
            'pending' => 'pending',
            'deny' => 'failed',
            'expire' => 'expired',
            'cancel' => 'canceled'
        ];

        $status = $statusMapping[$notification->transaction_status] ?? $notification->transaction_status;

        $transaksi->status = $status;
        $transaksi->updated_at = date('Y-m-d H:i:s');
        if (!$transaksi->save()) {
            return $this->fail('Gagal memperbarui status transaksi');
        }

        return $this->respond(['status' => 'success']);
    }
}
