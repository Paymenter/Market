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
            <div class="col-span-2 overflow-x-auto dark:bg-secondary-100 p-2 rounded-md bg-secondary-50">
                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $resource->name }}</h1>
                <!-- Description with markdown -->
                <p class="mt-2 text-gray-600 dark:text-gray-400 prose dark:prose-invert">
                <div class="prose dark:prose-invert max-w-full">{!! Str::markdown($resource->description) !!}</div>
                </p>
            </div>
            <div class="col-span-1 overflow-x-auto dark:bg-secondary-100 p-2 rounded-md ml-4 bg-secondary-50">
                <!-- Seller -->
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Seller</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    <span
                        data-popover-target="popover-user-profile">{{ $resource->user()->get()->first()->username }}</span>
                <div data-popover id="popover-user-profile" role="tooltip"
                    class="absolute z-10 invisible inline-block w-64 text-sm font-light text-gray-500 transition-opacity duration-300 bg-secondary-50 border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:bg-secondary-100 dark:border-gray-600">
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
                                    ? auth()->user()->orders()->where('resource_id', $resource->id)->get()->first()->status == 'paid'
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
                                    class="z-10 hidden bg-secondary-50 divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
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
                                class="z-10 hidden bg-secondary-50 divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
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
                                class="z-10 hidden bg-secondary-50 divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
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
            <div class="mt-8">
                <!-- Reviews -->
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reviews</h1>
                <!-- Average rating -->
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    <span
                        class="font-semibold text-gray-900 dark:text-white">{{ number_format($resource->reviews()->avg('rating'), 2) }}</span>
                    <span>average based on {{ $resource->reviews()->count() }} reviews.</span>
                </p>
                @if (!auth()->user())
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        <span class="font-semibold text-gray-900 dark:text-white">You must be logged in to leave a
                            review.</span>
                    </p>
                @elseif($resource->price == 0 && $resource->user()->get()->first()->id == auth()->user()->id && $resource->reviews()->where('user_id', auth()->user()->id)->count() > 0)
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        <span class="font-semibold text-gray-900 dark:text-white">Leave a review</span>
                    </p>
                    <form method="POST" action="{{ route('resource.review', $resource->id) }}">
                        @csrf
                        <div class="mt-2">
                            <label for="rating"
                                class="block text-sm font-medium leading-5 text-gray-700 dark:text-gray-200">Rating</label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <select id="rating" name="rating"
                                    class="block w-full form-select transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                    <option value="5">5 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="2">2 Stars</option>
                                    <option value="1">1 Stars</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-2">
                            <label for="review"
                                class="block text-sm font-medium leading-5 text-gray-700 dark:text-gray-200">Review</label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <textarea id="review" name="review" rows="3"
                                    class="block w-full form-textarea transition duration-150 ease-in-out sm:text-sm sm:leading-5"></textarea>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-800 border border-transparent rounded-lg active:bg-gray-900 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                                Submit
                            </button>
                        </div>
                    </form>
                @else
                    @if (auth()->user()->id ==
                            $resource->user()->get()->first()->id)
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            <span class="font-semibold text-gray-900 dark:text-white">You cannot leave a review on your
                                own resource.</span>
                        </p>
                        <!-- Check if user has ordered resource -->
                    @elseif (!auth()->user()->orders()->where('resource_id', $resource->id)->exists() && $resource->price !== 0)
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            <span class="font-semibold text-gray-900 dark:text-white">You must have purchased this
                                resource to leave a review.</span>
                        </p>
                    @else
                        @if (auth()->user()->orders()->where('resource_id', $resource->id)->exists())
                            @if (auth()->user()->orders()->where('resource_id', $resource->id)->get()->first()->status !== 'paid' && $resource->price !== 0)
                                <p class="mt-2 text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold text-gray-900 dark:text-white">You must have purchased
                                        this resource to leave a review.</span>
                                </p>
                            @else
                                @if (!auth()->user()->reviews()->where('resource_id', $resource->id)->exists())
                                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                                        <span class="font-semibold text-gray-900 dark:text-white">Leave a review</span>
                                    </p>
                                    <form method="POST" action="{{ route('resource.review', $resource->id) }}">
                                        @csrf
                                        <div class="mt-2">
                                            <label for="rating"
                                                class="block text-sm font-medium leading-5 text-gray-700 dark:text-gray-200">Rating</label>
                                            <div class="mt-1 rounded-md shadow-sm">
                                                <select id="rating" name="rating"
                                                    class="block w-full form-select transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                                    <option value="5">5 Stars</option>
                                                    <option value="4">4 Stars</option>
                                                    <option value="3">3 Stars</option>
                                                    <option value="2">2 Stars</option>
                                                    <option value="1">1 Stars</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <label for="review"
                                                class="block text-sm font-medium leading-5 text-gray-700 dark:text-gray-200">Review</label>
                                            <div class="mt-1 rounded-md shadow-sm">
                                                <textarea id="review" name="review" rows="3"
                                                    class="block w-full form-textarea transition duration-150 ease-in-out sm:text-sm sm:leading-5"></textarea>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <button type="submit"
                                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-800 border border-transparent rounded-lg active:bg-gray-900 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                                                Submit
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            @endif
                        @endif
                    @endif
                @endif
                @foreach ($resource->reviews()->get() as $review)
                    @php
                        $user = $review
                            ->user()
                            ->get()
                            ->first();
                    @endphp
                    <article class="mt-2">
                        <a class="flex items-center mb-4 space-x-4" href="{{ route('user.show', $user->username) }}">
                            <img class="w-10 h-10 rounded-full" src="{{ asset('storage/avatars/' . $user->avatar) }}"
                                alt="">
                            <div class="space-y-1 font-medium dark:text-white">
                                <p>{{ $user->username }} <time datetime="2014-08-16 19:00"
                                        class="block text-sm text-gray-500 dark:text-gray-400">Joined on
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </time></p>
                            </div>
                        </a>
                        <div class="flex items-center mb-1">
                            @for ($i = 0; $i < $review->rating; $i++)
                                <svg aria-hidden="true" class="w-5 h-5 text-yellow-400" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <title>First star</title>
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                            @endfor
                            @for ($i = 0; $i < 5 - $review->rating; $i++)
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-800" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <title>First star</title>
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                            @endfor
                        </div>
                        <footer class="mb-5 text-sm text-gray-500 dark:text-gray-400">
                            <p>Reviewed at <time
                                    datetime="{{ $review->created_at }}">{{ $review->created_at->format('d/m/Y') }}</time>
                            </p>
                        </footer>
                        <p class="mb-2 text-gray-500 dark:text-gray-400">
                            {{ $review->body }}
                        </p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts-main>
