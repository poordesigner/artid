<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ayuda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 sm:p-8">
        <p class="mt-2 text-gray-600">{{ __('Conoce tu plataforma de identidad digital para obras de arte, paso a paso.') }}</p>

        <!-- Introducción -->
        <section id="intro" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">¿Qué es ARTid?</h2>
            <p class="mt-2 text-sm text-gray-700">
                <strong>ARTid</strong> — by <a href="https://poordesigner.com" class="text-indigo-600 hover:underline" target="_blank" rel="noopener">POORdesigner.com</a> — es una herramienta diseñada para que los artistas puedan generar una identidad digital para sus obras físicas.
            </p>

            <h3 class="mt-4 font-semibold">¿Cómo está estructurado ARTid?</h3>
            <ul class="mt-2 text-sm space-y-2 list-disc list-inside text-gray-700">
                <li><strong>Ficha de Datos</strong> — ARTid permite crear una ficha técnica para cada obra: título, año, edición, serie, técnicas, dimensiones, descripción e imagen.</li>
                <li><strong>QR permanente</strong> — ARTid genera un código QR permanente, que se convierte en un medio digital único de acceso a la ficha de la obra. El QR se imprime sobre la obra y **nunca cambia**.</li>
                <li><strong>Llaves cifradas</strong> — La generación de llaves cifradas hace que la relación entre la Ficha de Datos y el código QR sea única, evitando suplantaciones. Sólo la obra cuyo QR codifica a la URL firmada puede acceder a su ficha pública.</li>
            </ul>
        </section>

        <!-- Índice -->
        <div class="mt-6 bg-white rounded-lg shadow-sm p-5">
            <h2 class="font-semibold text-lg">{{ __('Índice') }}</h2>
            <ol class="mt-2 text-sm text-indigo-600 space-y-1">
                <li><a href="#registro" class="hover:underline">1. Crear tu cuenta en ARTid</a></li>
                <li><a href="#panel" class="hover:underline">2. El panel de obras</a></li>
                <li><a href="#obra" class="hover:underline">3. Crear una obra</a></li>
                <li><a href="#series" class="hover:underline">4. Organizar obras en series</a></li>
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
                <li>Haz clic en <strong>«Continue with Google»</strong> o regístrate con <strong>email y contraseña</strong> (el enlace «¿No tenés cuenta? Registrate» está en la página de login).</li>
                <li>Se crea tu cuenta de artista y entras al <strong>panel de obras</strong>.</li>
            </ol>
        </section>

        <!-- 2 -->
        <section id="panel" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">2. El panel de obras</h2>
            <p class="mt-2 text-sm text-gray-600">La página principal te muestra la lista de tus obras. Cada fila incluye el <strong>QR</strong> (en miniatura), el <strong>título</strong>, el <strong>año</strong> y el <strong>estado</strong>.</p>
            <ul class="mt-2 text-sm space-y-1 list-disc list-inside">
                <li><strong>New Artwork</strong> — crear una obra nueva.</li>
                <li><strong>Series</strong> — administrar tus series.</li>
                <li><strong>+ Expo</strong> / <strong>+ Propiedad</strong> — sumar historial a una obra.</li>
                <li><strong>Edit</strong> — modificar los datos de la obra.</li>
            </ul>
        </section>

        <!-- 3 -->
        <section id="obra" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">3. Crear una obra</h2>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>Panel → <strong>«New Artwork»</strong>.</li>
                <li>Completá: título, año, edición (ej. <code>1/3</code>), serie y descripción.</li>
                <li><strong>Artwork ID</strong> (opcional) — identificador permanente. Si lo dejás vacío se genera automáticamente del título.</li>
                <li><strong>Técnicas</strong> — seleccioná una o más. Escribí para filtrar y usá el <code>x</code> para quitar.</li>
                <li><strong>Dimensiones</strong> (ej. <code>50 x 70 cm</code>) y subí la <strong>imagen</strong> de la obra.</li>
                <li>Al guardar, se crea la obra y ya podés ver su QR y su ficha.</li>
            </ol>
        </section>

        <!-- 4 -->
        <section id="series" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">4. Organizar obras en series</h2>
            <p class="mt-2 text-sm text-gray-600">Las <strong>series</strong> agrupan obras relacionadas; el nombre de la serie aparece en la ficha pública de cada obra.</p>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>Panel → <strong>«Series»</strong>.</li>
                <li>Creá una serie con su nombre y una descripción.</li>
                <li>Asigná la serie a tus obras desde el formulario de creación/edición.</li>
                <li>Podés <strong>editar</strong> o <strong>eliminar</strong> series existentes.</li>
            </ol>
        </section>

        <!-- 5 -->
        <section id="qr" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">5. Generar e imprimir el QR</h2>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>En la lista de obras verás el <strong>QR</strong> de cada una.</li>
                <li>Hacé clic sobre el QR para abrirlo en tamaño completo (SVG vectorial).</li>
                <li>Guardalo e imprimilo sobre la obra física.</li>
                <li>El QR codifica una <strong>URL firmada</strong> que dirige a la ficha pública de la obra.</li>
            </ol>
        </section>

        <!-- 6 -->
        <section id="ficha" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">6. La ficha pública</h2>
            <p class="mt-2 text-sm text-gray-600">Al escanear el QR se abre la <strong>ficha pública</strong> de la obra, alojada por ARTid en <span class="font-mono">{{ config('artid.public_url') }}</span>. La ficha muestra:</p>
            <ul class="mt-2 text-sm space-y-1 list-disc list-inside">
                <li>La <strong>imagen</strong>, el <strong>título</strong> y el <strong>artista</strong>.</li>
                <li>La <strong>metadata</strong> (año, edición, serie, técnica, dimensiones, descripción).</li>
                <li>El <strong>historial</strong>: exposiciones y proveniencia.</li>
                <li>Un sello <strong>«Verificado por ARTid»</strong> que confirma que la información es auténtica y está firmada.</li>
            </ul>
            <p class="mt-2 text-sm text-gray-600">La ficha es de solo lectura y pública: cualquiera con el QR puede verla.</p>
        </section>

        <!-- 7 -->
        <section id="exposiciones" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">7. Registrar exposiciones</h2>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>Abre la obra → <strong>«+ Expo»</strong>.</li>
                <li>Completá: nombre, <strong>fecha de inicio</strong>, <strong>fecha de fin</strong>, <strong>ubicación</strong> (ciudad y país) y una descripción.</li>
                <li>Podés usar la lista de sugerencias de ciudades o escribir la vuestra.</li>
                <li>Al guardar, la exposición aparece en la obra y en su <strong>ficha pública</strong>.</li>
            </ol>
        </section>

        <!-- 8 -->
        <section id="propiedad" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">8. Control de propiedad</h2>
            <p class="mt-2 text-sm text-gray-600">El <strong>historial de propiedad</strong> (proveniencia) registra quién es el dueño de la obra y cómo fue pasando de mano en mano.</p>

            <h3 class="mt-4 font-semibold">Primer propietario</h3>
            <ol class="mt-2 text-sm space-y-2 list-decimal list-inside">
                <li>Abre la obra → <strong>«+ Propiedad»</strong> → tipo <strong>«Initial owner (artist)»</strong>.</li>
                <li>Indicá el nombre, email opcional, fecha y notas.</li>
                <li>El primer propietario queda registrado vislumbrable.</li>
            </ol>

            <h3 class="mt-4 font-semibold">Transferencia / venta</h3>
            <ol class="mt-2 text-sm space-y-2 list-decimal list-inside">
                <li>Abre la obra → <strong>«+ Propiedad»</strong> → tipo <strong>«Transfer / Sale»</strong>.</li>
                <li>Completá los datos del nuevo propietario y guardá. ARTid genera una <strong>llave secreta</strong> (se muestra una sola vez, ¡guárdala!).</li>
                <li>Entregá la llave al nuevo dueño junto con la obra.</li>
                <li>El nuevo dueño (o vos) puede introducir la llave en <strong>«Secret key → Reveal»</strong> para ver los datos del propietario.</li>
            </ol>
            <p class="mt-3 text-sm text-gray-600">Los datos del propietario en una transferencia están <strong>cifrados</strong>: ARTid guarda la marca de que ocurrió la transferencia, pero solo quien posee la llave puede conocer quién es el dueño en ese momento.</p>
        </section>

        <p class="mt-8 text-sm text-gray-500">{{ __('¿Dudas? Seguí estos pasos o contactá con soporte desde el panel.') }}</p>
    </div>
    </div>
</x-app-layout>