<x-layouts-main>
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <caption class="p-5 text-lg font-semibold text-left text-gray-900 bg-secondary-50 dark:text-white dark:bg-secondary-100">
            Unpublished resources
        </caption>
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="">
                </th>
                <th scope="col" class="px-6 py-3">
                    Resource
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resources->where('status', 'pending')->sortBy('created_at') as $resource)
                <tr class="text-white bg-secondary-50 border-b dark:bg-secondary-100 dark:border-gray-700 hover:dark:border-gray-800 hover:dark:bg-gray-900 hover:cursor-pointer"
                    onclick="window.location.href = '{{ route('admin.resources.show', $resource->id) }}'">
                    <td class="flex items-center w-1/3 px-6 py-4">
                        <img src="{{ asset('storage/resource/' . $resource->image) }}"
                            onerror="this.onerror=null;this.src='/img/logo.png';" class="w-10 h-10 rounded-md"
                            alt="Image">
                        <p class="pl-1 text-sm font-medium text-gray-900 dark:text-white" id="productName">
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
                                <p class="text-base font-semibold leading-none text-gray-900 dark:text-white">
                                    {{ $resource->user()->get()->first()->username }}
                                </p>
                                <p class="mb-4 text-sm font-light">
                                    {{ $resource->user()->get()->first()->sbio }}
                                </p>
                                <ul class="flex text-sm font-light">
                                    <li class="mr-2">
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white">{{ $resource->user()->get()->first()->resources()->where('status', 'published')->count() }}</span>
                                        <span>Resources</span>
                                    </li>
                                </ul>
                            </div>
                            <div data-popper-arrow></div>
                        </div>
                        • <span class="text-xs text-gray-500">€{{ $resource->price ?? 'Free' }}</span>
                    </td>
                </tr>
            @endforeach
            @empty($resources->count())
                <tr>
                    <td class="px-6 py-4 text-gray-900 dark:text-white">
                        No resources found using the selected filters.
                    </td>
                </tr>
            @endempty
        </tbody>
    </table>

    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <caption class="p-5 text-lg font-semibold text-left text-gray-900 bg-secondary-50 dark:text-white dark:bg-secondary-100">
            Published resources
        </caption>
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="">
                </th>
                <th scope="col" class="px-6 py-3">
                    Resource
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resources->where('status', 'published')->sortBy('created_at') as $resource)
                <tr class="text-white bg-secondary-50 border-b dark:bg-secondary-100 dark:border-gray-700 hover:dark:border-gray-800 hover:dark:bg-gray-900 hover:cursor-pointer"
                    onclick="window.location.href = '{{ route('admin.resources.show', $resource->id) }}'">
                    <td class="flex items-center w-1/3 px-6 py-4">
                        <img src="{{ asset('storage/resource/' . $resource->image) }}"
                            onerror="this.onerror=null;this.src='/img/logo.png';" class="w-10 h-10 rounded-md"
                            alt="Image">
                        <p class="pl-1 text-sm font-medium text-gray-900 dark:text-white" id="productName">
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
                                <p class="text-base font-semibold leading-none text-gray-900 dark:text-white">
                                    {{ $resource->user()->get()->first()->username }}
                                </p>
                                <p class="mb-4 text-sm font-light">
                                    {{ $resource->user()->get()->first()->sbio }}
                                </p>
                                <ul class="flex text-sm font-light">
                                    <li class="mr-2">
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white">{{ $resource->user()->get()->first()->resources()->where('status', 'published')->count() }}</span>
                                        <span>Resources</span>
                                    </li>
                                </ul>
                            </div>
                            <div data-popper-arrow></div>
                        </div>
                        • <span class="text-xs text-gray-500">€{{ $resource->price ?? 'Free' }}</span>
                    </td>
                </tr>
            @endforeach
            @empty($resources->count())
                <tr>
                    <td class="px-6 py-4 text-gray-900 dark:text-white">
                        No resources found using the selected filters.
                    </td>
                </tr>
            @endempty
        </tbody>
    </table>

</x-layouts-main>
