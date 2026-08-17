# CONTEXTO — Prototipo estático ARTid

Documento de referencia del prototipo estático de ARTid, construido dentro del repo `tachoatomico/portal`, carpeta `arte/`. Sirve de base para el desarrollo del SaaS.

---

## 1. Ubicación y despliegue

- **Repo**: `tachoatomico/portal` (rama `main`)
- **Carpeta**: `arte/`
- **Hosting**: Coolify (sitio estático) → `https://tachoatomico.poordesigner.com/arte/`
- **Short URL**: `tatomico.s.gy` (Short.io), apunta a la página intermedia `redirect.html`

## 2. Estructura de archivos

```
arte/
├── index.html            # Shell de la app (ficha de obra)
├── css/
│   └── style.css         # Diseño oscuro minimalista, responsive
├── js/
│   └── app.js            # Routing por hash + carga dinámica de metadata
├── redirect.html         # Página intermedia: ?art=<ID> → #/art/<ID>
├── obras.html            # Lista de obras con sus QR
├── editor.html           # Editor de metadata.json (descargar/copiar)
└── artworks/
    ├── manifest.json     # Lista de artwork_id disponibles
    └── <ARTWORK_ID>/
        ├── metadata.json # Metadata de la obra
        └── <imagen>.jpg  # Imagen de la obra
```

## 3. Cómo funciona

### 3.1 Routing (hash)
- URL pública: `https://.../arte/#/art/<ARTWORK_ID>`
- `app.js` lee `window.location.hash`, extrae el último segmento (en mayúsculas) como `artwork_id`.
- El hash no se envía al servidor → funciona en cualquier hosting estático sin config.

### 3.2 Carga dinámica
1. Identifica `artwork_id` desde el hash.
2. Hace `fetch('artworks/<id>/metadata.json')` (ruta **relativa**).
3. Renderiza la ficha: imagen, título, artista·año·edición, estado, y campos opcionales.
4. Si no hay hash o no existe la obra → pantalla "obra no encontrada".

### 3.3 Página intermedia (redirect.html)
- Recibe `?art=<ID>` (o `?id=`, `?obra=`) y redirige con `window.location.replace('./#/art/' + art)`.
- Tiene **modo debug**: `?debug=1` muestra cómo llega la info (href, search, hash, params) sin redirigir.
- Permite un único short link y solo cambia el parámetro en el QR.

### 3.4 Lista de obras (obras.html)
- Lee `artworks/manifest.json`.
- Por cada obra, muestra título + QR (codifica `https://tatomico.s.gy?art=<ID>`).
- QR generado vía API externa (`api.qrserver.com`).

### 3.5 Editor (editor.html)
- Carga la lista de obras y muestra su `metadata.json` en un formulario editable.
- Botones: "Descargar JSON" y "Copiar JSON".
- No guarda en GitHub (sitio estático): el cambio se aplica manualmente (reemplazar archivo + commit).

## 4. Schema de metadata.json

```json
{
  "artwork_id": "NATURAI-3.0",
  "title": "naturAI 3.0",
  "artist": "@tachoatomico",
  "year": 2025,
  "edition": "1/1",
  "status": "created",
  "series": "naturAI",
  "description": "...",
  "image": "naturai-3-0.jpg"
}
```

Campos soportados en el render (opcionales, se omiten si no existen):
- `series`, `technique`, `dimensions`, `description`, `location`, `owner`.

Estados (`status`): `created`, `exhibited`, `sold`, `transferred`, `archived`.

## 5. Convención de artwork_id

- MAYÚSCULAS, guiones para separar palabras/versiones.
- Sin tildes ni caracteres especiales (ASCII).
- Ejemplos: `INTERCAMBIOS`, `NATURAI-3.0`, `OPUESTOS-DISTORSION`, `KIEP-1.0`.

## 6. Obras registradas

| artwork_id | título |
|---|---|
| INTERCAMBIOS | intercambios |
| OPUESTOS-DISTORSION | opuestos/distorsión |
| KIEP-1.0 | KIEP 1.0 |
| KIEP-2.0 | KIEP 2.0 |
| NATURAI-4.0 | naturAI 4.0 |
| NATURAI-3.0 | naturAI 3.0 |
| NATURAI-2.0 | naturAI 2.0 |
| NATURAI-1.0 | naturAI 1.0 |
| THE-GIFT-1.0 | the gift 1.0 |

## 7. Diseño visual

- Fondo oscuro (`#0d0d0d`), texto claro, acento fucsia (`#ff0066`).
- Imagen protagonista + metadata secundaria.
- Minimalista, artístico, responsive, orientado a móvil/QR.
- Sin frameworks pesados (HTML + CSS + JS vanilla + Google Fonts Inter).

## 8. Decisiones clave

- **Rutas relativas** (no absolutas): la app funciona bajo cualquier subpath (`/arte/`).
- **Hash routing** por portabilidad (sin config de servidor).
- **Un solo short link** + parámetro `?art=` (en vez de un link por obra).
- **GitHub como almacén/versionado** en el prototipo (se reemplaza por DB en el SaaS).

## 9. Limitaciones del prototipo (motivan el SaaS)

- Un solo artista (sin cuentas ni multi-tenant).
- Metadata en JSON estático (edición manual).
- Sin storage propio (imágenes en el repo).
- Sin analytics ni historial automático.
- Sin autenticación ni permisos.

---

## Relación con el SaaS

Este prototipo valida: la mecánica QR → short URL → ficha, el diseño de ficha pública, la convención de IDs y el flujo de metadata. El SaaS (`docs/PROMPT.md`) toma estos conceptos y los escala a multi-usuario con backend, base de datos y storage propios.
