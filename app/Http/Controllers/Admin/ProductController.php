<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('admin.products.index', ['products' => $products]);
    }

    public function create()
    {
        $services = Service::all();
        return view('admin.products.create', ['services' => $services]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'manufacturer' => 'required',
            'release_date' => 'required|date',
            'price' => 'required|numeric',
            'services' => 'array',
        ]);

        $product = Product::create($validated);

        if (isset($validated['services'])) {
            $product->services()->sync($validated['services']);
        }

        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        $services = Service::all();
        return view('admin.products.edit', [
            'product' => $product,
            'services' => $services,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'manufacturer' => 'required',
            'release_date' => 'required|date',
            'price' => 'required|numeric',
            'services' => 'array',
        ]);

        $product->update($validated);
        $product->services()->sync($validated['services'] ?? []);

        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index');
    }
}

