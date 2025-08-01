<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\DTOs\ProductFilterData;
use App\Enums\Currency;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFilterRequest;
use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\Interfaces\Repositories\ProductRepositoryInterface;
use Illuminate\Contracts\View\View;

final class ProductController extends Controller
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected CurrencyRateRepositoryInterface $currencyRateRepository,
    ) {}

    public function index(ProductFilterRequest $request): View
    {
        $filterData = ProductFilterData::fromRequest($request);
        $products = $this->productRepository->filterProducts($filterData);
        $products->withQueryString();

        return view('user.products.index', compact('products'));
    }

    public function show(int $id): View
    {
        $product = $this->productRepository->findWithRelations($id, ['services']);

        $currencies = array_column(Currency::cases(), 'value');

        $rates = $this->currencyRateRepository->getRatesForCurrencies($currencies);

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
