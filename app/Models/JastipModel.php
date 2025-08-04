<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JastipModel extends Model
{
    protected $table = 'jastip';
    protected $fillable = [
        "id",
        "user_id",
        "keterangan",
        "catatan", // diisi oleh admin
        "status",
        "created_at",
        "updated_at"
    ];


    public function user()
    {
        return $this->belongsTo(PenggunaModel::class, 'user_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(JastipDetailModel::class, 'jastip_id', 'id');
    }
}
