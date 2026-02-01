<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - Login</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-100">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-10 sm:pt-0">
        <!-- Logo -->
        <div class="mb-6">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <!-- Login Form Container -->
        <div class="w-full max-w-lg bg-white p-12 rounded-3xl shadow-xl">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Login</h1>

            <!-- ✅ Session Status (contoh: "Password reset berhasil") -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- ✅ Global Error (contoh: login gagal) -->
            @if (session('error'))
                <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- ✅ Semua Error (opsional, bisa dihapus kalau tidak mau list semua error sekaligus) -->
            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-5 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}"
                           placeholder="masukkan email Anda">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-5 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}"
                           placeholder="masukkan passsword Anda">
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
                           class="w-full px-5 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition border-gray-300">
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

</body>
</html>
