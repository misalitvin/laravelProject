<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
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

        $syncData = [];

        if (!empty($validated['services'])) {
            foreach ($validated['services'] as $serviceId => $serviceData) {
                if (isset($serviceData['selected']) && $serviceData['selected']) {
                    $syncData[$serviceId] = [
                        'days_to_complete' => $serviceData['days_to_complete'] ?? 0,
                        'cost' => $serviceData['cost'] ?? 0,
                    ];
                }
            }

            $product->services()->sync($syncData);
        }
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

        $syncData = [];

        if (!empty($validated['services'])) {
            foreach ($validated['services'] as $serviceId => $serviceData) {
                if (!empty($serviceData['selected'])) {
                    $syncData[$serviceId] = [
                        'days_to_complete' => $serviceData['days_to_complete'] ?? 0,
                        'cost' => $serviceData['cost'] ?? 0,
                    ];
                }
            }

            $product->services()->sync($syncData);
        } else {
            $product->services()->detach();
        }

        return redirect()->route('admin.products.index');
    }



    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index');
    }
}

