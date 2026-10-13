<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Desayunos',
                'descripcion' => 'Platillos para desayuno',
            ],
            [
                'nombre' => 'Comidas',
                'descripcion' => 'Platillos principales',
            ],
            [
                'nombre' => 'Bebidas',
                'descripcion' => 'Bebidas frías y calientes',
            ],
            [
                'nombre' => 'Postres',
                'descripcion' => 'Postres y alimentos dulces',
            ]
        ];

        foreach ($categorias as $categoria) {
            Categoria::updateOrCreate(
                ['nombre' => $categoria['nombre']],
                [
                    'descripcion' => $categoria['descripcion'],
                    'activo' => true
                ]
            );
        }
    }
}