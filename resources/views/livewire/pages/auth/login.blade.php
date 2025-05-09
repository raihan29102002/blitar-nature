<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $role = Auth::user()->role;

    // if ($role === 'admin') {
    //     $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
    // } else {
    //     $this->redirectIntended(default: route('wisatawan.dashboard', absolute: false), navigate: true);
    // }
};

?>

<div class="min-h-screen flex flex-col items-center justify-center py-6 px-4">
    <div class="grid md:grid-cols-2 items-center gap-10 max-w-6xl max-md:max-w-md w-full">
      
      <!-- Left Content -->
      <div>
        <h2 class="lg:text-5xl text-3xl font-bold lg:leading-[57px] text-slate-900">
          Seamless Login for Exclusive Access
        </h2>
        <p class="text-sm mt-6 text-slate-500 leading-relaxed">
          Immerse yourself in a hassle-free login journey with our intuitively designed login form.
        </p>
        <p class="text-sm mt-12 text-slate-500">
          Don't have an account?
          <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline ml-1">Register here</a>
        </p>
      </div>
      <x-auth-session-status class="mb-4" :status="session('status')" />
  
      <!-- Right Form -->
      <form wire:submit="login" class="max-w-md md:ml-auto w-full">
        <h3 class="text-slate-900 lg:text-3xl text-2xl font-bold mb-8">Sign in</h3>
  
        <div class="space-y-6">
          <!-- Email -->
          <div>
            <label class='text-sm text-slate-800 font-medium mb-2 block'>Email</label>
            <input wire:model.defer="form.email" type="email" required
              class="bg-slate-100 w-full text-sm text-slate-800 px-4 py-3 rounded-md outline-0 border border-gray-200 focus:border-blue-600 focus:bg-transparent"
              placeholder="Enter Email" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
          </div>
  
          <!-- Password -->
          <div>
            <label class='text-sm text-slate-800 font-medium mb-2 block'>Password</label>
            <input wire:model.defer="form.password" type="password" required
              class="bg-slate-100 w-full text-sm text-slate-800 px-4 py-3 rounded-md outline-0 border border-gray-200 focus:border-blue-600 focus:bg-transparent"
              placeholder="Enter Password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
          </div>
  
          <!-- Remember + Forgot -->
          <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center">
              <input wire:model="form.remember" id="remember-me" type="checkbox"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded" />
              <label for="remember-me" class="ml-3 block text-sm text-slate-500">Remember me</label>
            </div>
            @if (Route::has('password.request'))
              <div class="text-sm">
                <a href="{{ route('password.request') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                  Forgot your password?
                </a>
              </div>
            @endif
          </div>
        </div>
  
        <!-- Submit Button -->
        <div class="!mt-12">
          <button type="submit"
            class="w-full shadow-xl py-2.5 px-4 text-sm font-semibold rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none cursor-pointer">
            Log in
          </button>
        </div>
  
        <!-- Optional social login -->
        <div class="my-4 flex items-center gap-4">
          <hr class="w-full border-slate-300" />
          <p class="text-sm text-slate-800 text-center">or</p>
          <hr class="w-full border-slate-300" />
        </div>
        <!-- Social login buttons here... -->
      </form>
    </div>
</div>
  
