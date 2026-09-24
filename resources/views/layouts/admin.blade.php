<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0369a1',
                        secondary: '#075985',
                        sidebar: '#0d5d7a',
                        sidebarLight: '#0f6988',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-700 antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white border-b border-gray-200 h-20 flex items-center justify-between px-4 lg:px-6">
            <div class="flex items-center gap-3 min-w-[180px]">
                <a href="{{ route('admin.index') }}" class="text-2xl font-bold tracking-wide text-gray-800">Logo</a>
            </div>

            <div class="flex-1 flex items-center justify-center px-4">
                <div class="w-full max-w-xl relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" placeholder="Search categories..." class="w-full rounded-md border border-gray-200 bg-gray-50 pl-11 pr-4 py-3 text-sm outline-none focus:border-primary focus:bg-white" />
                </div>
            </div>

            <div class="flex items-center gap-4 min-w-[220px] justify-end">
                <button type="button" class="relative text-gray-600 hover:text-primary">
                    <i class="fa-solid fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 w-4 h-4 text-[10px] rounded-full bg-red-500 text-white flex items-center justify-center">3</span>
                </button>

                <div class="flex items-center gap-3">
                    <div class="text-right text-sm">
                        <div class="font-medium text-gray-700">Admin</div>
                        <div class="text-gray-500">Super Admin</div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-sky-500 text-white flex items-center justify-center font-bold">A</div>
                </div>
            </div>
        </header>

        <div class="flex flex-1 min-h-0">
            <aside class="w-72 bg-[#0d5d7a] text-white">
                <nav class="p-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('admin.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-md {{ request()->routeIs('admin.index') ? 'bg-[#0e6c8f] text-white font-medium' : 'hover:bg-[#0f6988] text-white/90' }}">
                                <i class="fa-solid fa-gauge-high"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-md hover:bg-[#0f6988] text-white/90">
                                <i class="fa-solid fa-box"></i>
                                <span>Products</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-3 py-3 rounded-md {{ request()->routeIs('admin.categories') ? 'bg-[#0e6c8f] text-white font-medium' : 'hover:bg-[#0f6988] text-white/90' }}">
                                <i class="fa-solid fa-folder"></i>
                                <span>Categories</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.brands') }}" class="flex items-center gap-3 px-3 py-3 rounded-md {{ request()->routeIs('admin.brands') ? 'bg-[#0e6c8f] text-white font-medium' : 'hover:bg-[#0f6988] text-white/90' }}">
                                <i class="fa-solid fa-tag"></i>
                                <span>Brands</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-md hover:bg-[#0f6988] text-white/90">
                                <i class="fa-solid fa-bag-shopping"></i>
                                <span>Orders</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-md hover:bg-[#0f6988] text-white/90">
                                <i class="fa-solid fa-users"></i>
                                <span>Customers</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-md hover:bg-[#0f6988] text-white/90">
                                <i class="fa-solid fa-star"></i>
                                <span>Reviews</span>
                            </a>
                        </li>
                    </ul>

                    <div class="mt-8 pt-4 border-t border-white/15">
                        <div class="text-xs uppercase tracking-wider text-white/70 mb-3 px-3">Settings</div>
                        <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-md hover:bg-[#0f6988] text-white/90">
                            <i class="fa-solid fa-gear"></i>
                            <span>General Settings</span>
                        </a>
                    </div>
                </nav>
            </aside>

            <main class="flex-1 bg-gray-100 p-6">
                @if (session('success'))
                    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 shadow-sm toast-message" role="alert">
                        <div class="flex items-center justify-between gap-3">
                            <span>{{ session('success') }}</span>
                            <button type="button" class="toast-close text-green-700 hover:text-green-900" aria-label="Close">&times;</button>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-sm toast-message" role="alert">
                        <div class="flex items-center justify-between gap-3">
                            <span>{{ session('error') }}</span>
                            <button type="button" class="toast-close text-red-700 hover:text-red-900" aria-label="Close">&times;</button>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toast-close').forEach(function (button) {
                button.addEventListener('click', function () {
                    const toast = button.closest('.toast-message');
                    if (toast) toast.remove();
                });
            });

            document.querySelectorAll('.toast-message').forEach(function (toast) {
                setTimeout(function () {
                    toast.remove();
                }, 4000);
            });
        });
    </script>
</body>
</html>
