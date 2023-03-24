<x-layouts-main>
    <div class="lg:w-4/5 lg:mx-auto py-10">
        <div class="col-span-3 overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <caption
                    class="p-5 text-lg font-semibold text-left text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                    Our products
                    <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Browse a list of all our
                        extensions and themes.</p>
                </caption>
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="">
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Resource
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Stats
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($resources as $resource)
                        <tr class="text-white bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:dark:border-gray-800 hover:dark:bg-gray-900 hover:cursor-pointer"
                            onclick="window.location.href = '/resource/{{ $resource->id }}';">
                            <td class="flex items-center w-1/3 px-6 py-4">
                                <img src="{{ $resource->image }}" onerror="this.onerror=null;this.src='/img/logo.png';"
                                    class="w-10 h-10 rounded-md" alt="Image">
                                <p class="pl-1 text-sm font-medium text-gray-900 dark:text-white" id="productName">
                                    {{ $resource->name }} </p>
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ $resource->slogan }}
                                <br>
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
                                • <span class="text-xs text-gray-500">€{{ $resource->price ?? 'Free' }}</span>
                            </td>

                            <td class="px-6 py-4 text-gray-900 dark:text-white">
                                Downloads: {{ $resource->downloads }}<br>Views: {{ $resource->views }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-white">
                                @if ($resource->status == 'published')
                                    <span
                                        class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-sm dark:bg-green-700 dark:text-green-100">
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-sm dark:bg-red-700 dark:text-red-100"
                                        data-tooltip-target="inactive">
                                        Inactive
                                    </span>
                                    <div id="inactive" role="tooltip"
                                        class="absolute z-10 inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm tooltip dark:bg-gray-700 opacity-0 invisible"
                                        style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(1378px, 58px);"
                                        data-popper-placement="bottom">
                                        Not Published or Pending Review
                                        <div class="tooltip-arrow" data-popper-arrow=""
                                            style="position: absolute; left: 0px; transform: translate(53px, 0px);">
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts-main>
