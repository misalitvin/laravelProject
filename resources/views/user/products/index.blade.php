@php use Illuminate\Support\Str; @endphp
<x-layout title="All Products" heading="Product Catalog">
    <x-slot name="heading">
        Products
    </x-slot>

    <x-product-filters />

    <div class="space-y-4">
        @foreach($products as $product)
            <a href="{{ route('products.show', $product) }}"
               class="hover-underline block px-4 py-6 border border-gray-200 bg-white rounded shadow-sm hover:shadow">
                <div class="font-bold text-blue-600">
                    {{ $product->name }}
                </div>
                <div class="text-gray-700">
                    {{ Str::limit($product->description, 80) }}
                </div>
                <div class="mt-2 text-sm text-gray-800">
                    <strong>{{ number_format($product->price, 2) }} BYN</strong>
                </div>
            </a>
        @endforeach

        <div class="pt-4">
            {{ $products->links() }}
        </div>
    </div>
</x-layout>
