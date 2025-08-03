<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApi;
use App\Models\DetailTransaksiModel;
use App\Models\ProdukModel;
use App\Models\TransaksiModel;
use Illuminate\Support\Facades\DB;

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
        $dataTransaksi['order_id'] = $data['orderNumber'] ?? '';
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

    public function midtransToken()
    {
        // First install Midtrans PHP library via Composer:
        // composer require midtrans/midtrans-php

        // Load Midtrans configuration
        \Midtrans\Config::$serverKey = 'SB-Mid-server-6dbkHjrTRxKMtO62L_WDUcrn';
        \Midtrans\Config::$isProduction = false; // Set to true if in production environment
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        try {
            $request = $this->request->getJSON(true);

            // Di method midtransToken()
            $transactionDetails = [
                'order_id' => $request['transaction_details']['order_id'],
                'gross_amount' => $request['transaction_details']['gross_amount']
            ];
            // Prepare customer details
            $customerDetails = [
                'first_name' => $request['customer_details']['first_name'],
                'email' => $request['customer_details']['email'],
                'phone' => $request['customer_details']['phone']
            ];

            // Prepare item details
            $itemDetails = [];
            foreach ($request['item_details'] as $item) {
                $itemDetails[] = [
                    'id' => $item['id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'name' => $item['name']
                ];
            }

            // Prepare payment method if specified
            $enablePayments = [];
            if (isset($request['enabled_payments'])) {
                $enablePayments = $request['enabled_payments'];
            }

            // Generate Snap token
            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'enabled_payments' => $enablePayments
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            return $this->respond([
                'token' => $snapToken,
                'redirect_url' => \Midtrans\Snap::getSnapUrl($params)
            ]);
        } catch (\Exception $e) {
            return $this->failServerError('Gagal membuat token pembayaran: ' . $e->getMessage());
        }
    }

    public function midtransNotification()
    {
        // Load Midtrans configuration
        \Midtrans\Config::$serverKey = 'SB-Mid-server-6dbkHjrTRxKMtO62L_WDUcrn';
        \Midtrans\Config::$isProduction = false; // Set to true if in production environment

        try {
            $notification = new \Midtrans\Notification();

            $transaction = $notification->transaction_status;
            $orderId = $notification->order_id;
            $fraud = $notification->fraud_status;



            // Update status based on notification
            $status = '';
            if ($transaction == 'capture') {
                if ($fraud == 'challenge') {
                    $status = 'challenge';
                } else if ($fraud == 'accept') {
                    $status = 'success';
                }
            } else if ($transaction == 'settlement') {
                $status = 'success';
            } else if (
                $transaction == 'cancel' ||
                $transaction == 'deny' ||
                $transaction == 'expire'
            ) {
                $status = 'failed';
            } else if ($transaction == 'pending') {
                $status = 'pending';
            }

            if (!empty($status)) {
                // Update the transaction status in the database
                $transaksi = TransaksiModel::where('order_id', $orderId)->first();
                if ($transaksi) {
                    $transaksi->status = $status;
                    $transaksi->updated_at = date('Y-m-d H:i:s');
                    $transaksi->save();
                } else {
                    return $this->failNotFound('Transaksi tidak ditemukan');
                }

                // You might want to add additional logic here, like:
                // - Send email notification
                // - Update stock if payment failed
                // - Log the transaction
            }

            return $this->respond(['message' => 'Notifikasi berhasil diproses']);
        } catch (\Exception $e) {
            return $this->failServerError('Gagal memproses notifikasi: ' . $e->getMessage());
        }
    }
}
