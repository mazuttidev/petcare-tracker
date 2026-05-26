@extends('layouts.app', ['activeNav' => 'pets', 'title' => 'Novo Pet — PetCare'])

@section('content')

<x-page-header title="Novo pet" subtitle="Preencha os dados do seu animal de estimação.">
    <x-slot name="actions">
        <x-btn variant="ghost" href="{{ route('pets.index') }}">Cancelar</x-btn>
    </x-slot>
</x-page-header>

<form id="pet-form" method="POST" action="{{ route('pets.store') }}">
    @csrf

    @include('pets._form', ['pet' => null])

    <div style="display:flex;gap:12px;margin-top:24px">
        <x-btn type="submit" icon="check">Cadastrar pet</x-btn>
        <x-btn variant="secondary" href="{{ route('pets.index') }}">Cancelar</x-btn>
    </div>
</form>

@endsection
