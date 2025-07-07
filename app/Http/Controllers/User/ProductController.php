<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

final class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $products = $this->productService
            ->searchAndFilter($request)
            ->paginate(10)
            ->withQueryString();

        return view('user.products.index', ['products' => $products]);
    }

    public function show(Product $product)
    {
        $product->load('services');

        return view('user.products.show', compact('product'));
    }
}
