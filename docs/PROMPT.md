# PROMPT DE DESARROLLO — ARTid (SaaS)

Sistema SaaS de identidad digital para obras de arte — QR + Short URL + ficha digital editable + historial versionado.

---

## 1. CONCEPTO GENERAL

Desarrollar una plataforma web multi-usuario (SaaS) para que **artistas** asocien cada una de sus **obras físicas** con una **ficha digital pública y actualizable**.

El objetivo es que cada obra física tenga un **QR permanente** impreso en ella durante años, mientras que la información digital asociada pueda **actualizarse sin cambiar el QR**.

Arquitectura:

```
OBRA FÍSICA
    ▼
  QR
    ▼
Short URL (permanente, administrado vía Short.io)
    ▼
Aplicación web (ARTid)
    ▼
Backend + Base de datos (Laravel + Postgres)
    ▼
Metadata + imágenes + historial
```

El QR **NO** debe apuntar directamente al backend ni al storage. Debe apuntar a una URL corta (`https://tatomico.s.gy?art=<ARTWORK_ID>`), cuyo destino puede cambiar en el futuro sin modificar el QR.

## 2. OBJETIVO

Pasar del prototipo estático (un sitio que lee `metadata.json` desde GitHub) a una **plataforma multi-usuario** donde:

- Varios artistas tengan **cuenta propia**.
- Cada artista gestione sus obras (crear, editar, subir imágenes, ver QR).
- Cada obra tenga una **ficha pública** con URL amigable.
- Cada obra tenga un **QR permanente** asociado.
- El historial de cambios quede **versionado**.

## 3. DIFERENCIAS CON EL PROTOTIPO

| Prototipo (estático) | ARTid (SaaS) |
|---|---|
| Un solo artista | Multi-tenant (varios artistas) |
| `metadata.json` en GitHub | Metadata en base de datos |
| Sin autenticación | Registro/login de artistas |
| Edición manual de JSON | Dashboard de gestión |
| Sin storage propio | Storage de imágenes (R2/S3) |
| Sin analytics | Conteo de escaneos por QR |

## 4. ROLES Y MULTI-TENANCY

- **Artista**: crea y gestiona sus propias obras. Solo ve/edita lo suyo.
- **Admin**: gestiona artistas, planes y la plataforma.
- El aislamiento se garantiza con `artist_id` en todas las entidades del artista.

## 5. MODELO DE DATOS (boceto)

```
artists
  id, name, slug, email, password, plan_id, timestamps

artworks
  id, artist_id, slug, artwork_id,
  title, year, edition, status, series,
  technique, dimensions, description,
  location, owner, short_url, qr_code, timestamps

media
  id, artwork_id, type (image|video), path, sort_order

plans / subscriptions (fase billing)
```

### Estados de la obra (`status`)

- `created`
- `exhibited`
- `sold`
- `transferred`
- `archived`

El estado se modifica **solo** desde el dashboard, sin tocar el QR.

### Campos de metadata (extensibles)

`artwork_id`, `title`, `artist`, `year`, `edition`, `status`, `series`, `technique`, `dimensions`, `description`, `location`, `owner`, `image(s)`.

## 6. FUNCIONALIDAD

### 6.1 Autenticación
- Registro y login de artistas.
- Protección de rutas de gestión (middleware auth).

### 6.2 Gestión de obras (dashboard)
- Listar obras del artista.
- Crear obra (formulario de metadata).
- Editar obra.
- Subir imagen(es) / video.
- Ver y descargar el QR.
- Cambiar estado.

### 6.3 Ficha pública de obra
- URL amigable: `/{slug}` o `/o/{artwork_id}`.
- Muestra imagen protagonista + metadata secundaria + estado.
- Responsive, elegante, orientada a QR (reusar diseño del prototipo).

### 6.4 QR + Short URL
- Generar QR por obra (librería server o cliente).
- El QR codifica el short URL permanente.
- Integración con Short.io para crear el short link automáticamente.

### 6.5 Historial
- Versionado de cambios (audit log o historial por obra).

## 7. STACK TECNOLÓGICO

- **Backend**: Laravel (PHP 8.x)
- **Base de datos**: PostgreSQL
- **Cache/colas**: Redis
- **Storage**: Cloudflare R2 (S3-compatible) para imágenes/videos
- **Frontend**: Blade + Tailwind (o Livewire/Inertia) — dashboards
- **Páginas públicas**: Blade (SEO-friendly, sin framework pesado)
- **Infra**: VPS + Coolify (proyecto nuevo)
- **Short URL**: Short.io API

## 8. API (boceto, Fase 1)

```
POST   /register
POST   /login
POST   /logout
GET    /api/artworks             # del artista autenticado
POST   /api/artworks
GET    /api/artworks/{id}
PUT    /api/artworks/{id}
DELETE /api/artworks/{id}
POST   /api/artworks/{id}/media  # subir imagen/video
GET    /o/{artwork_id}           # pública, sin auth
```

## 9. FASES DE DESARROLLO

- **Fase 0**: fundamentos (repo, proyecto Laravel, Coolify, deploy).
- **Fase 1**: MVP — auth, CRUD de obras, storage, página pública, QR.
- **Fase 2**: short URL (Short.io) + QR permanente.
- **Fase 3**: dashboard completo + analytics básico.
- **Fase 4**: billing (Stripe), planes y límites.
- **Fase 5**: API pública, webhooks, equipos.

## 10. CRITERIOS DE ACEPTACIÓN (MVP)

1. Un artista se registra, inicia sesión y crea una obra.
2. La obra genera una ficha pública en `/{slug}` con su imagen y metadata.
3. Subir una imagen y editarla se refleja al recargar.
4. El QR de la obra codifica el short URL permanente.
5. Un artista **no** puede ver ni editar obras de otro artista.
6. El estado de la obra se cambia desde el dashboard sin modificar el QR.

## 11. FILOSOFÍA

- Simplicidad > sofisticación.
- Mantenibilidad > descentralización.
- Identidad de la obra > infraestructura.
- Migrabilidad > dependencia tecnológica.

## 12. NO INCLUIR EN EL MVP

- NFT, blockchain, wallets, IPFS/IPNS.
- Panel administrativo complejo.
- Billing (se deja para Fase 4).

---

## Referencias

- Prompt original (prototipo estático): `tachoatomico/docs/Prompt_desarrollo_QR_ShortURL_GitHub_Obras_Arte.docx`
- Prototipo estático: repo `tachoatomico/portal`, carpeta `arte/`
- Short URL actual: `tatomico.s.gy`
