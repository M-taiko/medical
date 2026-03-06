<!DOCTYPE html>
@php $locale = app()->getLocale(); $isAr = $locale === 'ar'; @endphp
<html lang="{{ str_replace('_', '-', $locale) }}" dir="{{ $isAr ? 'rtl' : 'ltr' }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Masar Dental') }} — Super Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @if($isAr)
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5',
                        secondary: '#7C3AED',
                        accent: '#10B981',
                    }
                }
            }
        }
    </script>
    <style>
        .glass { background: rgba(255,255,255,0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.18); }
        .dark .glass { background: rgba(17,24,39,0.7); border: 1px solid rgba(255,255,255,0.05); }
        @if($isAr) body { font-family: 'Cairo', sans-serif !important; } @endif
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
      x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }"
      :class="{ 'dark': darkMode }"
      x-init="$watch('darkMode', v => localStorage.setItem('darkMode', v))">

    <!-- Topbar -->
    <header class="glass fixed top-0 w-full z-40 border-b border-gray-200 dark:border-gray-800 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary to-secondary flex items-center gap-2">
                    <i data-lucide="shield-check" class="text-primary h-6 w-6"></i>
                    Masar Dental <span class="text-xs font-normal text-gray-500 dark:text-gray-400 ml-1">Super Admin</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('lang.switch', $isAr ? 'en' : 'ar') }}"
                   class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-sm font-bold hover:bg-primary hover:text-white transition-all">
                    {{ $isAr ? 'EN' : 'عربي' }}
                </a>
                <button @click="darkMode = !darkMode" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <i data-lucide="moon" x-show="!darkMode" class="w-5 h-5"></i>
                    <i data-lucide="sun" x-show="darkMode" class="w-5 h-5"></i>
                </button>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                    </button>
                </form>
                <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-red-500 to-orange-500 text-white flex items-center justify-center font-bold text-sm">
                    SA
                </div>
            </div>
        </div>
    </header>

    <div class="flex h-screen pt-16">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 {{ $isAr ? 'right-0' : 'left-0' }} z-30 w-64 pt-16 glass transform transition-transform duration-300 md:translate-x-0 border-{{ $isAr ? 'l' : 'r' }} border-gray-200 dark:border-gray-800 shadow-lg"
               :class="{ '{{ $isAr ? 'translate-x-full' : '-translate-x-full' }}': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            <div class="h-full overflow-y-auto p-4 space-y-1">
                @php
                    $navItem = function($route, $icon, $label, $active = null) use ($isAr) {
                        $isActive = $active ? request()->routeIs($active) : request()->routeIs($route);
                        return '<a href="' . route($route) . '" class="' . ($isActive ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800') . ' flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm"><i data-lucide="' . $icon . '" class="w-4 h-4 shrink-0"></i> ' . $label . '</a>';
                    };
                @endphp

                <p class="px-4 pt-2 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Overview</p>
                <a href="{{ route('superadmin.dashboard') }}" class="{{ request()->routeIs('superadmin.dashboard') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i> Dashboard
                </a>

                <p class="px-4 pt-4 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Clinics</p>
                <a href="{{ route('superadmin.clinics.index') }}" class="{{ request()->routeIs('superadmin.clinics.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                    <i data-lucide="building-2" class="w-4 h-4 shrink-0"></i> Manage Clinics
                </a>
                <a href="{{ route('superadmin.subscriptions.index') }}" class="{{ request()->routeIs('superadmin.subscriptions.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                    <i data-lucide="credit-card" class="w-4 h-4 shrink-0"></i> Subscriptions
                </a>
                <a href="{{ route('superadmin.plans.index') }}" class="{{ request()->routeIs('superadmin.plans.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                    <i data-lucide="package" class="w-4 h-4 shrink-0"></i> Plans
                </a>

                <p class="px-4 pt-4 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Platform Finance</p>
                <a href="{{ route('superadmin.accounting.index') }}" class="{{ request()->routeIs('superadmin.accounting.index') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 shrink-0"></i> Accounting
                </a>
                <a href="{{ route('superadmin.accounting.income') }}" class="{{ request()->routeIs('superadmin.accounting.income') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                    <i data-lucide="trending-up" class="w-4 h-4 shrink-0"></i> Income
                </a>
                <a href="{{ route('superadmin.accounting.expenses') }}" class="{{ request()->routeIs('superadmin.accounting.expenses') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                    <i data-lucide="trending-down" class="w-4 h-4 shrink-0"></i> Expenses
                </a>
                <a href="{{ route('superadmin.accounting.reports') }}" class="{{ request()->routeIs('superadmin.accounting.reports') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                    <i data-lucide="file-bar-chart" class="w-4 h-4 shrink-0"></i> Reports
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 transition-all duration-300 {{ $isAr ? 'md:mr-64' : 'md:ml-64' }} p-6 overflow-y-auto">
            <div class="max-w-7xl mx-auto space-y-6">
                @if (session('success'))
                    <div class="p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-green-900/20 dark:text-green-400 border border-green-200 dark:border-green-800">
                        <span class="font-medium">✓</span> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-red-900/20 dark:text-red-400 border border-red-200 dark:border-red-800">
                        <span class="font-medium">✗</span> {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => { lucide.createIcons(); });
    </script>
</body>
</html>
