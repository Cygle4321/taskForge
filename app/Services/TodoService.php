<?php

namespace App\Services;

use App\Jobs\SendTodoCreatedMailJob;
use App\Models\Todo;
use App\Models\User;
use App\Repositories\TodoRepository;
use Illuminate\Support\Collection;

class TodoService
{
    protected TodoRepository $repository;

    public function __construct(TodoRepository $repository)
    {
        $this->repository = $repository;
    }

    // Récupérer les todos : admin voit tout, utilisateur normal voit les siennes
    public function getTodosForUser(User $user): Collection
    {
        if ($user->isAdmin()) {
            return Todo::with('category')->get();
        }
        return $this->repository->getTodosByUser($user->id);
    }

    // Récupérer une todo : admin peut voir n'importe laquelle, sinon vérification propriétaire
    public function getTodoForUser(int $todoId, User $user): ?Todo
    {
        $todo = $this->repository->find($todoId);
        if (!$todo) return null;

        if ($user->isAdmin()) {
            return $todo;
        }

        if (!$this->repository->belongsToUser($todo, $user->id)) {
            return null;
        }

        return $todo;
    }

    // Créer une todo
    public function createTodo(array $data, int $userId): Todo
    {
        // Ajouter l'utilisateur aux données
        $data['user_id'] = $userId;
        //Add pour les jobs
        $todo = $this->repository->create($data);

        // Déclencher le Job d'envoi d'email (asynchrone)
        // dispatch() met le Job dans la queue. Laravel s'occupe du reste.
        $user = User::find($userId);
        SendTodoCreatedMailJob::dispatch($todo, $user);

        return $todo;
        // return $this->repository->create($data);
    }

    // Mettre à jour une todo
    public function updateTodo(Todo $todo, array $data): bool
    {
        return $this->repository->update($todo, $data);
    }

    // Supprimer une todo
    public function deleteTodo(Todo $todo): bool
    {
        return $this->repository->delete($todo);
    }
}
