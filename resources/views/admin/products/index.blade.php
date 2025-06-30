<x-layout title="All Products" heading="Product Catalog">
    <x-slot:heading>
        Products page
    </x-slot:heading>


    <x-slot:action>
        <a href="{{ route('admin.products.create') }}"
           class="inline-block rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
            Create Product
        </a>
    </x-slot:action>

    <x-product-filters />

    <div class="space-y-4">
        @foreach($products as $product)
            <a href="{{ route('admin.products.show', $product) }}" class="hover-underline block px-4 py-6 border border-gray-200">
                <div class="font-bold text-blue-500">
                    {{ $product->name }}
                </div>
                <div>
                    <strong>{{ $product->price }}</strong>
                </div>
            </a>
        @endforeach

        <div>
            {{ $products->links() }}
        </div>
    </div>
</x-layout>
