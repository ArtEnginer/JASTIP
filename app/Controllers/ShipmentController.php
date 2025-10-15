<?php

namespace App\Controllers;

use App\Models\ShipmentModel;

class ShipmentController extends BaseController
{
    /**
     * Display shipment management page
     */
    public function index()
    {
        return view('pages/panel/admin/shipment');
    }

    /**
     * Display delivery note (surat jalan)
     */
    public function deliveryNote($id)
    {
        $shipment = ShipmentModel::with(['packages'])->find($id);

        if (!$shipment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pengiriman tidak ditemukan');
        }

        $packages = $shipment->getPackagesWithDetails();
        $totalBiaya = $packages->sum('biaya');

        $data = [
            'shipment' => $shipment,
            'packages' => $packages,
            'totalBiaya' => $totalBiaya,
        ];

        return view('shipment/delivery_note', $data);
    }
}
