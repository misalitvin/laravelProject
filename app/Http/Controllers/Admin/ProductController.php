<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTOs\ProductFilterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFilterRequest;
use App\Http\Requests\StoreOrUpdateProductRequest;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\Service;
use App\Services\ProductService;
use Illuminate\Http\Request;

final class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }


    public function index(ProductFilterRequest $request)
    {
        $filterData = ProductFilterData::fromRequest($request);

        $products = $this->productService
            ->searchAndFilter($filterData)
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }


    public function create()
    {
        $services = Service::all();
        $manufacturers = Manufacturer::all();

        return view('admin.products.create', compact('services', 'manufacturers'));
    }

    public function store(StoreOrUpdateProductRequest $request)
    {
        $validated = $request->validated();

        $product = Product::create($validated);

        $this->productService->syncServices($product, $validated['services'] ?? []);

        return redirect()->route('admin.products.index');
    }

    public function show(Product $product)
    {
        $product->load('services');

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load('services');
        $services = Service::all();
        $manufacturers = Manufacturer::all();

        return view('admin.products.edit', compact('product', 'services', 'manufacturers'));
    }

    public function update(StoreOrUpdateProductRequest $request, Product $product)
    {

        $product->update($request->validated());

        $this->productService->syncServices($product, $request->validated('services') ?? []);

        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index');
    }
}
