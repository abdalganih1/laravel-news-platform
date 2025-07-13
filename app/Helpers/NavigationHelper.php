<?php

// app/Helpers/NavigationHelper.php

// تأكد من أن الـ Namespace صحيح إذا وضعته في مكان آخر
namespace App\Helpers;

use Illuminate\Support\Facades\Route;

// دالة للشريط الجانبي للمدير (مثال)
if (!function_exists('adminNavLinkRTL')) {
    function adminNavLinkRTL($routeName, $iconSvgPath, $label) {
        $activeClasses = request()->routeIs($routeName.'*')
            ? 'bg-indigo-100 text-indigo-800 font-semibold'
            : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900';
        $url = Route::has($routeName) ? route($routeName) : '#';
        $iconColorClass = request()->routeIs($routeName.'*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500';

        return <<<HTML
        <a href="{$url}"
           class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition duration-150 ease-in-out {$activeClasses}">
            <svg class="w-5 h-5 ms-3 {$iconColorClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                {$iconSvgPath}
            </svg>
            <span class="flex-grow text-right">{$label}</span>
        </a>
        HTML;
    }
}

// دالة للشريط الجانبي للمحرر
if (!function_exists('editorNavLinkRTL')) {
    function editorNavLinkRTL($routeName, $iconSvgPath, $label) {
        // Using Cyan as the accent color for Editor active state
        $activeClasses = request()->routeIs($routeName.'*')
            ? 'bg-cyan-50 text-cyan-800 font-semibold'
            : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900';
        $url = Route::has($routeName) ? route($routeName) : '#';
        $iconColorClass = request()->routeIs($routeName.'*') ? 'text-cyan-600' : 'text-gray-400 group-hover:text-gray-500';

        return <<<HTML
        <a href="{$url}"
           class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition duration-150 ease-in-out {$activeClasses}">
            <svg class="w-5 h-5 ms-3 {$iconColorClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                {$iconSvgPath}
            </svg>
            <span class="flex-grow text-right">{$label}</span>
        </a>
        HTML;
    }
}