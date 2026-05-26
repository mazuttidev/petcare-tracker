<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetRequest;
use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PetController extends Controller
{
    // Lista todos os pets não deletados, ordenados pelos mais recentes
    public function index(): View
    {
        $pets = Pet::latest()->get();

        return view('pets.index', compact('pets'));
    }

    // Exibe o formulário de cadastro
    public function create(): View
    {
        return view('pets.create');
    }

    /**
     * Valida via PetRequest e persiste o novo pet.
     *
     * O Laravel injeta PetRequest, que roda authorize() e rules() antes de
     * chegar aqui. Se falhar, redireciona automaticamente de volta ao
     * formulário com $errors e old() preenchidos — sem código extra no controller.
     */
    public function store(PetRequest $request): RedirectResponse
    {
        // validated() retorna apenas os campos que passaram pelas regras
        Pet::create($request->validated());

        return redirect()
            ->route('pets.index')
            ->with('success', 'Pet cadastrado com sucesso!');
    }

    // Exibe o perfil completo de um pet com suas pesagens
    public function show(Pet $pet): View
    {
        $pet->load(['pesagens' => fn($q) => $q->orderBy('data')]);

        return view('pets.show', compact('pet'));
    }

    // Exibe o formulário de edição preenchido
    public function edit(Pet $pet): View
    {
        return view('pets.edit', compact('pet'));
    }

    /**
     * Valida via PetRequest (regra unique ignora o pet atual) e salva.
     *
     * PetRequest detecta o Pet da rota via $this->route('pet'), então a mesma
     * classe funciona para store e update sem nenhuma condicional aqui.
     */
    public function update(PetRequest $request, Pet $pet): RedirectResponse
    {
        $pet->update($request->validated());

        return redirect()
            ->route('pets.show', $pet)
            ->with('success', 'Dados atualizados com sucesso!');
    }

    // Soft delete — deleted_at é preenchido; registro permanece no banco
    public function destroy(Pet $pet): RedirectResponse
    {
        $pet->delete();

        return redirect()
            ->route('pets.index')
            ->with('success', "Pet \"{$pet->nome}\" removido. Pode ser restaurado se necessário.");
    }
}
