<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksiModel extends Model
{
    protected $table = 'detail_transaksi';
    protected $fillable = [
        "id",
        "transaksi_id",
        "produk_id",
        "jumlah",
        "harga",
        "created_at",
        "updated_at"
    ];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiModel::class, 'transaksi_id', 'id');
    }

    public function produk()
    {
        return $this->belongsTo(ProdukModel::class, 'produk_id', 'id');
    }
}
