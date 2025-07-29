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
        "status"
    ];

    public function user()
    {
        return $this->belongsTo(PenggunaModel::class, 'user_id', 'id');
    }
}
