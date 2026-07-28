<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>بلدية إذنا - بوابة الخدمات الإلكترونية</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-background font-sans">

<div class="min-h-screen">
    {{-- Public Navbar --}}
    <header class="bg-surface border-b border-border">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 no-underline">
                <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center shrink-0">
                    <img src="{{ asset('logo.png') }}" alt="بلدية إذنا" class="w-7 h-7 object-contain">
                </div>
                <span class="font-bold text-text text-sm whitespace-nowrap">بلدية إذنا</span>
            </a>
            <nav class="flex items-center gap-5" aria-label="القائمة الرئيسية">
                <a href="{{ route('public.services.index') }}" class="text-sm text-text-secondary hover:text-primary transition-colors whitespace-nowrap no-underline @if(request()->routeIs('public.services.*')) text-primary font-semibold @endif">الخدمات</a>
                <a href="{{ route('public.announcements.index') }}" class="text-sm text-text-secondary hover:text-primary transition-colors whitespace-nowrap no-underline @if(request()->routeIs('public.announcements.*')) text-primary font-semibold @endif">الإعلانات</a>
                <a href="{{ route('home') }}" class="text-sm text-text-secondary hover:text-primary transition-colors whitespace-nowrap no-underline">الرئيسية</a>
                <a href="{{ route('login') }}" class="text-sm text-text-secondary hover:text-primary transition-colors whitespace-nowrap no-underline">تسجيل الدخول</a>
            </nav>
        </div>
    </header>

    {{-- Main Content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-surface border-t border-border mt-12">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="text-center text-xs text-text-tertiary">
                <p>جميع الحقوق محفوظة &copy; {{ date('Y') }} بلدية إذنا</p>
            </div>
        </div>
    </footer>
</div>

@livewireScripts
@stack('scripts')
</body>
</html>
