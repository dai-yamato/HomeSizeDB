<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-8">
    <div class="text-center">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">おかえりなさい！</h1>
        <p class="mt-2 text-gray-500 font-medium">ログインして住宅データを管理しましょう</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        <!-- Email Address -->
        <div class="space-y-1">
            <x-input-label for="email" :value="__('Email')" class="ml-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest" />
            <x-text-input wire:model="form.email" id="email" class="block w-full bg-gray-50/50 border-gray-100 rounded-2xl focus:ring-indigo-500/10 focus:border-indigo-500 p-4 font-bold" type="email" name="email" required autofocus autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <div class="flex items-center justify-between px-1">
                <x-input-label for="password" :value="__('Password')" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-indigo-600 font-bold hover:text-indigo-800" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot?') }}
                    </a>
                @endif
            </div>

            <x-text-input wire:model="form.password" id="password" class="block w-full bg-gray-50/50 border-gray-100 rounded-2xl focus:ring-indigo-500/10 focus:border-indigo-500 p-4 font-bold"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember" class="inline-flex items-center cursor-pointer group">
                <input wire:model="form.remember" id="remember" type="checkbox" class="w-5 h-5 rounded-lg border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 transition-all cursor-pointer" name="remember">
                <span class="ms-3 text-sm text-gray-600 font-medium group-hover:text-gray-900 transition-colors">{{ __('ログイン状態を保持') }}</span>
            </label>
        </div>

        <div class="pt-4">
            <button class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 active:scale-[0.98] transition-all duration-200">
                {{ __('ログイン') }}
            </button>
        </div>

        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-500 font-medium">
                アカウントをお持ちでないですか？
                <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline" wire:navigate>
                    新規登録
                </a>
            </p>
        @endif
    </form>
</div>
