<x-layout>
    <x-slot name="heading">
        Edit Service
    </x-slot>

    <form method="POST" action="{{ route('admin.services.update', $service) }}">
        @csrf
        @method('PATCH')

        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold text-gray-900">Service Information</h2>
                <p class="mt-1 text-sm text-gray-600">Update the name of the service.</p>

                <div class="mt-10 sm:col-span-4">
                    <x-form-label for="name">Name</x-form-label>
                    <div class="mt-2">
                        <x-form-input name="name" id="name" placeholder="Service name" required
                                      value="{{ old('name', $service->name) }}" />
                        <x-form-error name="name" />
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href="{{ route('admin.services.index') }}" class="text-sm font-semibold text-gray-900">Cancel</a>
            <button type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Update
            </button>
        </div>
    </form>
</x-layout>
