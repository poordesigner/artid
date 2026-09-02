# QRTE — Mapa de Agentes AI First

> Documento de referencia: agentes actuales y propuestos, según best practices AI First.
> Última actualización: 2026-09-01

---

## Agentes Existentes (implementados)

### 1. Bot de Soporte — Chatwoot
- **Estado:** ✅ Implementado
- **Ubicación:** Workflow n8n `qrte-support-agent` + `SupportContextController`
- **Función:** Responde automáticamente en el inbox de Chatwoot ("Soporte QRTE") con contexto por tema: cuenta, obras, facturación, historial, QR/ficha, configuración, otros. Usa paquetes de conocimiento cacheados (`config/support_packs.php`) y rota entre `router_model` / `chat_model` configurados por admin.
- **Tecnología:** n8n + Groq (LLM) + API contextual Laravel
- **Alcance:** Canal de soporte primario para usuarios logueados y no logueados

### 2. Analizador de Tickets con IA
- **Estado:** ✅ Implementado
- **Ubicación:** `AnalyzeTicketJob` + n8n `qrte-ticket-analyzer` + modelo `TicketAnalysis`
- **Función:** Triage automático de tickets de soporte al crear o clickear "Analizar con IA" en admin: resumen, prioridad (normal/alta), contexto del usuario, borrador de respuesta y acciones sugeridas. Todo asist-first — el admin decide.
- **Tecnología:** Groq (LLM) vía webhook n8n, respuestas síncronas
- **Modelo:** `TicketAnalysis` con estados pending → processing → completed/failed

### 3. Widget de Chat en Vivo
- **Estado:** ✅ Implementado
- **Ubicación:** `x-chatwoot-widget` en `layouts/app`, `layouts/public`, `welcome`
- **Función:** Canal de chat en tiempo real en todas las páginas (landing + dashboard). Conecta al inbox de soporte en `cwoot.poordesigner.com`.
- **Tecnología:** Chatwoot widget JavaScript

### 4. Configuración Central de IA
- **Estado:** ✅ Implementado
- **Ubicación:** `AiConfigController` + `AppSetting` + tab "IA" en `/configuracion`
- **Función:** Admin configura modelos Groq (`router_model`, `chat_model`, `backup_model`) desde el panel. Endpoint `GET /api/support/llm` expone la config para que los workflows n8n la consulten dinámicamente.
- **Tecnología:** Laravel + Groq API + cache 5 min

---

## Agentes Propuestos — Producto y Experiencia

### 5. Onboarding Inteligente
- **Función:** Detectar progreso del artista nuevo después del registro (perfil completado, primera obra creada, tokens usados) y enviar nudges contextuales por Chatwoot o notificación push: "Creá tu primera obra con tus 5 tokens gratis", "Completá tu statement para que tu perfil público se vea mejor". Evita que el usuario se pierda tras el registro.
- **Trigger:** Registro + transcurridos 1h/24h/72h sin acción
- **Canal:** Chatwoot + email
- **Prioridad:** Alta

### 6. Enriquecimiento de Metadata de Obras
- **Función:** Al subir imagen de obra, analizarla con modelo de visión (Groq vision o similar) para sugerir automáticamente: técnicas, colores dominantes, estilo artístico, dimensión estimada, descripción. Reduce carga de input y mejora la ficha pública.
- **Trigger:** Upload de imagen en formulario de obra
- **Canal:** Sugerencia inline (no auto-aplica)
- **Prioridad:** Media

### 7. Auditor de Calidad de Fichas
- **Función:** Revisar periódicamente todas las fichas públicas: detectar obras sin imagen, sin descripción, sin serie, sin exposiciones, enlaces rotos. Generar reporte al admin con acciones sugeridas y dar feedback proactivo al artista por email ("Tu obra X necesita una descripción para completar su ficha").
- **Trigger:** Job programado (diario/semanal)
- **Canal:** Email al artista + dashboard admin
- **Prioridad:** Media

### 8. Generador de Descripciones IA
- **Función:** Asistir al artista al crear o editar una obra: sugiere descripción basándose en título, técnicas, dimensiones, imagen y contexto de la serie. Opcional — el artista puede aceptar, editar o ignorar. Mejora la calidad de fichas públicas y SEO.
- **Trigger:** Formulario de obra (campo descripción vacío o al clickear "Sugerir")
- **Canal:** Sugerencia inline en formulario
- **Prioridad:** Media

### 9. Scraper de Exposiciones
- **Función:** Dado un nombre de exposición y ubicación, buscar automáticamente fechas, galería, curador y links relevantes en la web. Autocompletar el formulario de exposición del artista. Ahorra tiempo y enriquece el historial de la obra.
- **Trigger:** Formulario "+ Expo" con nombre y ubicación
- **Canal:** Sugerencia inline
- **Prioridad:** Baja

### 10. Guard de Seguridad de QR
- **Función:** Verificación periódica de que los QR firmados siguen siendo válidos: cadena HMAC intacta, archivos R2 existes, fichas accesibles. Alertar al admin si detecta inconsistencias (obra sin imagen, firma corrupta, UUID huérfano).
- **Trigger:** Job programado (diario)
- **Canal:** Email admin + log
- **Prioridad:** Alta

---

## Agentes Propuestos — Analytics y Operaciones

### 11. Analítica de Consumo de Tokens
- **Función:** Monitorear patrones de uso: artistas que no consumieron welcome tokens, paquetes más populares, tasa de conversión registro→primera obra, churn, LTV. Dashboard de admin con alertas y sugerencias (ej: "Artista Y creó 0 obras en 30 días —¿ofrecer tokens extra?").
- **Trigger:** Dashboard + job semanal
- **Canal:** Dashboard admin + email resumen
- **Prioridad:** Alta

### 12. Monitor de Webhooks
- **Función:** Monitorear salud de webhooks Paddle y n8n: reintentos, fallos, latencia, duplicados. Dashboard con métricas y alertas por exceso de fallos. Evita que queden pagos sin acreditar o tickets sin analizar.
- **Trigger:** Cada webhook recibido + job de health check
- **Canal:** Dashboard admin + alerta crítica
- **Prioridad:** Alta

### 13. Conciliador de Saldos
- **Función:** Reconciliación nocturna: comparar `tokens_balance` en `artists` con el historial acumulado de `TokenTransactions`. Detectar discrepancias (créditos fantasma, consumos sin registrar, diferencias por race condition). Reporte al admin con diferencias y acciones sugeridas.
- **Trigger:** Job programado (nocturno)
- **Canal:** Email admin + log
- **Prioridad:** Alta

---

## Agentes Propuestos — Mercadeo y Lanzamiento

### 14. Generador de Contenido para Redes
- **Función:** Crear posts para Instagram, TikTok, LinkedIn y X (Twitter) adaptados al público artístico. Sugiere copy, hashtags, formato y mejor horario. Contenido tipo: "Así se ve una ficha de obra con QRTE", "Un artista registró su primera obra en 2 minutos", tutoriales rápidos, tips de identidad digital para arte. Admin aprueba o edita antes de publicar.
- **Trigger:** Programación semanal o bajo demanda
- **Canal:** Instagram, TikTok, LinkedIn, X
- **Prioridad:** Alta para lanzamiento

### 15. Email Marketer — Secuencias de Onboarding
- **Función:** Automatizar secuencias de email post-registro:
  - **Día 0:** Bienvenida + "Creá tu primera obra"
  - **Día 3:** Recordatorio de tokens gratis
  - **Día 7:** "¿Necesitás ayuda? Mirá nuestro tutorial"
  - **Día 14:** "Artistas como vos ya crearon X obras"
  - **Día 30:** "Comprá más tokens y seguí creando"
  
  Detecta si el usuario ya completó la acción y salta al siguiente paso. Evita spam si el usuario ya está activo.
- **Trigger:** Registro + calendario relativo
- **Canal:** Email (SMTP GoDaddy o servicio dedicado)
- **Prioridad:** Crítica para lanzamiento

### 16. SEO Auditor + Content Writer
- **Función:** Analizar las páginas públicas (fichas de obra, perfil de artista, `/planes`, `/caracteristicas`, `/ayuda`) y sugerir mejoras SEO: meta titles, descriptions, Open Graph tags, estructura de headings, keywords. Generar artículos de blog optimizados para búsquedas como "cómo crear identidad digital para obras de arte", "QR permanente para cuadros", "certificado de autenticidad para artistas".
- **Trigger:** Auditoría mensual + creación de contenido bajo demanda
- **Canal:** Blog + optimización on-page
- **Prioridad:** Alta

### 17. Programa de Referidos
- **Función:** Cuando un artista comparte su ficha pública o QR, el receptor ve un banner "¿Sos artista? Registrate y obtené X tokens gratis". El artista que refiere recibe tokens extra. El agente trackea referidos, envía emails de agradecimiento y sugiere incentivos basados en el patrón de uso.
- **Trigger:** Share de ficha + registro por link de referido
- **Canal:** Web (banner) + email + tokens
- **Prioridad:** Media (requiere base de usuarios)

### 18. Social Proof Generator
- **Función:** Detectar artistas activos (obras creadas, fichas públicas con tráfico) y solicitar testimonios o case studies. Generar contenido tipo: "María de Medellín ya tiene 12 obras con QRTE", "El Museo X usa QRTE para autenticar piezas". Publicar en landing y redes. Pide permiso antes de usar nombre/imagen.
- **Trigger:** Artista alcanza umbral de actividad (ej: 5+ obras)
- **Canal:** Email de solicitud + publicación en landing/redes
- **Prioridad:** Alta para lanzamiento

### 19. Monitor de Competencia y Tendencias
- **Función:** Monitorear competidores (Artnet, Artory, plataformas de certificación de arte) y tendencias del mercado artístico. Alertar sobre: nuevas features de competidores, cambios de precios, tendencias de autenticación (NFT, blockchain, certificados físicos), eventos de arte relevantes. Sugiere posicionamiento.
- **Trigger:** Monitoreo continuo (semanal)
- **Canal:** Email digest + dashboard admin
- **Prioridad:** Baja

### 20. A/B Tester de Landing Pages
- **Función:** Probar variantes de las landing (`/planes`, `/caracteristicas`, home): diferentes CTAs, colores, textos, ubicación del precio, prueba social. Medir conversión de registro y sugerir el ganador. Ejemplo: "El botón 'Empezá gratis' convierte 23% más que 'Registrarse'".
- **Trigger:** Bajo demanda cuando hay tráfico suficiente
- **Canal:** Dashboard admin + reportes
- **Prioridad:** Baja (requiere tráfico)

### 21. Gestor de Eventos y Ferias de Arte
- **Función:** Identificar ferias de arte, exposiciones y eventos relevantes (ARCO, ArtBo, Feria del Millón, Art Basel, etc.). Generar materiales promocionales (flyers con QR de demo, tarjetas), sugerir estrategia de presencia y trackear resultados post-evento.
- **Trigger:** Calendario de eventos + bajo demanda
- **Canal:** Materiales descargables + email + planificación
- **Prioridad:** Baja (depende de estrategia presencial)

---

## Matriz de Prioridad

| # | Agente | Categoría | Prioridad Lanzamiento | Esfuerzo |
|---|--------|-----------|----------------------|----------|
| 1 | Bot de Soporte | Producto | ✅ Hecho | — |
| 2 | Analizador de Tickets | Producto | ✅ Hecho | — |
| 3 | Widget Chat | Producto | ✅ Hecho | — |
| 4 | Config IA | Producto | ✅ Hecho | — |
| 15 | Email Marketer | Mercadeo | 🔴 Crítica | Medio |
| 18 | Social Proof | Mercadeo | 🔴 Alta | Bajo |
| 10 | Guard QR | Producto | 🔴 Alta | Bajo |
| 11 | Analítica Tokens | Ops | 🔴 Alta | Medio |
| 12 | Monitor Webhooks | Ops | 🔴 Alta | Medio |
| 13 | Conciliador Saldos | Ops | 🔴 Alta | Medio |
| 14 | Contenido Redes | Mercadeo | 🟡 Alta | Medio |
| 16 | SEO Writer | Mercadeo | 🟡 Alta | Medio |
| 5 | Onboarding | Producto | 🟡 Alta | Medio |
| 17 | Referidos | Mercadeo | 🟡 Media | Alto |
| 6 | Metadata IA | Producto | ⚪ Media | Medio |
| 7 | Auditor Fichas | Producto | ⚪ Media | Bajo |
| 8 | Descripciones IA | Producto | ⚪ Media | Bajo |
| 19 | Competencia | Mercadeo | ⚪ Baja | Bajo |
| 20 | A/B Testing | Mercadeo | ⚪ Baja | Medio |
| 21 | Eventos | Mercadeo | ⚪ Baja | Alto |
| 9 | Scraper Expo | Producto | ⚪ Baja | Alto |

---

## Roadmap Sugerido

### Fase 1 — Pre-lanzamiento (semana 1-2)
- [ ] **Email Marketer** (15): secuencias post-registro con welcome tokens
- [ ] **Social Proof** (18): primeros testimonios para la landing

### Fase 2 — Lanzamiento (semana 3-4)
- [ ] **Contenido Redes** (14): calendario de contenido Instagram/TikTok/X
- [ ] **Guard QR** (10): monitoreo de integridad de fichas públicas
- [ ] **Analítica Tokens** (11): dashboard de consumo para admin

### Fase 3 — Crecimiento (mes 2-3)
- [ ] **SEO Writer** (16): blog + optimización de páginas públicas
- [ ] **Onboarding** (5): nudges para activar usuarios dormidos
- [ ] **Monitor Webhooks** (12): salud de pagos y flujos automáticos
- [ ] **Conciliador Saldos** (13): integridad financiera

### Fase 4 — Madurez (mes 4+)
- [ ] **Referidos** (17): programa de referidos con tokens
- [ ] **Metadata IA** (6): enriquecimiento automático de obras
- [ ] **Auditor Fichas** (7): calidad de fichas públicas
- [ ] **Competencia** (19): monitoreo de mercado

---

*Documento vivo — actualizar conforme se implementen agentes.*
