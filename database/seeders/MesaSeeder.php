<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mesa;

class MesaSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Mesa::updateOrCreate(
                ['numero' => $i],
                ['estado' => 'disponible']
            );
        }
    }
}