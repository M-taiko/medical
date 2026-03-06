<!DOCTYPE html>
@php $locale = app()->getLocale(); $isAr = $locale === 'ar'; @endphp
<html lang="{{ str_replace('_', '-', $locale) }}" dir="{{ $isAr ? 'rtl' : 'ltr' }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Masar Dental') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @if($isAr)
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    @endif

    <!-- Tailwind CSS (via CDN for fast prototyping, assuming Vite is standard for production) -->
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
                        dark: '#111827',
                    }
                }
            }
        }
    </script>
    <style>
        /* Glassmorphism Classes */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .dark .glass {
            background: rgba(17, 24, 39, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        @if($isAr) body { font-family: 'Cairo', sans-serif !important; } @endif
    </style>

    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100" x-data="{ sidebarOpen: false, darkMode: false }" :class="{ 'dark': darkMode }">

    <!-- Topbar -->
    <header class="glass fixed top-0 w-full z-40 border-b border-gray-200 dark:border-gray-800 shadow-sm transition-all duration-300">
        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary to-secondary flex items-center gap-2">
                    <i data-lucide="tooth" class="text-primary h-6 w-6"></i>
                    Masar Dental
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Language Toggle -->
                <a href="{{ route('lang.switch', $isAr ? 'en' : 'ar') }}"
                   class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-primary hover:text-white hover:border-primary transition-all">
                    {{ $isAr ? 'EN' : 'عربي' }}
                </a>
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <i data-lucide="moon" x-show="!darkMode" class="w-5 h-5"></i>
                    <i data-lucide="sun" x-show="darkMode" class="w-5 h-5"></i>
                </button>

                @auth
                <!-- Notifications Bell -->
                <div class="relative" x-data="{ notifOpen: false }">
                    <button @click="notifOpen = !notifOpen" class="relative p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                        @if($unread > 0)
                        <span class="absolute -top-0.5 -right-0.5 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">{{ $unread > 9 ? '9+' : $unread }}</span>
                        @endif
                    </button>
                    <div x-show="notifOpen" @click.outside="notifOpen = false"
                         class="absolute right-0 mt-2 w-80 glass rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                        <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <p class="font-bold text-sm text-gray-800 dark:text-white">Notifications</p>
                            <a href="{{ route('notifications.index') }}" class="text-xs text-primary hover:underline">View all</a>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            @forelse(auth()->user()->unreadNotifications->take(5) as $notif)
                            <div class="p-3 border-b border-gray-50 dark:border-gray-700 last:border-0">
                                <p class="text-xs font-medium text-gray-800 dark:text-white">{{ $notif->data['message'] ?? 'Notification' }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                            @empty
                            <p class="text-center py-6 text-sm text-gray-400">No new notifications</p>
                            @endforelse
                        </div>
                        @if($unread > 0)
                        <div class="p-3 border-t border-gray-100 dark:border-gray-700">
                            <form method="POST" action="{{ route('notifications.read-all') }}">@csrf
                                <button class="text-xs text-primary hover:underline w-full text-center">Mark all as read</button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- User Info -->
                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-primary to-secondary text-white flex items-center justify-center font-bold text-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-xs font-semibold text-gray-800 dark:text-white leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
                    </div>
                </div>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button class="p-2 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 text-red-500 transition" title="Logout">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </header>

    <!-- Sidebar & Content Layout -->
    <div class="flex h-screen pt-16">
        
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 {{ $isAr ? 'right-0' : 'left-0' }} z-30 w-64 pt-16 glass transform transition-transform duration-300 md:translate-x-0 border-{{ $isAr ? 'l' : 'r' }} border-gray-200 dark:border-gray-800 shadow-lg" :class="{ '{{ $isAr ? 'translate-x-full' : '-translate-x-full' }}': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            <div class="h-full overflow-y-auto p-4 space-y-1">
                @auth
                <p class="px-4 pt-2 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Main</p>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i> {{ __('nav.dashboard') }}
                </a>
                @if(!auth()->user()->isAccountant())
                <a href="{{ route('patients.index') }}" class="{{ request()->routeIs('patients.*') || request()->routeIs('dental-chart.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                    <i data-lucide="users" class="w-4 h-4 shrink-0"></i> {{ __('nav.patients') }}
                </a>
                <a href="{{ route('appointments.index') }}" class="{{ request()->routeIs('appointments.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                    <i data-lucide="calendar" class="w-4 h-4 shrink-0"></i> {{ __('nav.appointments') }}
                </a>
                @endif
                @if(auth()->user()->isDoctor())
                <a href="{{ route('prescriptions.index') }}" class="{{ request()->routeIs('prescriptions.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                    <i data-lucide="file-text" class="w-4 h-4 shrink-0"></i> Prescriptions
                </a>
                <a href="{{ route('treatments.index') }}" class="{{ request()->routeIs('treatments.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                    <i data-lucide="activity" class="w-4 h-4 shrink-0"></i> Treatments
                </a>
                @endif
                @if(auth()->user()->isClinicAdmin() || auth()->user()->isDoctor())
                <a href="{{ route('inventory.index') }}" class="{{ request()->routeIs('inventory.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                    <i data-lucide="package" class="w-4 h-4 shrink-0"></i> {{ __('nav.inventory') }}
                </a>
                <a href="{{ route('suppliers.index') }}" class="{{ request()->routeIs('suppliers.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                    <i data-lucide="truck" class="w-4 h-4 shrink-0"></i> Suppliers
                </a>
                @endif
                @if(auth()->user()->isClinicAdmin() || auth()->user()->isAccountant())
                <a href="{{ route('financials.index') }}" class="{{ request()->routeIs('financials.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                    <i data-lucide="receipt" class="w-4 h-4 shrink-0"></i> {{ __('nav.financials') }}
                </a>
                @endif
                @if(auth()->user()->isClinicAdmin())
                <div class="pt-3">
                    <p class="px-4 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Admin</p>
                    <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                        <i data-lucide="settings" class="w-4 h-4 shrink-0"></i> {{ __('nav.settings') }}
                    </a>
                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                        <i data-lucide="users-cog" class="w-4 h-4 shrink-0"></i> Team Members
                    </a>
                </div>
                @endif
                <div class="pt-3">
                    <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all text-sm">
                        <i data-lucide="bell" class="w-4 h-4 shrink-0"></i> Notifications
                        @php $sidebarUnread = auth()->user()->unreadNotifications->count(); @endphp
                        @if($sidebarUnread > 0)
                        <span class="ml-auto h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">{{ $sidebarUnread }}</span>
                        @endif
                    </a>
                </div>
                @endauth
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 transition-all duration-300 {{ $isAr ? 'md:mr-64' : 'md:ml-64' }} p-6 overflow-y-auto">
            <div class="max-w-7xl mx-auto space-y-6">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                        <span class="font-medium">{{ __('common.success') }}</span> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                        <span class="font-medium">{{ __('common.error') }}</span> {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
