<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('nombre', 'Administrador')->first();
        $mesero = Role::where('nombre', 'Mesero')->first();
        $cocina = Role::where('nombre', 'Cocina')->first();

        User::updateOrCreate(
            ['email' => 'admin@restaurante.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('12345678'),
                'role_id' => $admin->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'mesero@restaurante.com'],
            [
                'name' => 'Mesero',
                'password' => Hash::make('12345678'),
                'role_id' => $mesero->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'cocina@restaurante.com'],
            [
                'name' => 'Cocina',
                'password' => Hash::make('12345678'),
                'role_id' => $cocina->id,
            ]
        );
    }
}