<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            {{-- Right Side of Navbar (in RTL) --}}
            <div class="flex">
                               <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        {{-- Replace x-application-logo with your custom image --}}
                        {{-- Make sure the image file exists in public/images/ --}}
                        <img src="{{ asset('images/app-logo.png') }}" alt="{{ config('app.name') }} Logo" class="block h-9 w-auto">
                        {{-- You might need to adjust h-9 and w-auto based on your logo dimensions --}}
                    </a>
                </div>

                <!-- Navigation Links -->
                {{-- Use space-x-reverse for RTL spacing --}}
                <div class="hidden space-x-8 space-x-reverse sm:-my-px sm:me-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('الرئيسية') }}
                    </x-nav-link>
                    <x-nav-link :href="route('frontend.posts.index')" :active="request()->routeIs('frontend.posts.index')">
                        {{ __('الأخبار') }}
                    </x-nav-link>
                    <x-nav-link :href="route('frontend.pages.about')" :active="request()->routeIs('frontend.pages.about')">
                        {{ __('حول المنصة') }}
                    </x-nav-link>
                    @auth
                     <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard*') || request()->routeIs('admin.*') || request()->routeIs('editor.*')">
                        {{ __('لوحة التحكم') }}
                    </x-nav-link>
                    @endauth
                </div>
            </div>

            {{-- Left Side of Navbar (in RTL) --}}
            <div class="hidden sm:flex sm:items-center sm:me-6">
                 @guest
                    <div class="space-x-4 space-x-reverse">
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline hover:text-indigo-600">تسجيل الدخول</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm text-gray-700 underline hover:text-indigo-600">إنشاء حساب</a>
                        @endif
                    </div>
                 @else
                    <!-- Settings Dropdown -->
                    <x-dropdown align="left" width="48"> {{-- Changed align to "left" for RTL --}}
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->first_name }}</div>

                                <div class="me-1"> {{-- Changed ms-1 to me-1 for RTL --}}
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                             @if(Auth::user()->user_role === 'admin')
                                <x-dropdown-link :href="route('admin.dashboard')">
                                    {{ __('لوحة تحكم المدير') }}
                                </x-dropdown-link>
                             @elseif(Auth::user()->user_role === 'editor')
                                <x-dropdown-link :href="route('editor.dashboard')">
                                    {{ __('لوحة تحكم المحرر') }}
                                </x-dropdown-link>
                             @endif
                             <div class="border-t border-gray-200"></div>

                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('الملف الشخصي') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('frontend.claims.index')">
                                {{ __('بلاغاتي') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('تسجيل الخروج') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endguest
            </div>

            <!-- Hamburger -->
            {{-- Changed -me-2 to -ms-2 for RTL --}}
            <div class="-ms-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
             <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('الرئيسية') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('frontend.posts.index')" :active="request()->routeIs('frontend.posts.index')">
                {{ __('الأخبار') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('frontend.pages.about')" :active="request()->routeIs('frontend.pages.about')">
                {{ __('حول المنصة') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 text-right"> {{-- Added text-right --}}
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                 @if(Auth::user()->user_role === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')">
                        {{ __('لوحة تحكم المدير') }}
                    </x-responsive-nav-link>
                 @elseif(Auth::user()->user_role === 'editor')
                    <x-responsive-nav-link :href="route('editor.dashboard')">
                        {{ __('لوحة تحكم المحرر') }}
                    </x-responsive-nav-link>
                 @endif
                <x-responsive-nav-link :href="route('dashboard')"> {{-- Generic dashboard link --}}
                    {{ __('لوحة التحكم') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('الملف الشخصي') }}
                </x-responsive-nav-link>
                 <x-responsive-nav-link :href="route('frontend.claims.index')">
                    {{ __('بلاغاتي') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('تسجيل الخروج') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
        <div class="py-1 border-t border-gray-200">
            <x-responsive-nav-link :href="route('login')">
                {{ __('تسجيل الدخول') }}
            </x-responsive-nav-link>
            @if (Route::has('register'))
                <x-responsive-nav-link :href="route('register')">
                    {{ __('إنشاء حساب') }}
                </x-responsive-nav-link>
            @endif
        </div>
        @endauth
    </div>
</nav>