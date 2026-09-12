# AGENTS.md — QRTE

Contexto del proyecto para agentes de IA. Leer antes de tocar código.

> Infra: `{gestor}/infraestructura/vps-01.md` (VPS-01)
> Reglas: `{gestor}/reglas/github-deploy.md`
> Ficha: perfil Laravel | esquema `solo-prod` | repo poordesigner/qrte | qrte.poordesigner.com
> Nota: las rutas `{gestor}` resuelven en el equipo del admin, no en el repo.
> Fina: `.opencode/context/project-intelligence/stack.md, patterns.md, deploy.md`

## 1. Qué es (estado actual)

**Marca actual: QRTE** (antes ARTid), por POORdesigner.com. SaaS (`qrte.poordesigner.com`): identidad digital para obras físicas con **pago único por tokens**. Cada obra obtiene **QR permanente** firmado que apunta a ficha pública `/o/{publicId}`.

- `1 token = QR + ficha básica para siempre`. Crear obra consume 1 (`Artist::canCreateArtwork()`).
- Suscripciones Paddle son legado deprecado. `/planes` vende paquetes de tokens.

## 2. Stack (resumen, detalle en `stack.md`)

Laravel 13 / PHP 8.3 / MySQL / Redis / Vite + Tailwind + Alpine. R2, Google OAuth + Breeze (`Artist`), QR firmado versionado, Paddle one-time + webhooks idempotentes, Redis queues, Chatwoot + n8n + Groq, SMTP GoDaddy por env.

## 3. Tokens

- Welcome `QRTE_WELCOME_TOKENS` default 5, una vez (`welcome_tokens_claimed`).
- Paquetes en Configuración. Checkout `TokenController@checkout` → Paddle → webhook acredita por `custom_data.token_package_id`.
- Consumo atómico `consumeToken()` + `TokenTransaction` (`grant/purchase/consume`). Panel `/tokens`.
- Admin otorga en `/configuracion/cuentas` (`grant`). Consumo real fijo 1 por obra.

## 4. Modelos principales

- `Artist`: usuario, `tokens_balance`, `is_admin`, redes, métodos `tokenBalance/canCreateArtwork/addTokens/consumeToken/grantWelcomeTokens`.
- `Artwork`: `artwork_id` display, `public_id` UUID para firma, obra/serie/técnica/exposiciones/links (máx 10).
- `Series, Technique, Exhibition, Ownership` (proveniencia cifrada `initial/transfer`).
- `TokenPackage/Function/Action/Transaction`, `SupportTicket/Attachment/Reply`, `TicketAnalysis`, `OnboardingEmail`.
- Legado sin UI: `Plan, Subscription, Payment, WebhookEvent, GitHubService`.

## 5. QR firmado

`public_id` UUID + HMAC-SHA256 versionada (`config/qrte.php`). `GET /o/{publicId}` verifica firma, sin firma 404. Perfil público `GET /artist/{id}`.

## 6. Roles

Admin `/admin` con stats. Artista `/panel`. `/dashboard` redirige por rol. Ver `patterns.md` para regla de middleware.

## 7. Paddle (env en Coolify)

Vars solo como nombres: `PADDLE_ENV, PADDLE_API_KEY, PADDLE_WEBHOOK_SECRET, PADDLE_CLIENT_TOKEN`. Activo one-time + sync paquetes. Legado suscripción sin UI. Webhook `POST /webhooks/paddle` idempotente por `event_id`.

## 8. Páginas y flujo

`/panel → /artworks` crear con 1 token → `/artworks/{id}` QR + expos + propiedad. `/tokens, /profile, /configuracion, /planes, /tickets, /ayuda` (12 secciones, mismo contenido log/no-log).

## 9. Soporte (resumen, split a `docs/` en fase 3b)

Funcional: widget Chatwoot a inbox único + tickets `TKT-####` privados con adjuntos en R2 + hilo con reply por mail. IA: bot `qrte-support-agent` por packs + triage `qrte-ticket-analyzer` con botón Analizar que solo sugiere borrador, nada auto-envía ni auto-cierra. Onboarding manual por secuencias día 0/3/7/14/30.

## 10. Localización

Selector ES/EN. Todo texto blade en `__()` con clave en `lang/es.json` y `lang/en.json`. Validar JSON.

## 11. Deploy (detalle en `deploy.md`)

Repo `poordesigner/qrte`, `solo-prod`, `VPS-01`, Auto-Deploy ON, hook `migrate --force` a potestad del admin. Solo push y esperar aviso.

## 12. Assets

Logos en `public/img/` (`navbar, logo, favicon, logo_box`). `logo_box` para Paddle y R2 público.

## 13. Convenciones (detalle en `patterns.md`)

Tailwind + Alpine, `x-breadcrumb`, obra a WEBP ≤300KB, `TKT-####`, acceso dueño o admin 403, `__()` en ambos JSON, idempotencias Paddle/welcome.

## 14. Docs y punteros

- `docs/historia/PROMPT.md, ROADMAP.md, CONTEXTO-ESTATICO.md`: historia, no spec.
- Fase 3b: `docs/soporte-ia.md + docs/onboarding.md` para el detalle de sección 9.


