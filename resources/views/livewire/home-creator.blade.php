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
}; ?>

<section>
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
</section>
