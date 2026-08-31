<?php

namespace App\Http\Controllers;

use App\Jobs\AnalyzeTicketJob;
use App\Mail\TicketReplyMail;
use App\Models\ArtistNotification;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\TicketAnalysis;
use App\Support\SupportContextBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class TicketAnalysisController extends Controller
{
    /* ---- Admin ---- */

    public function show(Request $request, SupportTicket $ticket): View
    {
        $ticket->load(['artist', 'attachments', 'analysis', 'replies']);

        $artist = $ticket->artist;
        $artist?->loadCount(['artworks', 'series']);

        return view('configuracion.tickets-show', compact('ticket', 'artist'));
    }

    public function analyze(Request $request, SupportTicket $ticket): RedirectResponse
    {
        if ($ticket->analysis && $ticket->analysis->isPending()) {
            return back()->with('status', 'analysis-pending');
        }

        $analysis = $ticket->analysis()
            ->create([
                'status' => TicketAnalysis::STATUS_PENDING,
            ]);

        dispatch(new AnalyzeTicketJob($ticket, $analysis));

        return back()->with('status', 'analysis-started');
    }

    public function reply(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:10000'],
        ]);

        $artist = $ticket->artist;

        if (! $artist) {
            return back()->with('status', 'reply-no-artist');
        }

        $reply = $ticket->replies()->create([
            'sender' => 'admin',
            'body' => $validated['body'],
            'sent_at' => now(),
        ]);

        $ticket->update(['status' => SupportTicket::STATUS_CLOSED]);

        $artist->notifications()->create([
            'type' => 'ticket_reply',
            'title' => __('Tu ticket :number tiene una respuesta', ['number' => $ticket->number]),
            'body' => $validated['body'],
            'url' => route('tickets.show', $ticket->number),
        ]);

        try {
            Mail::to($artist)->send(new TicketReplyMail($ticket, $artist, $validated['body']));
        } catch (\Throwable $e) {
            Log::error('TicketReplyMail error', [
                'ticket' => $ticket->number,
                'error' => $e->getMessage(),
            ]);

            // la reply ya quedó registrada en el hilo; el admin sabrá que el mail falló
            return back()->with('status', 'reply-saved-mail-failed');
        }

        return back()->with('status', 'reply-sent');
    }

    /* ---- API para n8n ---- */

    public function context(Request $request, SupportTicket $ticket): JsonResponse
    {
        if (! $this->validSecret($request)) {
            abort(403, 'Secret invalido');
        }

        $ticket->load(['artist']);
        $artist = $ticket->artist;

        $packTopic = (string) config('ticket_agent.topic_pack_map.'.$ticket->topic, 'otros');

        $artistDetail = $artist ? [
            'id' => $artist->id,
            'name' => $artist->name,
            'email' => $artist->email,
            'email_verified' => (bool) $artist->email_verified_at,
            'registered_at' => $artist->created_at?->toIso8601String(),
            'account_days' => $artist->created_at ? max(1, $artist->created_at->diffInDays(now())) : 0,
            'instagram' => $artist->instagram,
            'website_url' => $artist->website_url,
            'avatar' => $artist->avatar,
            'public_profile' => route('public.artist', $artist->id),
            'tokens_balance' => $artist->tokens_balance,
            'has_welcome_tokens' => $artist->welcome_tokens_claimed,
            'artworks_count' => $artist->artworks()->count(),
            'series_count' => $artist->series()->count(),
            'previous_tickets' => $artist->supportTickets()
                ->where('support_tickets.id', '!=', $ticket->id)
                ->get(['number', 'topic', 'subject', 'status', 'created_at'])
                ->map(fn ($t) => [
                    'number' => $t->number,
                    'topic' => $t->topic,
                    'subject' => $t->subject,
                    'status' => $t->status,
                    'created_at' => $t->created_at?->toIso8601String(),
                ]),
        ] : null;

        return response()->json([
            'ticket' => [
                'id' => $ticket->id,
                'number' => $ticket->number,
                'topic' => $ticket->topic,
                'topic_label' => $ticket->topicLabel(),
                'subject' => $ticket->subject,
                'message' => $ticket->message,
                'status' => $ticket->status,
                'created_at' => $ticket->created_at?->toIso8601String(),
                'attachments_count' => $ticket->attachments()->count(),
            ],
            'artist' => $artistDetail,
            'knowledge' => [
                'pack_topic' => $packTopic,
                'pack' => SupportContextBuilder::pack($packTopic),
            ],
            'brand' => (string) config('support_packs.brand', 'QRTE'),
        ]);
    }

    /* ---- Privados ---- */

    private function validSecret(Request $request): bool
    {
        $secret = (string) config('ticket_agent.secret');

        return $secret !== '' && hash_equals($secret, (string) $request->query('secret', ''));
    }
}