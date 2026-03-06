<!DOCTYPE html>
@php $locale = app()->getLocale(); $isAr = $locale === 'ar'; @endphp
<html lang="{{ str_replace('_', '-', $locale) }}" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Masar Dental</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @if($isAr)<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet" />@endif

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: '#4F46E5',
                        secondary: '#7C3AED',
                    }
                }
            }
        }
    </script>
    <style>
        @if($isAr) body { font-family: 'Cairo', sans-serif !important; } @endif
    .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }
    </style>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-100 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Background Decoration -->
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-secondary/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>

    <div class="fixed top-4 right-4 z-50"><a href="{{ route('lang.switch', $isAr ? 'en' : 'ar') }}" class="px-3 py-1.5 rounded-lg border border-gray-300 text-sm font-bold bg-white/70 hover:bg-white transition-all">{{ $isAr ? 'EN' : 'عربي' }}</a></div>
    <div class="w-full max-w-md relative z-10 glass-panel p-8 sm:p-10 rounded-[2rem]">
        
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center shadow-xl shadow-primary/30 mx-auto mb-6 transform hover:scale-105 transition-transform cursor-pointer">
                <i data-lucide="tooth" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 mb-2">{{ __('auth.welcome_back') }}</h1>
            <p class="text-slate-500 font-medium">{{ __('auth.login_subtitle') }}</p>
        </div>

        @if(session('error'))
        <div class="p-4 mb-6 text-sm text-red-800 rounded-xl bg-red-100/50 border border-red-200">
            <span class="font-bold flex items-center gap-2"><i data-lucide="alert-circle" class="w-4 h-4"></i> {{ session('error') }}</span>
        </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('auth.email') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="h-5 w-5 text-slate-400"></i>
                    </div>
                    <input type="email" name="email" value="doctor@masardental.com" required class="w-full pl-11 pr-4 py-3 bg-white/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder-slate-400 text-slate-700 font-medium" placeholder="doctor@masardental.com">
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-bold text-slate-700">{{ __('auth.password') }}</label>
                    <a href="#" class="text-xs font-semibold text-primary hover:text-secondary transition-colors">{{ __('auth.forgot_password') }}</a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="h-5 w-5 text-slate-400"></i>
                    </div>
                    <input type="password" name="password" value="password" required class="w-full pl-11 pr-4 py-3 bg-white/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder-slate-400 text-slate-700 font-medium" placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center">
                <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-slate-300 rounded text-primary">
                <label for="remember-me" class="ml-2 block text-sm text-slate-600 font-medium">
                    {{ __('auth.remember_me') }}
                </label>
            </div>

            <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 hover:shadow-xl hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900">
                {{ __('auth.sign_in') }} <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-slate-500">
            {{ __('auth.demo_hint') }}
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
