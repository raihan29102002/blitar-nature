<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center py-6 px-4 bg-teal-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-40 z-0"></div>
        <div class="relative z-10 grid md:grid-cols-2 items-center gap-10 max-w-6xl max-md:max-w-md w-full text-white">
            <div class="flex flex-col justify-center">
                <h2 class="lg:text-5xl text-3xl font-bold lg:leading-[57px]">
                    Reset Your Password
                </h2>
                <p class="text-sm mt-6 text-slate-300 leading-relaxed">
                    Forgot your password? No worries. Enter your email address and we’ll send you a link to reset it.
                </p>
                <p class="text-sm mt-12 text-slate-300">
                    Remember your password?
                    <a href="{{ route('login') }}" class="text-lime-400 font-medium hover:underline ml-1">Login here</a>
                </p>
            </div>

            <div class="bg-white bg-opacity-10 backdrop-blur-md rounded-xl p-6 shadow-lg">
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <h3 class="text-white lg:text-3xl text-2xl font-bold mb-8">
                        Forgot Password
                    </h3>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Email -->
                    <div>
                        <label for="email" class='text-sm text-white font-medium mb-2 block'>Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                               placeholder="Enter your email"
                               class="bg-white bg-opacity-20 w-full text-sm text-white px-4 py-3 rounded-md outline-0 border border-white placeholder-white focus:border-lime-400 focus:bg-transparent" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <div class="!mt-12">
                        <button type="submit"
                                class="w-full py-2.5 px-4 text-sm font-semibold rounded text-white bg-transparent border border-lime-400 hover:bg-lime-500 hover:text-white transition duration-200">
                            Send Reset Link
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
