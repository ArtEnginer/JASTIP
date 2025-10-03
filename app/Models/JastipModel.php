<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JastipModel extends Model
{
    protected $table = 'jastip';
    protected $fillable = [
        "id",
        "nomor_resi",
        "nama_penerima",
        "alamat_penerima",
        "no_telp_penerima",
        "biaya",
        "bobot",
        "keterangan",
        "catatan",
        "status",
        "created_at",
        "updated_at"
    ];

    // status history relation
    public function statusHistory()
    {
        return $this->hasMany(StatusModel::class, 'jastip_id', 'id');
    }
}
