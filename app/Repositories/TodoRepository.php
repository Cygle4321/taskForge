<?php

namespace App\Repositories;

use App\Models\Todo;
use Illuminate\Support\Collection;

class TodoRepository
{
    // Récupérer toutes les todos d'un utilisateur
    public function getTodosByUser(int $userId): Collection
    {
        return Todo::where('user_id', $userId)->with('category')->get();
    }

    // Récupérer une todo par son ID (sans vérification utilisateur)
    public function find(int $id): ?Todo
    {
        return Todo::with('category')->find($id);
    }

    // Créer une todo
    public function create(array $data): Todo
    {
        return Todo::create($data);
    }

    // Mettre à jour une todo
    public function update(Todo $todo, array $data): bool
    {
        return $todo->update($data);
    }

    // Supprimer une todo
    public function delete(Todo $todo): bool
    {
        return $todo->delete();
    }

    // Vérifier si la todo appartient à l'utilisateur
    public function belongsToUser(Todo $todo, int $userId): bool
    {
        return $todo->user_id === $userId;
    }
}
