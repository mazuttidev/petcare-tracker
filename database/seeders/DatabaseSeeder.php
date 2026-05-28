<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ordem importa: UserSeeder limpa tudo e cria usuários antes dos pets
        $this->call([
            UserSeeder::class, // 1. cria os usuários de teste (e limpa dados anteriores)
            PetSeeder::class,  // 2. cria pets e pesagens vinculados a esses usuários
        ]);
    }
}
