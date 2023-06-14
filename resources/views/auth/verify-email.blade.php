<x-layouts-main>

    <section class="bg-secondary-50 dark:bg-secondary-100">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-[90vh] lg:py-0">
            <a href="/" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="/img/logo.png" alt="logo">
                Paymenter
            </a>
            <div
                class="w-full bg-secondary-50 rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-secondary-100 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">

                    <div class="w-full px-6 py-4 mt-6 overflow-hidden sm:max-w-md sm:rounded-lg">
                        <div class="mb-4 text-sm text-gray-600 dark:text-white">
                            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                        </div>

                        @if (session('status') == 'verification-link-sent')
                            <div class="mb-4 text-sm font-medium text-green-600">
                                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                            </div>
                        @endif

                        <div class="flex items-center justify-between mt-4">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf

                                <div>
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25">
                                        {{ __('Resend Verification Email') }}
                                    </button>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit" class="text-sm text-gray-600 underline hover:text-gray-900">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</section>
</x-layouts-main>
