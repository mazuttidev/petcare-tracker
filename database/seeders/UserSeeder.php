<?php

namespace Database\Seeders;

use App\Models\Pesagem;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa em ordem segura para respeitar as FKs
        // (PetSeeder não precisa mais fazer a própria limpeza)
        Pesagem::query()->delete();
        Pet::withTrashed()->forceDelete();
        User::query()->delete();

        // ── Usuário 1: admin de testes ─────────────────────────────
        User::create([
            'name'     => 'Admin Teste',
            'email'    => 'admin@petcare.test',
            'password' => Hash::make('password'),
        ]);

        // ── Usuário 2: tutora de exemplo ───────────────────────────
        User::create([
            'name'     => 'Maria Tutora',
            'email'    => 'maria@petcare.test',
            'password' => Hash::make('password'),
        ]);
    }
}
