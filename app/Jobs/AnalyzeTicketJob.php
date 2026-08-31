<?php

namespace App\Jobs;

use App\Models\SupportTicket;
use App\Models\TicketAnalysis;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnalyzeTicketJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 90;

    public function __construct(
        public SupportTicket $ticket,
        public TicketAnalysis $analysis,
    ) {}

    public function handle(): void
    {
        $url = (string) config('ticket_agent.n8n_webhook_url');
        $secret = (string) config('ticket_agent.secret');

        if (! $url || ! $secret) {
            $this->failTicket('ticket_agent.no_config');

            return;
        }

        $this->analysis->update([
            'status' => TicketAnalysis::STATUS_PROCESSING,
        ]);

        try {
            $response = Http::timeout($this->timeout)
                ->post($url, [
                    'secret' => $secret,
                    'ticket_id' => $this->ticket->id,
                    'ticket_number' => $this->ticket->number,
                    'context_url' => route('api.tickets.context', $this->ticket),
                    'llm_url' => route('support.llm'),
                ]);

            if (! $response->successful()) {
                $this->failTicket('ticket_agent.agent_http_'.$response->status());

                return;
            }

            $data = $response->json() ?? [];

            // n8n con respondToWebhook "allIncomingItems" devuelve [{...}]; normalizar al primer item.
            if (array_is_list($data) && isset($data[0]) && is_array($data[0])) {
                $data = $data[0];
            }

            $summary = isset($data['summary']) ? (string) $data['summary'] : null;
            $priority = in_array($data['priority'] ?? null, ['normal', 'alta'], true) ? $data['priority'] : null;
            $draft = isset($data['draft_reply']) ? (string) $data['draft_reply'] : null;
            $actions = isset($data['suggested_actions']) && is_array($data['suggested_actions'])
                ? array_values(array_filter((array) $data['suggested_actions'], fn ($a) => is_string($a) && trim($a) !== ''))
                : [];

            $this->analysis->update([
                'status' => TicketAnalysis::STATUS_COMPLETED,
                'summary' => $summary,
                'priority' => $priority ?? TicketAnalysis::PRIORITY_NORMAL,
                'draft_reply' => $draft,
                'suggested_actions' => $actions ?: null,
                'analysis' => $data,
                'model' => isset($data['model']) ? (string) $data['model'] : null,
                'analyzed_at' => now(),
                'error' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error('TicketAnalysisAgent error', [
                'ticket' => $this->ticket->number,
                'error' => $e->getMessage(),
            ]);

            $this->failTicket($e->getMessage());
        }
    }

    private function failTicket(string $error): void
    {
        $this->analysis->update([
            'status' => TicketAnalysis::STATUS_FAILED,
            'error' => $error,
        ]);
    }
}