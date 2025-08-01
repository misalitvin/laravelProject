<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Change Password') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-lg mx-auto">
        @include('profile.partials.update-password-form')
    </div>
</x-app-layout>
