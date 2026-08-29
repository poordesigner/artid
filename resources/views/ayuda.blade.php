@extends('layouts.public')
@section('title', __('Ayuda') . ' — ARTid')
@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 sm:p-8">
        <h1 class="font-semibold text-xl text-gray-900">{{ __('Ayuda') }}</h1>
        <p class="mt-2 text-gray-600">{{ __('Conoce tu plataforma de identidad digital para obras de arte, paso a paso.') }}</p>

        <!-- Introducción -->
        <section id="intro" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">{{ __('¿Qué es ARTid?') }}</h2>
            <p class="mt-2 text-sm text-gray-700">
                <strong>ARTid</strong> — by <a href="https://poordesigner.com" class="text-indigo-600 hover:underline" target="_blank" rel="noopener">POORdesigner.com</a> — {{ __('es una herramienta diseñada para que los artistas puedan generar una identidad digital para sus obras físicas.') }}
            </p>

            <h3 class="mt-4 font-semibold">{{ __('¿Cómo está estructurado ARTid?') }}</h3>
            <ul class="mt-2 text-sm space-y-2 list-disc list-inside text-gray-700">
                <li><strong>{{ __('Ficha de Datos') }}</strong> — {{ __('Crea una ficha técnica básica de tu obra: título, año, edición, serie, técnicas, dimensiones, descripción, imagen y links externos.') }}</li>
                <li><strong>{{ __('QR Permanente') }}</strong> — {{ __('Cada obra tiene un código QR único que nunca cambia. Se imprime sobre la obra y dirige a la ficha pública.') }}</li>
                <li><strong>{{ __('Llaves Cifradas') }}</strong> — {{ __('La relación entre la ficha técnica y la ruta de acceso del código QR está firmada criptográficamente. Solo la obra auténtica accede a su ficha.') }}</li>
                <li><strong>{{ __('Historial de Exposiciones y Propiedad') }}</strong> — {{ __('Documenta el historial de exposiciones y la proveniencia de cada obra.') }}</li>
                <li><strong>{{ __('Pago único, sin suscripción') }}</strong> — {{ __('1 token = QR + ficha básica de una obra, para siempre.') }}</li>
            </ul>
        </section>

        <!-- Índice -->
        <div class="mt-6 bg-white rounded-lg shadow-sm p-5">
            <h2 class="font-semibold text-lg">{{ __('Índice') }}</h2>
            <ol class="mt-2 text-sm text-indigo-600 space-y-1">
                <li><a href="#cuenta" class="hover:underline">{{ __('1. Crear tu cuenta') }}</a></li>
                <li><a href="#tokens" class="hover:underline">{{ __('2. Tokens: cómo funcionan y cómo comprarlos') }}</a></li>
                <li><a href="#panel" class="hover:underline">{{ __('3. El panel de obras') }}</a></li>
                <li><a href="#obra" class="hover:underline">{{ __('4. Crear una obra') }}</a></li>
                <li><a href="#series" class="hover:underline">{{ __('5. Organizar obras en series') }}</a></li>
                <li><a href="#qr" class="hover:underline">{{ __('6. Generar e imprimir el QR') }}</a></li>
                <li><a href="#ficha" class="hover:underline">{{ __('7. La ficha pública y tu perfil público') }}</a></li>
                <li><a href="#exposiciones" class="hover:underline">{{ __('8. Registrar exposiciones') }}</a></li>
                <li><a href="#propiedad" class="hover:underline">{{ __('9. Control de propiedad (proveniencia)') }}</a></li>
                <li><a href="#enlaces" class="hover:underline">{{ __('10. Enlaces externos en tu obra') }}</a></li>
                <li><a href="#perfil" class="hover:underline">{{ __('11. Tu perfil de artista') }}</a></li>
                <li><a href="#configuracion" class="hover:underline">{{ __('12. Configuración y seguridad') }}</a></li>
            </ol>
        </div>

        <!-- 1 -->
        <section id="cuenta" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">{{ __('1. Crear tu cuenta') }}</h2>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>{{ __('Entra a :app.', ['app' => config('app.url')]) }}</li>
                <li>{{ __('Haz clic en «Continue with Google» o regístrate con email y contraseña.') }}</li>
                <li>{{ __('Se crea tu cuenta de artista y recibes :count tokens de bienvenida, gratis y sin tarjeta.', ['count' => config('artid.welcome_tokens', 0)]) }}</li>
            </ol>
        </section>

        <!-- 2 -->
        <section id="tokens" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">{{ __('2. Tokens: cómo funcionan y cómo comprarlos') }}</h2>
            <ul class="mt-3 text-sm space-y-2 list-disc list-inside">
                <li>{{ __('1 token = QR + ficha básica de una obra, para siempre.') }}</li>
                <li>{{ __('Al guardar una obra nueva se consume 1 token de tu saldo.') }}</li>
                <li>{{ __('Para comprar: entra a tu panel → «Comprar tokens», elige un paquete y paga una sola vez con Paddle. Los tokens se acreditan a tu saldo automáticamente.') }}</li>
                <li>{{ __('En «Mis tokens» ves tu saldo, los paquetes disponibles y el historial de movimientos (compras, tokens otorgados y consumos).') }}</li>
                <li>{{ __('Si no te quedan tokens no puedes crear obras nuevas, pero sí editar y gestionar las existentes.') }}</li>
            </ul>
        </section>

        <!-- 3 -->
        <section id="panel" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">{{ __('3. El panel de obras') }}</h2>
            <p class="mt-2 text-sm text-gray-600">{{ __('La página principal te muestra la lista de tus obras. Cada fila incluye el QR (en miniatura), el título, el año y el estado.') }}</p>
            <ul class="mt-2 text-sm space-y-1 list-disc list-inside">
                <li>{{ __('«New Artwork» — crear una obra nueva (consume 1 token).') }}</li>
                <li>{{ __('«Series» — administrar tus series.') }}</li>
                <li>{{ __('«+ Expo» / «+ Propiedad» — sumar historial a una obra.') }}</li>
                <li>{{ __('«Edit» / «Delete» — modificar o eliminar la obra.') }}</li>
                <li>{{ __('Filtros por estado: Todas / Activas / Inactivas. Las obras inactivas se muestran atenuadas.') }}</li>
            </ul>
        </section>

        <!-- 4 -->
        <section id="obra" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">{{ __('4. Crear una obra') }}</h2>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>{{ __('Panel → «New Artwork».') }}</li>
                <li>{{ __('Completa: título, año, edición (pieza única, tiraje o P/A con nº de copias), serie y descripción.') }}</li>
                <li>{{ __('Artwork ID (opcional): identificador permanente; solo mayúsculas, guiones o puntos (ej. NATURAI-3.0). Si lo dejas vacío, se genera del título.') }}</li>
                <li>{{ __('Técnicas: selecciona una o más. Escribe para filtrar y usa el «x» para quitar.') }}</li>
                <li>{{ __('Dimensiones (alto x ancho x profundidad) y unidad.') }}</li>
                <li>{{ __('Imagen: JPG o PNG, máximo 2 MB. Se optimiza automáticamente para la web.') }}</li>
                <li>{{ __('Al guardar se crea la obra, se consume 1 token y ya puedes ver su QR y su ficha pública.') }}</li>
            </ol>
        </section>

        <!-- 5 -->
        <section id="series" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">{{ __('5. Organizar obras en series') }}</h2>
            <p class="mt-2 text-sm text-gray-600">{{ __('Las series agrupan obras relacionadas; el nombre de la serie aparece en la ficha pública de cada obra.') }}</p>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>{{ __('Panel → «Series».') }}</li>
                <li>{{ __('Crea una serie con su nombre y una descripción.') }}</li>
                <li>{{ __('Asigna la serie a tus obras desde el formulario de creación o edición.') }}</li>
                <li>{{ __('Puedes editar o eliminar series existentes.') }}</li>
            </ol>
        </section>

        <!-- 6 -->
        <section id="qr" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">{{ __('6. Generar e imprimir el QR') }}</h2>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>{{ __('En la lista de obras verás el QR de cada una.') }}</li>
                <li>{{ __('Haz clic sobre el QR para abrirlo en tamaño completo (SVG vectorial, listo para imprimir).') }}</li>
                <li>{{ __('Guárdalo e imprímelo sobre la obra física, o inclúyelo donde quieras.') }}</li>
                <li>{{ __('El QR codifica una URL firmada que dirige a la ficha pública de la obra.') }}</li>
                <li>{{ __('El QR nunca cambia: aunque edites la obra, el QR y su ficha siguen siendo los mismos.') }}</li>
            </ol>
        </section>

        <!-- 7 -->
        <section id="ficha" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">{{ __('7. La ficha pública y tu perfil público') }}</h2>
            <p class="mt-2 text-sm text-gray-600">{{ __('Al escanear el QR se abre la ficha pública de la obra, alojada por ARTid en :url. Verifica la firma del QR: sin firma válida, la ficha no se muestra (404).', ['url' => config('artid.public_url')]) }}</p>
            <ul class="mt-2 text-sm space-y-1 list-disc list-inside">
                <li>{{ __('Muestra la imagen, el título y el artista.') }}</li>
                <li>{{ __('La metadata: año, edición, serie, técnica, dimensiones y descripción.') }}</li>
                <li>{{ __('El historial: exposiciones y proveniencia.') }}</li>
                <li>{{ __('Un sello «Verificado por ARTid» que confirma que la información es auténtica y está firmada.') }}</li>
            </ul>
            <p class="mt-2 text-sm text-gray-600">{{ __('La ficha es de solo lectura y pública: cualquiera con el QR puede verla.') }}</p>
            <p class="mt-2 text-sm text-gray-600">{{ __('También tienes un perfil público como artista, donde se muestran tu avatar, declaración (statement), CV, redes sociales y enlaces de perfil.') }}</p>
        </section>

        <!-- 8 -->
        <section id="exposiciones" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">{{ __('8. Registrar exposiciones') }}</h2>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>{{ __('Abre la obra → «+ Expo».') }}</li>
                <li>{{ __('Completa: nombre, fecha de inicio, fecha de fin, ubicación (ciudad y país) y una descripción.') }}</li>
                <li>{{ __('Puedes usar la lista de sugerencias de ciudades o escribir la tuya.') }}</li>
                <li>{{ __('Al guardar, la exposición aparece en la obra y en su ficha pública.') }}</li>
            </ol>
        </section>

        <!-- 9 -->
        <section id="propiedad" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">{{ __('9. Control de propiedad (proveniencia)') }}</h2>
            <p class="mt-2 text-sm text-gray-700">{{ __('El historial de propiedad (proveniencia) registra quién es el dueño de la obra y cómo fue pasando de mano en mano.') }}</p>

            <h3 class="mt-4 font-semibold">{{ __('Primer propietario') }}</h3>
            <ol class="mt-2 text-sm space-y-2 list-decimal list-inside">
                <li>{{ __('Abre la obra → «+ Propiedad» → tipo «Initial owner (artist)».') }}</li>
                <li>{{ __('Indica el nombre, email opcional, fecha y notas.') }}</li>
                <li>{{ __('El primer propietario queda registrado de forma visible.') }}</li>
            </ol>

            <h3 class="mt-4 font-semibold">{{ __('Transferencia / venta') }}</h3>
            <ol class="mt-2 text-sm space-y-2 list-decimal list-inside">
                <li>{{ __('Abre la obra → «+ Propiedad» → tipo «Transfer / Sale».') }}</li>
                <li>{{ __('Completa los datos del nuevo propietario (pueden ser anónimos) y guarda. ARTid genera una llave secreta que se muestra una sola vez: ¡guárdala!') }}</li>
                <li>{{ __('Entrega la llave al nuevo dueño junto con la obra.') }}</li>
                <li>{{ __('Quien tenga la llave puede revelar los datos del propietario desde «Secret key → Reveal».') }}</li>
            </ol>
            <p class="mt-3 text-sm text-gray-700">{{ __('Los datos de una transferencia están cifrados: ARTid guarda la marca de que ocurrió, pero solo la llave desbloquea quién es el dueño en ese momento.') }}</p>
        </section>

        <!-- 10 -->
        <section id="enlaces" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">{{ __('10. Enlaces externos en tu obra') }}</h2>
            <ul class="mt-3 text-sm space-y-2 list-disc list-inside">
                <li>{{ __('Puedes asociar hasta 10 enlaces a cada obra (tipo video, foto o blog).') }}</li>
                <li>{{ __('Los enlaces aparecen en la ficha pública de la obra.') }}</li>
            </ul>
        </section>

        <!-- 11 -->
        <section id="perfil" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">{{ __('11. Tu perfil de artista') }}</h2>
            <ul class="mt-3 text-sm space-y-2 list-disc list-inside">
                <li>{{ __('En «Perfil» completas tu información pública: foto de perfil, nombre, email, declaración (statement), hoja de vida en PDF, página web y redes sociales (Instagram, Behance, ArtStation, YouTube, TikTok).') }}</li>
                <li>{{ __('Puedes agregar hasta 5 enlaces de perfil (portafolio, CV o exposiciones).') }}</li>
                <li>{{ __('Toda esa información se muestra en tu perfil público.') }}</li>
            </ul>
        </section>

        <!-- 12 -->
        <section id="configuracion" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">{{ __('12. Configuración y seguridad') }}</h2>
            <ul class="mt-3 text-sm space-y-2 list-disc list-inside">
                <li>{{ __('En «Configuración» administras tu email y contraseña, revisas tu saldo de tokens y puedes eliminar tu cuenta.') }}</li>
                <li>{{ __('Para eliminar la cuenta debes escribir la palabra de confirmación. Esta acción no se puede deshacer.') }}</li>
            </ul>
        </section>

        <p class="mt-8 text-sm text-gray-500">{{ __('¿Dudas? Sigue estos pasos o contáctanos desde el chat de soporte en la plataforma.') }}</p>
            </div>
        </div>
    </div>
@endsection