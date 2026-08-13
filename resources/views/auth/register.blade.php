<!-- Name -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Biro Jasa</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div class="w-[500px] bg-white rounded-lg shadow-sm px-10 py-8">
          <!-- Logo -->
        <div class="flex justify-center">
            <div class="w-20 h-20 flex items-center justify-center">
                <img 
                    src="{{ asset('images/logo.png') }}" 
                    alt="Logo"
                    class="w-20 h-20 object-contain"
                >
            </div>
        </div>

        <!-- Nama Aplikasi -->
<div class="text-center mt-0 mb-4">
    <h2 class="text-lg font-semibold text-gray-800">
        Biro Jasa STNK
    </h2>

    <p class="text-sm text-gray-500">
        Management System
    </p>
</div>

        <!-- Title -->
        <h1 class="text-3xl font-bold text-center text-black mb-7">
            Register
        </h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <label
                    for="name"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Name
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    class="block mt-2 w-full h-[48px]
                           rounded-lg border border-gray-300
                           px-4
                           focus:border-indigo-500
                           focus:ring-1 focus:ring-indigo-500
                           focus:outline-none"
                >

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
                <label
                    for="email"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    class="block mt-2 w-full h-[48px]
                           rounded-lg border border-gray-300
                           px-4
                           focus:border-indigo-500
                           focus:ring-1 focus:ring-indigo-500
                           focus:outline-none"
                >

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
                <label
                    for="password"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Password
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="block mt-2 w-full h-[48px]
                           rounded-lg border border-gray-300
                           px-4
                           focus:border-indigo-500
                           focus:ring-1 focus:ring-indigo-500
                           focus:outline-none"
                >

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
                    class="block mt-2 w-full h-[48px]
                           rounded-lg border border-gray-300
                           px-4
                           focus:border-indigo-500
                           focus:ring-1 focus:ring-indigo-500
                           focus:outline-none"
                >

                @if ($errors->get('password_confirmation'))
                    <div class="mt-2 text-sm text-red-600">
                        @foreach ($errors->get('password_confirmation') as $message)
                            <p>{{ $message }}</p>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Bottom -->
            <div class="flex items-center justify-between mt-6">

                <a
                    href="{{ route('login') }}"
                    class="text-sm text-gray-600 underline
                           hover:text-gray-900"
                >
                    Already registered?
                </a>

                <button
                    type="submit"
                    class="px-6 py-2.5
                           bg-indigo-600
                           text-white
                           text-sm font-semibold
                           rounded-lg
                           hover:bg-indigo-700
                           focus:outline-none
                           focus:ring-2
                           focus:ring-indigo-500
                           focus:ring-offset-2
                           transition"
                >
                    REGISTER
                </button>

            </div>

        </form>

    </div>
</body>
</html>