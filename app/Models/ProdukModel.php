<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukModel extends Model
{
    protected $table = 'produk';
    protected $fillable = [
        "id",
        "kode",
        "nama",
        "deskripsi",
        "gambar",
        "harga",
        "stok",
        "kategori_kode"

    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_kode', 'kode');
    }
}
