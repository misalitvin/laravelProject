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
                <strong>Price:</strong>
                <span id="base-price" data-base-price="{{ $product->price }}">
                    {{ number_format($product->price, 2) }}
                </span> EUR
            </p>
            <p>
                <strong>Price in other currencies:</strong>
            <ul class="list-disc list-inside ml-5">
                <li>USD: <span id="base-usd">{{ number_format($prices['USD'] ?? 0, 2) }}</span></li>
                <li>PLN: <span id="base-pln">{{ number_format($prices['PLN'] ?? 0, 2) }}</span></li>
            </ul>
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
                            <span>{{ number_format($service->pivot->cost, 2) }} EUR</span><br>
                            <span class="text-xs"> {{ $service->pivot->days_to_complete }} days to complete</span>
                        </div>
                    </div>
                @endforeach
            </form>
        </div>

        <div class="text-lg font-bold">
            Total:
            <span id="total-eur">{{ number_format($product->price, 2) }}</span> EUR
            <ul class="list-disc list-inside ml-5 mt-1">
                <li>USD: <span id="total-usd">{{ number_format($prices['USD'] ?? 0, 2) }}</span></li>
                <li>PLN: <span id="total-pln">{{ number_format($prices['PLN'] ?? 0, 2) }}</span></li>
            </ul>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const basePrice = parseFloat(document.getElementById('base-price').dataset.basePrice);

            const prices = {
                USD: parseFloat("{{ $prices['USD'] ?? 0 }}"),
                PLN: parseFloat("{{ $prices['PLN'] ?? 0 }}"),
            };

            const checkboxes = document.querySelectorAll('.service-checkbox');

            function updateTotal() {
                let totalEUR = basePrice;

                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        totalEUR += parseFloat(cb.dataset.cost);
                    }
                });

                document.getElementById('total-eur').textContent = totalEUR.toFixed(2);

                document.getElementById('total-usd').textContent = (totalEUR * prices.USD / basePrice).toFixed(2);
                document.getElementById('total-pln').textContent = (totalEUR * prices.PLN / basePrice).toFixed(2);
            }

            updateTotal();

            checkboxes.forEach(cb => cb.addEventListener('change', updateTotal));
        });
    </script>
</x-layout>
