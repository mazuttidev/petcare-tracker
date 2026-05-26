@extends('layouts.app', ['activeNav' => 'pets', 'title' => 'Editar ' . $pet->nome . ' — PetCare'])

@section('content')

<x-page-header title="Editar: {{ $pet->nome }}" subtitle="Altere os dados do pet e salve ao final.">
    <x-slot name="actions">
        <x-btn variant="ghost" href="{{ route('pets.show', $pet) }}">Cancelar</x-btn>
    </x-slot>
</x-page-header>

<form id="pet-form" method="POST" action="{{ route('pets.update', $pet) }}">
    @csrf
    @method('PUT')

    @include('pets._form', ['pet' => $pet])

    <div style="display:flex;gap:12px;margin-top:24px">
        <x-btn type="submit" icon="check">Salvar alterações</x-btn>
        <x-btn variant="secondary" href="{{ route('pets.show', $pet) }}">Cancelar</x-btn>
    </div>
</form>

@endsection
