<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ayuda — ARTid</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900">
    <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6">
        <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-900">&larr; {{ __('Volver al panel') }}</a>

        <h1 class="mt-4 text-3xl font-bold">{{ __('Guía de inicio') }}</h1>
        <p class="mt-2 text-gray-600">{{ __('Sigue estos pasos para montar tu framework de identidad digital de obras.') }}</p>

        <ol class="mt-8 space-y-6">
            <li class="bg-white rounded-lg shadow-sm p-5">
                <h2 class="font-semibold text-lg">{{ __('1. Crea tu cuenta') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('Regístrate con Google o con email y contraseña.') }}</p>
            </li>

            <li class="bg-white rounded-lg shadow-sm p-5">
                <h2 class="font-semibold text-lg">{{ __('2. Conecta tu GitHub') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('En el Dashboard, haz clic en "Connect GitHub" y autoriza con tu cuenta. ARTid usará tu GitHub para guardar tus obras.') }}</p>
            </li>

            <li class="bg-white rounded-lg shadow-sm p-5">
                <h2 class="font-semibold text-lg">{{ __('3. Vincula o crea tu repositorio') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('Dashboard → "Configure repository" → elige un repositorio existente o crea uno nuevo. Ahí vivirá tu framework.') }}</p>
            </li>

            <li class="bg-white rounded-lg shadow-sm p-5">
                <h2 class="font-semibold text-lg">{{ __('4. Instala la ficha') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('En la configuración de GitHub, haz clic en "Install ficha". Esto escribe la página pública de tus obras en la raíz de tu repositorio.') }}</p>
            </li>

            <li class="bg-white rounded-lg shadow-sm p-5">
                <h2 class="font-semibold text-lg">{{ __('5. Publica tu ficha') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('Activa GitHub Pages: en tu repositorio → Settings → Pages → Source "main" → Save. O sube el contenido a tu propio hosting.') }}</p>
            </li>

            <li class="bg-white rounded-lg shadow-sm p-5">
                <h2 class="font-semibold text-lg">{{ __('6. Configura tu short URL') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('En GitHub settings → "Short URL", escribe tu dominio corto de short.io (ej. tatomico.s.gy). En short.io, crea un link que redirija a tu ficha.') }}</p>
            </li>

            <li class="bg-white rounded-lg shadow-sm p-5">
                <h2 class="font-semibold text-lg">{{ __('7. Crea tu primera obra') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('Artworks → "New Artwork" → completa título, año, edición, serie, técnicas y sube la imagen.') }}</p>
            </li>

            <li class="bg-white rounded-lg shadow-sm p-5">
                <h2 class="font-semibold text-lg">{{ __('8. Descarga el QR') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('En la lista de obras verás el QR de cada una. Ábrelo e imprímelo para colocarlo en la obra física.') }}</p>
            </li>

            <li class="bg-white rounded-lg shadow-sm p-5">
                <h2 class="font-semibold text-lg">{{ __('9. Historial (opcional)') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('Agrega exposiciones y registros de propiedad desde las acciones de cada obra. La página pública mostrará ese historial.') }}</p>
            </li>
        </ol>
    </div>
</body>
</html>
