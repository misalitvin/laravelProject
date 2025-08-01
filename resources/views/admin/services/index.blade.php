<x-layout title="All Services" heading="Service Catalog">
    <x-slot:heading>
        Services page
    </x-slot:heading>

    <x-slot:action>
        <a href="{{ route('admin.services.create') }}"
           class="inline-block rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
            Create Service
        </a>
    </x-slot:action>

    <div class="space-y-4">
        @foreach($services as $service)
            <a href="{{ route('admin.services.show', $service) }}" class="hover-underline block px-4 py-6 border border-gray-200">
                <div class="font-bold text-blue-500">
                    {{ $service->name }}
                </div>
            </a>
        @endforeach

        <div>
            {{ $services->links() }}
        </div>
    </div>
</x-layout>
