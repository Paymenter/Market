<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @isset($title)
            {{ $title }} | Market
        @else
            Paymenter | Market
        @endisset </title>
    @vite(['resources/js/app.js'])
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    @isset($title)<meta name="title" content="{{ $title }} | Market">@else <meta name="title" content="Paymenter | Market">@endisset
    @isset($description)<meta name="description" content="{{ $description }}">@else <meta name="description" content="Sell your resources or buy them!">@endisset
    @isset($title)<meta property="og:title" content="{{ $title }} | Market">@else <meta property="og:title" content="Paymenter | Market">@endisset
    @isset($description)<meta property="og:description" content="{{ $description }}">@else <meta property="og:description" content="Sell your resources or buy them!">@endisset
    @isset($image)<meta property="og:image" content="{{ $image }}">@endisset
    @isset($title)<meta property="twitter:title" content="{{ $title }} | Market">@else <meta property="twitter:title" content="Paymenter | Market">@endisset
    @isset($description)<meta property="twitter:description" content="{{ $description }}">@else <meta property="twitter:description" content="Sell your resources or buy them!">@endisset
    @isset($image)<meta property="twitter:image" content="{{ $image }}">@endisset
</head>

<body class="bg-secondary-100 dark:bg-secondary-50">
    <header>
        <nav class="border-gray-200 px-4 lg:px-6 py-2.5 bg-secondary-50 dark:bg-secondary-100">
            <div class="flex flex-wrap items-center justify-between max-w-screen-xl mx-auto">
                <a href="/" class="flex items-center">
                    <img src="/img/logo.png" class="h-6 mr-3 sm:h-9" alt="Flowbite Logo" />
                    <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">Paymenter</span>
                </a>
                <div class="flex items-center lg:order-2">
                    <button data-tooltip-target="tooltip-dark" type="button" id="darkmode"
                        class="inline-flex items-center p-2 mr-1 text-sm font-medium text-gray-500 rounded-lg dark:text-gray-400 hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div id="tooltip-dark" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Toggle dark mode
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <script>
                        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
                        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

                        // Change the icons inside the button based on previous settings
                        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                                '(prefers-color-scheme: dark)').matches)) {
                            themeToggleLightIcon.classList.remove('hidden');
                        } else {
                            themeToggleDarkIcon.classList.remove('hidden');
                        }

                        var themeToggleBtn = document.getElementById('darkmode');

                        themeToggleBtn.addEventListener('click', function() {

                            // toggle icons inside button
                            themeToggleDarkIcon.classList.toggle('hidden');
                            themeToggleLightIcon.classList.toggle('hidden');

                            // if set via local storage previously
                            if (localStorage.getItem('theme')) {
                                if (localStorage.getItem('theme') === 'light') {
                                    document.documentElement.classList.add('dark');
                                    localStorage.setItem('theme', 'dark');
                                } else {
                                    document.documentElement.classList.remove('dark');
                                    localStorage.setItem('theme', 'light');
                                }

                                // if NOT set via local storage previously
                            } else {
                                if (document.documentElement.classList.contains('dark')) {
                                    document.documentElement.classList.remove('dark');
                                    localStorage.setItem('theme', 'light');
                                } else {
                                    document.documentElement.classList.add('dark');
                                    localStorage.setItem('theme', 'dark');
                                }
                            }

                        });
                    </script>
                    @auth
                        <button type="button"
                            class="flex mx-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                            id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full" src="{{ asset('storage/avatars/' . auth()->user()->avatar) }}"
                                alt="user photo" onerror="this.src='/img/logo.png';this.onerror=null;">
                        </button>
                        <!-- Dropdown menu -->
                        <div class="z-50 hidden w-56 my-4 text-base list-none bg-secondary-50 divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                            id="dropdown">
                            <div class="px-4 py-3">
                                <span
                                    class="block text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->username }}</span>
                                <span
                                    class="block text-sm font-light text-gray-500 truncate dark:text-gray-400">{{ Auth::user()->email }}</span>
                            </div>
                            <ul class="py-1 font-light text-gray-500 dark:text-gray-400" aria-labelledby="dropdown">
                                <li>
                                    <a href="{{ route('user.show', Auth::user()->username) }}"
                                        class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">My
                                        profile</a>
                                </li>
                                <li>
                                    <a href="{{ route('settings.index') }}"
                                        class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">Account
                                        settings</a>
                                </li>
                            </ul>
                            <ul class="py-1 font-light text-gray-500 dark:text-gray-400" aria-labelledby="dropdown">
                                <li>
                                    <a href="#"
                                        class="flex items-center px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><svg
                                            class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                clip-rule="evenodd"></path>
                                        </svg> My likes</a>
                                </li>
                                <li>
                                    <a href="{{ route('user.resources', Auth::user()->username) }}"
                                        class="flex items-center px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><svg
                                            class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z">
                                            </path>
                                        </svg> Your resources</a>
                                </li>
                                <li>
                                    <a href="{{ route('resource.create') }}"
                                        class="flex items-center justify-between px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        <span class="flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-500"
                                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z"
                                                    clip-rule="evenodd"></path>
                                            </svg> Add resource
                                        </span>
                                    </a>
                                </li>

                            </ul>
                            @if (auth()->user()->is_admin)
                                <ul class="py-1 font-light text-gray-500 dark:text-gray-400" aria-labelledby="dropdown">
                                    <li>
                                        <a href="{{ route('admin.index') }}"
                                            class="flex items-center px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><svg
                                                class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M10 12a2 2 0 100-4 2 2 0 000 4zM3 10a7 7 0 1114 0 7 7 0 01-14 0zM3 10a7 7 0 0114 0 7 7 0 01-14 0z">
                                                </path>
                                            </svg> Admin panel</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.resources.index') }}"
                                            class="flex items-center px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><svg
                                                class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z">
                                                </path>
                                            </svg> All resources</a>
                                    </li>
                                </ul>
                            @endif


                            <ul class="py-1 font-light text-gray-500 dark:text-gray-400" aria-labelledby="dropdown">
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-red-600"
                                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">Sign
                                        out</a>
                                </li>
                            </ul>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                        </form>
                        <button data-collapse-toggle="mobile-menu-2" type="button"
                            class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                            aria-controls="mobile-menu-2" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    @else
                        <div class="flex justify-center" data-modal-toggle="login">
                            <a href="{{ route('login') }}"
                                class="text-gray-800 dark:text-white hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 lg:px-5 lg:py-2.5 mr-2 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800"
                                type="button">
                                Login
                            </a>
                        </div>
                        <div class="flex justify-center">
                            <a href="{{ route('register') }}"
                                class="block text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                type="button">
                                Register
                            </a>
                        </div>
                    @endauth
                </div>
                <div class="items-center justify-between hidden w-full lg:flex lg:w-auto lg:order-1"
                    id="mobile-menu-2">
                    <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
                        <li>
                            <a href="{{ route('home') }}"
                                class="block py-2 pl-3 pr-4 border-b border-gray-100 text-gray-700 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700 @if (request()->routeIs('home')) text-primary-600 dark:text-primary-500 @endif"
                                aria-current="page">Home</a>
                        </li>
                        <li>
                            <a href="{{ route('resources.index') }}"
                                class="block py-2 pl-3 pr-4 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700 @if (request()->routeIs('resources.*')) text-primary-600 dark:text-primary-500 @endif">Resources</a>
                        </li>
                        <li>
                            <a href="#"
                                class="block py-2 pl-3 pr-4 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700 @if (request()->routeIs('')) text-primary-600 dark:text-primary-500 @endif">Info</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <x-success />
    <div>
        {{ $slot }}
    </div>
</body>

</html>
