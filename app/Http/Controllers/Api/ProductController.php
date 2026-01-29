<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('category')->paginate(15);
    }

    public function store(StoreProductRequest $request)
    {
        return Product::create($request->validated());
    }

    public function show(Product $product)
    {
        return $product->load('category');
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete(); // soft delete
        return response()->json(['message' => 'Deleted']);
    }
}
