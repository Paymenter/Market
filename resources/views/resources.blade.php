<x-layouts-main>
    <div class="lg:w-4/5 lg:mx-auto">
        <div class="grid w-full grid-cols-2 px-6 py-10 md:grid-cols-3 sm:rounded-lg">
            <div class="col-span-2 overflow-x-auto rounded-md">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <caption
                        class="p-5 text-lg font-semibold text-left text-gray-900 bg-secondary-50 dark:text-white dark:bg-secondary-100">
                        Our products
                        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Browse a list of all our
                            extensions and themes.</p>
                    </caption>
                    <thead class="text-xs text-gray-700 uppercase bg-secondary-50 dark:bg-secondary-100 dark:text-gray-400">
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
                        @foreach ($resources as $resource)
                            <tr class="text-white border-b dark:border-gray-700 hover:dark:border-gray-800 hover:dark:bg-gray-900 hover:cursor-pointer bg-secondary-50 dark:bg-secondary-100"
                                onclick="window.location.href = '/resource/{{ $resource->id }}';">
                                <td class="flex items-center w-1/3 px-6 py-4">
                                    <img src="{{ asset('storage/resource/' . $resource->image) }}"
                                        onerror="this.onerror=null;this.src='/img/logo.png';"
                                        class="h-10 rounded-md" alt="Image">
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
                                                        class="font-semibold text-gray-900 dark:text-white">{{ $resource->user()->get()->first()->resources()->where('status', 'published')->count() }}</span>
                                                    <span>Resources</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div data-popper-arrow></div>
                                    </div>
                                    • <span class="text-xs text-gray-500">€{{ $resource->price ?? 'Free' }}</span>
                                </td>

                                <td class="px-6 py-4 text-gray-900 dark:text-white">
                                    Downloads: {{ $resource->downloads }}<br>Views: {{ $resource->views }}
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
            </div>
            <div class="w-full ml-2">
                <!-- sort by -->
                <div class="p-4 mb-4 bg-secondary-50 rounded-lg dark:bg-secondary-100">
                    <h2 class="text-lg font-semibold text-gray-700 capitalize dark:text-white">Sort by</h2>
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Category</span>
                        <select
                            class="block w-full mt-1 text-sm text-gray-700 bg-secondary-50 border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-300 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray"
                            id="sort-by-category" name="category">
                            <option>Any</option>
                            <option value="theme">Theme</option>
                            <option value="addons">Addons</option>
                        </select>
                    </label>
                    <script>
                        document.getElementById('sort-by-category').value = "{{ request()->category }}"
                        document.getElementById('sort-by-category').addEventListener('change', function() {
                            window.location.href = window.location.pathname + '?category=' + this.value
                        })
                    </script>
                    <form method="POST">
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Search</span>
                            <input type="text"
                                class="block w-full mt-1 text-sm text-gray-700 bg-secondary-50 border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-300 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray"
                                id="sort-by-color">
                        </label>
                        <div class="mt-4">
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
                                type="submit">
                                Search
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-layouts-main>
