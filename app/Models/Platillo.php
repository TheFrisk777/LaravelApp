<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Platillo extends Model
{
    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'disponible'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}