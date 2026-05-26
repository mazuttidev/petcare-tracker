@extends('layouts.app', ['activeNav' => 'pets', 'title' => 'Registrar Pesagem — ' . $pet->nome])

@section('content')

<x-page-header
    title="Registrar pesagem"
    subtitle="{{ $pet->nome }}{{ $pet->raca ? ' · ' . $pet->raca : '' }} · {{ $pet->especie }}">
    <x-slot name="actions">
        <x-btn variant="ghost" href="{{ route('pets.show', $pet) }}">Cancelar</x-btn>
    </x-slot>
</x-page-header>

<form method="POST" action="{{ route('pets.pesagens.store', $pet) }}">
    @csrf

    @include('pesagens._form', ['pesagem' => null])

    <div style="display:flex;gap:12px;margin-top:24px">
        <x-btn type="submit" icon="check">Salvar pesagem</x-btn>
        <x-btn variant="secondary" href="{{ route('pets.show', $pet) }}">Cancelar</x-btn>
    </div>
</form>

@endsection
