<x-layout>
    <x-slot name="heading">
        Service Details
    </x-slot>

    <div class="space-y-4">
        <p><strong>ID:</strong> {{ $service->id }}</p>
        <p><strong>Name:</strong> {{ $service->name }}</p>

        <div class="mt-6">
            <h2 class="font-bold text-lg mb-3">Products Using This Service</h2>

            @forelse($service->products as $product)
                <div class="p-3 border border-gray-300 rounded-md mb-2 bg-white">
                    <strong class="text-gray-800">{{ $product->name }}</strong><br>
                    Manufacturer: <span class="text-gray-700">{{ $product->manufacturer }}</span><br>
                    Release Date: <span class="text-gray-700">{{ \Illuminate\Support\Carbon::parse($product->release_date)->format('F j, Y') }}</span><br>
                    Price: <span class="text-gray-700">{{ number_format($product->price, 2) }} BYN</span><br>
                    @if($product->pivot)
                        Days to Complete: <span class="text-gray-700">{{ $product->pivot->days_to_complete }}</span><br>
                        Cost: <span class="text-gray-700">{{ number_format($product->pivot->cost, 2) }} BYN</span>
                    @endif
                </div>
            @empty
                <p class="italic text-gray-600">No products use this service.</p>
            @endforelse
        </div>

        <p class="mt-6 flex space-x-4">
            <a href="{{ route('admin.services.edit', $service->id) }}"
               class="text-indigo-600 hover:underline font-semibold">
                Edit Service
            </a>

        <form method="POST" action="{{ route('admin.services.destroy', $service->id) }}"
              onsubmit="return confirm('Are you sure you want to delete this service?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:underline font-semibold">
                Delete Service
            </button>
        </form>
        </p>
    </div>
</x-layout>
