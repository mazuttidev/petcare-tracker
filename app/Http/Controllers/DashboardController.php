<?php

namespace App\Http\Controllers;

use App\Models\Pesagem;
use App\Models\Pet;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            // Cartão 1 — contagem
            'totalAtivos'   => Pet::where('status', 'Ativo')->count(),
            'totalPets'     => Pet::count(),
            'contagemPorEspecie' => Pet::where('status', 'Ativo')
                ->selectRaw('especie, COUNT(*) as total')
                ->groupBy('especie')
                ->orderByDesc('total')
                ->pluck('total', 'especie'),

            // Cartão 2 — último pet cadastrado (soft-deleted excluídos automaticamente)
            'ultimoPet'     => Pet::latest()->first(),

            // Cartão 3 — pesagem mais recente (por data de medição, desempate por criação)
            'ultimaPesagem' => Pesagem::with('pet')
                ->orderByDesc('data')
                ->orderByDesc('id')
                ->first(),

            // Estatísticas secundárias
            'totalPesagens' => Pesagem::count(),
        ]);
    }
}
