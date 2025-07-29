<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApi;
use App\Models\TransaksiModel;

class TransaksiController extends BaseApi
{
    protected $modelName = TransaksiModel::class;
    protected $load = ['produk', 'user'];
}
