<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JastipModel extends Model
{
    protected $table = 'jastip';
    protected $fillable = [
        "id",
        "nomor_resi",
        "nama_penerima",
        "alamat_penerima",
        "no_telp_penerima",
        "biaya",
        "bobot",
        "keterangan",
        "catatan",
        "status",
        "shipment_id",
        "estimasi_sampai",
        "created_at",
        "updated_at"
    ];

    protected $casts = [
        'estimasi_sampai' => 'datetime',
        'bobot' => 'decimal:2',
        'biaya' => 'decimal:2',
    ];

    // status history relation
    public function statusHistory()
    {
        return $this->hasMany(StatusModel::class, 'jastip_id', 'id');
    }

    // Relasi ke shipment
    public function shipment()
    {
        return $this->belongsTo(ShipmentModel::class, 'shipment_id', 'id');
    }

    // Relasi ke shipment details
    public function shipmentDetail()
    {
        return $this->hasOne(ShipmentDetailModel::class, 'jastip_id', 'id');
    }
}
