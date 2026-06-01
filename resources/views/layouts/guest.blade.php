<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>MediaTalk — Watch. Discuss. Learn.</title>

        <!-- Google Fonts: Space Grotesk & Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-body antialiased bg-[#0b0b0e] text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#0b0b0e]">
            <div class="mb-4">
                <a href="{{ url('/') }}" class="font-display font-semibold tracking-tight text-3xl text-white hover:opacity-80 transition">
                    MediaTalk<span class="text-[#00f5d4]">.</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-[#121217] border border-white/10 rounded-cohere-lg shadow-xl overflow-hidden">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
