<?php

namespace App\Services;

use App\Models\Category;
use App\Models\User;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Collection;

class CategoryService
{
    protected CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getCategoriesForUser(User $user): Collection
    {
        if ($user->isAdmin()) {
            return Category::with('todos')->get();
        }
        return $this->repository->getCategoriesByUser($user->id);
    }

    // Récupérer une catégorie (avec vérification d'appartenance)
     public function getCategoryForUser(int $categoryId, User $user): ?Category
    {
        $category = $this->repository->find($categoryId);
        if (!$category) return null;

        if ($user->isAdmin()) {
            return $category;
        }

        if (!$this->repository->belongsToUser($category, $user->id)) {
            return null;
        }

        return $category;
    }

    // Créer une catégorie
    public function createCategory(array $data, int $userId): Category
    {
        $data['user_id'] = $userId;
        return $this->repository->create($data);
    }

    // Mettre à jour une catégorie
    public function updateCategory(Category $category, array $data): bool
    {
        return $this->repository->update($category, $data);
    }

    // Supprimer une catégorie (vérifie si elle contient des todos)
    public function deleteCategory(Category $category): bool
    {
        // Vérifier s'il y a des todos attachées
        if ($this->repository->countTodos($category) > 0) {
            throw new \Exception('Impossible de supprimer cette catégorie car elle contient des tâches', 409);
        }

        return $this->repository->delete($category);
    }
}
