<!-- Name -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Sistem Biro Jasa</title>
    @vite ('resources/css/app.css')
</head>
<body class="flex min-h-screen items-center justify-center bg-gray-100">
    <div class="w-[500px] rounded-lg bg-white px-10 py-8 shadow-sm">
        <!-- Logo -->
        <div class="flex justify-center">
            <div class="flex h-20 w-20 items-center justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 w-20 object-contain"/>
            </div>
        </div>

        <!-- Nama Aplikasi -->
        <div class="mb-4 mt-0 text-center">
            <h2 class="text-lg font-semibold text-gray-800">Biro Jasa STNK</h2>

            <p class="text-sm text-gray-500">Management System</p>
        </div>

        <!-- Title -->
        <h1 class="mb-7 text-center text-3xl font-bold text-black">Register</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700"> Name </label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="mt-2 block h-[48px] w-full rounded-lg border border-gray-300 px-4 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"/>

                @if ($errors->get('name'))
                    <div class="mt-2 text-sm text-red-600">
                        @foreach ($errors->get('name') as $message)
                            <p>{{ $message }}</p>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Email -->
            <div class="mt-4">
                <label for="email" class="block text-sm font-semibold text-gray-700"> Email </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    class="mt-2 block h-[48px] w-full rounded-lg border border-gray-300 px-4 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                />

                @if ($errors->get('email'))
                    <div class="mt-2 text-sm text-red-600">
                        @foreach ($errors->get('email') as $message)
                            <p>{{ $message }}</p>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Password -->
            <div class="mt-4">
                <label for="password" class="block text-sm font-semibold text-gray-700">
                    Password
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="mt-2 block h-[48px] w-full rounded-lg border border-gray-300 px-4 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                />

                @if ($errors->get('password'))
                    <div class="mt-2 text-sm text-red-600">
                        @foreach ($errors->get('password') as $message)
                            <p>{{ $message }}</p>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <label
                    for="password_confirmation"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Confirm Password
                </label>

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="mt-2 block h-[48px] w-full rounded-lg border border-gray-300 px-4 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                />

                @if ($errors->get('password_confirmation'))
                    <div class="mt-2 text-sm text-red-600">
                        @foreach ($errors->get('password_confirmation') as $message)
                            <p>{{ $message }}</p>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Bottom -->
            <div class="mt-6 flex items-center justify-between">
                <a
                    href="{{ route('login') }}"
                    class="text-sm text-gray-600 underline hover:text-gray-900"
                >
                    Already registered?
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    REGISTER
                </button>
            </div>
        </form>
    </div>
</body>
</html>
