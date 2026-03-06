<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('住宅寸法管理') }}
            </h2>
            <livewire:home-selector />
        </div>
    </x-slot>

    <div class="py-2 sm:py-6">
        <div class="max-w-7xl mx-auto px-0 sm:px-6 lg:px-8">
            <livewire:home-navigator />
        </div>
    </div>
</x-app-layout>
