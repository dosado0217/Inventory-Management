<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories for the authenticated user.
     */
    public function index(Request $request)
    {
        return Category::where('user_id', $request->user()->id)->get();
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50'
        ], [
            'name.required' => 'Name is required.',
            'name.max' => 'Name should not be more than 50 characters.',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Successfully created category.',
            'category' => $category
        ], 201);
    }

    /**
     * Display a specific category owned by the user.
     */
    public function show(Request $request, string $id)
    {
        $category = Category::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        return response()->json($category);
    }

    /**
     * Update a specific category owned by the user.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50'
        ], [
            'name.required' => 'Name is required.',
            'name.max' => 'Name should not be more than 50 characters.'
        ]);

        if ($validated['name'] === $category->name) {
            return response()->json([
                'message' => 'No changes detected. Use a different category name.'
            ], 422);
        }

        $duplicate = Category::where('name', $validated['name'])
            ->where('id', '!=', $id)
            ->where('user_id', $request->user()->id)
            ->exists();

        if ($duplicate) {
            return response()->json([
                'message' => 'Do not use the same name.'
            ], 422);
        }

        $category->update(['name' => $validated['name']]);

        return response()->json([
            'message' => 'Category successfully updated.',
            'category' => $category
        ]);
    }

    /**
     * Delete a specific category owned by the user.
     */
    public function destroy(Request $request, string $id)
    {
        $category = Category::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category successfully deleted.']);
    }
}
