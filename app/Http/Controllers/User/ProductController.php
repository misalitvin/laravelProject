<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($search = $request->input('search')) {
            $search = strtolower($search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($priceMin = $request->input('price_min')) {
            $query->where('price', '>=', $priceMin);
        }

        if ($priceMax = $request->input('price_max')) {
            $query->where('price', '<=', $priceMax);
        }

        if ($sort = $request->input('sort')) {
            if ($sort === 'name_asc') {
                $query->orderBy('name');
            } elseif ($sort === 'name_desc') {
                $query->orderBy('name', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(10)->withQueryString();
        return view('user.products.index', ['products' => $products]);
    }

    public function show(Product $product)
    {
        $product->load('services');
        return view('user.products.show', compact('product'));
    }
}
