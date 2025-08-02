<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiModel extends Model
{
    protected $table = 'transaksi';
    protected $fillable = [
        "id",
        "user_id",
        "total_harga",
        "status",
        "nama_penerima",
        "alamat_pengiriman",
        "email_penerima",
        'nomor_telepon_penerima',
        "created_at",
        "updated_at"

    ];


    public function user()
    {
        return $this->belongsTo(PenggunaModel::class, 'user_id', 'id');
    }
}
