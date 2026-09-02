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
                    <button @click="tab = 'turnstile'"
                        :class="tab === 'turnstile' ? 'border-brand text-brand' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition">
                        Turnstile
                    </button>
                </nav>
            </div>

            {{-- Panel: Tratamiento de datos --}}
            <div x-show="tab === 'datos'" x-cloak class="mt-6 bg-white shadow-sm sm:rounded-lg p-6 sm:p-8">
                <p class="text-xs text-gray-400">Última actualización: 02 de septiembre de 2026</p>
                <h2 class="mt-2 text-xl font-semibold text-gray-900">Política de Tratamiento de Datos Personales — QRTE (POORdesigner.com)</h2>
                <p class="text-sm text-gray-500">Ley 1581 de 2012, Decreto 1377 de 2013 y demás normas aplicables — República de Colombia</p>
                <p class="mt-3 text-sm text-gray-600">Esta Política de Tratamiento de Datos Personales describe cómo QRTE recopila, utiliza, almacena, protege y trata la información personal de sus usuarios, de conformidad con la Ley 1581 de 2012, el Decreto 1377 de 2013 y demás normas aplicables de la República de Colombia.</p>

                <div class="mt-6 prose prose-sm max-w-none text-gray-700 space-y-6">
                    <section>
                        <h3 class="font-semibold text-gray-900">1. Responsable del tratamiento</h3>
                        <p class="mt-1">QRTE es una plataforma operada por POORdesigner.com, quien actúa como Responsable del Tratamiento de los Datos Personales para las finalidades descritas en esta Política.</p>
                        <p class="mt-2 font-medium text-gray-900">Contacto para asuntos relacionados con protección de datos:</p>
                        <p>Correo electrónico: <a href="mailto:qrte@poordesigner.com" class="text-brand hover:underline">qrte@poordesigner.com</a></p>
                        <p class="mt-1">Sitios web:</p>
                        <ul class="list-disc list-inside">
                            <li>https://artid.poordesigner.com</li>
                            <li>https://qrte.poordesigner.com</li>
                        </ul>
                        <p class="mt-2">QRTE podrá apoyarse en proveedores tecnológicos nacionales o internacionales que actúen como Encargados del Tratamiento, quienes tratarán la información únicamente para la prestación de servicios relacionados con la operación de la plataforma.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">2. Datos personales que tratamos</h3>
                        <p class="mt-1">Dependiendo del uso de la plataforma, podremos tratar las siguientes categorías de información:</p>
                        <p class="mt-3 font-medium">2.1 Datos de identificación y contacto</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Nombre y apellidos.</li>
                            <li>Dirección de correo electrónico.</li>
                            <li>Imagen de perfil.</li>
                            <li>Enlaces públicos y redes sociales.</li>
                            <li>Información de contacto que el usuario decida proporcionar.</li>
                        </ul>
                        <p class="mt-3 font-medium">2.2 Datos de autenticación y seguridad</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Identificadores de autenticación.</li>
                            <li>Registros de inicio de sesión.</li>
                            <li>Configuración de seguridad.</li>
                            <li>Proveedores externos de autenticación (como Google OAuth).</li>
                            <li>Tokens de sesión.</li>
                            <li>Registros asociados a la protección de la cuenta.</li>
                        </ul>
                        <p class="mt-3 font-medium">2.3 Contenido generado por el usuario</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Obras registradas.</li>
                            <li>Imágenes.</li>
                            <li>Descripciones.</li>
                            <li>Series.</li>
                            <li>Exposiciones.</li>
                            <li>Historiales de proveniencia.</li>
                            <li>Enlaces.</li>
                            <li>Documentos y archivos adjuntos.</li>
                            <li>Información incorporada voluntariamente por el usuario.</li>
                        </ul>
                        <p class="mt-3 font-medium">2.4 Datos técnicos y de uso</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Dirección IP.</li>
                            <li>Fecha de registro.</li>
                            <li>Último acceso.</li>
                            <li>Tipo de navegador.</li>
                            <li>Idioma.</li>
                            <li>Configuración regional.</li>
                            <li>Registros de actividad.</li>
                            <li>Historial de uso de tokens.</li>
                            <li>Cookies y tecnologías similares.</li>
                        </ul>
                        <p class="mt-3 font-medium">2.5 Datos de pagos</p>
                        <p>QRTE no almacena información de tarjetas de crédito ni medios de pago. Los pagos son procesados por proveedores especializados como Paddle u otros autorizados. QRTE únicamente conserva información relacionada con:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Identificador de transacción.</li>
                            <li>Estado del pago.</li>
                            <li>Producto adquirido.</li>
                            <li>Cantidad de tokens acreditados.</li>
                            <li>Información administrativa necesaria para la prestación del servicio.</li>
                        </ul>
                        <p class="mt-3 font-medium">2.6 Comunicaciones de soporte</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Conversaciones de soporte.</li>
                            <li>Tickets.</li>
                            <li>Correos electrónicos.</li>
                            <li>Adjuntos enviados por el usuario.</li>
                            <li>Interacciones con sistemas automatizados de atención.</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">3. Información pública</h3>
                        <p class="mt-1">Algunas funcionalidades de QRTE están diseñadas para generar contenido público. Dependiendo de la configuración elegida por el usuario, la siguiente información podrá ser visible para terceros:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Fichas de obras.</li>
                            <li>Perfiles de artista.</li>
                            <li>Imágenes publicadas.</li>
                            <li>Enlaces públicos.</li>
                            <li>Información asociada a códigos QR.</li>
                        </ul>
                        <p class="mt-2">El usuario es responsable de determinar qué información desea publicar y entiende que cualquier información marcada como pública podrá ser visualizada por personas que accedan al enlace correspondiente o escaneen el código QR asociado.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">4. Datos sensibles y menores de edad</h3>
                        <p class="mt-1">QRTE no solicita ni requiere datos sensibles tales como:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Información médica.</li>
                            <li>Datos biométricos.</li>
                            <li>Información sobre orientación sexual.</li>
                            <li>Convicciones religiosas.</li>
                            <li>Afiliación política.</li>
                            <li>Datos de menores de edad.</li>
                        </ul>
                        <p class="mt-2">Si dichos datos son suministrados voluntariamente por el usuario, serán tratados exclusivamente para la finalidad relacionada con la solicitud correspondiente y podrán ser eliminados a petición del titular.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">5. Finalidades del tratamiento</h3>
                        <p class="mt-1">Los datos personales podrán ser tratados para:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Crear y gestionar cuentas de usuario.</li>
                            <li>Verificar identidad y acceso.</li>
                            <li>Prestar los servicios ofrecidos por QRTE.</li>
                            <li>Generar identificadores y códigos QR.</li>
                            <li>Mantener fichas públicas y perfiles de artista.</li>
                            <li>Gestionar historiales de obras y proveniencia.</li>
                            <li>Administrar tokens y transacciones.</li>
                            <li>Procesar pagos mediante terceros autorizados.</li>
                            <li>Prevenir fraude y accesos no autorizados.</li>
                            <li>Atender solicitudes de soporte.</li>
                            <li>Gestionar comunicaciones operativas.</li>
                            <li>Mejorar la experiencia de uso.</li>
                            <li>Realizar análisis estadísticos agregados.</li>
                            <li>Cumplir obligaciones legales.</li>
                            <li>Gestionar reclamaciones y procesos administrativos.</li>
                            <li>Mantener la seguridad e integridad de la plataforma.</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">6. Automatización, inteligencia artificial y sistemas de soporte</h3>
                        <p class="mt-1">QRTE podrá utilizar herramientas automatizadas, sistemas de inteligencia artificial o mecanismos de procesamiento asistido para:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Atención inicial de consultas.</li>
                            <li>Clasificación de solicitudes.</li>
                            <li>Recuperación de información de ayuda.</li>
                            <li>Generación de respuestas sugeridas.</li>
                            <li>Detección de fraude.</li>
                            <li>Análisis de incidencias.</li>
                            <li>Mejora continua del servicio.</li>
                        </ul>
                        <p class="mt-2">Estas herramientas se utilizan como apoyo operativo y no sustituyen necesariamente la supervisión humana cuando resulte apropiado.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">7. Base legal para el tratamiento</h3>
                        <p class="mt-1">El tratamiento de datos se fundamenta en:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>La autorización previa, expresa e informada del titular.</li>
                            <li>La ejecución de la relación contractual derivada del uso del servicio.</li>
                            <li>El cumplimiento de obligaciones legales.</li>
                            <li>La protección de intereses legítimos relacionados con la seguridad, estabilidad y operación de la plataforma.</li>
                        </ul>
                        <p class="mt-2">La autorización podrá otorgarse mediante:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Formularios electrónicos.</li>
                            <li>Casillas de aceptación.</li>
                            <li>Registro de cuenta.</li>
                            <li>Acciones inequívocas que evidencien consentimiento.</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">8. Derechos de los titulares</h3>
                        <p class="mt-1">Los titulares de los datos tienen derecho a:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Conocer sus datos personales.</li>
                            <li>Actualizar sus datos.</li>
                            <li>Rectificar información incorrecta.</li>
                            <li>Solicitar prueba de la autorización otorgada.</li>
                            <li>Conocer el uso dado a sus datos.</li>
                            <li>Solicitar la supresión cuando proceda legalmente.</li>
                            <li>Revocar la autorización cuando sea aplicable.</li>
                            <li>Acceder gratuitamente a su información.</li>
                            <li>Presentar consultas o reclamos.</li>
                            <li>Acudir ante la Superintendencia de Industria y Comercio cuando consideren vulnerados sus derechos.</li>
                            <li>Solicitar una copia razonable y técnicamente disponible de la información proporcionada a la plataforma.</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">9. Deberes de QRTE</h3>
                        <p class="mt-1">QRTE se compromete a:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Garantizar la confidencialidad de la información.</li>
                            <li>Informar adecuadamente las finalidades del tratamiento.</li>
                            <li>Adoptar medidas razonables de seguridad.</li>
                            <li>Tratar los datos conforme a la ley.</li>
                            <li>Tramitar consultas y reclamos oportunamente.</li>
                            <li>Mantener información veraz y actualizada cuando ello dependa de QRTE.</li>
                            <li>Exigir a sus proveedores niveles adecuados de protección y seguridad.</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">10. Procedimiento para consultas y reclamos</h3>
                        <p class="mt-1">Las solicitudes relacionadas con protección de datos podrán enviarse a: <a href="mailto:qrte@poordesigner.com" class="text-brand hover:underline">qrte@poordesigner.com</a> indicando:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Nombre completo.</li>
                            <li>Correo registrado.</li>
                            <li>Derecho que desea ejercer.</li>
                            <li>Descripción de la solicitud.</li>
                        </ul>
                        <p class="mt-2"><strong>Consultas</strong> serán atendidas dentro de los plazos previstos por la legislación colombiana.</p>
                        <p class="mt-1"><strong>Reclamos</strong> serán gestionados conforme a los términos establecidos en la Ley 1581 de 2012 y demás normas aplicables.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">11. Transferencias y transmisiones nacionales e internacionales</h3>
                        <p class="mt-1">QRTE no vende ni comercializa datos personales. La información podrá ser compartida únicamente con proveedores y aliados necesarios para la prestación del servicio, incluyendo servicios de:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Procesamiento de pagos.</li>
                            <li>Almacenamiento.</li>
                            <li>Infraestructura tecnológica.</li>
                            <li>Correos electrónicos.</li>
                            <li>Autenticación.</li>
                            <li>Analítica.</li>
                            <li>Soporte al cliente.</li>
                            <li>Seguridad.</li>
                        </ul>
                        <p class="mt-2">Algunos de estos proveedores podrán encontrarse fuera de Colombia. Al utilizar QRTE, el usuario reconoce que determinadas transferencias internacionales pueden resultar necesarias para la adecuada prestación del servicio.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">12. Conservación de la información</h3>
                        <p class="mt-1">Los datos serán conservados:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Mientras exista una cuenta activa.</li>
                            <li>Mientras sea necesario para prestar el servicio.</li>
                            <li>Mientras exista una obligación legal de conservación.</li>
                            <li>Mientras puedan surgir reclamaciones relacionadas con la relación contractual.</li>
                        </ul>
                        <p class="mt-2">Posteriormente podrán ser eliminados, anonimizados o bloqueados según corresponda.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">13. Seguridad de la información</h3>
                        <p class="mt-1">QRTE aplica medidas técnicas, organizativas y administrativas razonables destinadas a proteger la información contra:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Acceso no autorizado.</li>
                            <li>Pérdida.</li>
                            <li>Alteración.</li>
                            <li>Divulgación.</li>
                            <li>Uso indebido.</li>
                        </ul>
                        <p class="mt-2">Ningún sistema es absolutamente seguro, por lo que no es posible garantizar una seguridad perfecta o absoluta.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">14. Incidentes de seguridad</h3>
                        <p class="mt-1">En caso de incidentes de seguridad que puedan afectar significativamente la información personal tratada, QRTE adoptará medidas razonables de investigación, mitigación y notificación conforme a la normativa aplicable.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">15. Responsabilidad sobre la información publicada</h3>
                        <p class="mt-1">El usuario es responsable de asegurarse de que la información incorporada en:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Obras.</li>
                            <li>Perfiles.</li>
                            <li>Historiales.</li>
                            <li>Certificados.</li>
                            <li>Descripciones.</li>
                            <li>Documentos adjuntos.</li>
                        </ul>
                        <p class="mt-2">cumple con la legislación aplicable y no vulnera derechos de terceros. QRTE no garantiza la exactitud, autenticidad, legalidad o legitimidad de la información suministrada por los usuarios.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">16. Menores de edad</h3>
                        <p class="mt-1">QRTE no está dirigido a menores de 18 años. Si se detecta la recopilación de datos de menores sin las autorizaciones legalmente requeridas, dichos datos podrán ser eliminados o bloqueados. Los representantes legales podrán solicitar su revisión o eliminación.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">17. Cookies y tecnologías similares</h3>
                        <p class="mt-1">QRTE utiliza cookies y tecnologías similares para:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Mantener sesiones activas.</li>
                            <li>Mejorar la seguridad.</li>
                            <li>Recordar preferencias.</li>
                            <li>Analizar el uso de la plataforma.</li>
                            <li>Optimizar la experiencia del usuario.</li>
                        </ul>
                        <p class="mt-2">La desactivación de determinadas cookies podrá afectar algunas funcionalidades del servicio.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">18. Vigencia y modificaciones</h3>
                        <p class="mt-1">La presente Política entra en vigor desde su publicación. QRTE podrá modificarla para adaptarse a cambios legales, regulatorios, tecnológicos u operativos. Las actualizaciones serán publicadas en la plataforma indicando su fecha de entrada en vigor y, cuando el cambio sea sustancial, se procurará informar a los usuarios mediante medios razonables.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">19. Autorización</h3>
                        <p class="mt-1">Al registrarte, acceder o utilizar QRTE, declaras que has leído y comprendido esta Política y autorizas el tratamiento de tus datos personales según las finalidades aquí descritas.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">20. Contacto</h3>
                        <p class="mt-1">QRTE / POORdesigner.com<br>https://artid.poordesigner.com<br>https://qrte.poordesigner.com<br><a href="mailto:qrte@poordesigner.com" class="text-brand hover:underline">qrte@poordesigner.com</a></p>
                    </section>
                </div>
            </div>

            {{-- Panel: Términos y condiciones --}}
            <div x-show="tab === 'terminos'" x-cloak class="mt-6 bg-white shadow-sm sm:rounded-lg p-6 sm:p-8">
                <p class="text-xs text-gray-400">{{ __('Última actualización: :date', ['date' => '02 de septiembre de 2026']) }}</p>
                <h2 class="mt-2 text-xl font-semibold text-gray-900">{{ __('Términos y Condiciones de Uso — QRTE (POORdesigner.com)') }}</h2>

                <div class="mt-6 prose prose-sm max-w-none text-gray-700 space-y-6">
                    <section>
                        <h3 class="font-semibold text-gray-900">1. {{ __('Aceptación') }}</h3>
                        <p class="mt-1">{{ __('Al crear cuenta, acceder o usar QRTE aceptas estos Términos y la Política de Tratamiento de Datos (Ley 1581). Si no estás de acuerdo, no uses el servicio. El uso del checkout de Paddle implica también aceptar sus términos.') }}</p>
                    </section>
                    <section>
                        <h3 class="font-semibold text-gray-900">2. {{ __('Descripción del servicio') }}</h3>
                        <p class="mt-1">{{ __('QRTE es un SaaS de Identidad Digital para obras físicas: ficha técnica, QR permanente firmado (HMAC-SHA256), perfil público, historial de exposiciones, proveniencia cifrada y enlaces. Cada obra consume 1 token = QR + ficha básica para siempre. No hay suscripción. Los planes por suscripción en el código son legado y no están a la venta.') }}</p>
                    </section>
                    <section>
                        <h3 class="font-semibold text-gray-900">3. {{ __('Cuenta') }}</h3>
                        <p class="mt-1">{{ __('Debes ser mayor de 18 años, dar datos veraces y custodiar tu contraseña/2FA. Eres responsable de lo que ocurra en tu cuenta. Podemos suspender cuentas por fraude, spam, suplantación o violación de estos Términos.') }}</p>
                    </section>
                    <section>
                        <h3 class="font-semibold text-gray-900">4. {{ __('Tokens y pagos') }}</h3>
                        <div class="mt-2 space-y-2">
                            <p><strong>4.1</strong> {{ __('Recibes ARTID_WELCOME_TOKENS (hoy 5) al primer registro, una sola vez.') }}</p>
                            <p><strong>4.2</strong> {{ __('Paquetes de tokens se pagan una sola vez vía Paddle Billing (merchant of record). Paddle cobra, factura y gestiona impuestos. Nosotros solo acreditamos tokens al recibir transaction.completed con custom_data.token_package_id.') }}</p>
                            <p><strong>4.3</strong> {{ __('Los tokens no expiran, no son transferibles ni canjeables por dinero. Salvo error de acreditación o exigencia legal, no hay reembolsos. Si Paddle reembolsa, descontamos los tokens no consumidos; si ya se consumieron, el saldo puede quedar negativo.') }}</p>
                            <p><strong>4.4</strong> {{ __('Crear obra descuenta 1 token de forma atómica. Sin saldo no puedes crear, pero sí editar/gestionar obras existentes. El admin puede otorgar tokens de cortesía (grant).') }}</p>
                        </div>
                    </section>
                    <section>
                        <h3 class="font-semibold text-gray-900">5. {{ __('Contenido del artista y licencias') }}</h3>
                        <div class="mt-2 space-y-2">
                            <p><strong>5.1</strong> {{ __('Sigues siendo dueño de tus obras, imágenes, textos y enlaces. Nos otorgas licencia mundial, no exclusiva y revocable (al borrar) para alojar, optimizar (WEBP ≤300KB en R2), mostrar en tu ficha/perfil público y generar el QR.') }}</p>
                            <p><strong>5.2</strong> {{ __('Declaras que tienes derechos sobre lo que subes y que no infringe propiedad intelectual, privacidad o ley. No subas contenido ilegal, difamatorio, que incite al odio o que vulnere derechos de terceros.') }}</p>
                            <p><strong>5.3</strong> {{ __('Ficha pública /o/{publicId}?s=... y perfil /artist/{id} son públicos por diseño: cualquiera con el QR o el link puede verlos. No subas datos sensibles que no quieras hacer públicos.') }}</p>
                        </div>
                    </section>
                    <section>
                        <h3 class="font-semibold text-gray-900">6. {{ __('QR y verificación') }}</h3>
                        <p class="mt-1">{{ __('El QR codifica una URL firmada versionada (s=v1.hmac). Verificamos firma en servidor; sin firma válida devolvemos 404. El artwork_id es solo display. Garantizamos que el QR no cambia aunque edites la obra, mientras mantengas la obra activa. No garantizamos que un QR impreso siga legible si se deteriora físicamente.') }}</p>
                    </section>
                    <section>
                        <h3 class="font-semibold text-gray-900">7. {{ __('Proveniencia y COA') }}</h3>
                        <p class="mt-1">{{ __('El historial de propiedad (initial/transfer + llave secreta) es una herramienta de trazabilidad, no un título legal de propiedad ni un certificado notarial. Las transferencias cifradas solo son legibles con la llave. Tú gestionas la entrega de la llave al nuevo poseedor.') }}</p>
                    </section>
                    <section>
                        <h3 class="font-semibold text-gray-900">8. {{ __('Uso aceptable') }}</h3>
                        <p class="mt-1">{{ __('Prohibido: scraping masivo, ingeniería inversa, eludir firmas/rate limits, suplantar, subir malware, o usar QRTE para engañar sobre autenticidad. Podemos limitar, ocultar o eliminar contenido que viole esto.') }}</p>
                    </section>
                    <section>
                        <h3 class="font-semibold text-gray-900">9. {{ __('Disponibilidad y soporte') }}</h3>
                        <p class="mt-1">{{ __('Servicio en la nube (VPS Coolify + R2 + Redis). Objetivo de disponibilidad best-effort, sin SLA contractual en esta etapa. Soporte vía widget Chatwoot y tickets /tickets (respuesta por email desde qrte@poordesigner.com). Las respuestas de IA son asistivas y no vinculantes.') }}</p>
                    </section>
                    <section>
                        <h3 class="font-semibold text-gray-900">10. {{ __('Privacidad') }}</h3>
                        <p class="mt-1">{{ __('El tratamiento de datos se rige por la pestaña Tratamiento de datos (Ley 1581) y, para Turnstile, por su addendum. Chatwoot y Paddle actúan como encargados/procesadores según el caso.') }}</p>
                    </section>
                    <section>
                        <h3 class="font-semibold text-gray-900">11. {{ __('Propiedad intelectual de QRTE') }}</h3>
                        <p class="mt-1">{{ __('Marca QRTE, logos, código, textos y diseño son de POORdesigner.com. No se otorga licencia de marca. No copies ni redistribuyas el software.') }}</p>
                    </section>
                    <section>
                        <h3 class="font-semibold text-gray-900">12. {{ __('Garantías y responsabilidad') }}</h3>
                        <p class="mt-1">{{ __('Servicio "tal cual" y "según disponibilidad". En la máxima medida permitida por la ley colombiana: (a) excluimos garantías implícitas de comerciabilidad/idoneidad; (b) nuestra responsabilidad agregada no excederá lo que pagaste en los 6 meses previos; (c) no respondemos por lucro cesante, pérdida de datos o daños indirectos. Nada limita responsabilidad por dolo o culpa grave.') }}</p>
                    </section>
                    <section>
                        <h3 class="font-semibold text-gray-900">13. {{ __('Indemnidad') }}</h3>
                        <p class="mt-1">{{ __('Nos indemnizarás por reclamaciones de terceros derivadas de tu contenido o uso indebido del servicio.') }}</p>
                    </section>
                    <section>
                        <h3 class="font-semibold text-gray-900">14. {{ __('Terminación') }}</h3>
                        <p class="mt-1">{{ __('Puedes eliminar tu cuenta desde Configuración (acción irreversible). Podemos suspender/terminar por incumplimiento. Al terminar, tu saldo de tokens no usados se pierde y tus fichas públicas pueden ocultarse; si la ley exige conservación, las retenemos de forma no pública.') }}</p>
                    </section>
                    <section>
                        <h3 class="font-semibold text-gray-900">15. {{ __('Cambios') }}</h3>
                        <p class="mt-1">{{ __('Podemos actualizar estos Términos. Publicaremos la nueva versión en /legal con fecha y, si el cambio es sustancial, avisaremos por email con al menos 15 días de antelación. El uso continuado después de la vigencia implica aceptación.') }}</p>
                    </section>
                    <section>
                        <h3 class="font-semibold text-gray-900">16. {{ __('Ley aplicable y jurisdicción') }}</h3>
                        <p class="mt-1">{{ __('Se rigen por las leyes de la República de Colombia. Cualquier controversia se somete a los jueces de Medellín, sin perjuicio de normas imperativas de protección al consumidor.') }}</p>
                    </section>
                    <section>
                        <h3 class="font-semibold text-gray-900">17. {{ __('Contacto') }}</h3>
                        <p class="mt-1"><a href="mailto:qrte@poordesigner.com" class="text-brand hover:underline">qrte@poordesigner.com</a> — POORdesigner.com / QRTE, https://artid.poordesigner.com</p>
                    </section>
                </div>
            </div>

            {{-- Panel: Cookies --}}
            <div x-show="tab === 'cookies'" x-cloak class="mt-6 bg-white shadow-sm sm:rounded-lg p-6 sm:p-8">
                <p class="text-xs text-gray-400">Última actualización: 02 de septiembre de 2026</p>
                <h2 class="mt-2 text-xl font-semibold text-gray-900">Política de Cookies</h2>
                <p class="mt-3 text-sm text-gray-600">QRTE utiliza cookies y tecnologías similares para garantizar el funcionamiento de la plataforma, mejorar la experiencia de usuario, mantener la seguridad de las sesiones y recordar determinadas preferencias de configuración. Al utilizar QRTE aceptas el uso de las cookies descritas en esta Política, en la medida permitida por la legislación aplicable.</p>

                <div class="mt-6 prose prose-sm max-w-none text-gray-700 space-y-6">
                    <section>
                        <h3 class="font-semibold text-gray-900">1. ¿Qué son las cookies?</h3>
                        <p class="mt-1">Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo cuando visitas un sitio web. Permiten reconocer tu navegador, recordar preferencias y facilitar determinadas funcionalidades.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">2. Cookies que utilizamos</h3>
                        <p class="mt-2 font-medium">Cookies esenciales</p>
                        <p class="mt-1">Son necesarias para el funcionamiento de la plataforma y no pueden desactivarse sin afectar funcionalidades básicas. Estas cookies pueden utilizarse para:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Mantener sesiones autenticadas.</li>
                            <li>Verificar la identidad del usuario.</li>
                            <li>Proteger el acceso a la cuenta.</li>
                            <li>Recordar configuraciones de idioma.</li>
                            <li>Gestionar procesos relacionados con autenticación y seguridad.</li>
                            <li>Mantener información temporal necesaria para determinadas operaciones dentro del servicio.</li>
                        </ul>
                        <p class="mt-3 font-medium">Cookies de preferencias</p>
                        <p>Permiten recordar configuraciones seleccionadas por el usuario, tales como:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Idioma.</li>
                            <li>Opciones de visualización.</li>
                            <li>Preferencias de navegación.</li>
                        </ul>
                        <p class="mt-3 font-medium">Cookies de soporte</p>
                        <p>El sistema de soporte podrá utilizar tecnologías propias o de terceros para mantener conversaciones activas, identificar solicitudes de soporte y mejorar la continuidad de la atención al usuario. Actualmente, el widget de soporte basado en Chatwoot puede utilizar cookies o tecnologías similares para mantener la conversación y asociar mensajes a una misma sesión.</p>
                        <p class="mt-2">Puedes consultar la información publicada por Chatwoot en: <a href="https://www.chatwoot.com/privacy" target="_blank" rel="noopener" class="text-brand hover:underline">chatwoot.com/privacy</a></p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">3. Cookies analíticas</h3>
                        <p class="mt-1">QRTE podrá incorporar herramientas de analítica que permitan comprender el uso general de la plataforma, identificar errores y mejorar la experiencia de los usuarios. En caso de habilitarse herramientas analíticas adicionales, QRTE actualizará esta Política cuando resulte necesario.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">4. Cookies publicitarias</h3>
                        <p class="mt-1">Actualmente QRTE no utiliza cookies de publicidad comportamental ni redes publicitarias de terceros para mostrar anuncios personalizados.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">5. Gestión de cookies</h3>
                        <p class="mt-1">La mayoría de navegadores permiten:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Bloquear cookies.</li>
                            <li>Eliminar cookies existentes.</li>
                            <li>Limitar determinadas categorías de cookies.</li>
                        </ul>
                        <p class="mt-2">Ten en cuenta que la desactivación de cookies esenciales puede afectar funcionalidades como:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Inicio de sesión.</li>
                            <li>Seguridad de la cuenta.</li>
                            <li>Persistencia de la sesión.</li>
                            <li>Preferencias de idioma.</li>
                            <li>Sistemas de soporte.</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">6. Cambios en esta Política</h3>
                        <p class="mt-1">QRTE podrá actualizar esta Política de Cookies cuando incorpore nuevas funcionalidades, proveedores o tecnologías que impliquen el uso de cookies o tecnologías similares. Las versiones actualizadas serán publicadas con su fecha de vigencia correspondiente.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">7. Contacto</h3>
                        <p class="mt-1">Si tienes preguntas sobre esta Política de Cookies puedes escribirnos a: <a href="mailto:qrte@poordesigner.com" class="text-brand hover:underline">qrte@poordesigner.com</a></p>
                    </section>
                </div>
            </div>

            {{-- Panel: Turnstile --}}
            <div x-show="tab === 'turnstile'" x-cloak class="mt-6 bg-white shadow-sm sm:rounded-lg p-6 sm:p-8">
                <p class="text-xs text-gray-400">{{ __('Última actualización del addendum de Cloudflare: 18 de junio de 2025') }}</p>
                <h2 class="mt-2 text-xl font-semibold text-gray-900">Cloudflare Turnstile — {{ __('Addendum de privacidad') }}</h2>
                <p class="text-sm text-gray-500">{{ __('Traducción al español del Turnstile Privacy Addendum de Cloudflare, complementario a su Política de Privacidad principal. Fuente oficial:') }} <a href="https://www.cloudflare.com/en-gb/turnstile-privacy-policy/" target="_blank" rel="noopener" class="text-brand hover:underline">cloudflare.com/en-gb/turnstile-privacy-policy</a></p>

                <div class="mt-6 prose prose-sm max-w-none text-gray-700 space-y-6">
                    <section>
                        <h3 class="font-semibold text-gray-900">1. {{ __('Introducción') }}</h3>
                        <p class="mt-1">{{ __('Turnstile, desarrollado por Cloudflare, Inc., es una herramienta de seguridad pro-privacidad que procesa señales mínimas únicamente para proteger los sitios web contra actividad maliciosa, distinguiendo usuarios humanos de bots y bloqueando el tráfico automatizado. Cloudflare no decide si un sitio usa Turnstile; lo pone a disposición de cualquier web que necesite detectar y bloquear bots.') }}</p>
                        <p class="mt-1">{{ __('En QRTE usamos Turnstile en formularios de registro y acceso para prevenir spam y abuso. El widget que ves ("Verificación de seguridad") es provisto por Cloudflare.') }}</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">2. {{ __('Alcance de este addendum') }}</h3>
                        <p class="mt-1">{{ __('Este addendum complementa la') }} <a href="https://www.cloudflare.com/en-gb/privacypolicy/" target="_blank" rel="noopener" class="text-brand hover:underline">{{ __('Política de Privacidad principal de Cloudflare') }}</a> {{ __('y aporta información específica sobre Turnstile (incluida su Challenge Platform; toda referencia a "Turnstile" aplica por igual a dicha plataforma). Cuando este addendum es más específico, prevalece sobre la política general.') }}</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">3. {{ __('Información que se recopila') }}</h3>
                        <p class="mt-1">{{ __('Turnstile procesa señales del lado del cliente ("Signals") como: dirección IP, huella TLS (TLS Fingerprint), cabecera User-Agent, sitekey y origen asociado. Cloudflare declara que no puede identificar directamente a personas a partir de estas señales, incluidas las IP.') }}</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">4. {{ __('Cómo se usa la información') }}</h3>
                        <p class="mt-1"><strong>a) {{ __('Detección y bloqueo de bots.') }}</strong> {{ __('Turnstile evalúa las señales del visitante y del sitio para distinguir humanos de bots y bloquear el tráfico malicioso. La finalidad no es identificar, perfilar ni segmentar personas, sino únicamente proteger la web visitada. Estas señales son estrictamente necesarias para ese fin y permitir una experiencia segura.') }}</p>
                        <p class="mt-1">{{ __('Para esta finalidad Cloudflare actúa como Encargado (procesador): trata las señales en nombre y por instrucciones de QRTE (Responsable). Si quieres ejercer derechos sobre este tratamiento, contáctanos a') }} <a href="mailto:qrte@poordesigner.com" class="text-brand hover:underline">qrte@poordesigner.com</a> {{ __('y nosotros lo gestionamos con Cloudflare.') }}</p>
                        <p class="mt-2"><strong>b) {{ __('Mejora de la capacidad de detección de Turnstile.') }}</strong> {{ __('Cloudflare también procesa las señales para mejorar sus algoritmos de detección y responder a amenazas de bots en evolución, manteniendo la seguridad de las webs que visitas.') }}</p>
                        <p class="mt-1">{{ __('Para esta finalidad Cloudflare actúa como Responsable (controller) y su tratamiento se rige por este addendum junto con su Política principal.') }}</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">5. {{ __('Aviso para residentes en UE y Reino Unido') }}</h3>
                        <p class="mt-1">{{ __('En la medida en que estos datos califiquen como datos personales:') }}</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>{{ __('Como encargado (protección del sitio del cliente), el cliente de Cloudflare —QRTE— como responsable determina la base legal y Cloudflare trata los datos por nuestra instrucción y en nuestro nombre.') }}</li>
                            <li>{{ __('Como responsable (mejora de Turnstile), Cloudflare se ampara en su interés legítimo en mejorar la eficacia de la detección de bots.') }}</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">6. {{ __('Cookies') }}</h3>
                        <p class="mt-1">{{ __('Las señales recopiladas por Turnstile son estrictamente necesarias para detectar y bloquear bots y permitir una experiencia segura en sitios que lo implementan. Para más detalle sobre cookies de Cloudflare, consulta su') }} <a href="https://www.cloudflare.com/en-gb/cookie-policy/" target="_blank" rel="noopener" class="text-brand hover:underline">{{ __('Política de cookies') }}</a> {{ __('y la') }} <a href="https://developers.cloudflare.com/turnstile/" target="_blank" rel="noopener" class="text-brand hover:underline">{{ __('documentación de Turnstile') }}</a>.</p>
                    </section>

                    <section>
                        <h3 class="font-semibold text-gray-900">7. {{ __('Contacto para temas de privacidad de Turnstile') }}</h3>
                        <p class="mt-1">{{ __('Para preguntas sobre este addendum o tus datos tratados vía Turnstile, puedes contactar al Delegado de Protección de Datos de Cloudflare en') }} <a href="mailto:dpo@cloudflare.com" class="text-brand hover:underline">dpo@cloudflare.com</a> {{ __('o a nosotros en') }} <a href="mailto:qrte@poordesigner.com" class="text-brand hover:underline">qrte@poordesigner.com</a> {{ __('si tu consulta se refiere al uso de Turnstile en QRTE.') }}</p>
                    </section>

                    <p class="pt-4 text-sm text-gray-500 border-t">{{ __('Texto traducido por QRTE a partir del original en inglés. En caso de discrepancia, prevalece el original de Cloudflare:') }} <a href="https://www.cloudflare.com/en-gb/turnstile-privacy-policy/" target="_blank" rel="noopener" class="text-brand hover:underline">cloudflare.com/en-gb/turnstile-privacy-policy</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection