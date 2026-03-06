<?php

use Livewire\Volt\Component;
use App\Models\Home;
use App\Services\HomeContext;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $name = '';

    public function createHome()
    {
        $this->validate([
            'name' => 'required|min:1',
        ]);

        $home = Home::create(['name' => $this->name]);
        Auth::user()->homes()->attach($home->id, ['role' => 'owner']);

        app(HomeContext::class)->setCurrentHomeId($home->id);

        $this->name = '';
        return redirect()->route('dashboard')->with('status', '新しい家を作成しました！');
    }

    public function deleteHome($id)
    {
        $home = Auth::user()->homes()->wherePivot('role', 'owner')->find($id);
        
        if ($home) {
            // Check if it's the current home
            $context = app(HomeContext::class);
            if ($context->getCurrentHomeId() == $id) {
                $context->clear();
            }
            
            $home->delete();
        }
    }

    public function with(): array
    {
        return [
            'homes' => Auth::user()->homes()->withPivot('role')->get(),
        ];
    }
}; ?>

<section class="space-y-12">
    <div>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('登録済みの「家」') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('現在管理している住宅の一覧です。') }}
            </p>
        </header>

        <div class="mt-6 space-y-4">
            @forelse($homes as $home)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <div>
                        <p class="font-bold text-gray-900">{{ $home->name }}</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $home->pivot->role === 'owner' ? '所有者' : '閲覧者' }}</p>
                    </div>
                    
                    @if($home->pivot->role === 'owner')
                        <button 
                            wire:click="deleteHome({{ $home->id }})" 
                            wire:confirm="この家と、紐付くすべてのデータ（階、部屋、場所、寸法）を完全に削除しますか？"
                            class="p-2 text-gray-400 hover:text-red-600 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-500 italic">登録された家がありません。</p>
            @endforelse
        </div>
    </div>

    <div class="pt-8 border-t border-gray-100">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('新しい「家」を作成') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('管理する住宅名（例：マイホーム、実家など）を入力してください。') }}
            </p>
        </header>

        <form wire:submit="createHome" class="mt-6 space-y-6">
            <div>
                <x-input-label for="home-name" :value="__('住宅名')" />
                <x-text-input wire:model="name" id="home-name" type="text" class="mt-1 block w-full" placeholder="例：マイホーム" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('保存') }}</x-primary-button>
            </div>
        </form>
    </div>
</section>
