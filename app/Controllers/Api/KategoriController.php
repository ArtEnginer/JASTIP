<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApi;
use App\Models\KategoriModel;

class KategoriController extends BaseApi
{
    protected $modelName = KategoriModel::class;
    protected $load = ['produk'];

    public function validateCreate(&$request)
    {
        return $this->validate([
            'nama' => 'required',
            'kode' => 'required|is_unique[kategori.kode]',

        ]);
    }
}
