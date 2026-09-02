@extends('layouts.public')
@section('title', __('Aspectos legales') . ' — QRTE')
@section('content')
<div class="py-12">
    <div class="max-w-[75rem] mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ __('Aspectos legales') }}</h1>
        <p class="mt-2 text-sm text-gray-600">{{ __('Términos y políticas que rigen el uso de QRTE. Selecciona una pestaña para consultar cada documento.') }}</p>

        <div x-data="{ tab: '{{ request('tab', 'datos') }}' }" class="mt-8">
            {{-- Tabs --}}
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex gap-6 overflow-x-auto" aria-label="Tabs">
                    <button @click="tab = 'datos'"
                        :class="tab === 'datos' ? 'border-brand text-brand' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition">
                        {{ __('Tratamiento de datos') }}
                    </button>
                    <button @click="tab = 'terminos'"
                        :class="tab === 'terminos' ? 'border-brand text-brand' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition">
                        {{ __('Términos y condiciones') }}
                    </button>
                    <button @click="tab = 'cookies'"
                        :class="tab === 'cookies' ? 'border-brand text-brand' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition">
                        {{ __('Política de cookies') }}
                    </button>
                </nav>
            </div>

            {{-- Panel: Tratamiento de datos --}}
            <div x-show="tab === 'datos'" x-cloak class="mt-6 bg-white shadow-sm sm:rounded-lg p-6 sm:p-8">
                <p class="text-xs text-gray-400">{{ __('Última actualización: :date', ['date' => '01 de septiembre de 2026']) }}</p>
                <h2 class="mt-2 text-xl font-semibold text-gray-900">{{ __('Política de Tratamiento de Datos Personales') }}</h2>
                <p class="text-sm text-gray-500">{{ __('Ley 1581 de 2012, Decreto 1377 de 2013 y normas complementarias — República de Colombia') }}</p>

                <div class="mt-6 prose prose-sm max-w-none text-gray-700 space-y-6">
                    <section>
                        <h3 class="font-semibold text-gray-900">1. {{ __('Responsable del tratamiento') }}</h3>
                        <p class="mt-1">{{ __('QRTE, proyecto de POORdesigner.com. Correo de contacto para temas de datos personales:') }} <a href="mailto:qrte@poordesigner.com" class="text-brand hover:underline">qrte@poordesigner.com</a>. {{ __('Sitio web:') }} https://artid.poordesigner.com — https://qrte.poordesigner.com.</p>
                        <p class="mt-1">{{ __('Para efectos de la Ley 1581 de 2012, QRTE actúa como Responsable del tratamiento. Cuando usamos proveedores (encargados) — por ejemplo Paddle (pagos), R2/Cloudflare (almacenamiento), proveedor de correo y hosting — lo hacemos bajo acuerdos que les obligan a tratar los datos solo según nuestras instrucciones.') }}</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">2. {{ __('Datos que tratamos') }}</h3>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>{{ __('Identificación y contacto del artista: nombre, correo electrónico, foto de perfil, enlaces y redes que decidas publicar.') }}</li>
                            <li>{{ __('Contenido que subes: obras, imágenes, descripciones, series, exposiciones, historial de propiedad, enlaces y archivos adjuntos de tickets de soporte.') }}</li>
                            <li>{{ __('Datos de uso y técnicos: fecha de creación de cuenta, último acceso, saldo y movimientos de tokens, logs, dirección IP, cookies y preferencias de idioma.') }}</li>
                            <li>{{ __('Datos de pago: QRTE no almacena datos de tarjeta. Los pagos los procesa Paddle Billing; nosotros solo guardamos el resultado (paquete comprado, importe, estado y tokens acreditados).') }}</li>
                            <li>{{ __('Comunicaciones de soporte: tickets, respuestas y adjuntos que nos envías.') }}</li>
                        </ul>
                        <p class="mt-2 text-sm text-gray-600">{{ __('No solicitamos datos sensibles (salud, biometría, orientación, etc.) ni datos de menores. Si nos los envías voluntariamente en una descripción o adjunto, los trataremos solo para atender tu solicitud y los podrás pedir suprimir.') }}</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">3. {{ __('Finalidades') }}</h3>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>{{ __('Crear y administrar tu cuenta de artista, autenticarte (incluido Google OAuth) y mantener tu sesión.') }}</li>
                            <li>{{ __('Prestar el servicio: generar QR firmado, ficha pública verificada, perfil público, exposiciones, proveniencia cifrada y enlaces.') }}</li>
                            <li>{{ __('Gestionar tokens: acreditar bienvenida y compras, descontar al crear obra y mostrar historial.') }}</li>
                            <li>{{ __('Procesar pagos únicos de paquetes de tokens a través de Paddle y prevenir fraude.') }}</li>
                            <li>{{ __('Atender soporte (Chatwoot y tickets), enviar respuestas por correo y mejorar la calidad del servicio.') }}</li>
                            <li>{{ __('Enviar comunicaciones operativas (verificación de correo, confirmación de compra, respuesta a tickets, avisos de seguridad) y, si lo autorizas, novedades del servicio.') }}</li>
                            <li>{{ __('Cumplir obligaciones legales, atender requerimientos de autoridades y defender derechos en reclamaciones.') }}</li>
                            <li>{{ __('Analítica agregada y seguridad: medir uso, detectar abusos y mantener la integridad de los QR firmados.') }}</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">4. {{ __('Base legal y autorización') }}</h3>
                        <p class="mt-1">{{ __('Tratamos tus datos con base en tu autorización previa, expresa e informada, que nos otorgas al registrarte, al marcar la casilla de aceptación o al usar el servicio; además del cumplimiento contractual (prestarte QRTE) y el cumplimiento legal cuando aplique. Puedes revocar la autorización salvo que exista un deber legal o contractual que lo impida; la revocatoria no afecta tratamientos ya realizados.') }}</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">5. {{ __('Derechos del titular') }}</h3>
                        <p class="mt-1">{{ __('Conforme a la Ley 1581 y el Decreto 1377, tienes derecho a:') }}</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>{{ __('Conocer, actualizar y rectificar tus datos.') }}</li>
                            <li>{{ __('Solicitar prueba de la autorización otorgada.') }}</li>
                            <li>{{ __('Ser informado del uso dado a tus datos, previa solicitud.') }}</li>
                            <li>{{ __('Revocar la autorización y/o pedir la supresión cuando no se respeten los principios legales o no exista deber de conservarlos.') }}</li>
                            <li>{{ __('Presentar quejas ante la Superintendencia de Industria y Comercio por infracciones a la ley.') }}</li>
                            <li>{{ __('Acceder gratuitamente a tus datos tratados.') }}</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">6. {{ __('Deberes de QRTE como Responsable') }}</h3>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>{{ __('Solicitar y conservar copia de la autorización cuando se requiera.') }}</li>
                            <li>{{ __('Informar la finalidad y los derechos que te asisten.') }}</li>
                            <li>{{ __('Garantizar que la información sea veraz, completa, exacta, actualizada y comprensible.') }}</li>
                            <li>{{ __('Adoptar medidas técnicas y administrativas para proteger los datos contra pérdida, uso indebido o acceso no autorizado.') }}</li>
                            <li>{{ __('Tramitar consultas y reclamos en los plazos de ley e informar a Encargados cualquier novedad sobre los datos.') }}</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">7. {{ __('Procedimiento para consultas y reclamos') }}</h3>
                        <p class="mt-1">{{ __('Escríbenos a') }} <a href="mailto:qrte@poordesigner.com" class="text-brand hover:underline">qrte@poordesigner.com</a> {{ __('con el asunto "Datos personales" e indica tu nombre, correo registrado y el derecho que deseas ejercer (consulta, actualización, rectificación o supresión) con el detalle.') }}</p>
                        <ul class="mt-2 list-disc list-inside space-y-1">
                            <li><strong>{{ __('Consultas:') }}</strong> {{ __('serán atendidas en un máximo de 10 días hábiles. Si no es posible, te informaremos el motivo y la nueva fecha (máximo 5 días hábiles adicionales).') }}</li>
                            <li><strong>{{ __('Reclamos:') }}</strong> {{ __('si consideras que tus datos deben corregirse o suprimirse, presenta el reclamo con los hechos, tu identificación y documentos de soporte. Si está incompleto, en 5 días hábiles te pediremos completarlo; si no lo completas en 2 meses, entenderemos que desistes. Una vez completo, lo marcaremos como "reclamo en trámite" y lo resolveremos en máximo 15 días hábiles (prorrogables 8 días más con aviso).') }}</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">8. {{ __('Transferencias y transmisiones') }}</h3>
                        <p class="mt-1">{{ __('No vendemos tus datos. Solo los compartimos con encargados necesarios para operar QRTE (pagos con Paddle, almacenamiento R2/Cloudflare, envío de correos, hosting/VPS y Chatwoot para soporte) y, si la ley lo exige, con autoridades competentes. Cuando haya transferencia internacional (datos alojados fuera de Colombia), se hace bajo estándares de seguridad y confidencialidad exigidos por la Ley 1581.') }}</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">9. {{ __('Conservación y seguridad') }}</h3>
                        <p class="mt-1">{{ __('Conservamos tus datos mientras mantengas cuenta activa o sea necesario para prestar el servicio, cumplir la ley y atender reclamaciones. Al eliminar tu cuenta, suprimimos o anonimicemos los datos que no debamos conservar por obligación legal. Aplicamos cifrado en tránsito (HTTPS), control de acceso por roles (artista/admin), firma HMAC en QR y almacenamiento cifrado para proveniencia.') }}</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">10. {{ __('Datos de menores') }}</h3>
                        <p class="mt-1">{{ __('QRTE no está dirigido a menores de 18 años. Si detectamos datos de un menor sin autorización de representante legal, los suprimiremos.') }}</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">11. {{ __('Canales y vigencia') }}</h3>
                        <p class="mt-1">{{ __('Canal único para derechos de habeas data:') }} <a href="mailto:qrte@poordesigner.com" class="text-brand hover:underline">qrte@poordesigner.com</a>. {{ __('Esta política rige desde su publicación y se actualiza cuando cambien finalidades, encargados o normativa. Publicaremos la nueva versión en esta página con su fecha y, si el cambio es sustancial, te avisaremos por correo antes de que entre en vigor.') }}</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">12. {{ __('Autorización') }}</h3>
                        <p class="mt-1">{{ __('Al registrarte y usar QRTE declaras que conoces esta Política y autorizas el tratamiento de tus datos en los términos aquí descritos, incluyendo el tratamiento por encargados para las finalidades indicadas.') }}</p>
                    </section>

                    <p class="pt-4 text-sm text-gray-500 border-t">{{ __('¿Dudas sobre esta política? Escríbenos a') }} <a href="mailto:qrte@poordesigner.com" class="text-brand hover:underline">qrte@poordesigner.com</a>.</p>
                </div>
            </div>

            {{-- Panel: Términos y condiciones (placeholder) --}}
            <div x-show="tab === 'terminos'" x-cloak class="mt-6 bg-white shadow-sm sm:rounded-lg p-6 sm:p-8">
                <h2 class="text-xl font-semibold text-gray-900">{{ __('Términos y condiciones') }}</h2>
                <p class="mt-2 text-sm text-gray-600">{{ __('Estamos preparando este documento. Mientras tanto, el uso de QRTE se rige por la') }} {{ __('Política de Tratamiento de Datos') }} {{ __('y las condiciones informadas en el proceso de compra de tokens (precio, 1 token = 1 obra, pago único vía Paddle).') }}</p>
                <p class="mt-2 text-sm text-gray-600">{{ __('Si necesitas una cláusula específica para un contrato o exposición, escríbenos a') }} <a href="mailto:qrte@poordesigner.com" class="text-brand hover:underline">qrte@poordesigner.com</a>.</p>
            </div>

            {{-- Panel: Cookies --}}
            <div x-show="tab === 'cookies'" x-cloak class="mt-6 bg-white shadow-sm sm:rounded-lg p-6 sm:p-8">
                <h2 class="text-xl font-semibold text-gray-900">{{ __('Política de cookies') }}</h2>
                <p class="mt-2 text-sm text-gray-600">{{ __('QRTE usa cookies esenciales para autenticación, preferencias de idioma (:locale) y estado de pago pendiente. No usamos cookies de publicidad de terceros. Puedes bloquear cookies desde tu navegador, pero algunas funciones (login, idioma) pueden dejar de funcionar.') }}</p>
                <p class="mt-2 text-sm text-gray-600">{{ __('El widget de Chatwoot puede usar cookies propias para mantener la conversación de soporte. Consulta su política en') }} <a href="https://www.chatwoot.com/privacy-policy" target="_blank" rel="noopener" class="text-brand hover:underline">chatwoot.com/privacy-policy</a>.</p>
            </div>
        </div>
    </div>
</div>
@endsection