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
        <p class="mt-2 text-gray-600">{{ __('Configura tu framework de identidad digital de obras paso a paso.') }}</p>

        <!-- Introducción -->
        <section id="intro" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">¿Qué es ARTid?</h2>
            <p class="mt-2 text-sm text-gray-700">
                <strong>ARTid</strong> — by <a href="https://poordesigner.com" class="text-indigo-600 hover:underline" target="_blank" rel="noopener">POORdesigner.com</a> — es una herramienta que da
                <strong>identidad digital</strong> a tus obras de arte físicas. Cada obra recibe un
                <strong>QR permanente</strong> impreso en ella, que enlaza a una
                <strong>ficha digital pública y actualizable</strong>, sin necesidad de volver a imprimir el QR nunca.
            </p>

            <ul class="mt-4 text-sm space-y-2 list-disc list-inside text-gray-700">
                <li><strong>QR permanente</strong> — se imprime una vez en la obra física y nunca cambia.</li>
                <li><strong>Short URL</strong> — el punto intermedio entre el QR y la ficha. Si cambias de hosting, solo re-apuntas el short URL.</li>
                <li><strong>Ficha pública</strong> — muestra la imagen, la metadata y el historial (exposiciones y proveniencia).</li>
                <li><strong>Eres dueño de tu información</strong> — tus obras viven en <em>tu</em> GitHub, <em>tu</em> dominio y <em>tu</em> short URL, no en un servidor ajeno.</li>
            </ul>

            <p class="mt-4 text-sm text-gray-600">Filosofía: <strong>propiedad del artista &gt; control de la plataforma</strong>. El QR nunca cambia; el destino sí.</p>
        </section>

        <!-- Cómo funciona -->
        <section id="como-funciona" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">Cómo funciona la ficha (open source)</h2>
            <p class="mt-2 text-sm text-gray-600">La <strong>ficha</strong> es un conjunto de archivos estáticos (HTML + CSS + JS) que se instalan en <strong>tu</strong> repositorio. Es <strong>open source</strong>: tú la posees, puedes descargarla, modificarla y alojarla donde quieras.</p>

            <h3 class="mt-4 font-semibold">El flujo al escanear el QR</h3>
            <ol class="mt-2 text-sm space-y-2 list-decimal list-inside">
                <li>Escaneas el <strong>QR</strong> → apunta a <code>https://tudominio.s.gy?art=&lt;ID&gt;</code>.</li>
                <li><strong>short.io</strong> redirige a <code>https://tu-ficha/redirect.html?art=&lt;ID&gt;</code>.</li>
                <li><code>redirect.html</code> convierte <code>?art=&lt;ID&gt;</code> en <code>#/art/&lt;ID&gt;</code> (routing por hash, sin servidor).</li>
                <li><code>index.html</code> + <code>js/app.js</code> leen el hash y cargan desde GitHub:
                    <ul class="mt-1 ml-5 list-disc">
                        <li><code>artworks/&lt;ID&gt;/metadata.json</code> — datos de la obra.</li>
                        <li><code>artworks/&lt;ID&gt;/exhibitions.json</code> — exposiciones.</li>
                        <li><code>artworks/&lt;ID&gt;/ownership.json</code> — proveniencia.</li>
                    </ul>
                </li>
                <li>Se renderiza la obra con su imagen, metadata e historial.</li>
            </ol>

            <h3 class="mt-4 font-semibold">Los archivos</h3>
            <ul class="mt-2 text-sm space-y-1 list-disc list-inside">
                <li><code>index.html</code> — estructura base de la página.</li>
                <li><code>js/app.js</code> — lógica: routing + carga de JSON + render.</li>
                <li><code>css/style.css</code> — diseño oscuro minimalista.</li>
                <li><code>redirect.html</code> — puente entre el short URL y la ficha.</li>
            </ul>

            <p class="mt-4 text-sm text-gray-600">Todo es tuyo: el repositorio, el dominio y el short URL. El <strong>QR nunca cambia</strong>; si mañana migras a otro hosting, solo re-apuntas el short URL.</p>
        </section>

        <!-- Índice -->
        <div class="mt-6 bg-white rounded-lg shadow-sm p-5">
            <h2 class="font-semibold text-lg">{{ __('Índice') }}</h2>
            <ol class="mt-2 text-sm text-indigo-600 space-y-1">
                <li><a href="#intro" class="hover:underline">¿Qué es ARTid?</a></li>
                <li><a href="#como-funciona" class="hover:underline">Cómo funciona la ficha (open source)</a></li>
                <li><a href="#registro" class="hover:underline">1. Crear tu cuenta en ARTid</a></li>
                <li><a href="#github-cuenta" class="hover:underline">2. Conectar tu cuenta de GitHub</a></li>
                <li><a href="#repo" class="hover:underline">3. Vincular o crear tu repositorio</a></li>
                <li><a href="#ficha" class="hover:underline">4. Instalar la ficha</a></li>
                <li><a href="#pages" class="hover:underline">5. Publicar con GitHub Pages</a></li>
                <li><a href="#shortio" class="hover:underline">6. Configurar short.io</a></li>
                <li><a href="#obra" class="hover:underline">7. Crear tu primera obra</a></li>
                <li><a href="#qr" class="hover:underline">8. Generar el QR</a></li>
                <li><a href="#historial" class="hover:underline">9. Historial de la obra</a></li>
            </ol>
        </div>

        <!-- 1 -->
        <section id="registro" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">1. Crear tu cuenta en ARTid</h2>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>Entra a <span class="font-mono">{{ config('app.url') }}</span>.</li>
                <li>Haz clic en <strong>«Continuar con Google»</strong> o regístrate con email y contraseña.</li>
                <li>Se crea tu cuenta de artista y entras al <strong>Dashboard</strong>.</li>
            </ol>
        </section>

        <!-- 2 -->
        <section id="github-cuenta" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">2. Conectar tu cuenta de GitHub</h2>
            <p class="mt-2 text-sm text-gray-600">ARTid guarda tus obras en <strong>tu</strong> repositorio de GitHub. Necesitas una cuenta de GitHub:</p>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>Si no tienes cuenta, créala en <a href="https://github.com/signup" class="text-indigo-600 hover:underline" target="_blank" rel="noopener">github.com/signup</a>.</li>
                <li>En el Dashboard de ARTid, haz clic en <strong>«Connect GitHub»</strong>.</li>
                <li>GitHub te pedirá autorizar a ARTid (acceso a tus repositorios). Acepta.</li>
                <li>Al volver verás «Conectado como @tuusuario».</li>
            </ol>
        </section>

        <!-- 3 -->
        <section id="repo" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">3. Vincular o crear tu repositorio</h2>
            <p class="mt-2 text-sm text-gray-600">El repositorio es donde vivirán tus obras (<code>artworks/</code>) y la ficha.</p>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>Dashboard → <strong>«Configure repository»</strong>.</li>
                <li><strong>Opción A — vincular existente</strong>: elige uno de tus repositorios y clic en <strong>«Link»</strong>.</li>
                <li><strong>Opción B — crear nuevo</strong>: escribe un nombre (ej. <code>arte</code>) y clic en <strong>«Create»</strong>.</li>
                <li>Si el repo ya tiene obras, usa <strong>«Sync artworks»</strong> para importarlas.</li>
            </ol>
        </section>

        <!-- 4 -->
        <section id="ficha" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">4. Instalar la ficha</h2>
            <p class="mt-2 text-sm text-gray-600">La ficha es la página pública que muestra tu obra al escanear el QR.</p>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>En la configuración de GitHub, haz clic en <strong>«Install ficha»</strong>.</li>
                <li>ARTid escribe en la raíz de tu repositorio: <code>index.html</code>, <code>redirect.html</code>, <code>js/app.js</code> y <code>css/style.css</code>.</li>
            </ol>
        </section>

        <!-- 5 -->
        <section id="pages" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">5. Publicar con GitHub Pages</h2>
            <p class="mt-2 text-sm text-gray-600">Para que la ficha sea pública y accesible por URL:</p>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>Ve a tu repositorio en GitHub → <strong>Settings</strong> → <strong>Pages</strong>.</li>
                <li>En <strong>«Build and deployment»</strong> → Source: <strong>«Deploy from a branch»</strong>.</li>
                <li>Rama: <strong><code>main</code></strong>, carpeta: <strong><code>/ (root)</code></strong> → <strong>Save</strong>.</li>
                <li>GitHub te da una URL del tipo <code>https://tuusuario.github.io/turepo/</code>.</li>
                <li>Esa URL es tu <strong>ficha base</strong>. El short URL apuntará a <code>&lt;esa URL&gt;/redirect.html</code>.</li>
                <li class="text-gray-500">(Alternativa: sube el contenido del repo a tu propio hosting/dominio.)</li>
            </ol>
        </section>

        <!-- 6 -->
        <section id="shortio" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">6. Configurar short.io</h2>
            <p class="mt-2 text-sm text-gray-600">El short URL es el punto intermedio entre el QR y tu ficha. Así el QR nunca cambia aunque cambies de hosting.</p>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>Crea una cuenta en <a href="https://short.io" class="text-indigo-600 hover:underline" target="_blank" rel="noopener">short.io</a>.</li>
                <li>Añade un <strong>dominio corto</strong> (puedes usar el gratuito <code>.s.gy</code> que te asigna, o conectar tu propio dominio).</li>
                <li>Configura el <strong>root redirect</strong> del dominio para que apunte a la URL de tu ficha + <code>redirect.html</code>. Ejemplo:
                    <pre class="mt-1 bg-gray-100 rounded p-2 font-mono text-xs overflow-x-auto">https://tuusuario.github.io/turepo/redirect.html</pre>
                </li>
                <li>Prueba: abre <code>https://tudominio.s.gy?art=NOMBRE_DE_OBRA</code> → debe redirigir a tu ficha y mostrar la obra.</li>
                <li>En ARTid → GitHub settings → <strong>«Short URL»</strong>, escribe tu dominio corto (ej. <code>tudominio.s.gy</code>) y guarda.</li>
            </ol>
            <p class="mt-3 text-sm text-gray-600">Así, el QR codifica <code>https://tudominio.s.gy?art=&lt;ID&gt;</code>, y short.io reenvía a tu ficha con el parámetro <code>?art=</code>.</p>
        </section>

        <!-- 7 -->
        <section id="obra" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">7. Crear tu primera obra</h2>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>Menú → <strong>«Artworks»</strong> → <strong>«New Artwork»</strong>.</li>
                <li>Completa: título, año, edición (1/x), serie, técnicas y descripción.</li>
                <li>Sube la <strong>imagen</strong> de la obra.</li>
                <li>El <strong>Artwork ID</strong> se genera del título (o escríbelo tú, en mayúsculas y guiones).</li>
                <li>Al guardar, ARTid commitea <code>artworks/&lt;ID&gt;/metadata.json</code> + la imagen a tu repositorio.</li>
            </ol>
        </section>

        <!-- 8 -->
        <section id="qr" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">8. Generar el QR</h2>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>En la lista de obras verás el <strong>QR</strong> de cada una.</li>
                <li>Haz clic sobre el QR para abrirlo en tamaño completo.</li>
                <li>Descárgalo (SVG, vectorial) e imprímelo en la obra física.</li>
                <li>El QR codifica <code>https://tudominio.s.gy?art=&lt;ID&gt;</code>.</li>
            </ol>
        </section>

        <!-- 9 -->
        <section id="historial" class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="font-semibold text-xl">9. Historial de la obra</h2>
            <ol class="mt-3 text-sm space-y-2 list-decimal list-inside">
                <li>En la lista de obras, clic en el <strong>título</strong> para abrir el historial.</li>
                <li><strong>+ Expo</strong>: agrega exposiciones (nombre, fecha, descripción, links).</li>
                <li><strong>+ Propiedad</strong>: registra la propiedad. En una venta se genera una <strong>llave secreta</strong> para revelar al nuevo propietario.</li>
                <li>El historial se muestra en la ficha pública (exposiciones + proveniencia).</li>
            </ol>
        </section>

        <p class="mt-8 text-sm text-gray-500">{{ __('¿Dudas? Revisa tu configuración en GitHub settings o contacta con soporte.') }}</p>
    </div>
</body>
</html>
