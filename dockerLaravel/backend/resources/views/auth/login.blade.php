<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6 text-center">Sign in</h1>

        @if ($errors->any())
            <div class="mb-4 rounded bg-red-100 text-red-700 p-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium mb-1">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-1">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" class="mr-2">
                <label for="remember" class="text-sm">Remember me</label>
            </div>

            <button
                type="submit"
                class="w-full bg-black text-white rounded px-4 py-2"
            >
                Login
            </button>
        </form>
    </div>
</body>
</html>