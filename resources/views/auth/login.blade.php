<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-600">
  <div class="bg-white bg-opacity-20 backdrop-blur-lg shadow-lg rounded-2xl w-full max-w-md p-8 text-center text-white">
    <img src="/images/my-logo.png" alt="Logo" class="mx-auto mb-4 w-20 h-20 object-contain">
    <h1 class="text-2xl font-bold mb-6">Welcome Back</h1>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div class="text-left">
            <label for="email" class="font-semibold">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="mt-1 w-full p-3 rounded-lg bg-white bg-opacity-30 focus:bg-opacity-50 outline-none">
            @error('email')<span class="text-red-300 text-sm">{{ $message }}</span>@enderror
        </div>
        <div class="text-left">
            <label for="password" class="font-semibold">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="mt-1 w-full p-3 rounded-lg bg-white bg-opacity-30 focus:bg-opacity-50 outline-none">
            @error('password')<span class="text-red-300 text-sm">{{ $message }}</span>@enderror
        </div>
        <div class="flex items-center justify-between text-sm">
            <label class="inline-flex items-center">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600"> Remember me
            </label>
            <a href="{{ route('password.request') }}" class="underline">Forgot Password?</a>
        </div>
        <button type="submit" class="w-full py-3 rounded-lg font-semibold bg-yellow-400 text-black hover:bg-yellow-300">Log in</button>
    </form>
    <p class="mt-4 text-sm">Don't have an account? <a href="{{ route('register') }}" class="underline">Register</a></p>
  </div>
</body>
</html>
