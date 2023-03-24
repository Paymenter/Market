<x-layouts-main>
    <x-slot name="title">
        {{ $resource->name }}
    </x-slot>
    <x-slot name="description">
        {{ $resource->description }}
    </x-slot>
    <x-slot name="username">
        {{ $resource->user()->get()->first()->username }}
    </x-slot>
    <x-slot name="image">
        {{ asset('storage/resources/' . $resource->image) }}
    </x-slot>
    <div class="lg:w-4/5 lg:mx-auto">
        <div class="grid w-full grid-cols-2 px-6 py-10 md:grid-cols-3 sm:rounded-lg">
            <div class="col-span-2 overflow-x-auto dark:bg-gray-800 p-2 rounded-md bg-white">
                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $resource->name }}</h1>
                <!-- Description with markdown -->
                <p class="mt-2 text-gray-600 dark:text-gray-400 prose dark:prose-invert">
                <div class="prose dark:prose-invert">{!! Str::markdown($resource->description) !!}</div>
                </p>
            </div>
            <div class="col-span-1 overflow-x-auto dark:bg-gray-800 p-2 rounded-md ml-4 bg-white">
                <!-- Seller -->
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Seller</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    <span
                        data-popover-target="popover-user-profile">{{ $resource->user()->get()->first()->username }}</span>
                <div data-popover id="popover-user-profile" role="tooltip"
                    class="absolute z-10 invisible inline-block w-64 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:bg-gray-800 dark:border-gray-600">
                    <div class="p-3">
                        <div class="flex items-center justify-between mb-2">
                            <a href="#">
                                <img class="w-10 h-10 rounded-full"
                                    src="{{ asset('storage/avatars/' .$resource->user()->get()->first()->avatar) }}"></a>
                        </div>
                        <p class="text-base font-semibold leading-none text-gray-900 dark:text-white">
                            {{ $resource->user()->get()->first()->username }}
                        </p>
                        <p class="mb-4 text-sm font-light">
                            {{ $resource->user()->get()->first()->sbio }}
                        </p>
                        <ul class="flex text-sm font-light">
                            <li class="mr-2">
                                <span
                                    class="font-semibold text-gray-900 dark:text-white">{{ $resource->user()->get()->first()->resources()->count() }}</span>
                                <span>Resources</span>
                            </li>
                        </ul>
                    </div>
                    <div data-popper-arrow></div>
                </div>
                </p>
                <!-- Price -->
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Price</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    <span class="font-semibold text-gray-900 dark:text-white">
                        @if ($resource->price == 0)
                            <span class="bg-green-800 p-1 border-sm text-white"> Free </span>
                        @else
                            €{{ $resource->price }}
                        @endif
                    </span>
                </p>
                <!-- Buy button -->
                <div class="mt-4 text-right">
                    @if (auth()->user())
                        @if (auth()->user()->orders()->where('resource_id', $resource->id)->exists() || $resource->price == 0)
                            @if (auth()->user()->orders()->where('resource_id', $resource->id)->get()->first()
                                    ? auth()->user()->orders()->where('resource_id', $resource->id)->get()->first()->status == 'completed'
                                    : false)
                                <a href="{{ route('resource.download', $resource->id) }}"
                                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-800 border border-transparent rounded-lg active:bg-gray-900 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                                    Download
                                </a>
                            @elseif($resource->price == 0)
                                <a href="{{ route('resource.download', $resource->id) }}"
                                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-800 border border-transparent rounded-lg active:bg-gray-900 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                                    Download
                                </a>
                            @else
                                <button id="dropdownHoverButton" data-dropdown-toggle="dropdownHover"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    type="button">Buy <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg></button>
                                <div id="dropdownHover"
                                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby="dropdownHoverButton">
                                        @if ($resource->user()->get()->first()->stripe_id)
                                            <li>
                                                <a href="{{ route('resource.buy.stripe', $resource->id) }}"
                                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Buy
                                                    with Stripe</a>
                                            </li>
                                        @endif
                                        @if ($resource->user()->get()->first()->paypal)
                                            <li class="mt-1">
                                                <a href="{{ route('resource.buy.paypal', $resource->id) }}"
                                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Buy
                                                    with PayPal</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            @endif
                        @else
                            <button id="dropdownHoverButton" data-dropdown-toggle="dropdownHover"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                type="button">Buy <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg></button>
                            <div id="dropdownHover"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownHoverButton">
                                    @if ($resource->user()->get()->first()->stripe_id)
                                        <li>
                                            <a href="{{ route('resource.buy.stripe', $resource->id) }}"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Buy
                                                with Stripe</a>
                                        </li>
                                    @endif
                                    @if ($resource->user()->get()->first()->paypal)
                                        <li class="mt-1">
                                            <a href="{{ route('resource.buy.paypal', $resource->id) }}"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Buy
                                                with PayPal</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    @else
                        @if ($resource->price == 0)
                            <a href="{{ route('resource.download', $resource->id) }}"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-800 border border-transparent rounded-lg active:bg-gray-900 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                                Download
                            </a>
                        @else
                            <button id="dropdownHoverButton" data-dropdown-toggle="dropdownHover"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                type="button">Buy <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg></button>
                            <div id="dropdownHover"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownHoverButton">
                                    @if ($resource->user()->get()->first()->stripe_id)
                                        <li>
                                            <a href="{{ route('resource.buy.stripe', $resource->id) }}"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Buy
                                                with Stripe</a>
                                        </li>
                                    @endif
                                    @if ($resource->user()->get()->first()->paypal)
                                        <li class="mt-1">
                                            <a href="{{ route('resource.buy.paypal', $resource->id) }}"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Buy
                                                with PayPal</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    @endif
                </div>
                <!-- Stats -->
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Stats</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $resource->downloads }}</span>
                    <span>Downloads</span>
                </p>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $resource->views }}</span>
                    <span>Views</span>
                </p>

                <!-- Owner controls -->
                @if (auth()->user())
                    @if (Auth::user()->id ==
                            $resource->user()->get()->first()->id)
                        <div class="mt-4 text-right">
                            <a href="{{ route('resource.edit', $resource->id) }}"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-800 border border-transparent rounded-lg active:bg-gray-900 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                                Edit
                            </a>
                            <a href="{{ route('resource.delete', $resource->id) }}"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-800 border border-transparent rounded-lg active:bg-gray-900 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                                Delete
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-layouts-main>
