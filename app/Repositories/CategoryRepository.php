<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryRepository
{
    // Récupérer toutes les catégories d'un utilisateur
    public function getCategoriesByUser(int $userId): Collection
    {
        return Category::where('user_id', $userId)->with('todos')->get();
    }

    // Récupérer une catégorie par son ID (sans vérification)
    public function find(int $id): ?Category
    {
        return Category::with('todos')->find($id);
    }

    // Créer une catégorie
    public function create(array $data): Category
    {
        return Category::create($data);
    }

    // Mettre à jour une catégorie
    public function update(Category $category, array $data): bool
    {
        return $category->update($data);
    }

    // Supprimer une catégorie
    public function delete(Category $category): bool
    {
        return $category->delete();
    }

    // Vérifier si la catégorie appartient à l'utilisateur
    public function belongsToUser(Category $category, int $userId): bool
    {
        return $category->user_id === $userId;
    }

    // Compter les todos attachées à une catégorie
    public function countTodos(Category $category): int
    {
        return $category->todos()->count();
    }
}
