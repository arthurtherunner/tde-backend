<?php

namespace Database\Seeders;

use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // criando admin
        User::create([
            'nome' => 'Admin',
            'email' => 'admin@unifan.com',
            'senha' => Hash::make('12345678'),
            'tipo' => 'admin',
        ]);

        // criando um autor de exemplo
        User::create([
            'nome' => 'Professor Exemplo',
            'email' => 'autor@unifan.com',
            'senha' => Hash::make('12345678'),
            'tipo' => 'autor',
        ]);
    }
}