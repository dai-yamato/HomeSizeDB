<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-slate-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-indigo-100 via-slate-50 to-white">
            <div class="mb-8">
                <a href="/" wire:navigate class="flex flex-col items-center gap-2 group">
                    <div class="w-16 h-16 bg-white rounded-[2rem] shadow-xl shadow-indigo-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                        <x-application-logo class="w-10 h-10 text-indigo-600" />
                    </div>
                    <span class="text-xl font-black text-gray-900 tracking-tighter">HomeSize DB</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-8 py-10 bg-white/80 backdrop-blur-xl shadow-[0_32px_64px_-15px_rgba(0,0,0,0.1)] border border-white/50 overflow-hidden rounded-[2.5rem]">
                {{ $slot }}
            </div>

            <p class="mt-8 text-sm text-gray-400 font-medium">© {{ date('Y') }} (株)GooDy. All rights reserved.</p>
        </div>
    </body>
</html>
