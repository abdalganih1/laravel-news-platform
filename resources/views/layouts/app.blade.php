<!DOCTYPE html>
{{-- Add dir="rtl" and ensure lang is set correctly --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        {{-- Use Figtree as fallback, prioritize Cairo --}}
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Optional: Add specific styles for better RTL adjustments if needed --}}
        <style>
            body {
                font-family: 'Cairo', 'Figtree', sans-serif;
            }
            /* Add other RTL adjustments if Tailwind alone isn't sufficient */
        </style>
    </head>
    {{-- Force light mode by default, prioritize Cairo font --}}
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen bg-gray-100">
            {{-- Navigation should now respect RTL better due to html dir="rtl" --}}
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>