<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportTicketAttachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function index(Request $request): View
    {
        $tickets = $request->user()
            ->supportTickets()
            ->withCount('attachments')
            ->latest()
            ->get();

        return view('tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        return view('tickets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'topic' => ['required', 'in:'.implode(',', SupportTicket::TOPICS)],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'attachments' => ['nullable', 'array', 'max:3'],
            'attachments.*' => ['file', 'mimes:jpeg,jpg,png,webp,gif,pdf', 'max:5120'],
        ]);

        $ticket = DB::transaction(function () use ($request, $validated) {
            $ticket = SupportTicket::create([
                'artist_id' => $request->user()->id,
                'topic' => $validated['topic'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'status' => SupportTicket::STATUS_OPEN,
            ]);

            $ticket->forceFill(['number' => 'TKT-'.str_pad((string) $ticket->id, 4, '0', STR_PAD_LEFT)])->save();

            if ($files = $request->file('attachments')) {
                foreach ($files as $file) {
                    $path = 'support_tickets/'.$ticket->number.'/'.Str::random(24).'.'.$file->extension();
                    Storage::disk('r2')->put($path, $file->get());

                    $ticket->attachments()->create([
                        'disk' => 'r2',
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
            }

            return $ticket;
        });

        return redirect()
            ->route('tickets.index')
            ->with('status', __('Ticket creado.'))
            ->with('ticket_number', $ticket->number);
    }

    public function show(Request $request, string $number): View
    {
        $ticket = SupportTicket::with('attachments')->where('number', $number)->firstOrFail();

        $this->authorizeAccess($request->user(), $ticket);

        return view('tickets.show', compact('ticket'));
    }

    public function attachment(Request $request, string $number, SupportTicketAttachment $attachment)
    {
        abort_unless($attachment->ticket?->number === $number, 404);

        $this->authorizeAccess($request->user(), $attachment->ticket);

        return Storage::disk($attachment->disk)->response($attachment->path, $attachment->original_name);
    }

    /* ---- Admin ---- */

    public function adminIndex(Request $request): View
    {
        $tickets = SupportTicket::with(['artist', 'attachments', 'analysis'])->latest();

        if ($request->query('status') === 'open') {
            $tickets->where('status', SupportTicket::STATUS_OPEN);
        } elseif ($request->query('status') === 'closed') {
            $tickets->where('status', SupportTicket::STATUS_CLOSED);
        }

        $tickets = $tickets->limit(100)->get();

        return view('configuracion.tickets', compact('tickets'));
    }

    public function adminUpdateStatus(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:open,closed'],
        ]);

        $ticket->update(['status' => $validated['status']]);

        return back()->with('status', __('Estado del ticket actualizado.'));
    }

    private function authorizeAccess($user, SupportTicket $ticket): void
    {
        abort_unless($user->isAdmin() || $ticket->artist_id === $user->id, 403);
    }
}