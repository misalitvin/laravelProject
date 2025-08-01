@php use Illuminate\Support\Carbon; @endphp
<x-layout>
    <x-slot name="heading">
        Product Details
    </x-slot>

    <div class="space-y-4">
        <p><strong>ID:</strong> {{ $product->id }}</p>
        <p><strong>Name:</strong> {{ $product->name }}</p>
        <p><strong>Description:</strong> {{ $product->description }}</p>
        <p><strong>Manufacturer:</strong> {{ $product->manufacturer->name }}</p>
        <p><strong>Release Date:</strong> {{ Carbon::parse($product->release_date)->format('F j, Y') }}</p>
        <p><strong>Price:</strong> {{ number_format($product->price, 2) }} EUR</p>

        <div class="mt-6">
            <h2 class="font-bold text-lg mb-3">Services</h2>

            @forelse($product->services as $service)
                <div class="p-3 border border-gray-300 rounded-md mb-2 bg-white">
                    <strong class="text-gray-800">{{ $service->name }}</strong><br>
                    Days to Complete: <span class="text-gray-700">{{ $service->pivot->days_to_complete }}</span><br>
                    Cost: <span class="text-gray-700">{{ number_format($service->pivot->cost, 2) }} EUR</span>
                </div>
            @empty
                <p class="italic text-gray-600">No services attached.</p>
            @endforelse
        </div>

        <p class="mt-6 flex space-x-4">
            <a href="{{ route('admin.products.edit', $product->id) }}"
               class="text-indigo-600 hover:underline font-semibold">
                Edit Product
            </a>

        <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}"
              onsubmit="return confirm('Are you sure you want to delete this product?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:underline font-semibold">
                Delete Product
            </button>
        </form>
        </p>
    </div>
</x-layout>
