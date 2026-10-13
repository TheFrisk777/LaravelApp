<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected $table = 'ordenes';
    
    protected $fillable = [
        'mesa_id',
        'user_id',
        'estado',
        'total'
    ];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detalles()
    {
        return $this->hasMany(OrdenDetalle::class);
    }
}