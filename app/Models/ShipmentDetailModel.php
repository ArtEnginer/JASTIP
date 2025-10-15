<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentDetailModel extends Model
{
    protected $table = 'shipment_details';

    protected $fillable = [
        'id',
        'shipment_id',
        'jastip_id',
        'created_at',
        'updated_at'
    ];

    // Relasi ke shipment
    public function shipment()
    {
        return $this->belongsTo(ShipmentModel::class, 'shipment_id', 'id');
    }

    // Relasi ke jastip
    public function jastip()
    {
        return $this->belongsTo(JastipModel::class, 'jastip_id', 'id');
    }
}
