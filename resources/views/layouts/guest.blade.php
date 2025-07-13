<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl"> {{-- Added dir="rtl" --}}
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
         {{-- Add Cairo Font --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
         {{-- Custom Styles for Font --}}
         <style>
             body {
                 font-family: 'Cairo', 'Figtree', sans-serif;
             }
         </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100 text-right"> {{-- Added text-right --}}
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                {{-- Replace x-application-logo with your custom image --}}
                <a href="{{ route('home') }}"> {{-- Link to home route --}}
                     <img src="{{ asset('images/app-logo.png') }}" alt="{{ config('app.name') }} Logo" class="w-20 h-20 fill-current text-gray-500">
                     {{-- Adjust w-20 and h-20 if needed --}}
                </a>
            </div>

            {{-- The login/register/etc. form content goes here --}}
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>