<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MHS AUTO SUPPLY') }}</title>

    <!-- ✅ Add this line -->
    <link rel="icon" type="image/png" href="{{ asset('images/my-logo.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: linear-gradient(to right, #3b82f6, #9333ea);
            min-height:100vh;
        }
        .glass-card{
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(20px);
            border-radius:20px;
            box-shadow:0 8px 32px rgba(0,0,0,0.2);
            padding:20px;
        }
    </style>
</head>

<body class="font-sans antialiased">
<div class="min-h-screen">
    @include('layouts.navigation')
    <main class="p-6">
        <div class="glass-card">
            {{ $slot }}
        </div>
    </main>
</div>
</body>
</html>