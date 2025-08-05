<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-100">
    <div class="flex items-center justify-center h-screen w-screen bg-gray-100">
        <div class="w-full max-w-5xl h-[80%] flex shadow-md rounded-2xl overflow-hidden border border-sky-100 bg-gradient-to-br from-sky-50 to-white p-6">
            <!-- Kiri -->
            <div class="hidden md:flex w-1/2 bg-gradient-to-br from-sky-400 to-blue-600 text-white items-center justify-center text-center rounded-l-2xl">
                <div class="flex flex-col items-center text-center px-10">
                    <img src="{{ asset('images/logo-dinkominfo.png') }}" alt="Ilustrasi Login" class="w-100 mb-4">
                    <h1 class="text-2xl font-semibold mb-3">Selamat Datang di Simanis Dimas</h1>
                    <h4 class="text-sm italic">"Sistem Manajemen dan Inventaris Dinas Komunikasi dan Informasi Kabupaten Banyumas"</h4>
                </div>
            </div>

            <!-- Kanan -->
            <div class="w-full md:w-1/2 flex items-center justify-center px-10">
                <div class="w-full">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
