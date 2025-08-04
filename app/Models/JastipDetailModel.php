<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JastipDetailModel extends Model
{
    protected $table = 'jastip_detail';
    protected $fillable = [
        "id",
        "jastip_id",
        "nama_barang",
        "jumlah",
        "keterangan",
        "catatan", // diisi oleh admin
        "status",
        "created_at",
        "updated_at"
    ];
}
