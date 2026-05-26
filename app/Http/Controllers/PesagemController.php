<?php

namespace App\Http\Controllers;

use App\Http\Requests\PesagemRequest;
use App\Models\Pesagem;
use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PesagemController extends Controller
{
    // Exibe o formulário de registro de nova pesagem
    public function create(Pet $pet): View
    {
        return view('pesagens.create', compact('pet'));
    }

    // Valida via PesagemRequest (inclui regra de variação > 30%) e persiste
    public function store(PesagemRequest $request, Pet $pet): RedirectResponse
    {
        $pet->pesagens()->create($request->validated());
        $this->sincronizarPesoAtual($pet);

        return redirect()
            ->route('pets.show', $pet)
            ->with('success', 'Pesagem registrada com sucesso!');
    }

    // Exibe o formulário de edição preenchido
    public function edit(Pet $pet, Pesagem $pesagem): View
    {
        // Garante que a pesagem realmente pertence ao pet da rota
        abort_if($pesagem->pet_id !== $pet->id, 404);

        return view('pesagens.edit', compact('pet', 'pesagem'));
    }

    // Valida e salva a pesagem editada
    public function update(PesagemRequest $request, Pet $pet, Pesagem $pesagem): RedirectResponse
    {
        abort_if($pesagem->pet_id !== $pet->id, 404);

        $pesagem->update($request->validated());
        $this->sincronizarPesoAtual($pet);

        return redirect()
            ->route('pets.show', $pet)
            ->with('success', 'Pesagem atualizada com sucesso!');
    }

    // Remove permanentemente a pesagem (sem soft delete na entidade Pesagem)
    public function destroy(Pet $pet, Pesagem $pesagem): RedirectResponse
    {
        abort_if($pesagem->pet_id !== $pet->id, 404);

        $pesagem->delete();
        $this->sincronizarPesoAtual($pet);

        return redirect()
            ->route('pets.show', $pet)
            ->with('success', 'Pesagem de ' . $pesagem->data->format('d/m/Y') . ' removida.');
    }

    /**
     * Mantém pet.peso_atual alinhado com a pesagem mais recente.
     * Chamado após qualquer operação que altere o conjunto de pesagens do pet.
     */
    private function sincronizarPesoAtual(Pet $pet): void
    {
        $peso = $pet->pesagens()->orderByDesc('data')->value('peso_kg');
        $pet->update(['peso_atual' => $peso]); // null quando não há pesagens
    }
}
