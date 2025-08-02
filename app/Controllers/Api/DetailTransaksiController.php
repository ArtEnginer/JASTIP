<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApi;

use App\Models\DetailTransaksiModel;

class DetailTransaksiController extends BaseApi
{
    protected $modelName = DetailTransaksiModel::class;
    protected $load = ['transaksi', 'produk'];
}
