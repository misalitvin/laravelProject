@php use Illuminate\Support\Carbon; @endphp
<x-layout>
    <x-slot name="heading">
        {{ $product->name }}
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white p-6 rounded shadow">
            <p><strong>Description:</strong> {{ $product->description }}</p>
            <p><strong>Manufacturer:</strong> {{ $product->manufacturer->name }}</p>
            <p><strong>Release Date:</strong> {{ Carbon::parse($product->release_date)->format('F j, Y') }}</p>
            <p>
                <strong>Base Price:</strong>
                <span id="base-price" data-base-price="{{ $product->price }}">
                {{ number_format($product->price, 2) }}
                </span> BYN
            </p>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-semibold mb-4">Available Services</h2>

            <form id="service-form" class="space-y-4">
                @foreach ($product->services as $service)
                    <div class="flex items-center justify-between border p-4 rounded">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox"
                                   class="service-checkbox rounded border-gray-300 text-indigo-600"
                                   data-cost="{{ $service->pivot->cost }}">
                            <span class="text-gray-800">{{ $service->name }}</span>
                        </label>
                        <div class="text-sm text-gray-600">
                            <span>{{ number_format($service->pivot->cost, 2) }} BYN</span><br>
                            <span class="text-xs"> {{ $service->pivot->days_to_complete }} days to complete</span>
                        </div>
                    </div>
                @endforeach
            </form>
        </div>

        <div class="text-lg font-bold">
            Total: <span id="total-price">{{ number_format($product->price, 2) }}</span> BYN
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const basePriceEl = document.getElementById('base-price');
            const basePrice = parseFloat(basePriceEl.dataset.basePrice);
            const totalPriceEl = document.getElementById('total-price');
            const checkboxes = document.querySelectorAll('.service-checkbox');

            function updateTotal() {
                let total = basePrice;

                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        total += parseFloat(cb.dataset.cost);
                    }
                });

                totalPriceEl.textContent = total.toFixed(2);
            }
            updateTotal();

            checkboxes.forEach(cb => cb.addEventListener('change', updateTotal));
        });
    </script>

</x-layout>
