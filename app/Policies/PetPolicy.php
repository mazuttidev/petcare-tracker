<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;

class PetPolicy
{
    // Qualquer usuário autenticado pode listar e cadastrar pets
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    // Somente o dono pode ver, editar ou apagar o pet
    public function view(User $user, Pet $pet): bool
    {
        return $pet->user_id === $user->id;
    }

    public function update(User $user, Pet $pet): bool
    {
        return $pet->user_id === $user->id;
    }

    public function delete(User $user, Pet $pet): bool
    {
        return $pet->user_id === $user->id;
    }
}
