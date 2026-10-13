<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenDetalle extends Model
{
    protected $table = 'orden_detalles';
    
    protected $fillable = [
        'orden_id',
        'platillo_id',
        'cantidad',
        'precio',
        'notas'
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class);
    }

    public function platillo()
    {
        return $this->belongsTo(Platillo::class);
    }
}