<x-guest-layout class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">

        <div class="w-full max-w-lg bg-white p-12 rounded-3xl shadow-xl">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Login</h1>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus
                           class="w-full px-5 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                           placeholder="you@example.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-5 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Captcha -->
                <div>
                    <label for="captcha" class="block text-sm font-medium text-gray-700 mb-2">Captcha</label>
                    <div class="flex items-center space-x-3 mb-2">
                        <span id="captcha-img" class="border rounded-lg">{!! captcha_img('flat') !!}</span>
                        <button type="button" id="reload"
                                class="px-3 py-1 text-sm bg-gray-200 rounded-lg hover:bg-gray-300 transition">↻</button>
                    </div>
                    <input id="captcha" type="text" name="captcha" placeholder="Masukkan captcha" required
                           class="w-full px-5 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    <x-input-error :messages="$errors->get('captcha')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <label class="flex items-center space-x-2">
                        <input id="remember_me" type="checkbox"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" name="remember">
                        <span>Remember me</span>
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition">
                    Log in
                </button>
            </form>
        </div>

    </div>

    <script>
        document.getElementById('reload').addEventListener('click', function () {
            fetch('/captcha-refresh')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('captcha-img').innerHTML = data.captcha;
                });
        });
    </script>
</x-guest-layout>