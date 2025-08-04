<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApi;
use App\Models\JastipDetailModel;
use App\Models\JastipModel;

class JastipController extends BaseApi
{
    protected $modelName = JastipModel::class;
    protected $load = ['details', 'user'];

    public function validateCreate(&$request)
    {
        return $this->validate([
            'keterangan' => 'required',
        ]);
    }

    public function addJastip()
    {
        $data = $this->request->getPost();
        if (!$this->validateCreate($request)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $dataJastip['user_id'] = auth()->user()->id; // Assuming user is authenticated
        $dataJastip['status'] = 'pending'; // Default status for new Jastip
        $dataJastip['catatan'] = $dataJastip['catatan'] ?? ''; // Optional field for admin notes
        $dataJastip['created_at'] = date('Y-m-d H:i:s');
        $dataJastip['updated_at'] = date('Y-m-d H:i:s');
        $dataJastip['keterangan'] = $data['keterangan'];
        // uuid

        $jastipId = JastipModel::insertGetId($dataJastip);
        if (!$jastipId) {
            return $this->failServerError('Failed to create Jasa Titip');
        }
        // Handle Jastip Details
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                if (!empty($item['nama_barang']) && !empty($item['jumlah'])) {
                    JastipDetailModel::insert([
                        'jastip_id' => $jastipId,
                        'nama_barang' => $item['nama_barang'],
                        'jumlah' => $item['jumlah'],
                        'keterangan' => $item['keterangan'] ?? '',
                        'status' => 'pending', // Default status for details
                    ]);
                }
            }
        }

        return $this->respondCreated([
            'message' => 'Jasa Titip created successfully',
            'jastip_id' => $jastipId,
        ]);
    }
}
