<x-layouts-main>
    <div class="flex flex-col items-center justify-center py-12 overflow-hidden sm:px-6 lg:px-8">

        <div class="relative w-full max-w-5xl p-4 md:h-auto">
            <!-- Modal content -->
            <div class="relative p-4 bg-secondary-50 rounded-lg shadow dark:bg-secondary-100 sm:p-5">
                <!-- Modal header -->
                <div class="flex items-center justify-between pb-4 mb-4 border-b rounded-t sm:mb-5 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Update Settings
                    </h3>
                </div>
                <!-- Modal body -->
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid gap-4 mb-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="sbio"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Short bio</label>
                            <input type="text" name="sbio" id="sbio" value="{{ $user->sbio }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Developer @Paymenter" required>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="bio"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bio</label>
                            <textarea id="bio" rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Write your description..." name="bio" required>{{ $user->bio }}</textarea>
                        </div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            for="avatar">Update Profile image</label>
                        <input
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            id="avatar" name="avatar" type="file" accept="image/png, image/jpeg">
                    </div>
                    <div class="flex items-center space-x-4">
                        <button type="submit"
                            class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            Update settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="relative w-full max-w-3xl p-4 md:h-auto" id="stripe">
            <div class="relative p-4 bg-secondary-50 rounded-lg shadow dark:bg-secondary-100 sm:p-5">
                <div
                    class="flex items-center justify-between pb-4 mb-4 border-b rounded-t sm:mb-5 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Connect to Stripe
                    </h3>
                </div>
                <!-- display stripe account info with variable account -->
                @isset($status)
                    <!-- show green button with stripe account info -->
                    <div class="flex flex-col">
                        <button type="button" disabled
                            class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            Connected to Stripe as {{ $account->business_profile->name }}
                        </button>
                        <p class="text-sm text-gray-900 dark:text-white">This means that you can now accept payments from
                            your customers. And create resources!</p>
                        <br>
                        <!-- visit stripe dashboard -->
                        <a href="https://dashboard.stripe.com" target="_blank"
                            class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            Visit Stripe Dashboard
                        </a>
                    </div>
                @else
                    <!-- show red button with stripe account info -->
                    <div class="flex flex-col">
                        <h1 class="text-xl dark:text-white"> You are not connected to Stripe </h1>
                        <p class="text-sm dark:text-gray-300">This is required to accept payments from your customers. And
                            create resources!</p>
                        <br>
                        <a href="{{ route('settings.connect') }}">
                            <button type="button"
                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Connect to Stripe
                            </button>
                        </a>
                    </div>
                @endisset
            </div>
        </div>
        <div class="relative w-full max-w-3xl p-4 md:h-auto" id="paypal">
            <div class="relative p-4 bg-secondary-50 rounded-lg shadow dark:bg-secondary-100 sm:p-5">
                <div
                    class="flex items-center justify-between pb-4 mb-4 border-b rounded-t sm:mb-5 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Fill your PayPal email
                    </h3>
                </div>
                <form action="{{ route('settings.paypal') }}" method="POST">
                    @csrf
                    <div class="grid gap-4 mb-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="paypal"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">PayPal
                                email</label>
                            <input type="email" name="paypal" id="paypal" value="{{ $user->paypal }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="paypal@paymenter.org" required>
                        </div>
                        
                        <button type="submit"
                            class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            Update Paypal email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts-main>
