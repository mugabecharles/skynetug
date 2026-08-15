<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Mail\TicketOpened;
use App\Mail\TicketReplyNotification;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\TicketAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = Auth::user()->supportTickets()
            ->latest()
            ->paginate(15);

        return view('dashboard.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('dashboard.tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject'     => ['required', 'string', 'max:200'],
            'category'    => ['required', 'in:technical,billing,sales,general'],
            'priority'    => ['required', 'in:low,medium,high,urgent'],
            'description' => ['required', 'string', 'min:10'],
            'attachments.*' => ['nullable', 'file', 'max:10240'], // 10 MB
        ]);

        $ticketNumber = 'TKT-' . strtoupper(Str::random(8));

        $ticket = Auth::user()->supportTickets()->create([
            'ticket_number' => $ticketNumber,
            'subject'       => $request->subject,
            'category'      => $request->category,
            'priority'      => $request->priority,
            'description'   => $request->description,
            'status'        => 'open',
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-attachments', 'public');
                TicketAttachment::create([
                    'ticket_id'     => $ticket->id,
                    'filename'      => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type'     => $file->getMimeType(),
                    'file_size'     => $file->getSize(),
                ]);
            }
        }

        // Notify all support agents and admins by email
        $staffEmails = User::whereIn('role', ['super_admin', 'support_agent'])
            ->where('is_active', true)
            ->pluck('email')
            ->toArray();

        foreach ($staffEmails as $email) {
            $staffUser = User::where('email', $email)->first();
            if ($staffUser) {
                Mail::to($email)->queue(new TicketOpened($ticket, $staffUser));
            }
        }

        return redirect()->route('dashboard.tickets.show', $ticket->id)
            ->with('success', "Ticket #{$ticketNumber} created. Our team will respond shortly.");
    }

    public function show(int $id)
    {
        $ticket = Auth::user()->supportTickets()
            ->with(['replies.user', 'replies.attachments'])
            ->findOrFail($id);

        return view('dashboard.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, int $id)
    {
        $ticket = Auth::user()->supportTickets()
            ->whereNotIn('status', ['resolved', 'closed'])
            ->findOrFail($id);

        $request->validate([
            'message'       => ['required', 'string', 'min:5'],
            'attachments.*' => ['nullable', 'file', 'max:10240'],
        ]);

        $reply = TicketReply::create([
            'ticket_id'      => $ticket->id,
            'user_id'        => Auth::id(),
            'message'        => $request->message,
            'is_staff_reply' => false,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-attachments', 'public');
                TicketAttachment::create([
                    'ticket_id'     => $ticket->id,
                    'reply_id'      => $reply->id,
                    'filename'      => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type'     => $file->getMimeType(),
                    'file_size'     => $file->getSize(),
                ]);
            }
        }

        $ticket->update([
            'status'        => 'open',
            'last_reply_at' => now(),
        ]);

        // Notify assigned agent or all support staff
        $notifyUser = $ticket->assignedTo ?? null;
        if ($notifyUser) {
            Mail::to($notifyUser->email)->queue(
                new TicketReplyNotification($ticket, $reply, $notifyUser->name, false)
            );
        } else {
            $staffEmails = User::whereIn('role', ['super_admin', 'support_agent'])
                ->where('is_active', true)
                ->get();
            foreach ($staffEmails as $staff) {
                Mail::to($staff->email)->queue(
                    new TicketReplyNotification($ticket, $reply, $staff->name, false)
                );
            }
        }

        return back()->with('success', 'Reply sent successfully.');
    }
}
