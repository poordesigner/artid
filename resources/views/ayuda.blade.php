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
        <a href="{{ route('artworks.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">&larr; {{ __('Volver al panel') }}</a>

        <h1 class="mt-4 text-3xl font-bold">{{ __('Guía de inicio') }}</h1>
        <p class="mt-2 text-gray-600">{{ __('Conoce tu plataforma de identidad digital para obras de arte, paso a paso.') }}</p>

        <!-- Introducción -->
        <section id="intro" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">¿Qué es ARTid?</h2>
            <p class="mt-2 text-sm text-gray-700">
                <strong>ARTid</strong> — by <a href="https://poordesigner.com" class="text-indigo-600 hover:underline" target="_blank" rel="noopener">POORdesigner.com</a> — es una herramienta diseñada para que los artistas puedan generar una identidad digital para sus obras físicas.
            </p>

            <h3 class="mt-4 font-semibold">¿Cómo está estructurado ARTid?</h3>
            <ul class="mt-2 text-sm space-y-2 list-disc list-inside text-gray-700">
                <li><strong>Ficha de Datos</strong> — ARTid permite crear una ficha básica para cada obra de arte.</li>
                <li><strong>QR permanente</strong> — ARTid crea un código QR permanente, que se convierte en un medio digital único de acceso a la ficha técnica de la obra.</li>
                <li><strong>Llaves cifradas</strong> — La creación de llaves cifradas hace que la relación entre la Ficha de Datos y el código QR sea única, evitando suplantaciones.</li>
            </ul>
        </section>

        <!-- Índice -->
        <div class="mt-6 bg-white rounded-lg shadow-sm p-5">
            <h2 class="font-semibold text-lg">{{ __('Índice') }}</h2>
            <ol class="mt-2 text-sm text-indigo-600 space-y-1">
                <li><a href="#registro" class="hover:underline">1. Crear tu cuenta en ARTid</a></li>
                <li><a href="#panel" class="hover:underline">2. El panel de obras</a></li>
                <li><a href="#obra" class="hover:underline">3. Crear tu primera obra</a></li>
                <li><a href="#series" class="hover:underline">4. Organizar en series</a></li>
                <li><a href="#qr" class="hover:underline">5. Generar e imprimir el QR</a></li>
                <li><a href="#ficha" class="hover:underline">6. La ficha pública</a></li>
                <li><a href="#exposiciones" class="hover:underline">7. Registrar exposiciones</a></li>
                <li><a href="#propiedad" class="hover:underline">8. Control de propiedad</a></li>
            </ol>
        </div>

        <!-- 1 -->
        <section id="registro" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">1. Crear tu cuenta en ARTid</h2>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>Entra a <span class="font-mono">{{ config('app.url') }}</span>.</li>
                <li>Clic en <strong>«Continuar con Google»</strong>, o regístrate con tu <strong>email y contraseña</strong>.</li>
                <li>Se crea tu cuenta de artista y entras al <strong>panel de obras</strong>.</li>
            </ol>
        </section>

        <!-- 2 -->
        <section id="panel" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">2. El panel de obras</h2>
            <p class="mt-2 text-sm text-gray-600">El panel (<strong>Artworks</strong>) lista todas tus obras con su QR, título, año y estado. Desde aquí puedes:</p>
            <ul class="mt-2 text-sm space-y-1 list-disc list-inside">
                <li><strong>New Artwork</strong> — crear una obra nueva.</li>
                <li>Clic en el <strong>QR</strong> — verlo en tamaño completo para imprimir.</li>
                <li>Clic en el <strong>título</strong> — abrir la obra: metadata, exposiciones y propiedad.</li>
                <li><strong>+ Expo</strong> / <strong>+ Propiedad</strong> — atajos para sumar historial.</li>
                <li><strong>Edit</strong> — modificar los datos de la obra.</li>
                <li><strong>Series</strong> — administrar tus series.</li>
            </ul>
        </section>

        <!-- 3 -->
        <section id="obra" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">3. Crear tu primera obra</h2>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>Panel → <strong>«New Artwork»</strong>.</li>
                <li>Completa los campos:
                    <ul class="mt-1 ml-5 list-disc">
                        <li><strong>Título</strong> (obligatorio).</li>
                        <li><strong>Artwork ID</strong> (opcional) — identificador permanente de la obra, en mayúsculas y guiones. Ej: <code>NATURAI-3.0</code>. Si lo dejas vacío, se genera automáticamente.</li>
                        <li><strong>Imagen</strong> — foto de la obra (se almacena de forma segura).</li>
                        <li><strong>Año</strong>, <strong>Edición</strong> (ej. 1/3), <strong>Dimensiones</strong> y <strong>Descripción</strong>.</li>
                        <li><strong>Serie</strong> — elige una de tus series o ninguna.</li>
                        <li><strong>Técnicas</strong> — marca las técnicas utilizadas en la obra.</li>
                    </ul>
                </li>
                <li>Guarda: se genera su <strong>ID</strong> y su <strong>QR firmado</strong>.</li>
            </ol>
        </section>

        <!-- 4 -->
        <section id="series" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">4. Organizar en series</h2>
            <p class="mt-2 text-sm text-gray-600">Las <strong>series</strong> agrupan obras relacionadas, y su nombre se muestra en cada ficha pública.</p>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>Panel → <strong>«Series»</strong>.</li>
                <li>Crea una serie con su <strong>nombre</strong> y una descripción opcional.</li>
                <li>Asigna esa serie a tus obras desde el formulario de creación/edición.</li>
                <li>Puedes <strong>editar</strong> o <strong>eliminar</strong> series existentes.</li>
            </ol>
        </section>

        <!-- 5 -->
        <section id="qr" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">5. Generar e imprimir el QR</h2>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>En la lista de obras verás el <strong>QR</strong> de cada una.</li>
                <li>Clic sobre el QR para abrirlo en <strong>tamaño completo</strong>.</li>
                <li>Descárgalo (SVG, vectorial) e imprímelo en la obra física.</li>
                <li>El QR codifica la <strong>URL firmada</strong> de la ficha pública. Es permanente: aunque edites la obra, el QR sigue funcionando.</li>
            </ol>
        </section>

        <!-- 6 -->
        <section id="ficha" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">6. La ficha pública</h2>
            <p class="mt-2 text-sm text-gray-600">Al escanear el QR se abre la <strong>ficha pública</strong> de la obra, alojada por ARTid (<span class="font-mono">arte.poordesigner.com/o/&lt;id&gt;</span>). Muestra:</p>
            <ul class="mt-2 text-sm space-y-1 list-disc list-inside">
                <li>La <strong>imagen</strong> de la obra.</li>
                <li>La <strong>metadata</strong>: título, año, edición, técnica, dimensiones y descripción.</li>
                <li>El <strong>historial</strong>: exposiciones y proveniencia.</li>
                <li>El <strong>estado</strong> de la obra.</li>
            </ul>
            <p class="mt-2 text-sm text-gray-600">La fichas solo se muestran si la <strong>firma</strong> es válida. Así, ARTid garantiza que la información pública proviene del artista.</p>
        </section>

        <!-- 7 -->
        <section id="exposiciones" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">7. Registrar exposiciones</h2>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>Abre la obra → sección <strong>Exhibitions</strong> → <strong>«+ Add exhibition»</strong>.</li>
                <li>Completa: <strong>nombre</strong>, <strong>fecha</strong>, <strong>descripción</strong> y <strong>links</strong> (opcional).</li>
                <li>Al guardar, la exposición aparece en la obra y en su <strong>ficha pública</strong>.</li>
            </ol>
        </section>

        <!-- 8 -->
        <section id="propiedad" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">8. Control de propiedad</h2>
            <p class="mt-2 text-sm text-gray-600">El <strong>historial de propiedad</strong> (proveniencia) registra quién es el dueño de la obra y cómo fue pasando de mano en mano.</p>

            <h3 class="mt-4 font-semibold">Propietario inicial</h3>
            <ol class="mt-2 text-sm space-y-2 list-decimal list-inside">
                <li>Abre la obra → <strong>Ownership / Provenance</strong> → <strong>«+ Add ownership»</strong>.</li>
                <li>Tipo: <strong>«Initial owner (artist)»</strong> — registra al artista como dueño, con nombre, email opcional, fecha y notas.</li>
            </ol>

            <h3 class="mt-4 font-semibold">Transferencia / venta</h3>
            <ol class="mt-2 text-sm space-y-2 list-decimal list-inside">
                <li>Abre la obra → <strong>«+ Add ownership»</strong> → tipo <strong>«Transfer / Sale»</strong>.</li>
                <li>Completa los datos y guarda: se genera una <strong>llave secreta</strong> (se muestra una sola vez, ¡guárdala!).</li>
                <li>Entrega la llave al <strong>nuevo propietario</strong>, junto con la obra.</li>
                <li>Si el nuevo dueño necesita demostrar la propiedad, introduce la llave en <strong>«Secret key» → Reveal</strong> y verá los detalles del registro.</li>
            </ol>
            <p class="mt-3 text-sm text-gray-600">Los datos del propietario en una transferencia están <strong>cifrados</strong>: ARTid guarda una marca de que ocurrió la transferencia, pero solo quien posee la llave puede conocer los datos de la persona.</p>
        </section>

        <p class="mt-8 text-sm text-gray-500">{{ __('¿Dudas? Revisa las secciones de este guía o contacta con soporte.') }}</p>
    </div>
</body>
</html>