<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the products for the authenticated user.
     */
    public function index(Request $request)
    {
        return Product::with(['category:id,name', 'supplier:id,name'])
            ->where('user_id', $request->user()->id)
            ->get();
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id'
        ], [
            'name.required' => 'Name is required.',
            'name.max' => 'Name should not be more than 50 characters.',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be an integer',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'The selected category does not exist.',
            'supplier_id.required' => 'Supplier is required.',
            'supplier_id.exists' => 'The selected supplier does not exist.'
        ]);

        $product = Product::create(array_merge($validated, [
            'user_id' => $request->user()->id,
        ]));

        return response()->json([
            'message' => 'Product successfully added.',
            'product' => $product
        ], 201);
    }

    /**
     * Display the specified product owned by the user.
     */
    public function show(Request $request, string $id)
    {
        $product = Product::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        return response()->json($product);
    }

    /**
     * Update the specified product owned by the user.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id'
        ], [
            'name.required' => 'Name is required.',
            'name.max' => 'Name should not be more than 50 characters.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be an integer.',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'The selected category does not exist.',
            'supplier_id.required' => 'Supplier is required.',
            'supplier_id.exists' => 'The selected supplier does not exist.'
        ]);

        $product->update($validated);

        return response()->json([
            'message' => 'Product successfully updated.',
            'product' => $product
        ], 200);
    }

    /**
     * Remove the specified product owned by the user.
     */
    public function destroy(Request $request, string $id)
    {
        $product = Product::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product successfully deleted.'
        ], 200);
    }
}
