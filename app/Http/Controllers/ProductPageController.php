<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductPageController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with('category');

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::all();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => [
                'category_id' => $request->category_id,
            ],
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        if (! $search) {
            return redirect()->route('home');
        }

        $query = Product::search($search)->query(fn ($builder) => $builder->with('category'));

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::all();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => [
                'category_id' => $request->category_id,
                'search' => $search,
            ],
        ]);
    }

    public function show(Product $product)
    {
        $product->load('category');

        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }
}
