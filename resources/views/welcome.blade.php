{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MHS AUTO SUPPLY</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-purple-600 min-h-screen flex items-center justify-center">

    <div class="bg-purple-700 bg-opacity-30 backdrop-blur-md rounded-xl p-10 max-w-3xl w-full flex flex-col md:flex-row items-center justify-between shadow-lg">
        {{-- Text Section --}}
        <div class="text-white md:w-1/2 mb-6 md:mb-0">
            <h1 class="text-4xl font-bold mb-4">Welcome to <span class="text-yellow-400">MHS AUTO SUPPLY</span></h1>
            <p class="text-lg mb-6">QUALITY AND STRONG CAR PARTS</p>
            
            <div class="flex space-x-4">
                <a href="{{ route('login') }}" class="bg-yellow-400 text-gray-800 font-semibold px-6 py-2 rounded hover:bg-yellow-500 transition">Login</a>
                <a href="{{ route('register') }}" class="bg-white text-gray-800 font-semibold px-6 py-2 rounded hover:bg-gray-100 transition">Register</a>
            </div>
        </div>

        {{-- Laravel Logo --}}
       <div class="md:w-1/2 flex justify-center">
            <img src="{{ asset('images/my-logo.png') }}" alt="App Logo" class="w-60 h-60 object-contain drop-shadow-lg">
        </div>
    </div>

</body>
</html>
