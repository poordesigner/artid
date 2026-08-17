# PROMPT DE DESARROLLO — ARTid (Facilitador de identidad digital para obras de arte)

ARTid es un **facilitador**: ayuda al artista a montar su propio framework de identidad digital (QR + short URL + ficha pública), siendo el artista **dueño de su información**.

---

## 1. CONCEPTO GENERAL

ARTid **no aloja** las obras del artista. Es un **configurador** que lo conecta con sus propias herramientas.

| Pieza del framework | Dueño | Rol de ARTid |
|---|---|---|
| Repo GitHub (metadata + imágenes) | Artista | Conectar vía OAuth y escribir ahí |
| Ficha pública (frontend estático) | Artista (self-host) | Plantilla open source descargable |
| Short URL (short.io) | Artista | (Pro) gestionar en su nombre |
| QR permanente | Artista | Generarlo apuntando a su short URL |
| Analytics | ARTid | Conteo de escaneos (futuro) |

**Flujo de la obra:**

```
OBRA FÍSICA
    ▼
  QR (permanente)
    ▼
Short URL (short.io del artista)
    ▼
Ficha pública (self-host, dominio del artista)
    ▼
Metadata + imágenes (repo GitHub del artista)
```

El **QR nunca cambia**; el destino del short URL sí puede cambiar (migrabilidad).

---

## 2. PRINCIPIO DE PROPIEDAD

- El artista es **dueño** de su metadata, sus imágenes, su short URL y su ficha.
- La **ficha pública** (páginas de enrutamiento y renderizado) es **open source**, descargable y montable en el servidor/dominio que el artista elija.
- El historial versionado es nativo: el **git history** del repo del artista.

---

## 3. PIEZAS DE CÓDIGO

1. **Framework "ficha" open source** — frontend estático (routing por hash + render + redirect), versión genérica del prototipo. El artista lo descarga y lo monta.
2. **Configurador SaaS (ARTid)** — auth + integración GitHub (estilo Coolify) + CRUD de obras que commitea a `artworks/<ID>/metadata.json` e imágenes en el repo del artista + generador de QR.
3. **Servicios futuros** — gestión short.io (Pro), analytics, tokens/credits.

---

## 4. ALMACENAMIENTO (evolución)

- **v1**: GitHub del artista (portafolios livianos).
- **Pro**: Cloudflare R2 (S3-compatible) para multimedia pesada.
- Recomendación del sistema: escalar a R2 (free o pago).

---

## 5. MODELO DE DATOS

La **fuente de verdad** es el repo GitHub del artista. La BD de ARTid es **índice/caché** + cuenta/créditos.

**Repo del artista (GitHub):**

```
<repo>/
  artworks/
    <ARTWORK_ID>/
      metadata.json
      <imagen>.jpg
    manifest.json
```

**BD de ARTid (índice/caché):**

```
artists
  id, name, email, github_id, github_token, credits, timestamps

artworks  (caché de lo que vive en GitHub)
  id, artist_id, artwork_id, title, github_repo, github_path,
  short_url, qr_code, status, timestamps

credits / token_usage  (Fase billing)
```

**Metadata de la obra** (esquema del `metadata.json`): `artwork_id`, `title`, `artist`, `year`, `edition`, `status`, `series`, `technique`, `dimensions`, `description`, `location`, `owner`, `image`.

**Estados (`status`)**: `created`, `exhibited`, `sold`, `transferred`, `archived`.

---

## 6. FUNCIONALIDAD

- **Auth** del artista (registro/login, Google + email/password).
- **Vinculación GitHub** (OAuth, estilo Coolify).
- **Configurador**: crear/editar/borrar obras (commit a GitHub), subir imágenes, ver/descargar QR, cambiar estado.
- **Ficha pública**: open source, self-host por el artista.
- **QR + short URL**: el QR codifica el short URL del artista.
- **Historial**: git history del repo del artista.
- **Futuro**: analytics, gestión short.io (Pro), tokens.

---

## 7. STACK TECNOLÓGICO

- **Backend**: Laravel (PHP 8.x), MySQL, Redis.
- **GitHub**: API (OAuth + contenidos/commits).
- **Short.io**: API (futuro / Pro).
- **Storage**: Cloudflare R2 (opción Pro).
- **Frontend ficha**: HTML + CSS + JS vanilla (open source).
- **Infra**: VPS + Coolify.

---

## 8. MONETIZACIÓN (tokens)

- **Créditos por consumo**, no suscripción.
- Se consumen por **acciones de configuración/gestión** (crear obra, generar QR, export, gestión Pro).
- **No** se cobra por escaneo servido (preservar la promesa del QR permanente).

---

## 9. FASES DE DESARROLLO

- **Fase 0**: fundamentos (repo, Laravel, Coolify, deploy). ✅
- **Fase 1**: MVP — auth, vinculación GitHub, configurador (CRUD → commits a GitHub), ficha open source, QR.
- **Fase 2**: short URL (short.io del artista) + QR permanente.
- **Fase 3**: analytics + dashboard completo.
- **Fase 4**: tokens/billing + Pro (R2, gestión short.io).
- **Fase 5**: API pública, webhooks, equipos.

---

## 10. CRITERIOS DE ACEPTACIÓN (MVP)

1. El artista crea cuenta y vincula su cuenta de GitHub.
2. El configurador crea una obra y commitea `metadata.json` al repo del artista.
3. La ficha pública open source se descarga y monta en un servidor propio.
4. El QR codifica el short URL del artista.
5. El artista puede exportar/tomarse su información en cualquier momento.

---

## 11. FILOSOFÍA

- **Propiedad del artista > control del SaaS**.
- Simplicidad > sofisticación.
- Migrabilidad > dependencia tecnológica.
- El QR nunca cambia; el destino sí.

---

## 12. NO INCLUIR (MVP)

- NFT, blockchain, wallets, IPFS/IPNS.
- Storage R2 (queda para Pro).
- Analytics (Fase 3).
- Billing (Fase 4).

---

## Decisiones abiertas

- **Repo GitHub del artista**: ¿ARTid lo crea automáticamente o el artista vincula uno existente? (TBD)

---

## Referencias

- Prototipo estático: `docs/CONTEXTO-ESTATICO.md`
- Prompt original (prototipo estático): `tachoatomico/docs/Prompt_desarrollo_QR_ShortURL_GitHub_Obras_Arte.docx`
- Prototipo estático: repo `tachoatomico/portal`, carpeta `arte/`
- Short URL actual: `tatomico.s.gy`
