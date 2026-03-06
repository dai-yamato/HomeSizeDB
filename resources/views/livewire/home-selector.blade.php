<?php

use Livewire\Volt\Component;
use App\Models\Home;
use App\Services\HomeContext;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public function selectHome($id)
    {
        app(HomeContext::class)->setCurrentHomeId($id);
        return redirect()->route('dashboard');
    }

    public function with(): array
    {
        return [
            'homes' => Auth::user()?->homes ?? [],
            'currentHomeId' => app(HomeContext::class)->getCurrentHomeId(),
        ];
    }
}; ?>

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 shadow-sm transition-all focus:ring-2 focus:ring-indigo-500">
        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        <span>
            @php $currentHome = $homes->firstWhere('id', $currentHomeId); @endphp
            {{ $currentHome ? $currentHome->name : '家を選択' }}
        </span>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>

    <div x-show="open" @click.away="open = false" 
        class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 animate-in fade-in slide-in-from-top-2 duration-200"
        x-cloak>
        <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">マイホーム</div>
        @foreach($homes as $home)
            <button wire:click="selectHome({{ $home->id }})" 
                class="w-full text-left px-4 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors {{ $home->id == $currentHomeId ? 'text-indigo-600 bg-indigo-50/50' : 'text-gray-700' }}">
                <span class="font-medium">{{ $home->name }}</span>
                @if($home->id == $currentHomeId)
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                @endif
            </button>
        @endforeach
        <div class="border-t border-gray-100 my-1"></div>
        <a href="{{ route('profile') }}" class="block px-4 py-3 text-sm text-gray-500 hover:bg-gray-50">
            家を追加・設定
        </a>
    </div>
</div>
