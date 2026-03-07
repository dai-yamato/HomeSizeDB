<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-8">
    <div class="text-center">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">はじめましょう！</h1>
        <p class="mt-2 text-gray-500 font-medium">アカウントを作成して住宅データを整理しましょう</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="register" class="space-y-5">
        <!-- Name -->
        <div class="space-y-1">
            <x-input-label for="name" :value="__('Name')" class="ml-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest" />
            <x-text-input wire:model="name" id="name" class="block w-full bg-gray-50/50 border-gray-100 rounded-2xl focus:ring-indigo-500/10 focus:border-indigo-500 p-4 font-bold" type="text" name="name" required autofocus autocomplete="name" placeholder="山田 太郎" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="space-y-1">
            <x-input-label for="email" :value="__('Email')" class="ml-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest" />
            <x-text-input wire:model="email" id="email" class="block w-full bg-gray-50/50 border-gray-100 rounded-2xl focus:ring-indigo-500/10 focus:border-indigo-500 p-4 font-bold" type="email" name="email" required autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <x-input-label for="password" :value="__('Password')" class="ml-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest" />
            <x-text-input wire:model="password" id="password" class="block w-full bg-gray-50/50 border-gray-100 rounded-2xl focus:ring-indigo-500/10 focus:border-indigo-500 p-4 font-bold"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="ml-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block w-full bg-gray-50/50 border-gray-100 rounded-2xl focus:ring-indigo-500/10 focus:border-indigo-500 p-4 font-bold"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-4">
            <button class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 active:scale-[0.98] transition-all duration-200">
                {{ __('アカウントを作成') }}
            </button>
        </div>

        <p class="text-center text-sm text-gray-500 font-medium pb-2">
            すでにアカウントをお持ちですか？
            <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline" wire:navigate>
                ログイン
            </a>
        </p>
    </form>
</div>
