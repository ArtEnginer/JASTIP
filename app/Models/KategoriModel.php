<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriModel extends Model
{
    protected $table = 'kategori';
    protected $fillable = [
        "id",
        "kode",
        "nama",
        "deskripsi"

    ];

    public function produk()
    {
        return $this->hasMany(ProdukModel::class, 'kategori_kode', 'kode');
    }
}
