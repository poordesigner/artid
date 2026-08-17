# ARTid — Facilitador de identidad digital para obras de arte

ARTid ayuda al artista a montar su propio framework de identidad (QR permanente + short URL + ficha pública), siendo el artista **dueño de su información**. El SaaS es el **configurador**.

## Estado

- **Origen**: prototipo estático (`arte/` dentro del repo `tachoatomico/portal`).
- **Decisión (revisada)**: ARTid es un **facilitador/configurador**, no un hosting centralizado.
- **Propiedad**: el artista es dueño de su GitHub, su short.io, su dominio y su ficha.
- **Stack**: Laravel + MySQL + Redis sobre Coolify.
- **Repo**: `poordesigner/artid`.
- **Local**: `C:\opencode\proyectos\bepoor\artid`.

---

## Roadmap

### Fase 0 — Fundamentos ✅
- [x] Repo `artid` + cuenta GitHub
- [x] Laravel base + docker (app/nginx/queue/scheduler/mysql/redis)
- [x] Deploy automático en Coolify

### Fase 1 — MVP (configurador)
- [x] Auth de artista (Breeze + Google OAuth)
- [ ] Vinculación GitHub (OAuth, estilo Coolify)
- [ ] Configurador: CRUD de obras → commits a GitHub (`metadata.json` + imágenes)
- [ ] Framework "ficha" open source (descargable / self-host)
- [ ] Generación de QR (apuntando al short URL del artista)

### Fase 2 — Short URL
- [ ] short.io del artista
- [ ] QR permanente → short URL

### Fase 3 — Analytics + dashboard
- [ ] Conteo de escaneos por QR
- [ ] Dashboard completo

### Fase 4 — Tokens/billing + Pro
- [ ] Créditos por consumo (no suscripción)
- [ ] Pro: storage R2 + gestión short.io

### Fase 5 — Madurez
- [ ] API pública con API keys
- [ ] Webhooks
- [ ] Analytics avanzado, equipos/colaboradores

---

## Modelo de datos (índice/caché)

```
artists
  id, name, email, github_id, github_token, credits, timestamps

artworks  (caché de lo que vive en GitHub)
  id, artist_id, artwork_id, title, github_repo, github_path,
  short_url, qr_code, status, timestamps

credits / token_usage  (Fase 4)
```

**Fuente de verdad**: repo GitHub del artista → `artworks/<ARTWORK_ID>/metadata.json` + imágenes.

---

## Principios

- Propiedad del artista > control del SaaS
- El QR nunca cambia; el destino sí puede cambiar
- Ficha pública open source y self-host
- Migrabilidad > dependencia tecnológica

---

## Decisiones abiertas

- **Repo GitHub del artista**: ¿ARTid lo crea automáticamente o el artista vincula uno existente? (TBD)

---

## Historial

- Prototipo estático: `tachoatomico/portal` → carpeta `arte/` (ver `docs/CONTEXTO-ESTATICO.md`).
- Prompt original: `tachoatomico/docs/Prompt_desarrollo_QR_ShortURL_GitHub_Obras_Arte.docx`.
