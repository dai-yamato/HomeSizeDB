<?php

use Livewire\Volt\Component;
use App\Models\Home;
use App\Models\Invitation;
use App\Services\HomeContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

new class extends Component {
    public $email = '';
    public $role = 'viewer';

    public function invite()
    {
        $this->validate([
            'email' => 'required|email',
            'role' => 'required|in:owner,editor,viewer',
        ]);

        $homeId = app(HomeContext::class)->getCurrentHomeId();
        if (!$homeId) return;

        $invitation = Invitation::create([
            'home_id' => $homeId,
            'email' => $this->email,
            'role' => $this->role,
            'token' => Str::random(32),
            'expires_at' => now()->addDays(7),
        ]);

        $url = URL::temporarySignedRoute(
            'invitations.accept',
            now()->addDays(7),
            ['token' => $invitation->token]
        );

        $this->email = '';
        $this->dispatch('invitation-sent', url: $url);
    }

    public function with(): array
    {
        $homeId = app(HomeContext::class)->getCurrentHomeId();
        $home = $homeId ? Home::with('users')->find($homeId) : null;

        return [
            'home' => $home,
            'invitations' => $homeId ? Invitation::where('home_id', $homeId)->where('expires_at', '>', now())->get() : [],
        ];
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('メンバー招待・管理') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('現在選択中の「家」に家族やメンバーを招待できます。') }}
        </p>
    </header>

    @if($home)
        <div class="mt-6 space-y-6">
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <h3 class="text-sm font-bold text-gray-700 mb-3">現在のメンバー</h3>
                <ul class="space-y-2">
                    @foreach($home->users as $user)
                        <li class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold text-xs">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ $user->name }}</span>
                            </div>
                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-500 rounded-full uppercase">{{ $user->pivot->role }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <form wire:submit="invite" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="invite-email" :value="__('メールアドレス')" />
                        <x-text-input wire:model="email" id="invite-email" type="email" class="mt-1 block w-full" placeholder="family@example.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="invite-role" :value="__('権限')" />
                        <select wire:model="role" id="invite-role" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="viewer">閲覧者 (Viewer)</option>
                            <option value="editor">編集者 (Editor)</option>
                            <option value="owner">管理者 (Owner)</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('招待を送る') }}</x-primary-button>
                </div>
            </form>

            @if(count($invitations) > 0)
                <div class="mt-6">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 text-amber-600">承諾待ちの招待</h3>
                    <ul class="space-y-2">
                        @foreach($invitations as $inv)
                            <li class="flex items-center justify-between bg-amber-50 p-3 rounded-lg border border-amber-100">
                                <span class="text-sm text-amber-800">{{ $inv->email }}</span>
                                <span class="text-[10px] text-amber-500 italic">Expires {{ $inv->expires_at->diffForHumans() }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div x-data="{ show: false, url: '' }" 
             x-on:invitation-sent.window="show = true; url = $event.detail.url"
             x-show="show" 
             class="mt-4 p-4 bg-green-50 border border-green-200 rounded-xl animate-in fade-in slide-in-from-top-1"
             x-cloak>
            <p class="text-sm text-green-800 font-bold mb-2">招待リンクを作成しました:</p>
            <div class="flex items-center gap-2">
                <input type="text" x-model="url" readonly class="flex-1 bg-white border-green-200 rounded-lg text-xs p-2 text-gray-500">
                <button @click="navigator.clipboard.writeText(url); alert('コピーしました')" class="bg-green-600 text-white px-3 py-2 rounded-lg text-xs font-bold">Copy</button>
            </div>
            <p class="mt-2 text-[10px] text-green-600">※デモ版のため招待メールは送信されません。このURLを直接メンバーに送ってください。</p>
        </div>
    @else
        <p class="mt-4 text-sm text-red-500 bg-red-50 p-4 rounded-xl">家が選択されていません。先に家を作成または選択してください。</p>
    @endif
</section>
