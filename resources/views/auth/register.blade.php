<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - My Laravel App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-purple-600 min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8 text-white">

        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('images/my-logo.png') }}" alt="Logo" class="w-24 h-24 mb-4 drop-shadow-lg">
            <h2 class="text-3xl font-bold">Create Your Account</h2>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium">Name</label>
                <input id="name" name="name" type="text" required autofocus
                       class="mt-1 w-full bg-white/20 text-white border-none rounded-md focus:ring-2 focus:ring-yellow-400 p-2"
                       value="{{ old('name') }}">
                @error('name')
                    <div class="text-yellow-300 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mt-4">
                <label for="email" class="block text-sm font-medium">Email</label>
                <input id="email" name="email" type="email" required
                       class="mt-1 w-full bg-white/20 text-white border-none rounded-md focus:ring-2 focus:ring-yellow-400 p-2"
                       value="{{ old('email') }}">
                @error('email')
                    <div class="text-yellow-300 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mt-4">
                <label for="password" class="block text-sm font-medium">Password</label>
                <input id="password" name="password" type="password" required
                       class="mt-1 w-full bg-white/20 text-white border-none rounded-md focus:ring-2 focus:ring-yellow-400 p-2">
                @error('password')
                    <div class="text-yellow-300 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <label for="password_confirmation" class="block text-sm font-medium">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="mt-1 w-full bg-white/20 text-white border-none rounded-md focus:ring-2 focus:ring-yellow-400 p-2">
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('login') }}" class="text-sm text-white hover:text-yellow-300 transition">
                    Already registered?
                </a>
                <button type="submit"
                        class="bg-yellow-400 text-gray-900 font-semibold py-2 px-6 rounded-lg shadow hover:bg-yellow-500 transition">
                    Register
                </button>
            </div>
        </form>
    </div>
</body>
</html>
