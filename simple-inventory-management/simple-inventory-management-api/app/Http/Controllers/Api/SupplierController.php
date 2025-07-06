<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the suppliers for the authenticated user.
     */
    public function index(Request $request)
    {
        return Supplier::where('user_id', $request->user()->id)->get();
    }

    /**
     * Store a newly created supplier.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'contact' => ['required', 'string', 'regex:/^(09\d{9}|\+639\d{9})$/'],
            'address' => 'required|string|max:255',
        ], [
            'name.required' => 'Name is required.',
            'name.max' => 'Name should not be more than 50 characters.',
            'contact.required' => 'Contact is required.',
            'contact.regex' => 'Contact should be a valid Philippine telephone or mobile number.',
            'address.required' => 'Address is required.',
            'address.max' => 'Address should not be more than 255 characters.',
        ]);

        $supplier = Supplier::create(array_merge($validated, [
            'user_id' => $request->user()->id,
        ]));

        return response()->json([
            'message' => 'Supplier successfully added.',
            'supplier' => $supplier
        ], 201);
    }

    /**
     * Display the specified supplier owned by the user.
     */
    public function show(Request $request, string $id)
    {
        $supplier = Supplier::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found.'], 404);
        }

        return response()->json($supplier);
    }

    /**
     * Update the specified supplier owned by the user.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found.'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'contact' => ['required', 'string', 'regex:/^(09\d{9}|\+639\d{9})$/'],
            'address' => 'required|string|max:255',
        ], [
            'name.required' => 'Name is required.',
            'name.max' => 'Name should not be more than 50 characters.',
            'contact.required' => 'Contact is required.',
            'contact.regex' => 'Contact should be a valid Philippine telephone or mobile number.',
            'address.required' => 'Address is required.',
            'address.max' => 'Address should not be more than 255 characters.',
        ]);

        $supplier->update($validated);

        return response()->json([
            'message' => 'Supplier successfully updated.',
            'supplier' => $supplier
        ]);
    }

    /**
     * Remove the specified supplier owned by the user.
     */
    public function destroy(Request $request, string $id)
    {
        $supplier = Supplier::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found.'], 404);
        }

        $supplier->delete();

        return response()->json(['message' => 'Supplier successfully deleted.']);
    }
}
