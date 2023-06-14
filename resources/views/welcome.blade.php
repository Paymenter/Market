<x-layouts-main>
    <!-- Browse though thousands of resources, sell your own, and more! Create new text with this button -->
    <div class="flex flex-col items-center justify-center w-full h-1/2">
        <div class="flex flex-col items-center justify-center w-full">
            <h1 class="text-5xl font-bold text-center text-gray-900 dark:text-white">Browse through thousands of
                resources,
                sell your own, and more!</h1><br />
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <a href="{{ route('resource.create') }}">Create a new resource</a>
            </button>
        </div>
    </div>
    <section>
        <div class="max-w-screen-xl px-4 py-8 mx-auto text-center lg:py-16 lg:px-6">
            <dl class="grid max-w-screen-md gap-8 mx-auto text-gray-900 sm:grid-cols-3 dark:text-white">
                <div class="flex flex-col items-center justify-center">
                    <dt class="mb-2 text-3xl md:text-4xl font-extrabold">
                        {{ \App\Models\Resource::where('status', 'published')->count() }}</dt>
                    <dd class="font-light text-gray-500 dark:text-gray-400">resources</dd>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <dt class="mb-2 text-3xl md:text-4xl font-extrabold">{{ \App\Models\User::count() }}</dt>
                    <dd class="font-light text-gray-500 dark:text-gray-400">users</dd>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <dt class="mb-2 text-3xl md:text-4xl font-extrabold">{{ \App\Models\Resource::sum('downloads') }}</dt>
                    <dd class="font-light text-gray-500 dark:text-gray-400">downloads</dd>
                </div>
            </dl>
        </div>
    </section>
    <!-- Some of our most popular resources -->
    <div class="flex flex-col items-center justify-center w-full mt-10">
        <div class="flex flex-col items-center justify-center w-full">
            <h1 class="text-5xl font-bold text-center text-gray-900 dark:text-white">Some of our most popular resources
            </h1>
        </div>
    </div>
    <div class="flex flex-col items-center justify-center w-full h-1/2">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach (App\Models\Resource::where('status', 'published')->orderBy('downloads', 'desc')->limit(3)->get() as $resource)
                <a class="bg-gray-50 dark:bg-secondary-100 text-center items-center p-4 rounded-md" href="{{ route('resource.show', $resource->id) }}">
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $resource->name }}</h4>
                    <img src="{{ asset('storage/resource/' . $resource->image) }}" alt="{{ $resource->name }}" onerror="this.onerror=null;this.src='/img/logo.png';" class="w-full h-64 object-cover">
                    <p class="text-gray-500 dark:text-gray-400">Downloads {{ $resource->downloads }}</p>
                </a>
            @endforeach
        </div>
    </div>
</x-layouts-main>
