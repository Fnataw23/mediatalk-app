<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MediaTalk') }}</title>

        <!-- Google Fonts: Space Grotesk & Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-body antialiased bg-white text-cohere-primary">
        <!-- Toast notifications block -->
        @if(session('xp_gained') || session('level_up') || session('achievement_unlocked'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" class="fixed bottom-5 right-5 z-[9999] space-y-2">
                @if(session('level_up'))
                    <div class="bg-cohere-deep-green text-white px-6 py-3.5 rounded-cohere-sm shadow-lg border border-cohere-pale-green/20 text-sm font-mono uppercase tracking-wide">
                        {{ session('level_up') }}
                    </div>
                @endif
                @if(session('achievement_unlocked'))
                    <div class="bg-cohere-primary text-white px-6 py-3.5 rounded-cohere-sm shadow-lg border border-cohere-coral/20 text-sm font-mono uppercase tracking-wide">
                        {!! session('achievement_unlocked') !!}
                    </div>
                @endif
                @if(session('xp_gained'))
                    <div class="bg-cohere-stone text-cohere-primary px-6 py-3 rounded-cohere-sm shadow-md border border-cohere-hairline text-xs font-mono uppercase tracking-wide">
                        {{ session('xp_gained') }}
                    </div>
                @endif
            </div>
        @endif

        <div class="min-h-screen bg-white">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white border-b border-cohere-hairline">
                    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        @livewireScripts
    </body>
</html>
