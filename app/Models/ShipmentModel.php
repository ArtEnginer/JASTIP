<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentModel extends Model
{
    protected $table = 'shipments';

    protected $fillable = [
        'id',
        'nomor_kontainer',
        'nama_kontainer',
        'tanggal_pengiriman',
        'estimasi_sampai',
        'status_pengiriman',
        'total_paket',
        'total_bobot',
        'keterangan',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'tanggal_pengiriman' => 'datetime',
        'estimasi_sampai' => 'datetime',
        'total_bobot' => 'decimal:2',
    ];

    // Relasi ke shipment details
    public function shipmentDetails()
    {
        return $this->hasMany(ShipmentDetailModel::class, 'shipment_id', 'id');
    }

    // Relasi ke paket jastip melalui shipment details
    public function packages()
    {
        return $this->belongsToMany(
            JastipModel::class,
            'shipment_details',
            'shipment_id',
            'jastip_id'
        )->withTimestamps();
    }

    // Get all packages with details
    public function getPackagesWithDetails()
    {
        return $this->packages()
            ->select([
                'jastip.id',
                'jastip.nomor_resi',
                'jastip.nama_penerima',
                'jastip.alamat_penerima',
                'jastip.no_telp_penerima',
                'jastip.bobot',
                'jastip.biaya',
                'jastip.keterangan',
                'jastip.status'
            ])
            ->get();
    }
}
