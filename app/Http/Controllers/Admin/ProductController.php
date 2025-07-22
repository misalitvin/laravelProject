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
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class ProductController extends Controller
{
    public function __construct(protected ProductService $productService) {}

    public function index(ProductFilterRequest $request): View
    {
        $filterData = ProductFilterData::fromRequest($request);

        $products = $this->productService
            ->searchAndFilter($filterData)
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $services = Service::all();
        $manufacturers = Manufacturer::all();

        return view('admin.products.create', compact('services', 'manufacturers'));
    }

    public function store(StoreOrUpdateProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $product = Product::create($validated);

        $this->productService->syncServices($product, $validated['services'] ?? []);

        return redirect()->route('admin.products.index');
    }

    public function show(Product $product): View
    {
        $product->load('services');

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $product->load('services');
        $services = Service::all();
        $manufacturers = Manufacturer::all();

        return view('admin.products.edit', compact('product', 'services', 'manufacturers'));
    }

    public function update(StoreOrUpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        $this->productService->syncServices($product, $request->validated('services') ?? []);

        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index');
    }
}
