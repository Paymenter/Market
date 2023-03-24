<x-layouts-main>
    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-screen-xl px-4 py-8 mx-auto text-center lg:py-16 lg:px-6">
            <dl class="grid max-w-screen-md gap-8 mx-auto text-gray-900 sm:grid-cols-3 dark:text-white">
                <div class="flex flex-col items-center justify-center">
                    <dt class="mb-2 text-3xl md:text-4xl font-extrabold"> {{ $users->count() }} </dt>
                    <dd class="font-light text-gray-500 dark:text-gray-400">Users</dd>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <dt class="mb-2 text-3xl md:text-4xl font-extrabold"> {{ $orders->count() }} </dt>
                    <dd class="font-light text-gray-500 dark:text-gray-400">Payments</dd>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <dt class="mb-2 text-3xl md:text-4xl font-extrabold"> {{ $orders->sum('amount') }} </dt>
                    <dd class="font-light text-gray-500 dark:text-gray-400">Total Payments</dd>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <dt class="mb-2 text-3xl md:text-4xl font-extrabold"> {{ $resources->count() }} </dt>
                    <dd class="font-light text-gray-500 dark:text-gray-400">Resources</dd>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <dt class="mb-2 text-3xl md:text-4xl font-extrabold"> {{ $resources->sum('views') }} </dt>
                    <dd class="font-light text-gray-500 dark:text-gray-400">Total Views</dd>
                </div>
            </dl>
        </div>
    </section>
    <!-- New added resources -->
    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-screen-xl px-4 py-8 mx-auto text-center lg:py-16 lg:px-6">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">New Added Resources</h2>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($resources->where('status', 'pending') as $resource)
                        <tr class="text-white bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:dark:border-gray-800 hover:dark:bg-gray-900 hover:cursor-pointer"
                            onclick="window.location.href = '/admin/resources/{{ $resource->id }}';">
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
                                                    class="font-semibold text-gray-900 dark:text-white">{{ $resource->user()->get()->first()->resources()->where('status', 'published')->count() }}</span>
                                                <span>Resources</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div data-popper-arrow></div>
                                </div>
                                • <span class="text-xs text-gray-500">€{{ $resource->price }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</x-layouts-main>
