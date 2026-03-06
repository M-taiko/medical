<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription Expired — Masar Dental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans bg-gray-50 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i data-lucide="clock" class="w-10 h-10 text-amber-500"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-3">Subscription Expired</h1>
        <p class="text-gray-500 mb-2">Your clinic subscription for <strong>{{ $clinic->name }}</strong> has expired.</p>
        <p class="text-gray-400 text-sm mb-8">Please contact the platform administrator to renew your subscription and restore full access.</p>
        <div class="space-y-3">
            <p class="text-sm font-semibold text-gray-700">Contact Support</p>
            <p class="text-gray-500 text-sm">admin@masardental.com</p>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-8">
            @csrf
            <button class="px-6 py-2.5 bg-gray-800 text-white rounded-xl font-semibold hover:bg-gray-900 transition">Sign Out</button>
        </form>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
