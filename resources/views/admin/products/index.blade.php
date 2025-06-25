<x-layout title="All Products" heading="Product Catalog">
    <x-slot:heading>
        Products page
    </x-slot:heading>


    <div class="space-y-4">
        @foreach($products as $product)
            <a href="/admin/{{$product['id']}}" class="hover-underline block px-4 py-6 border border-gray-200">
                <div class="font-bold text-blue-500">
                    {{ $product->name }}
                </div>
                <div>
                    <strong>{{ $product->price }}</strong>
                </div>
            </a>
        @endforeach

        <div>
          {{$products->links()}}
        </div>
    </div>
</x-layout>
