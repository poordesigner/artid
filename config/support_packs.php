<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Soporte automático (Chatwoot + n8n) - paquetes de contexto
    |--------------------------------------------------------------------------
    |
    | Cada "pack" es el conocimiento base que el asistente usa para responder.
    | Los packs estáticos viven acá; `facturacion` se completa en tiempo real
    | con TokenPackage / TokenFunction / welcome tokens desde la DB.
    |
    | El endpoint GET /api/support/context?topic={key} devuelve {topic, content}.
    |
    */

    'brand' => 'QRTE',

    'tll_seconds' => 300,

    'default_topic' => 'introduccion',

    'packs' => [

        'introduccion' => [
            'Eres el asistente de soporte de QRTE, de POORdesigner.com.',
            'Este chat es de soporte sobre la plataforma QRTE.',
            'Saluda de forma breve, amable y ofrece ayuda.',
            'Temas de soporte: conocer QRTE, cuenta, obras, QR/ficha, historial (exposiciones y propiedad), enlaces, facturacion/tokens y configuracion.',
            'Si la consulta no pertenece a QRTE, responde solo con el protocolo @@CONTEXTO@@ (busca el tema correcto u otros).',
        ],

        'conocer' => [
            'QRTE es una plataforma de identidad digital para obras de arte fisicas.',
            'El artista registra sus obras y cada una obtiene un QR permanente firmado que lleva a una ficha publica verificada.',
            'Incluye: ficha tecnica con metadata, historial (exposiciones y proveniencia), control de propiedad cifrado y perfil publico de artista.',
            'El QR se imprime sobre la obra; quien lo escanea ve la ficha con sello de verificacion.',
            'Al registrarse se reciben tokens de bienvenida sin tarjeta.',
            'Flujo de primer uso: registrar obra -> consume 1 token -> QR + ficha basica para siempre.',
            'Para profundizar en un tema usa el protocolo @@CONTEXTO@@ con: cuenta, obras, qr-ficha, historial, enlaces, facturacion o configuracion.',
        ],

        'cuenta' => [
            'Registro e ingreso con Google o email y contrasena.',
            'Verificacion de email: reenviar el link desde Configuracion.',
            'Perfil de artista: foto, nombre, email, declaracion (statement), hoja de vida en PDF, pagina web, redes sociales (Instagram, Behance, ArtStation, YouTube, TikTok) y hasta 5 enlaces de perfil (portafolio, CV, exposiciones).',
            'Lo del perfil se muestra en tu perfil publico de artista.',
            'Cambiar contrasena: Configuracion > Seguridad.',
            'Eliminar cuenta: escribir la palabra de confirmacion; la accion no se puede deshacer.',
        ],

        'obras' => [
            'Crear una obra consume 1 token (se requiere saldo mayor a cero).',
            'Campos de la obra: titulo, anio, edicion (pieza unica, tiraje o P/A con numero de copias), serie, tecnicas (una o varias), dimensiones (alto x ancho x profundidad y unidad), descripcion (max 500) e imagen (JPG/PNG, max 2 MB, se optimiza).',
            'Artwork ID opcional: solo mayusculas, guiones y puntos; si se deja vacio se genera del titulo. Es permanente y unico.',
            'Estados: Activa y Inactiva (archivada); el listado tiene filtros y orden.',
            'Las series agrupan obras y se asignan desde el formulario.',
            'Al guardar, se consume 1 token y desde la obra se accede al QR y a la ficha.',
        ],

        'qr-ficha' => [
            'QR permanente: nunca cambia; codifica una URL firmada que apunta a la ficha publica.',
            'Descarga: clic en el QR de la obra (formato SVG vectorial imprimible).',
            'Ficha publica: de solo lectura; muestra imagen, titulo, artista, metadata, historial y sello de verificacion. Sin firma valida no se muestra.',
            'Editar la obra no cambia el QR ni la ficha; ambos son estables.',
        ],

        'historial' => [
            'Exposiciones: desde la obra, opcion + Expo. Campos: nombre, fechas de inicio y fin, ubicacion (ciudad y pais) y descripcion. Aparecen en la ficha publica.',
            'Propiedad / proveniencia: desde la obra, opcion + Propiedad.',
            'Primer propietario (Initial owner): datos visibles en la obra.',
            'Transferencia / venta (Transfer / Sale): datos del nuevo dueño (pueden ser anonimos); QRTE genera una llave secreta que se muestra una sola vez.',
            'Solo quien tiene la llave secreta puede revelar al dueno (datos cifrados). Entregar la llave junto con la obra.',
        ],

        'enlaces' => [
            'Enlaces externos por obra: hasta 10 enlaces de tipo video, foto o blog; aparecen en la ficha publica.',
            'Enlaces de perfil: hasta 5 enlaces de portafolio, CV o exposiciones; aparecen en el perfil publico del artista.',
        ],

        'facturacion' => [
            'Modelo de tokens: 1 token = QR + ficha basica de una obra, para siempre; pago unico, sin suscripcion.',
            'Welcome tokens al registrarse: {welcome_tokens}.',
            'Paquetes de tokens activos:',
            '{packages}',
            'Compra: Panel > Mis tokens > Comprar tokens; checkout de pago unico y acreditacion automatica.',
            'Saldo e historial de tokens (compras, otorgados y consumos) se ven en Mis tokens.',
            'Usos de tokens (funciones):',
            '{functions}',
            'Si un pago tardio no se acredita o hay un error, revisar Mis tokens y derivar a un agente humano.',
        ],

        'configuracion' => [
            'Configuracion: cambia email y contrasena, y revisa tu saldo de tokens.',
            'Idioma: selector ES/EN disponible en la pagina.',
            'Eliminar cuenta: requiere escribir la palabra de confirmacion y la accion no se puede deshacer.',
        ],

        'otros' => [
            'Tema fuera del alcance de QRTE.',
            'Responde de forma amable y breve, declinando sin inventar contenido ni responder la consulta.',
            'Si el usuario insiste o pide asistencia humana, invítalo a escribir a qart@poordesigner.com con su solicitud.',
            'Termina SIEMPRE tu respuesta con esta linea exacta, sin nada despues: @@OOC@@',
        ],
    ],
];