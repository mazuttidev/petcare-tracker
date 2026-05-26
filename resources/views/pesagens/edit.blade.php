@extends('layouts.app', ['activeNav' => 'pets', 'title' => 'Editar Pesagem — ' . $pet->nome])

@section('content')

<x-page-header
    title="Editar pesagem"
    subtitle="{{ $pet->nome }} · {{ $pesagem->data->format('d/m/Y') }} · {{ number_format($pesagem->peso_kg, 2, ',', '') }} kg">
    <x-slot name="actions">
        <x-btn variant="ghost" href="{{ route('pets.show', $pet) }}">Cancelar</x-btn>
    </x-slot>
</x-page-header>

<form method="POST" action="{{ route('pets.pesagens.update', [$pet, $pesagem]) }}">
    @csrf
    @method('PUT')

    @include('pesagens._form', ['pesagem' => $pesagem])

    <div style="display:flex;gap:12px;margin-top:24px">
        <x-btn type="submit" icon="check">Salvar alterações</x-btn>
        <x-btn variant="secondary" href="{{ route('pets.show', $pet) }}">Cancelar</x-btn>
    </div>
</form>

@endsection
