<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');

        $products = Product::active()->with('category')
            ->when($category, fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $category)))
            ->when($request->query('q'), fn ($q, $t) => $q->where('title', 'like', "%$t%"))
            ->when($request->query('sort'), function ($q, $sort) {
                match ($sort) {
                    'cheap' => $q->orderByRaw('COALESCE(sale_price, price) IS NULL, COALESCE(sale_price, price) asc'),
                    'expensive' => $q->orderByRaw('COALESCE(sale_price, price) desc'),
                    'new' => $q->latest('id'),
                    default => $q->ordered(),
                };
            }, fn ($q) => $q->ordered())
            ->paginate(12)
            ->withQueryString();

        return view('pages.shop.index', [
            'products' => $products,
            'categories' => ProductCategory::active()->ordered()->withCount(['products' => fn ($q) => $q->active()])->get(),
            'currentCategory' => $category,
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::active()->with('category')->where('slug', $slug)->firstOrFail();

        return view('pages.shop.show', [
            'product' => $product,
            'related' => Product::active()->with('category')
                ->where('id', '!=', $product->id)
                ->when($product->category_id, fn ($q) => $q->orderByRaw('category_id = ? desc', [$product->category_id]))
                ->ordered()->take(4)->get(),
        ]);
    }
}
