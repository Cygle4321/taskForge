<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use App\Services\TodoService;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    protected TodoService $service;

    public function __construct(TodoService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $todos = $this->service->getTodosForUser(auth()->user());
        return response()->json([
            'success' => true,
            'message' => 'Todos récupérés avec succès',
            'data' => TodoResource::collection($todos)
        ]);
    }

    public function store(StoreTodoRequest $request)
    {
        $todo = $this->service->createTodo($request->validated(), auth()->id());
        return response()->json([
            'success' => true,
            'message' => 'Todo créé avec succès',
            'data' => new TodoResource($todo->load('category'))
        ], 201);
    }

    public function show(Todo $todo)
    {
        $this->authorize('view', $todo);

        if (!$todo) {
            return response()->json([
                'success' => false,
                'message' => 'Todo non trouvée ou non autorisée'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Todo récupérée avec succès',
            'data' => new TodoResource($todo->load('category'))
        ]);
    }

    public function update(UpdateTodoRequest $request, Todo $todo)
    {
        // if (!$this->service->getTodoForUser($todo->id, auth()->id())) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Non autorisé'
        //     ], 403);
        // }

        $this->authorize('update', $todo);
        $this->service->updateTodo($todo, $request->validated());
        $todo->refresh();
        return response()->json([
            'success' => true,
            'message' => 'Todo mise à jour avec succès',
            'data' => new TodoResource($todo->load('category'))
        ]);
    }

    public function destroy(Todo $todo)
    {
        // if (!$this->service->getTodoForUser($todo->id, auth()->id())) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Non autorisé'
        //     ], 403);
        // }
        $this->authorize('delete', $todo);
        $this->service->deleteTodo($todo);
        return response()->json([
            'success' => true,
            'message' => 'Todo supprimée avec succès'
        ], 200);
    }
}
