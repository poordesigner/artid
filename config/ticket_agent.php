<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Agente de tickets (gestor de IA)
    |--------------------------------------------------------------------------
    |
    | Orquestado por n8n: el workflow "qrte-ticket-analyzer" consume el
    | contexto expuesto por GET /api/tickets/{id}/context y devuelve el
    | análisis (resumen, prioridad, borrador, acciones) que Laravel persiste
    | en `ticket_analyses`. n8n lee el modelo Groq desde /api/support/llm.
    |
    */

    'n8n_webhook_url' => env('TICKET_AGENT_N8N_WEBHOOK_URL'),

    'secret' => env('TICKET_AGENT_WEBHOOK_SECRET'),

    'timeout' => (int) env('TICKET_AGENT_TIMEOUT', 60),

    /*
    | Mapeo de temas de ticket (SupportTicket::TOPICS) -> packs de
    | support_packs.php que el agente usa como conocimiento para el borrador.
    */
    'topic_pack_map' => [
        'cuenta' => 'cuenta',
        'obras' => 'obras',
        'facturacion' => 'facturacion',
        'tecnico' => 'configuracion',
        'otro' => 'otros',
    ],

];