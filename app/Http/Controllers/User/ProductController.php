<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\DTOs\ProductFilterData;
use App\Enums\Currency;
use App\Http\Controllers\Controller;
use App\Models\CurrencyRate;
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
        $filterData = ProductFilterData::fromRequest($request);

        $products = $this->productService
            ->searchAndFilter($filterData)
            ->paginate(10)
            ->withQueryString();

        return view('user.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load('services');

        $currencies = array_column(Currency::cases(), 'value');

        $rates = CurrencyRate::whereIn('currency', $currencies)
            ->pluck('rate', 'currency');

        $priceEUR = $product->price;

        $prices = [];

        foreach (Currency::cases() as $currency) {
            $rate = $rates[$currency->value] ?? 1;

            $prices[$currency->value] = $currency === Currency::EUR
                ? $priceEUR
                : $priceEUR * $rate;
        }

        return view('user.products.show', compact('product', 'prices'));
    }
}
