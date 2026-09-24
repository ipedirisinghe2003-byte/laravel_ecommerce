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
                        secondary: '#0d5d7a',
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
           <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-sky-800 text-gray-100 transition duration-300 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0">
<div class="flex items-center justify-center h-16 bg-sky-700 border-b border-gray-800">
<a href="{{route('admin.index')}}"><img src="{{asset('images/logo.png')}}" alt="Logo" class="h-12" /></a>
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
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-3 rounded-md {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.index') ? 'bg-[#0e6c8f] text-white font-medium' : 'hover:bg-[#0f6988] text-white/90' }}">
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
                            <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-md hover:bg-[#0f6988] text-white/90">
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
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>


            <div class="border-t border-gray-400 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm">&copy; 2026 Surfside Media All rights reserved.</p>
                <img src="{{ asset('assets/images/payment.png') }}" alt="Payment" class="mt-4 md:mt-0" />
            </div>
        </div>
    </footer>

    <button id="back-to-top"
        class="fixed bottom-8 right-8 bg-primary text-white w-10 h-10 rounded shadow-lg hover:bg-blue-600 transition hidden items-center justify-center z-50">
        <i class="fa-solid fa-chevron-up text-xl"></i>
    </button>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // --- Helper: Select Element ---
            const $ = (id) => document.getElementById(id);
            const $$ = (selector) => document.querySelectorAll(selector);

            // --- 1. UI Toggles (Mobile Menu & Avatar) ---
            const toggleUI = (btnId, menuId, overlayId = null) => {
                const btn = $(btnId);
                const menu = $(menuId);
                const overlay = overlayId ? $(overlayId) : null;

                if (!btn || !menu) return;

                const toggle = () => {
                    menu.classList.toggle('-translate-x-full');
                    menu.classList.toggle('hidden'); // For avatar type menus
                    if (overlay) overlay.classList.toggle('hidden');
                };

                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    toggle();
                });

                // Specific for mobile sidebar close button
                if ($(btnId === 'mobile-menu-btn' ? 'close-menu-btn' : null)) {
                    $('close-menu-btn').addEventListener('click', toggle);
                }

                // Close when clicking outside
                document.addEventListener('click', (e) => {
                    if (!menu.contains(e.target) && !btn.contains(e.target)) {
                        menu.classList.add('-translate-x-full');
                        menu.classList.add('hidden');
                        if (overlay) overlay.classList.add('hidden');
                    }
                });
            };

            toggleUI('mobile-menu-btn', 'mobile-sidebar', 'menu-overlay');
            toggleUI('mobile-avatar-button', 'avatar-submenu-mobile');

            // --- 2. Back to Top Button ---
            const backToTopBtn = $('back-to-top');
            if (backToTopBtn) {
                window.addEventListener('scroll', () => {
                    const isVisible = window.scrollY > 300;
                    backToTopBtn.classList.toggle('hidden', !isVisible);
                    backToTopBtn.classList.toggle('flex', isVisible);
                });

                backToTopBtn.addEventListener('click', () => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }

            // --- 3. Swiper Initializations (Unified Logic) ---
            const initSwiper = (selector, options) => {

                if (document.querySelector(selector)) return new window.Swiper(selector, options);
            };

            // Hero
            initSwiper('.main-slider', {
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                autoplay: {
                    delay: 5000
                },
            });

            // Product/Category Sliders (Common Breakpoints)
            const productBreakpoints = {
                640: {
                    slidesPerView: 2
                },
                768: {
                    slidesPerView: 3
                },
                1024: {
                    slidesPerView: 4
                }
            };

            initSwiper('.category-slider', {
                slidesPerView: 2,
                spaceBetween: 20,
                loop: true,
                breakpoints: {
                    ...productBreakpoints,
                    1024: {
                        slidesPerView: 6
                    }
                }
            });

            initSwiper('.brand-slider', {
                slidesPerView: 2,
                spaceBetween: 20,
                loop: true,
                breakpoints: {
                    ...productBreakpoints,
                    1024: {
                        slidesPerView: 5
                    }
                }
            });

            initSwiper('.featured-slider', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.feat-next',
                    prevEl: '.feat-prev'
                },
                breakpoints: productBreakpoints
            });

            initSwiper('.related-slider', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.related-next',
                    prevEl: '.related-prev'
                },
                breakpoints: productBreakpoints
            });

            // Gallery Thumbs logic
            const galleryThumbs = initSwiper('.gallery-thumbs', {
                spaceBetween: 10,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesProgress: true,
            });

            initSwiper('.gallery-top', {
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                },
                thumbs: {
                    swiper: galleryThumbs
                }
            });

            // --- 4. Tabs Logic ---
            $$('.tab-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    $$('.tab-btn').forEach(b => b.classList.remove('active'));
                    $$('.tab-content').forEach(c => c.classList.add('hidden'));

                    btn.classList.add('active');
                    const target = $(btn.getAttribute('data-target'));
                    if (target) target.classList.remove('hidden');
                });
            });

            // --- 5. Countdown Timer ---
            const countdownContainer = $('countdown-timer');
            if (countdownContainer) {
                const targetDate = new Date("2025-12-31T00:00:00").getTime();
                const updateTimer = () => {
                    const distance = targetDate - new Date().getTime();
                    if (distance < 0) {
                        countdownContainer.innerHTML = "EXPIRED";
                        return clearInterval(timerInterval);
                    }
                    const timeMap = {
                        days: Math.floor(distance / 864e5),
                        hours: Math.floor((distance % 864e5) / 36e5),
                        minutes: Math.floor((distance % 36e5) / 6e4),
                        seconds: Math.floor((distance % 6e4) / 1000)
                    };
                    Object.keys(timeMap).forEach(unit => {
                        const el = $(unit);
                        if (el) el.innerText = String(timeMap[unit]).padStart(2, '0');
                    });
                };
                const timerInterval = setInterval(updateTimer, 1000);
                updateTimer();
            }
        });

        // --- 6. Global Functions ---
        function updateQty(amount) {
            const input = document.getElementById('qty-input');
            if (!input) return;
            let val = parseInt(input.value) + amount;
            input.value = val < 1 ? 1 : val;
        }
    </script>
</body> -->
{{--  <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body> --}}

</html>
