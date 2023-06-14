<x-layouts-main>
    <!-- Show user details -->
    <div class="flex flex-col items-center justify-center py-12 overflow-hidden sm:px-6 lg:px-8">

        <div class="relative w-full max-w-5xl p-4 md:h-auto">
            <!-- show username, bio, and profile image -->
            <div class="relative p-4 bg-secondary-50 rounded-lg shadow dark:bg-secondary-100 sm:p-5">
                <div class="flex items-center justify-between pb-4 mb-4 border-b rounded-t sm:mb-5 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $user->name }}
                    </h3>
                </div>
                <div class="flex flex-col">
                    <div class="flex flex-row">
                        <img class="rounded-full h-20 w-20 object-cover"
                            src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="{{ $user->name }}"
                            onerror="this.src='{{ asset('storage/avatars/default.png') }}';this.onerror=null;">
                        <div class="flex flex-col ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $user->name }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ $user->sbio }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-400">
                                <span class="text-white">
                                    {{ $user->resources()->where('status', '=', 'published')->count() }}</span>
                                resources
                            </p>
                            <p class="text-gray-600 dark:text-gray-400">
                                <span class="text-white">
                                    {{ $user->resources()->where('status', '=', 'published')->sum('views') }}</span>
                                views
                            </p>
                            <p class="text-gray-600 dark:text-gray-400">
                                <span class="text-white">
                                    {{ $user->resources()->where('status', '=', 'published')->sum('downloads') }}</span>
                                downloads
                            </p>
                        </div>
                    </div>
                    <p class="mt-4 text-gray-600 dark:text-gray-300">
                        {{ $user->bio }}
                    </p>
                    <!-- Resource list -->
                    <div class="flex flex-col mt-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Resources
                        </h3>
                        @if(auth()->user()->id == $user->id)
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white text-right hover:underline">
                            <a href="/user/{{ $user->username }}/resources">View all</a>
                        </h4>
                        @endif
                        <div class="flex flex-col mt-4">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="">
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Resource
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Stats
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($user->resources()->where('status', '=', 'published')->get() as $resource)
                                        <tr class="text-white bg-secondary-50 border-b dark:bg-secondary-100 dark:border-gray-700 hover:dark:border-gray-800 hover:dark:bg-gray-900 hover:cursor-pointer"
                                            onclick="window.location.href = '/resource/{{ $resource->id }}';">
                                            <td class="flex items-center w-1/3 px-6 py-4">
                                                <img src="{{ $resource->image }}"
                                                    onerror="this.onerror=null;this.src='/img/logo.png';"
                                                    class="w-10 h-10 rounded-md" alt="Image">
                                                <p class="pl-1 text-sm font-medium text-gray-900 dark:text-white"
                                                    id="productName">
                                                    {{ $resource->name }} </p>
                                            </td>
                                            <td class="px-6 py-4 text-gray-900 dark:text-white">
                                                {{ $resource->slogan }}
                                                <br>
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
                                                        <p
                                                            class="text-base font-semibold leading-none text-gray-900 dark:text-white">
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
                                                • <span class="text-xs text-gray-500">€{{ $resource->price ?? 'Free' }}</span>
                                            </td>

                                            <td class="px-6 py-4 text-gray-900 dark:text-white">
                                                Downloads: {{ $resource->downloads }}<br>Views:
                                                {{ $resource->views }}
                                            </td>
                                        </tr>
                                    @empty
                                        <p class="text-gray-600 dark:text-gray-400">
                                            No resources found
                                        </p>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
</x-layouts-main>
