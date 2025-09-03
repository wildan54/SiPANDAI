<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Test Tailwind</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Login</h1>

        <form action="#" method="POST" class="space-y-4">
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" 
                    placeholder="you@example.com">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    placeholder="••••••••">
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label class="flex items-center text-sm text-gray-600">
                    <input type="checkbox" class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    Remember me
                </label>
                <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
            </div>

            <!-- Button -->
            <button type="submit"
                class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Sign In
            </button>
        </form>
    </div>

</body>
</html>
