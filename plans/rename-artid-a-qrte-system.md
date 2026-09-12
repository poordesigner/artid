# System — renombre total ARTid → QRTE (FASE 1 HECHA, resto bloqueado)

- Track: `System`. Readiness: `blocked` para fases 2-4. No ejecutar sin autorización por fase.
- Objetivo: eliminar todo rastro `qrte` y dejar `qrte` en código, repo, VPS e integraciones.
- Fase 1 completada 2026-09-11: repo GitHub `poordesigner/qrte` + remoto local + redeploy validado.
- Fases con autorización separada: 2) código `config/qrte.php`, envs `QRTE_*`, textos y rutas, 3) VPS Coolify dominios + deploy, 4) Paddle productos + Chatwoot + R2 público + `AGENTS.md` título y `REGISTRO.md`.
- Verificación por fase: push + deploy validado por admin antes de la siguiente. Nada de fases juntas.
- Gaps cerrados 2026-09-11: cero `qrte` en `database/` —sin migración de datos— y `qrte.poordesigner.com` ya resuelve al VPS igual que `qrte.`. Queda solo reconfigurar dominio en Coolify + `APP_URL`.


