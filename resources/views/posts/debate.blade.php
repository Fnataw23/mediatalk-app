<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-4xl text-cohere-primary leading-tight tracking-tight">
            {{ __('Weekly Debate') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:debate-card />
        </div>
    </div>
</x-app-layout>

