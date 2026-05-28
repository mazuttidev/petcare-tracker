<?php

namespace Database\Seeders;

use App\Models\Pet;
use App\Models\Pesagem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PetSeeder extends Seeder
{
    public function run(): void
    {
        // Limpeza própria: necessária ao rodar db:seed sem UserSeeder antes
        // (quando UserSeeder é chamado primeiro no DatabaseSeeder, já limpou tudo)
        Pesagem::query()->delete();
        Pet::withTrashed()->forceDelete();

        $admin = User::where('email', 'admin@petcare.test')->firstOrFail();
        $maria = User::where('email', 'maria@petcare.test')->firstOrFail();

        // ── Pets do Admin (admin@petcare.test) ─────────────────────

        // Rex: Labrador, macão clássico
        $rex = $admin->pets()->create([
            'nome'            => 'Rex',
            'especie'         => 'Cão',
            'raca'            => 'Labrador Retriever',
            'data_nascimento' => Carbon::now()->subYears(4)->subMonths(2)->toDateString(),
            'sexo'            => 'Macho',
            'castrado'        => true,
            'peso_atual'      => 32.40,
            'cor'             => 'Caramelo',
            'microchip'       => '985141002512345',
            'observacoes'     => 'Alérgico a frango. Ração premium 2× ao dia. Adora nadar.',
            'status'          => 'Ativo',
        ]);

        $this->inserirPesagens($rex, [
            [-7, 30.80, 'Clínica'],
            [-6, 31.00, 'Manual'],
            [-5, 31.20, 'Balança'],
            [-4, 31.50, 'Manual'],
            [-3, 31.80, 'Clínica'],
            [-2, 32.10, 'Manual'],
            [-1, 32.30, 'Balança'],
            [ 0, 32.40, 'Manual'],
        ]);

        // Mel: Gata SRD, peso oscilou e estabilizou
        $mel = $admin->pets()->create([
            'nome'            => 'Mel',
            'especie'         => 'Gato',
            'raca'            => 'SRD',
            'data_nascimento' => Carbon::now()->subYears(2)->subMonths(1)->toDateString(),
            'sexo'            => 'Fêmea',
            'castrado'        => true,
            'peso_atual'      => 4.60,
            'cor'             => 'Laranja e branco',
            'microchip'       => null,
            'observacoes'     => 'Castrada aos 8 meses. Gosta de altura — tem andaimes em casa.',
            'status'          => 'Ativo',
        ]);

        $this->inserirPesagens($mel, [
            [-6, 3.80, 'Clínica'],
            [-5, 4.10, 'Manual'],
            [-4, 4.50, 'Balança'],
            [-3, 4.80, 'Manual'],
            [-2, 4.70, 'Clínica'],
            [-1, 4.60, 'Manual'],
            [ 0, 4.60, 'Balança'],
        ]);

        // ── Pets da Maria (maria@petcare.test) ─────────────────────

        // Tobi: Vira-lata veterano, perdeu peso com dieta
        $tobi = $maria->pets()->create([
            'nome'            => 'Tobi',
            'especie'         => 'Cão',
            'raca'            => 'Vira-lata',
            'data_nascimento' => Carbon::now()->subYears(7)->subMonths(5)->toDateString(),
            'sexo'            => 'Macho',
            'castrado'        => false,
            'peso_atual'      => 12.20,
            'cor'             => 'Preto e branco',
            'microchip'       => null,
            'observacoes'     => 'Adotado da rua em 2021. Tem displasia leve no quadril — evitar escadas.',
            'status'          => 'Ativo',
        ]);

        $this->inserirPesagens($tobi, [
            [-5, 13.80, 'Clínica'],
            [-4, 13.50, 'Manual'],
            [-3, 13.10, 'Balança'],
            [-2, 12.70, 'Manual'],
            [-1, 12.40, 'Clínica'],
            [ 0, 12.20, 'Balança'],
        ]);

        // Pipoca: Gata Persa jovem, sem histórico de pesagens ainda
        $pipoca = $maria->pets()->create([
            'nome'            => 'Pipoca',
            'especie'         => 'Gato',
            'raca'            => 'Persa',
            'data_nascimento' => Carbon::now()->subMonths(8)->toDateString(),
            'sexo'            => 'Fêmea',
            'castrado'        => false,
            'peso_atual'      => 2.80,
            'cor'             => 'Branco',
            'microchip'       => null,
            'observacoes'     => 'Filhote resgatado. Primeira consulta veterinária em 15/06.',
            'status'          => 'Ativo',
        ]);

        $this->inserirPesagens($pipoca, [
            [-2, 2.30, 'Clínica'],
            [-1, 2.60, 'Manual'],
            [ 0, 2.80, 'Clínica'],
        ]);
    }

    /**
     * Insere pesagens a partir de um array [meses_atrás, peso_kg, fonte].
     * meses_atrás = 0 significa hoje; negativo = N meses atrás.
     */
    private function inserirPesagens(Pet $pet, array $entradas): void
    {
        foreach ($entradas as [$meses, $peso, $fonte]) {
            $data = $meses === 0
                ? Carbon::today()
                : Carbon::today()->subMonths(abs($meses));

            $pet->pesagens()->create([
                'data'    => $data->toDateString(),
                'peso_kg' => $peso,
                'fonte'   => $fonte,
            ]);
        }

        // Sincroniza peso_atual com a pesagem mais recente
        $pet->update(['peso_atual' => $pet->pesagens()->latest('data')->value('peso_kg')]);
    }
}
