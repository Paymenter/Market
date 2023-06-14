@extends('layouts.auth')
@section('content')
	<div class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 sm:justify-center sm:pt-0">
		<div>
			<a href="/">
				<img src="{{ asset('img/logo.png') }}" alt="logo" class="w-20 h-20">
			</a>
		</div>

		<div class="w-full px-6 py-4 mt-6 overflow-hidden bg-secondary-50 shadow-md sm:max-w-md sm:rounded-lg">
			<!-- Validation Errors -->
			@if ($errors->any())
				<div class="mb-4">
					<div class="font-medium text-red-600">
						{{ __('Whoops! Something went wrong.') }}
					</div>

					<ul class="mt-3 text-sm text-red-600 list-disc list-inside">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<form method="POST" action="{{ route('password.update') }}">
			@csrf

			<!-- Password Reset Token -->
				<input type="hidden" name="token" value="{{ $request->route('token') }}">

				<!-- Email Address -->
				<div>
					<label for="email" class="block text-sm font-medium text-gray-700">
						{{ __('Email') }}
					</label>

					<input id="email" name="email" type="email" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('email', $request->email) }}" required autofocus>
				</div>

				<!-- Password -->
				<div class="mt-4">
					<label for="password" class="block text-sm font-medium text-gray-700">
						{{ __('Password') }}
					</label>

					<input id="password" name="password" type="password" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
				</div>

				<!-- Confirm Password -->
				<div class="mt-4">
					<label for="password_confirmation" class="block text-sm font-medium text-gray-700">
						{{ __('Confirm Password') }}
					</label>

					<input id="password_confirmation" name="password_confirmation" type="password" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
				</div>

				<div class="flex items-center justify-end mt-4">
					<button type="submit" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25">
						{{ __('Reset Password') }}
					</button>
				</div>
			</form>
		</div>
	</div>
@endsection