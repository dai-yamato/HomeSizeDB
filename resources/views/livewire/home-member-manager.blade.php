<?php

use Livewire\Volt\Component;
use App\Models\Home;
use App\Models\Invitation;
use App\Services\HomeContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

new class extends Component {
    public $role = 'viewer';

    public function invite()
    {
        $this->validate([
            'role' => 'required|in:owner,editor,viewer',
        ]);

        $homeId = app(HomeContext::class)->getCurrentHomeId();
        if (!$homeId) return;

        $invitation = Invitation::create([
            'home_id' => $homeId,
            'email' => null, // No email required for link-based invitation
            'role' => $this->role,
            'token' => Str::random(32),
            'expires_at' => now()->addDays(7),
        ]);

        $url = URL::temporarySignedRoute(
            'invitations.accept',
            now()->addDays(7),
            ['token' => $invitation->token]
        );

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
            {{ __('招待リンクを発行して、家族やパートナーをこの「家」に招待できます。') }}
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
                                <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold text-xs uppercase">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ $user->name }}</span>
                            </div>
                            <span class="text-[10px] px-2 py-1 bg-gray-100 text-gray-500 rounded-full font-bold uppercase">{{ $user->pivot->role }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm">
                <h3 class="text-sm font-bold text-gray-700 mb-4">新しい招待リンクを発行</h3>
                <div class="flex flex-col sm:flex-row items-end gap-4">
                    <div class="flex-1 w-full">
                        <x-input-label for="invite-role" :value="__('付与する権限')" />
                        <select wire:model="role" id="invite-role" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-all">
                            <option value="viewer">閲覧者 (Viewer) - 閲覧のみ</option>
                            <option value="editor">編集者 (Editor) - 追加・削除可能</option>
                            <option value="owner">管理者 (Owner) - メンバー招待も可能</option>
                        </select>
                    </div>
                    <button wire:click="invite" class="w-full sm:w-auto px-6 py-2.5 bg-indigo-600 text-white text-sm font-black rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition-all">
                        招待リンクを作成
                    </button>
                </div>
            </div>

            <div x-data="{ show: false, url: '' }" 
                 x-on:invitation-sent.window="show = true; url = $event.detail.url"
                 x-show="show" 
                 class="p-6 bg-indigo-50 rounded-3xl border border-indigo-100 animate-in zoom-in-95 duration-300"
                 x-cloak>
                <div class="flex flex-col md:flex-row gap-6 items-center">
                    <div class="shrink-0 bg-white p-3 rounded-2xl shadow-sm">
                        <img :src="'https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=' + encodeURIComponent(url)" 
                             alt="QR Code" 
                             class="w-32 h-32 rounded-lg">
                    </div>
                    <div class="flex-1 space-y-3">
                        <p class="text-sm text-indigo-900 font-bold">招待URLを共有してください:</p>
                        <div class="flex items-center gap-2">
                            <input type="text" x-model="url" readonly class="flex-1 bg-white border-indigo-200 rounded-xl text-xs p-3 text-gray-500 focus:ring-0">
                            <button @click="navigator.clipboard.writeText(url); alert('コピーしました')" 
                                    class="bg-indigo-600 text-white px-4 py-3 rounded-xl text-xs font-black shadow-lg shadow-indigo-100 active:scale-95 transition-all">
                                Copy
                            </button>
                        </div>
                        <p class="text-[10px] text-indigo-400 font-medium italic">※このリンクは7日間有効です。相手がログインすると「家」が共有されます。</p>
                    </div>
                </div>
            </div>

            @if(count($invitations) > 0)
                <div class="mt-8 pt-8 border-t border-gray-100">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">有効な招待リンク</h3>
                    <div class="space-y-2">
                        @foreach($invitations as $inv)
                            <div class="flex items-center justify-between bg-white px-4 py-2 rounded-xl border border-gray-100 italic">
                                <span class="text-xs text-gray-400 font-bold uppercase">{{ $inv->role }} Link</span>
                                <span class="text-[10px] text-gray-300">Expires {{ $inv->expires_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="mt-4 p-8 bg-slate-50 border border-slate-100 rounded-[2rem] text-center">
            <p class="text-slate-400 font-bold italic text-sm">家が選択されていないため、招待機能は利用できません。</p>
        </div>
    @endif
</section>
