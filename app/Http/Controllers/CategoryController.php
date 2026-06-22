<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    protected CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $categories = $this->service->getCategoriesForUser(auth()->user());
        return response()->json([
            'success' => true,
            'message' => 'Catégories récupérées avec succès',
            'data' => CategoryResource::collection($categories)
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->service->createCategory($request->validated(), auth()->id());
        return response()->json([
            'success' => true,
            'message' => 'Catégorie créée avec succès',
            'data' => new CategoryResource($category)
        ], 201);
    }

    public function show(Category $category)
    {
        // if (!$this->service->getCategoryForUser($category->id, auth()->id())) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Non autorisé'
        //     ], 403);
        // }

        $this->authorize('view', $category);
        return response()->json([
            'success' => true,
            'message' => 'Catégorie récupérée avec succès',
            'data' => new CategoryResource($category->load('todos'))
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // if (!$this->service->getCategoryForUser($category->id, auth()->id())) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Non autorisé'
        //     ], 403);
        // }
        $this->authorize('update', $category);
        $this->service->updateCategory($category, $request->validated());
        $category->refresh();
        return response()->json([
            'success' => true,
            'message' => 'Catégorie mise à jour avec succès',
            'data' => new CategoryResource($category)
        ]);
    }

    public function destroy(Category $category)
    {
        // if (!$this->service->getCategoryForUser($category->id, auth()->id())) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Non autorisé'
        //     ], 403);
        // }
        $this->authorize('delete', $category);
        try {
            $this->service->deleteCategory($category);
            return response()->json([
                'success' => true,
                'message' => 'Catégorie supprimée avec succès'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?: 409);
        }
    }
}
