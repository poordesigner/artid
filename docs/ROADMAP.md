# ARTid — SaaS

Plataforma SaaS de identidad digital para obras de arte: cada obra física tiene un QR permanente que enlaza a su ficha digital, editable y con historial versionado.

## Estado

- **Origen**: evolución del prototipo estático (`arte/` dentro del repo `tachoatomico/portal`).
- **Decisión**: pasarlo a una plataforma multi-usuario con backend propio.
- **Stack**: Laravel (PHP-FPM + Postgres + Redis) sobre Coolify.
- **Repo**: `artid` (cuenta GitHub por definir).
- **Local**: `C:\opencode\proyectos\bepoor\artid`.

---

## Roadmap

### Fase 0 — Fundamentos
- [ ] Repo `artid` + cuenta GitHub nueva
- [ ] Proyecto Laravel base (`laravel new` / composer)
- [ ] Proyecto nuevo en Coolify (app PHP-FPM + postgres + redis)
- [ ] CI/deploy automático al pushear a `main`

### Fase 1 — MVP (núcleo multi-usuario)
- [ ] Modelos: `Artist` (cuenta), `Artwork`, `Edition`
- [ ] Autenticación: registro/login de artistas (Laravel Breeze/Fortify)
- [ ] Multi-tenant: todo filtrado por `artist_id`
- [ ] CRUD de obras (metadata) vía API + web
- [ ] Storage de imágenes (Cloudflare R2 / S3)
- [ ] Página pública de obra: `/{slug}` sirviendo desde la DB (reusar diseño ARTid)
- [ ] Generación de QR por obra

### Fase 2 — QR permanente + short URL
- [ ] Integración con API de Short.io (crear link por obra)
- [ ] QR apunta a short link permanente, no al hosting

### Fase 3 — Dashboard del artista
- [ ] UI de gestión: crear/editar obras, subir imágenes, ver QR
- [ ] Analytics básico: conteo de escaneos por QR

### Fase 4 — Multi-tenant + billing
- [ ] Planes (gratis/pago) y límites de obras
- [ ] Stripe: suscripciones
- [ ] Roles y permisos

### Fase 5 — Madurez
- [ ] API pública con API keys
- [ ] Webhooks
- [ ] Analytics avanzado, equipos/colaboradores

---

## Modelo de datos (boceto)

```
artists
  id, name, slug, email, password_hash, plan_id

artworks
  id, artist_id, slug (único por artista), artwork_id,
  title, year, edition, status, series, technique,
  dimensions, description, location, owner,
  short_url, qr_code, created_at, updated_at

media
  id, artwork_id, type (image/video), path, sort

editions (opcional, para ediciones numeradas)
  id, artwork_id, number, total, status

plans / subscriptions (Fase 4)
```

## API (boceto, Fase 1)

```
POST   /register
POST   /login
GET    /api/artworks            # listar las del artista autenticado
POST   /api/artworks            # crear obra
GET    /api/artworks/{id}
PUT    /api/artworks/{id}
DELETE /api/artworks/{id}
POST   /api/artworks/{id}/media # subir imagen
GET    /o/{slug}                # página pública de obra (sin auth)
```

---

## Principios

- Simplicidad > sofisticación
- Identidad de la obra > infraestructura
- Migrabilidad > dependencia tecnológica
- El QR nunca cambia; el destino sí puede cambiar

## Historial

Ver el repo estático previo (`tachoatomico/portal` → carpeta `arte/`) y el prompt original en `tachoatomico/docs/Prompt_desarrollo_QR_ShortURL_GitHub_Obras_Arte.docx`.
