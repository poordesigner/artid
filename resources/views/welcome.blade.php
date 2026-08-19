<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>artid</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 px-4">
        <img src="{{ asset('img/logo.png') }}" alt="ARTid" class="h-24 w-auto">

        <p class="mt-6 text-center text-gray-600 max-w-md">
            Identidad Digital para tus Obras de Arte
        </p>

        <div class="mt-8 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Iniciar sesión') }}
            </a>
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Registrarse') }}
            </a>
        </div>

        <a href="{{ route('ayuda') }}" class="mt-8 text-lg text-gray-600 hover:text-gray-900 underline">{{ __('Ayuda') }}</a>
    </div>
</body>
</html>
