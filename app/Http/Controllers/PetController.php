<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetRequest;
use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PetController extends Controller
{
    // Lista apenas os pets do usuário autenticado
    public function index(): View
    {
        $pets = Auth::user()->pets()->latest()->get();

        return view('pets.index', compact('pets'));
    }

    // Exibe o formulário de cadastro
    public function create(): View
    {
        return view('pets.create');
    }

    /**
     * Valida via PetRequest e persiste o novo pet associado ao usuário logado.
     *
     * Usar pets()->create() em vez de Pet::create() garante que user_id seja
     * preenchido pela relação — sem depender do form nem de $fillable.
     */
    public function store(PetRequest $request): RedirectResponse
    {
        Auth::user()->pets()->create($request->validated());

        return redirect()
            ->route('pets.index')
            ->with('success', 'Pet cadastrado com sucesso!');
    }

    // Exibe o perfil completo de um pet (Policy garante que é o dono)
    public function show(Pet $pet): View
    {
        $this->authorize('view', $pet);

        $pet->load(['pesagens' => fn($q) => $q->orderBy('data')]);

        return view('pets.show', compact('pet'));
    }

    // Exibe o formulário de edição preenchido
    public function edit(Pet $pet): View
    {
        $this->authorize('update', $pet);

        return view('pets.edit', compact('pet'));
    }

    /**
     * Valida via PetRequest (regra unique ignora o pet atual) e salva.
     */
    public function update(PetRequest $request, Pet $pet): RedirectResponse
    {
        $this->authorize('update', $pet);

        $pet->update($request->validated());

        return redirect()
            ->route('pets.show', $pet)
            ->with('success', 'Dados atualizados com sucesso!');
    }

    // Soft delete — deleted_at é preenchido; registro permanece no banco
    public function destroy(Pet $pet): RedirectResponse
    {
        $this->authorize('delete', $pet);

        $pet->delete();

        return redirect()
            ->route('pets.index')
            ->with('success', "Pet \"{$pet->nome}\" removido. Pode ser restaurado se necessário.");
    }
}
