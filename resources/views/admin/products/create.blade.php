<x-layout>
    <x-slot name="heading">
        Create Product
    </x-slot>

    <form method="POST" action="{{ route('admin.products.store') }}">
        @csrf
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold text-gray-900">Product Information</h2>
                <p class="mt-1 text-sm text-gray-600">Enter the details of the product you'd like to add.</p>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <x-form-label for="name">Name</x-form-label>
                        <div class="mt-2">
                            <x-form-input
                                name="name"
                                id="name"
                                placeholder="Product name"
                                value="{{ old('name') }}"
                                required
                            />
                            <x-form-error name="name"/>
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <x-form-label for="manufacturer_id">Manufacturer</x-form-label>
                        <div class="mt-2">
                            <select
                                name="manufacturer_id"
                                id="manufacturer_id"
                                required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Select a manufacturer</option>
                                @foreach ($manufacturers as $manufacturer)
                                    <option
                                        value="{{ $manufacturer->id }}"
                                        {{ old('manufacturer_id') == $manufacturer->id ? 'selected' : '' }}
                                    >
                                        {{ $manufacturer->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-form-error name="manufacturer_id" />
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <x-form-label for="release_date">Release Date</x-form-label>
                        <div class="mt-2">
                            <x-form-input
                                type="date"
                                name="release_date"
                                id="release_date"
                                value="{{ old('release_date') }}"
                                required
                            />
                            <x-form-error name="release_date"/>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <x-form-label for="price">Price</x-form-label>
                        <div class="mt-2">
                            <x-form-input
                                type="number"
                                name="price"
                                id="price"
                                step="0.1"
                                placeholder="99.99"
                                value="{{ old('price') }}"
                                required
                            />
                            <x-form-error name="price"/>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <x-form-label for="description">Description</x-form-label>
                        <div class="mt-2">
                            <textarea
                                name="description"
                                id="description"
                                rows="3"
                                class="w-full border rounded p-2"
                                placeholder="Describe your product"
                                required
                            >{{ old('description') }}</textarea>
                            <x-form-error name="description"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-900/10 pb-12 mt-8">
                <h2 class="text-base font-semibold text-gray-900">Add Services</h2>
                <p class="mt-1 text-sm text-gray-600">Select services for this product and set pricing and expiration time.</p>

                <div class="mt-6 grid grid-cols-1 gap-y-6">
                    @foreach ($services as $service)
                        @php
                            $oldService = old('services.' . $service->id, []);
                            $selected = isset($oldService['selected']);
                            $daysToComplete = $oldService['days_to_complete'] ?? '';
                            $cost = $oldService['cost'] ?? '';
                        @endphp

                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-x-4 items-end">
                            <div class="sm:col-span-2">
                                <label class="inline-flex items-center">
                                    <input
                                        type="checkbox"
                                        name="services[{{ $service->id }}][selected]"
                                        value="1"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        {{ $selected ? 'checked' : '' }}
                                    >
                                    <span class="ml-2 text-sm text-gray-700">{{ $service->name }}</span>
                                </label>
                            </div>

                            <div class="sm:col-span-5">
                                <x-form-label for="services[{{ $service->id }}][days_to_complete]">Days to Complete</x-form-label>
                                <x-form-input
                                    type="number"
                                    name="services[{{ $service->id }}][days_to_complete]"
                                    placeholder="e.g. 30"
                                    value="{{ $daysToComplete }}"
                                />
                                <x-form-error name="services.{{ $service->id }}.days_to_complete" />
                            </div>

                            <div class="sm:col-span-5">
                                <x-form-label for="services[{{ $service->id }}][cost]">Cost</x-form-label>
                                <x-form-input
                                    type="number"
                                    step="0.01"
                                    name="services[{{ $service->id }}][cost]"
                                    placeholder="e.g. 19.99"
                                    value="{{ $cost }}"
                                />
                                <x-form-error name="services.{{ $service->id }}.cost" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href="{{ route('admin.products.index') }}" class="text-sm font-semibold text-gray-900">Cancel</a>
            <button
                type="submit"
                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
                Save
            </button>
        </div>
    </form>
</x-layout>
