<?php

namespace Database\Seeders;

use App\Models\Pesagem;
use App\Models\Pet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PetSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa os dados anteriores para garantir idempotência
        Pesagem::query()->delete();
        Pet::withTrashed()->forceDelete();

        // ── Pet 1: Rex — Labrador, macão clássico ─────────────────
        $rex = Pet::create([
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

        // 8 pesagens mensais — leve ganho de peso nos últimos meses
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

        // ── Pet 2: Mel — Gata SRD, peso oscilou e estabilizou ─────
        $mel = Pet::create([
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

        // 7 pesagens — ganhou peso pós-castração, depois estabilizou
        $this->inserirPesagens($mel, [
            [-6, 3.80, 'Clínica'],
            [-5, 4.10, 'Manual'],
            [-4, 4.50, 'Balança'],
            [-3, 4.80, 'Manual'],
            [-2, 4.70, 'Clínica'],
            [-1, 4.60, 'Manual'],
            [ 0, 4.60, 'Balança'],
        ]);

        // ── Pet 3: Tobi — Vira-lata, veterano, perdeu peso com dieta
        $tobi = Pet::create([
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

        // 6 pesagens — estava acima do peso, colocado em dieta
        $this->inserirPesagens($tobi, [
            [-5, 13.80, 'Clínica'],
            [-4, 13.50, 'Manual'],
            [-3, 13.10, 'Balança'],
            [-2, 12.70, 'Manual'],
            [-1, 12.40, 'Clínica'],
            [ 0, 12.20, 'Balança'],
        ]);
    }

    /**
     * Insere pesagens a partir de um array [meses_atrás, peso_kg, fonte].
     * meses_atrás = 0 significa hoje; -1 = 1 mês atrás, etc.
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
