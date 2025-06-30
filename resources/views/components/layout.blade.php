<!doctype html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Product Catalog' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full">
<div class="min-h-full">
    <nav class="bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg" class="size-8" alt="Logo">
                    <div class="ml-10 space-x-4 hidden md:flex">
                        <a href="/admin/products" class="{{ request()->is('products*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Products</a>
                        <a href="/admin/services" class="{{ request()->is('services*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Services</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8 flex justify-between items-center">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                {{ $heading ?? $title }}
            </h1>

            @isset($action)
                <div>
                    {{ $action }}
                </div>
            @endisset
        </div>
    </header>


    <main>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>
</div>
</body>
</html>
