<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masar Dental - Enterprise Clinic Management</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

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
                        accent: '#10B981',
                        dark: '#0f172a',
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }
    </style>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 selection:bg-primary selection:text-white overflow-x-hidden">

    <!-- Background Elements -->
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center shadow-lg shadow-primary/30">
                        <i data-lucide="tooth" class="w-6 h-6 text-white"></i>
                    </div>
                    <span class="text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-primary to-secondary">
                        Masar Dental
                    </span>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#features" class="text-slate-600 hover:text-primary font-medium transition-colors">Features</a>
                    <a href="#about" class="text-slate-600 hover:text-primary font-medium transition-colors">Platform</a>
                    <a href="#testimonials" class="text-slate-600 hover:text-primary font-medium transition-colors">Why Us</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="hidden md:flex items-center gap-2 text-slate-600 hover:text-primary font-semibold transition-colors">
                        Sign In
                    </a>
                    <a href="{{ route('login') }}" class="flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl font-semibold shadow-xl shadow-slate-900/20 transition-all transform hover:-translate-y-0.5">
                        Get Started <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 sm:pt-40 sm:pb-24 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-card text-primary font-semibold text-sm mb-8 animate-bounce">
                <span class="flex h-2 w-2 rounded-full bg-accent"></span>
                Next-Gen SaaS For Modern Clinics
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 mb-8 max-w-4xl mx-auto leading-tight">
                The Operating System For Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Dental Practice</span>
            </h1>
            
            <p class="mt-6 text-xl text-slate-600 max-w-2xl mx-auto mb-10 leading-relaxed">
                Elevate your clinical workflow with Interactive SVG Odontograms, Automated WhatsApp PDF Reports, Multi-Tenant Architecture, and Smart Financials.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('login') }}" class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-4 text-lg font-bold rounded-2xl text-white bg-gradient-to-r from-primary to-secondary hover:from-indigo-600 hover:to-purple-700 shadow-xl shadow-primary/30 transition-all transform hover:-translate-y-1">
                    Enter Dashboard
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                </a>
                <a href="#features" class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-4 text-lg font-bold rounded-2xl text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 shadow-sm transition-all">
                    Explore Features
                    <i data-lucide="play-circle" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Interactive Odontogram Teaser -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-32 -mt-10 relative z-10">
        <div class="glass-card rounded-[2rem] p-2 md:p-4 shadow-2xl border border-white/60">
            <div class="bg-slate-900 rounded-[1.5rem] overflow-hidden relative" style="height: 500px;">
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-20">
                    <i data-lucide="activity" class="w-96 h-96 text-primary"></i>
                </div>
                <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-slate-900 to-transparent"></div>
                
                <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center p-8">
                    <h3 class="text-3xl font-bold mb-4">Interactive 3D-feel Dental Charts</h3>
                    <p class="text-slate-300 max-w-md mb-8">Click a tooth. Log diagnostics. Generate treatments. Fully visual and seamless.</p>
                    <a href="{{ route('login') }}" class="glass-card text-white border-white/20 px-6 py-3 rounded-xl font-semibold hover:bg-white hover:text-slate-900 transition-all">
                        Experience It Live
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 bg-white/50 border-t border-slate-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-primary font-semibold tracking-wide uppercase text-sm mb-3">Enterprise Capabilities</h2>
                <h3 class="text-4xl font-extrabold text-slate-900 mt-2 mb-4">Everything you need to scale</h3>
                <p class="text-lg text-slate-600">Masar Dental replaces 5 different software tools with one beautifully unified Multi-Tenant ecosystem.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="glass-card rounded-2xl p-8 hover:-translate-y-2 transition-transform duration-300 group border border-slate-100">
                    <div class="w-14 h-14 bg-indigo-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-primary transition-colors duration-300">
                        <i data-lucide="tooth" class="w-7 h-7 text-primary group-hover:text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Interactive Odontogram</h4>
                    <p class="text-slate-600">Select FDI teeth, record 'Decay', 'Crowns', and sync directly into precise patient histories visually.</p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-card rounded-2xl p-8 hover:-translate-y-2 transition-transform duration-300 group border border-slate-100">
                    <div class="w-14 h-14 bg-emerald-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-accent transition-colors duration-300">
                        <i data-lucide="message-circle" class="w-7 h-7 text-accent group-hover:text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">WhatsApp PDF Automation</h4>
                    <p class="text-slate-600">1-Click treatment finalization generates an elegant DomPDF report and sends it via WhatsApp API instantly.</p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-card rounded-2xl p-8 hover:-translate-y-2 transition-transform duration-300 group border border-slate-100">
                    <div class="w-14 h-14 bg-purple-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-secondary transition-colors duration-300">
                        <i data-lucide="building" class="w-7 h-7 text-secondary group-hover:text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">SaaS Multi-Tenancy</h4>
                    <p class="text-slate-600">Manage multiple clinics easily. Data is strictly isolated via Global Scopes and BelongsToClinic relations.</p>
                </div>

                <!-- Feature 4 -->
                <div class="glass-card rounded-2xl p-8 hover:-translate-y-2 transition-transform duration-300 group border border-slate-100">
                    <div class="w-14 h-14 bg-orange-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-orange-500 transition-colors duration-300">
                        <i data-lucide="package" class="w-7 h-7 text-orange-500 group-hover:text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Smart Inventory</h4>
                    <p class="text-slate-600">Automatic stock deduction linked to treatment templates. Get alerts organically when items are low.</p>
                </div>

                <!-- Feature 5 -->
                <div class="glass-card rounded-2xl p-8 hover:-translate-y-2 transition-transform duration-300 group border border-slate-100">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-blue-600 transition-colors duration-300">
                        <i data-lucide="receipt" class="w-7 h-7 text-blue-600 group-hover:text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Advanced Financials</h4>
                    <p class="text-slate-600">Track partial payments, generate invoices, map remaining balances and view net-profit instantly.</p>
                </div>

                <!-- Feature 6 -->
                <div class="glass-card rounded-2xl p-8 hover:-translate-y-2 transition-transform duration-300 group border border-slate-100">
                    <div class="w-14 h-14 bg-pink-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-pink-500 transition-colors duration-300">
                        <i data-lucide="layout" class="w-7 h-7 text-pink-500 group-hover:text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Modern UI/UX</h4>
                    <p class="text-slate-600">Built using Tailwind CSS, Blade Components, Alpine.js, and Lucide icons. A beautiful flow.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-slate-900 z-0"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-primary/30 to-secondary/30 z-0 mix-blend-overlay"></div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6">Ready to digitize your clinic?</h2>
            <p class="text-xl text-slate-300 mb-10 max-w-2xl mx-auto">Join Masar Dental today and experience the fastest, most beautifully designed Clinic Management System available.</p>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-3 px-8 py-4 text-lg font-bold rounded-xl text-slate-900 bg-white hover:bg-slate-50 hover:scale-105 transition-all shadow-[0_0_40px_rgba(255,255,255,0.3)]">
                Launch System Now <i data-lucide="rocket" class="w-5 h-5"></i>
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-950 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center gap-2 mb-4 md:mb-0">
                    <div class="w-8 h-8 bg-gradient-to-br from-primary to-secondary rounded-lg flex items-center justify-center shadow-lg">
                        <i data-lucide="tooth" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="text-xl font-bold text-white">Masar Dental</span>
                </div>
                <div class="text-slate-500 text-sm">
                    &copy; {{ date('Y') }} Masar Systems. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
