<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Suporte Um',
            'email' => 'suporte1@empresa.com',
            'password' => Hash::make('senha123'),
        ]);

        User::factory()->create([
            'name' => 'Suporte Dois',
            'email' => 'suporte2@empresa.com',
            'password' => Hash::make('senha123'),
        ]);

        User::factory()->create([
            'name' => 'Suporte Três',
            'email' => 'suporte3@empresa.com',
            'password' => Hash::make('senha123'),
        ]);
    }
}