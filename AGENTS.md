# AGENTS.md — ARTid

Contexto del proyecto para agentes de IA. Leer antes de tocar código.

## Qué es ARTid

Plataforma SaaS **ARTid by POORdesigner.com**: identidad digital para obras de arte físicas.
El artista registra sus obras y cada una obtiene un **QR permanente** firmado criptográficamente
que apunta a una **ficha pública verificada**. Incluye metadata, historial (exposiciones y
proveniencia) y control cifrado de propiedad.

Modelo: **SaaS centralizado** (datos en BD, imágenes en R2). No es open-source.

## Stack

- Laravel 13 / PHP 8.3 / MySQL / Redis / Vite + Tailwind + Alpine.js
- Almacenamiento de archivos: **R2** de Cloudflare (tipo `r2` en `config/filesystems.php`)
- Auth: Google OAuth + email/password (Breeze). Usuario = **`Artist`** (Authenticatable)
- QR: `simplesoftwareio/simple-qrcode`
- Pagos: **Paddle Billing** (sandbox y live)

## Modelos principales

- `Artist` — usuario (artista). Tiene `is_admin`, `granted_plan_id` + `granted_expires_at`
  (cuenta especial: plan otorgado por el admin sin pagar), campos de perfil (avatar, CV, redes).
- `Artwork` — obra. `artist_id`, `title`, `slug`, `artwork_id` (único, permanente), `public_id`
  (UUID), `year`, `edition`, `status` (`created`, `archived` = inactiva por límite), `series_id`,
  `technique`, `dimensions`, `description`, `image`.
- `Series`, `Technique` (32 técnicas + Graffiti, Arte Urbano, Stencil), `Exhibition`
  (start_date, end_date, location), `Ownership` (proveniencia cifrada).
- Planes: `Plan` (name, description, is_active, sort_order, paddle_product_id, max_artworks,
  is_free), `PlanPeriod` (number, period [monthly|quarterly|semiannual|annual], price,
  paddle_product_id, paddle_price_id), `PlanFeature`, `PlanLegalTerm`.
- Facturación: `Subscription` (artist_id, plan_id, plan_period_id, paddle_customer_id,
  paddle_subscription_id, status, next_billed_at, current_period_start/end, canceled_at),
  `Payment` (transacciones), `WebhookEvent` (idempotencia).

## QR firmado (ficha pública)

- `Artwork::public_id` = UUID. Firma HMAC-SHA256 **versionada** en `config/artid.php`
  (`signing_keys['v1']`, `active_signing_version`).
- `signedUrl()` → `{ARTID_PUBLIC_URL}/o/{public_id}?s={version}.{hmac}`.
- La ficha pública `GET /o/{publicId}` verifica la firma; sin firma válida → 404.
- `artwork_id` NO se usa en la firma; solo para display y rutas de imagen R2.

## Planes y gates por plan

- Cada plan tiene `max_artworks` y `is_free` (true = plan Free).
- `Artist::effectivePlan()` determina el plan actual con prioridad:
  **grant otorgado (si vigente) > suscripción paga > plan Free**.
- `Artist::currentMaxArtworks()` y `activeArtworksCount()` gobiernan el gate de obras.
- `Artist::enforcePlanLimits()`:
  - archiva obras (status `archived`) si exceden el límite (conserva las más recientes);
  - **reactiva** obras archivadas cuando sube el cupo (las más recientes primero).
- Se llama al crear obra, al otorgar/revocar grant, y en webhooks de suscripción (on plan change).
- Las obras con `status = 'archived'` son "inactivas" (atenuadas en la UI, filtrables).

## Cuentas especiales (grant)

- Admin asigna un plan a un artista **sin pago** mediante `granted_plan_id` + `granted_expires_at`.
- Vigencia: 7 / 30 / 90 días o sin expiración. Al vencer, `effectivePlan()` vuelve al plan anterior.
- NO interactúa con Paddle (no crea suscripción ni cambia cobros; la suscripción real sigue normal).
- Gestión: `/configuracion/cuentas` (solo admin).

## Roles y paneles

- **Admin** (`Artist::isAdmin()`): panel `/admin` con stats y tarjetas de gestión
  (cuentas, planes, configuración). Nav muestra "Panel".
- **Artista**: dashboard `/panel` con bienvenida, stats, acciones y obras recientes.
  Nav muestra "Panel" y "Obras".
- `/dashboard` redirige según rol (admin → `/admin`, artista → `/panel`).
- Middleware `admin` registrado en `bootstrap/app.php` (alias). **Ojo**: el `Base Controller`
  de este proyecto es un `abstract class Controller {}` SIN `middleware()` — no usar
  `$this->middleware()` en constructores (Laravel 11 no lo soporta acá); proteger con
  `Route::middleware('admin')` en `web.php` o el middleware en bootstrap.

## Paddle Billing

- Env vars (`.env` / Coolify):
  - `PADDLE_ENV=sandbox|production`
  - `PADDLE_API_KEY` (server-side, prefijo `pdl_`)
  - `PADDLE_WEBHOOK_SECRET` (endpoint secret key para firmas)
  - `PADDLE_CLIENT_TOKEN` (client-side para Paddle.js, prefijo test_)
  - `PADDLE_MIN_IMMEDIATE_CHARGE=10` (mínimo para cobro inmediato en upgrades)
- `PaddleService` (app/Services): crea productos/precios, customer, transacciones checkout,
  preview/update de suscripción, cancel, portal session, payment methods, credit balance.
- URLs: sandbox `https://sandbox-api.paddle.com`, live `https://api.paddle.com`.
- Flujo de suscripción:
  1. Checkout alojado `/pay` con Paddle.js → paga → webhooks `subscription.created/activated`
     + `transaction.*` → BD.
  2. Upgrade/downgrade: **preview** → página de confirmación con montos prorrateados →
     `subscriptions.update` con `proration_billing_mode`.
  3. **Mínimo $10** (Opción C): si el cargo prorrateado < mínimo → usar
     `prorated_next_billing_period` (se cobra en la próxima factura) y mostrar aviso claro.
  4. Cancelación: `next_billing_period` (queda vigente hasta fin de período). Botón "Reactivar
     plan" remueve el `scheduled_change` (`scheduled_change: null`).
  5. Customer portal: `/subscribe/portal` devuelve JSON URL → se abre en nueva pestaña
     (Paddle prohíbe iframe).
- **Prorrateo y crédito**: preview muestra detalle. En upgrade de misma frecuencia el crédito
  del plan viejo puede ser 0 (carga la diferencia); en downgrade aparece "crédito a favor".
  Paddle descuenta el balance automáticamente (`credit` de la transacción inmediata).
- Webhooks: `POST /webhooks/paddle` (excluido de CSRF en bootstrap). **Idempotencia**: se
  registra cada `event_id` en `webhook_events` y se omite duplicados.

## Localización (idioma)

- No persistente: middleware `SetLocale` (agregado al grupo web en bootstrap) detecta cookie
  `locale` o idioma del navegador.
- Selector ES/EN en navbar, login y landing. `x-language-switcher` componente.
- Traducciones en `lang/es.json` y `lang/en.json` (JSON keys = texto).
- **Español colombiano** (tú/usted neutro, no argentino). Textos de la UI sin `__()` no se
  traducen — envolver en `__()`.

## Deploy

- VPS `207.180.242.253` vía **Coolify** (proyecto UUID `rdz28v6ov6rkafdgnteqo726`).
- **No hay PHP/Composer/Node local en Windows**: migraciones y comandos se corren DENTRO del
  contenedor de la app vía SSH (configurar `docker ps` para el nombre, cambia en cada deploy).
- Git push se hace a `github.com/poordesigner/artid` (repo `main`). El token de push no debe
  commitearse en el repo (GitHub push protection lo bloquea); se usa vía remote configurado
  localmente o credencial del entorno, no en archivos del proyecto.
- **Coolify NO auto-despliegas** en cada push: hay que validar en el dashboard de Coolify.
  El usuario maneja el deploy y avisa.
- Migraciones: `php artisan migrate --force` dentro del contenedor tras el deploy.
- Env vars se setean en Coolify (dashboard), no en `.env` del repo.
- Cuidado: **no usar `$this->middleware()`** en este proyecto (Controller base sin el método).
- Scripts temporales de debug: crearlos en `storage/`, ejecutarlos vía `docker cp` al contenedor
  y `docker exec php <archivo>`, luego **borrarlos** (no commitear `storage/*.php`).

## Assets / branding

- Logos en `public/img/`:
  - `navbar_240x110.png` — navbar y footer
  - `logo_600x300.png` — login y checkout
  - `logo_box_1024x1024.png` — imagen cuadrada para Paddle (también en R2 público:
    `https://pub-10efd14d011c4a98a3d5281d393c13d1.r2.dev/logo_box_1024x1024.png`)
- Los productos de Paddle (Free, Artist, Profesional) usan ese `logo_box` como `image_url`.

## Convenciones de código

- Vistas: Tailwind + Alpine.js. Componentes Blade en `resources/views/components/`
  (`x-breadcrumb`, `x-language-switcher`, `x-locations-datalist`).
- Breadcrumbs: `x-breadcrumb` para páginas anidadas (editar/ver/crear obra, exposiciones, propiedad).
- El formulario de obra tiene técnicas multi-select (Alpine `techniquePicker`), dimensiones en 3
  campos (alto×ancho×profundidad + unidad), edición tipo (pieza única/tiraje/P-A), año limitado,
  descripción máx 500, imagen máx 2MB.