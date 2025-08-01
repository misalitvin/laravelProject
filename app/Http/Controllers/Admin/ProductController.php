<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTOs\ProductFilterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFilterRequest;
use App\Http\Requests\StoreOrUpdateProductRequest;
use App\Interfaces\Repositories\ManufacturerRepositoryInterface;
use App\Interfaces\Repositories\ProductRepositoryInterface;
use App\Interfaces\Repositories\ServiceRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected ProductRepositoryInterface $productRepository,
        protected ServiceRepositoryInterface $serviceRepository,
        protected ManufacturerRepositoryInterface $manufacturerRepository,
    ) {}

    public function index(ProductFilterRequest $request): View
    {
        $filterData = ProductFilterData::fromRequest($request);
        $products = $this->productService->getFilteredProducts($filterData);
        $products->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $services = $this->serviceRepository->getAll();
        $manufacturers = $this->manufacturerRepository->getAll();

        return view('admin.products.create', compact('services', 'manufacturers'));
    }

    public function store(StoreOrUpdateProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $product = $this->productRepository->create($validated);
        $this->productService->syncServices($product, $validated['services'] ?? []);

        return redirect()->route('admin.products.index');
    }

    public function show(int $id): View
    {
        $product = $this->productRepository->findWithRelations($id, ['services']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(int $id): View
    {
        $product = $this->productRepository->findWithRelations($id, ['services']);
        $services = $this->serviceRepository->getAll();
        $manufacturers = $this->manufacturerRepository->getAll();

        return view('admin.products.edit', compact('product', 'services', 'manufacturers'));
    }

    public function update(StoreOrUpdateProductRequest $request, int $id): RedirectResponse
    {
        $validated = $request->validated();
        $product = $this->productRepository->findById($id);

        $this->productRepository->update($product, $validated);
        $this->productService->syncServices($product, $validated['services'] ?? []);

        return redirect()->route('admin.products.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $product = $this->productRepository->findById($id);
        $this->productRepository->delete($product);

        return redirect()->route('admin.products.index');
    }
}
