<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl"> {{-- Added dir="rtl" for Arabic --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - لوحة تحكم المدير</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    {{-- Add Cairo Font for Arabic --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Custom Styles (Optional) --}}
    <style>
        body {
            font-family: 'Cairo', 'Figtree', sans-serif; /* Prioritize Cairo font */
        }
        /* Ensure sidebar stays fixed and content scrolls */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 250px; /* Adjust width as needed */
            flex-shrink: 0;
        }
        .admin-main-content {
            flex-grow: 1;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }
        /* Adjust navigation for RTL */
        .admin-main-content nav {
            padding-right: 250px; /* Account for sidebar width */
            padding-left: 1rem; /* Reset left padding */
        }
        /* Simple scrollbar styling (optional) */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="admin-layout">
        <!-- Sidebar -->
        @include('partials.admin.sidebar')

        <!-- Main Content Area -->
        <div class="admin-main-content">
            <!-- Breeze Navigation (Top Bar) -->
            {{-- We include the standard navigation, it includes user dropdown/logout --}}
            @include('layouts.navigation')

            <!-- Page Heading -->
            @hasSection('header') {{-- Use @hasSection for optional header --}}
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        @yield('header') {{-- Use yield for dynamic header content --}}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            @yield('content') {{-- Use yield for dynamic main content --}}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>