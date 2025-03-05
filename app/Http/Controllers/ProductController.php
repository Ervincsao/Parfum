<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'type_id' => 'required|exists:types,id',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    // Termék frissítése
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if ($product) {
            $validated = $request->validate([
                'name' => 'sometimes|required|string',
                'price' => 'sometimes|required|numeric',
                'description' => 'sometimes|nullable|string',
                'manufacturer_id' => 'sometimes|required|exists:manufacturers,id',
                'type_id' => 'sometimes|required|exists:types,id',
            ]);

            $product->update($validated);

            return response()->json($product);
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->delete();
            return response()->json(['message' => 'Product deleted']);
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }
}
