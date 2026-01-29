<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $query = Product::search($search)->query(fn ($builder) => $builder->with('category'));

            if ($request->has('category_id') && $request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            $products = $query->paginate(15)->withQueryString();
        } else {
            $query = Product::query()->with('category');

            if ($request->has('category_id') && $request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            $products = $query->paginate(15)->withQueryString();
        }

        $categories = Category::all();

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => [
                'category_id' => $request->category_id,
                'search' => $search,
            ],
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    public function create()
    {
        $categories = Category::all();

        return Inertia::render('Admin/Products/Form', [
            'categories' => $categories,
        ]);
    }

    public function edit(Product $product)
    {
        $product->load('category');
        $categories = Category::all();

        return Inertia::render('Admin/Products/Form', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }
}
