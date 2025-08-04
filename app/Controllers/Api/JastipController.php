<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApi;
use App\Models\JastipModel;

class JastipController extends BaseApi
{
    protected $modelName = JastipModel::class;
    protected $load = ['details'];

    public function validateCreate(&$request)
    {
        return $this->validate([
            'keterangan' => 'required',
        ]);
    }
}
