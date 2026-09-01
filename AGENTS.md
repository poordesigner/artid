# AGENTS.md — ARTid

Contexto del proyecto para agentes de IA. Leer antes de tocar código.

## Qué es ARTid (estado actual)

**Marca actual: QRTE** (antes ARTid), por POORdesigner.com.

Plataforma SaaS **QRTE** (`artid.poordesigner.com`, alias `qrte.poordesigner.com`, cuenta Chatwoot "QRTE"): identidad digital para obras de arte físicas.
El artista registra sus obras con **pago único por tokens** y cada obra obtiene un **QR permanente**
firmado criptográficamente que apunta a una **ficha pública verificada** (`/o/{publicId}`).
Incluye metadata, historial (exposiciones y proveniencia) y control cifrado de propiedad.
Soporte al usuario: **Chatwoot** (widget + bot) y **tickets de soporte** privados (`/tickets`, ver abajo).

- **Modelo de pago: tokens de consumo, NO suscripción.** `1 token = QR + ficha básica de una obra, para siempre`.
- Crear una obra consume **1 token**; el gate es `Artist::canCreateArtwork()` (saldo > 0).
- Los plan/suscripción (Paddle Billing subscriptions) son **legado**: el modelo de grants por plan está
  deprecado ("El acceso por plan ya no se usa. Usa el otorgamiento de tokens."). No hay UI de artista para
  suscripciones; `/planes` vende paquetes de tokens.

## Stack

- Laravel 13 / PHP 8.3 / MySQL / Redis / Vite + Tailwind + Alpine.js
- Almacenamiento de archivos: **R2** de Cloudflare (tipo `r2` en `config/filesystems.php`)
- Auth: Google OAuth + email/password (Breeze). Usuario = **`Artist`** (Authenticatable)
- QR: `simplesoftwareio/simple-qrcode`
- Pagos: **Paddle Billing** `PaddleService` (sandbox y live) — usado HOY para **pago único de paquetes de tokens**.

## Tokens (modelo de negocio actual)

- **Welcome tokens**: `config('artid.welcome_tokens')` (env `ARTID_WELCOME_TOKENS`, default 5) la primera vez
  que el artista crea cuenta (`grantWelcomeTokens()`, idempotente vía `welcome_tokens_claimed`).
- **Paquetes**: `TokenPackage` (admin los administra en Configuración → Paquetes). Checkout:
  `TokenController@checkout` → `PaddleService::createTokenCheckout` → checkout alojado → cookie `pending_package`.
  El webhook acredita tokens cuando llega `transaction.completed` con `custom_data.token_package_id`
  (idempotente por transacción; nota "Compra de paquete").
- **Consumo**: `Artist::consumeToken()` (1) atómico al crear obra; registra `TokenTransaction` type `consume`.
- **Historial**: `TokenTransaction` (type `grant` / `purchase` / `consume`, amount ±, balance_after, ref, note).
  Panel "Mis tokens" (`/tokens`): saldo, paquetes, funciones y movimientos.
- **Admin otorga tokens** (no planes): `/configuracion/cuentas` → `AccountController@grant` (addTokens type 'grant').
- Funciones (`TokenFunction` + acciones `TokenAction`) son configuración para la página de precios; el consumo
  real de obra es fijo (1 token). No implementar otros consumos sin confirmar.

## Modelos principales

- `Artist` — usuario (artista). `is_admin`, avatar, statement, cv_pdf, redes (instagram/behance/artstation/
  youtube/tiktok), website_url, `links` (ArtistLink), `tokens_balance`, `welcome_tokens_claimed`,
  `github_*` / `short_domain` (campos heredados del prototipo; ruta GitHub NO registrada → dormido).
  Métodos clave: `tokenBalance()`, `canCreateArtwork()`, `addTokens()`, `consumeToken()`, `grantWelcomeTokens()`,
  `activeSubscription()` / `effectivePlan()` / `enforcePlanLimits()` (legado, solo webhooks de suscripción).
- `Artwork` — obra. `artist_id`, `title`, `slug`, `artwork_id` (único, permanente), `public_id` (UUID, se usa en
  firma), `year`, `edition`, `status` (`created`|`archived`), `series_id`, `series`, `technique`, `dimensions`,
  `description`, `image` (R2). Relaciones: exposiciones, ownerships, `links` (ArtworkLink, máx 10).
- `Series`, `Technique`, `Exhibition` (start_date, end_date, location), `Ownership` (proveniencia cifrada, type
  `initial`|`transfer`, llave secreta por transferencia).
- Enlaces: `ArtworkLink` (video/foto/blog), `ArtistLink` (portafolio/CV/exposiciones, máx 5).
- Tokens: `TokenPackage`, `TokenFunction`, `TokenAction` (pivot `token_function_action`), `TokenTransaction`.
- Tickets de soporte: `SupportTicket` (consecutivo `TKT-####`, artist_id, topic, subject, message,
  status `open`|`closed`; relación `Artist::supportTickets()`), `SupportTicketAttachment` (disk r2, path,
  original_name, mime, size; `SupportTicket::attachments()`), `SupportTicketReply` (hilo: sender `admin`|`agent`,
  body, sent_at; `SupportTicket::replies()`, ver "Respuesta por email").
- Legacy (modelos/servicios aún presentes, sin UI de artista): `Plan`, `PlanPeriod`, `PlanFeature`,
  `PlanLegalTerm`, `Subscription`, `Payment`, `WebhookEvent` (idempotencia de webhooks Paddle), `GitHubService`.

## QR firmado (ficha pública)

- `Artwork::public_id` = UUID. Firma HMAC-SHA256 **versionada** en `config/artid.php`
  (`signing_keys['v1']`, `active_signing_version`).
- `signedUrl()` → `{ARTID_PUBLIC_URL}/o/{public_id}?s={version}.{hmac}`.
- `GET /o/{publicId}` (`PublicArtworkController@show`) verifica la firma (`verifySignature`); sin firma válida → 404.
- Hay también **perfil público de artista**: `GET /artist/{id}` (`PublicArtworkController@artist`).
- `artwork_id` NO se usa en la firma; solo display y rutas de imagen R2.

## Roles y paneles

- **Admin** (`Artist::isAdmin()`): panel `/admin` con stats (artistas, paquetes activos, tokens entregados,
  cobrado USD) y accesos a cuentas / config. Nav muestra "Panel".
- **Artista**: dashboard `/panel` con bienvenida, stats (Obras/Series/Tokens), acciones y obras recientes.
- `/dashboard` redirige según rol (admin → `/admin`, artista → `/panel`).
- Middleware `admin` en `bootstrap/app.php` (alias). **Ojo**: el `Base Controller` es un `abstract class Controller {}`
  SIN `middleware()` — no usar `$this->middleware()` en constructores (Laravel 11 no lo soporta acá); proteger con
  `Route::middleware('admin')` en `web.php` (ver grupo en `routes/web.php`).

## Paddle Billing (env vars en Coolify)

- `PADDLE_ENV`, `PADDLE_API_KEY` (prefijo `pdl_`), `PADDLE_WEBHOOK_SECRET`, `PADDLE_CLIENT_TOKEN`,
  `PADDLE_MIN_IMMEDIATE_CHARGE` (legado: suscripciones).
- Uso activo: **checkout one-time de paquetes** (`createTokenCheckout`) + crediting por webhook + sync de
  productos/precios de paquete (`TokenPackageController@syncToPaddle`).
- Flujo legado de suscripción (planes/upgrade/downgrade/cancel/portal, checkout embebido `/pay` con Paddle.js):
  sigue en el código (`SubscriptionController`, `PaddleService` preview/update/portal), sin UI actual de artista.
- Webhooks: `POST /webhooks/paddle` (excluido de CSRF en bootstrap). **Idempotencia**: cada `event_id` en
  `webhook_events` y se omite duplicados. Compra de tokens idempotente por transacción.

## Páginas y flujo clave (artista)

- Registro verify: crear cuenta + welcome tokens. Ingreso Google/email.
- `/panel` dashboard → `/artworks` (filtros Todas/Activas/Inactivas; orden), crear obra (consume 1 token),
  `/artworks/{id}` (resumen + QR + exposiciones + enlaces + propiedad), series, `+ Expo`, `+ Propiedad`
  (initial/transfer + llave secreta + reveal), enlaces de obra.
- `/tokens` — saldo/paquetes/historial. `/profile` — avatar, perfil, statement, CV PDF, redes, enlaces de perfil,
  contraseña, eliminar cuenta. `/configuracion` — Seguridad + Mis tokens (artista); admin: Planes, Paquetes,
  Usos de tokens. `/planes` — landing de paquetes público.
- `/tickets` — mis tickets de soporte (index), `/tickets/crear` (form: topic, subject, message, hasta 3 adjuntos
  imagen/PDF ≤5MB en R2), `/tickets/{number}` (detalle; el número llega vía flash `ticket_number`), descarga de
  adjuntos `/tickets/{number}/adjunto/{attachment}`. Acceso: dueño del ticket o admin (403 si no).
- Portal de ayuda: `/ayuda` — mismo contenido loggeado/no loggeado: `resources/views/ayuda/content.blade.php`
  (12 secciones) incluido desde `ayuda.blade.php` (no autenticado, header público) o `ayuda/panel.blade.php`
  (autenticado, header de panel). Botón "Crear un ticket de soporte" (solo loggeado). Mantener al día con los
  flujos reales.

## Chatwoot (soporte)

- Chatwoot corre en el VPS (Coolify, stack `pgxhgejin4zgyjdovg0blaz3`, dominio `https://cwoot.poordesigner.com`).
- **Alcance 1 acordado**: widget de chat en el SaaS (dashboard + landing) → inbox único de soporte. Widget ya integrado
  (`x-chatwoot-widget` en `layouts/app`, `layouts/public` y `welcome`; config `config/chatwoot.php`).
- **Bot de soporte** (Fase A): workflow n8n `qrte-support-agent` responde en el inbox "Soporte ARTid". Usa **contexto por
  paquetes**: `GET /api/support/context?topic={key}` (controller `SupportContextController`, packs en `config/support_packs.php`,
  cache TTL 5 min invalidado vía `App\Support\SupportContext::forgetAll()` al editar paquetes/usuos de tokens).
  Temas: `introduccion` (default), `conocer`, `cuenta`, `obras`, `qr-ficha`, `historial`, `enlaces`, `facturacion` (dinámico:
  TokenPackage/TokenFunction/welcome) , `configuracion`, `otros`. Protocolo `@@CONTEXTO:<key>@@` para cambio de tema por el LLM.

## Configuración de IA (modelos Groq)

- **Central**: `AppSetting` (tabla key-value) + `AiConfigController`. El admin edita en `/configuracion` → tab **IA**
  (`router_model`, `chat_model`, `backup_model`; defaults `qwen/qwen3.8-27b`).
- **`GET /api/support/llm`** (`AiConfigController@config`, cache 5 min) expone `{router_model, chat_model, backup_model}`;
  los workflows n8n lo consultan dinámicamente al empezar. Groq es el único proveedor; la credencial Bearer vive en n8n,
  NO en Laravel.
- `AppSetting::get/set(key, default)`. Al editar se invalida el cache (`Cache::forget(AiConfigController::CACHE_KEY)`).

## Gestor de tickets (agente 2, triage con IA)

- **Objetivo (asist-first)**: al abrir un ticket en admin hay un botón **"Analizar con IA"** que documenta en el ticket:
  resumen, prioridad sugerida (normal/alta), contexto del usuario y borrador de respuesta con acciones sugeridas. NADA se
  envía automáticamente; el admin decide.
- **Flujo**: admin `GET /configuracion/tickets/{ticket}` (`TicketAnalysisController@show`, vista
  `configuracion/tickets-show.blade.php`) → botón → `POST .../analyze` crea `TicketAnalysis` (status pending) y despacha
  `AnalyzeTicketJob` (cola Redis) → el job hace POST al webhook de n8n (`config/ticket_agent.php`) → n8n workflow
  `qrte-ticket-analyzer` consulta `GET /api/tickets/{id}/context?secret=` + `GET /api/support/llm`, llama a Groq y
  responde síncronamente `{summary, priority, draft_reply, suggested_actions[], model}` → el job persiste en
  `ticket_analyses` (status completed/failed) → la vista hace reload cada 4s mientras `pending`.
  **El análisis también se dispara automáticamente al crear el ticket** (`SupportTicketController@store` despacha el
  job), sin que el admin lo pida.
- El borrador del análisis (`draft_reply`) pre-completa el textarea editable en `configuracion/tickets-show.blade.php`.
  El admin edita, envía y decide cuándo cerrar el ticket (no hay auto-cierre).
- **`GET /api/tickets/{id}/context`** (con `?secret=` = `TICKET_AGENT_WEBHOOK_SECRET`, 403 si no): devuelve el ticket,
  el artista (email verificado, antigüedad, tokens, obras, series, tickets previos, perfil público) y el pack de
  conocimiento (mapeo `config/ticket_agent.topic_pack_map`: cuenta→cuenta, obras→obras, facturacion→facturacion,
  tecnico→configuracion, otro→otros) vía `SupportContextBuilder::pack()`.
- **Modelos**: `TicketAnalysis` (support_ticket_id, status pending|processing|completed|failed, summary, priority, draft_reply,
  suggested_actions json, analysis json, error, model, analyzed_at; relación `SupportTicket::analysis()` hasOne
  latestOfMany), `AnalyzeTicketJob` (timeout 90, 1 intento). `SupportContextBuilder` (app/Support) reúne el prompt base +
  packs; `SupportContextController` delega en él.
- **Config**: `config/ticket_agent.php` (n8n_webhook_url `TICKET_AGENT_N8N_WEBHOOK_URL`, secret
  `TICKET_AGENT_WEBHOOK_SECRET`, timeout `TICKET_AGENT_TIMEOUT`). Env vars se setean en Coolify.
- **Contrato del workflow n8n `qrte-ticket-analyzer`** (webhook POST; responde síncrono):
  - **Entrada**: `{secret, ticket_id, ticket_number, context_url, llm_url}`.
  - **Pasos**: validar `secret` (≈ `TICKET_AGENT_WEBHOOK_SECRET`) → `GET {{ $json.context_url }}` con `?secret=` →
    `GET {{ $json.llm_url }}` (modelo Groq `chat_model`, fallback `backup_model`) → sistema con el `knowledge.pack` del
    contexto + instrucciones (resumen ES/EN, prioridad normal|alta, borrador en el idioma del artista, acciones)
    → Groq (`POST https://api.groq.com/openai/v1/chat/completions`, misma credencial Bearer del agente 1, `response_format: json_object`)
    → responder JSON exacto `{summary, priority, draft_reply, suggested_actions[], model}` (llaves en español
    opción 0 = `{resumen, prioridad, borrador, acciones}`).
  - Las llaves del result JSON deben verse en Laravel como `priority` ∈ `normal|alta`. Si el modelo usa otro
    formato, mapearlo en n8n (nodo Code) antes de responder.

## Respuesta por email (hilo de ticket)

- Al responder un ticket desde admin (`POST /configuracion/tickets/{ticket}/reply`, `tickets.admin-reply`) se guarda
  una `SupportTicketReply` (sender `admin`|`agent`, body, sent_at) en el hilo (`SupportTicket::replies()`) y se envía
  el correo al artista con `TicketReplyMail` (`resources/views/emails/ticket-reply.blade.php`, HTML plano, sin
  `x-mail::*` que requiere el paquete `laravel/mail` no instalado). El ticket **no se cierra automáticamente**;
  el admin decide cuándo cerrarlo.
- El borrador del análisis (`draft_reply`) pre-completa el textarea editable en `configuracion/tickets-show.blade.php`.
- **Email (env en Coolify)**: cuenta GoDaddy `qrte-soporte@poordesigner.com` (SMTP
  `smtpout.secureserver.net`, puerto 465 SSL / 587 STARTTLS). Setear: `MAIL_MAILER=smtp`, `MAIL_HOST`,
  `MAIL_PORT`, `MAIL_USERNAME=qrte-soporte@poordesigner.com`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS=qrte-soporte@poordesigner.com`,
  `MAIL_FROM_NAME=QRTE`. Sin env → `config/mail.php` default `log` (no sale correo).

## Tickets de soporte

- Complemento de Chatwoot para consultas **privadas y estructuradas** (con adjuntos). Creados por el artista,
  gestionados por admin; no hay respuestas dentro del propio ticket (se atienden por mail/Chatwoot).
- Numero: consecutivo `TKT-####` (padding de 4 sobre el `id`, set `forceFill` con `str_pad` justo tras el create).
- Temas (`SupportTicket::TOPICS`): `cuenta`, `obras`, `facturacion`, `tecnico`, `otro` (labels en `TOPICS_LABELS`).
- Adjuntos: hasta **3** (imagen jpeg/jpg/png/webp/gif o PDF, ≤5MB) en R2 bajo `support_tickets/{number}/{random}`;
  nombre original preservado solo como `original_name`, el archivo se guarda con nombre aleatorio.
- Acceso (`authorizeAccess`): `user->isAdmin() || ticket->artist_id === user->id`, si no → 403.
- Admin: `/configuracion/tickets` (índice con filtro por status, últimas 100, `tickets.admin`),
  `POST /configuracion/tickets/{ticket}/status` para abrir/cerrar (`tickets.admin-status`) y
  `GET /configuracion/tickets/{ticket}` (`tickets.admin-show`) para el detalle con contexto del usuario + botón
  "Analizar con IA" (ver "Gestor de tickets"). El borrador del análisis pre-completa el textarea de respuesta.
  Enlace desde `/admin/dashboard` (en `admin/dashboard.blade.php`).

## Email Marketer (Secuencias de Onboarding)

- **Agente 16**: automación de emails post-registro para guiar al artista en las primeras semanas.
- **Ejecución**: manual desde admin (`/configuracion/onboarding` → botón "Ejecutar ahora") o vía
  `php artisan email:onboarding:process`. No hay cron automático aún.
- **Secuencias** (en `config/onboarding.php`):
  - Día 0: **Bienvenida** (`welcome`) — siempre se envía; da tokens y invita a crear primera obra.
  - Día 3: **Recordatorio tokens** (`reminder_tokens`) — si tiene tokens pero no creó obras.
  - Día 7: **Tutorial** (`tutorial`) — si aún no creó obras; ofrece tutorial y ayuda.
  - Día 14: **Prueba social** (`social_proof`) — si ya creó al menos 1 obra; muestra cuántos artistas hay.
  - Día 30: **Comprar tokens** (`sell_tokens`) — si tiene ≤2 tokens; invita a comprar paquete.
- **Detección de actividad**: combina `last_login_at` (middleware `TrackLastLogin`), `tokens_balance`,
  y `artworks()->exists()` para decidir si salta un paso. Las condiciones están en `OnboardingConditions`.
- **Tracking**: tabla `onboarding_emails` (artist_id + step, unique). Un email solo se envía 1 vez por artista.
- **Modelos**: `OnboardingEmail` (tracking), Notifications implementan `ShouldQueue` (envío asíncrono).
- **Templates**: `resources/views/emails/onboarding/` (5 templates HTML inline, estilo ticket-reply).
- **Admin**: `/configuracion/onboarding` (`admin.onboarding`) muestra stats por step (elegibles, enviados, pendientes).
- **Middleware**: `TrackLastLogin` actualiza `artists.last_login_at` en cada request autenticada (throttle 1 min).
- **Migraciones**: `add_last_login_at_to_artists_table` + `create_onboarding_emails_table`.

## Localización (idioma)

- No persistente: middleware `SetLocale` (grupo web en bootstrap) detecta cookie `locale` o idioma del navegador.
- Selector ES/EN (`x-language-switcher`). Traducciones en `lang/es.json` y `lang/en.json` (JSON keys = texto
  original; en.json traduce ES→EN). **Todo texto visible en blade debe ir en `__()`**; al agregar textos hay que
  definir la clave en AMBOS JSON (`lang/es.json` valor = mismo texto; `lang/en.json` valor = inglés). Validar JSON
  tras editar (duplicados y sintaxis). Español colombiano (tú/usted neutro).

## Deploy

- VPS `207.180.242.253` vía **Coolify** (clave SSH local: `~/.ssh/codex_portal_b2b_nopass`, usuario root).
- **No hay PHP/Composer/Node local en Windows**: migraciones y comandos se corren DENTRO del contenedor de la app
  vía SSH (`docker ps` para el nombre; cambia en cada deploy). En Windows no hay `node` ni `php`; usar `python` si se
  necesita validar JSON.
- Git push a `github.com/poordesigner/artid` (repo `main`). El token de push no se commitea (GitHub push protection);
  se usa vía remote configurado localmente o credencial del entorno.
- **Coolify NO auto-despliega** en cada push: validar en el dashboard de Coolify. El usuario maneja el deploy y avisa.
- Migraciones: `php artisan migrate --force` dentro del contenedor tras el deploy.
- Env vars se setean en Coolify (dashboard), no en `.env` del repo.
- Cuidado: **no usar `$this->middleware()`** en este proyecto.
- Scripts temporales de debug: crearlos en `storage/`, ejecutarlos vía `docker cp` + `docker exec php <archivo>`,
  luego **borrarlos** (no commitear `storage/*.php`).
- **JSON exportables de workflows n8n NO se versionan**: contienen secrets (webhook/chatewoot/ticket). El JSON de
  `qrte-ticket-analyzer` se mantiene en `C:\Users\DELL\AppData\Local\Temp\opencode\` y se guarda local (n8n import =
  desde ese archivo). No crear una carpeta `n8n/` ni `docs/workflows/` en el repo con secrets.

## Assets / branding

- Logos en `public/img/`: `navbar_240x120.png` (navbar/footer), `logo_600x300.png` (login y checkout),
  `favicon_192x192.png`, y `logo_box_1024x1024.png` (cuadrada para Paddle; también en R2 público).
- Los productos de Paddle de paquetes usan ese `logo_box` como `image_url`.

## Convenciones de código

- Vistas: Tailwind + Alpine.js. Componentes Blade en `resources/views/components/`
  (`x-breadcrumb`, `x-language-switcher`, `x-locations-datalist`).
- Breadcrumbs: `x-breadcrumb` para páginas anidadas (editar/ver/crear obra, exposiciones, propiedad).
- Formulario de obra: técnicas multi-select (Alpine `techniquePicker`), edición tipo (pieza única/tiraje/P-A),
  año limitado, descripción máx 500, imagen máx 2MB (se optimiza a WEBP ≤300KB en R2 `artworks/{artwork_id}/*.webp`).
- `docs/` (`PROMPT.md`, `ROADMAP.md`, `CONTEXTO-ESTATICO.md`) documenta el **concepto original** (configurador con
  GitHub del artista / short.io / tokens por acción). Es historia; el código actual pivotó a SaaS central con
  tokens por obra. Tratar `docs/` como referencia histórica, no como spec.