<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TicketReplyNotification;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminSupportController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'assignedTo'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('subject', 'like', "%$s%")->orWhere('ticket_number', 'like', "%$s%"));
        }
        if ($request->filled('status'))   { $query->where('status', $request->status); }
        if ($request->filled('priority')) { $query->where('priority', $request->priority); }

        $tickets = $query->paginate(25)->withQueryString();
        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(int $id)
    {
        $ticket = SupportTicket::with(['user', 'assignedTo', 'replies.user', 'replies.attachments'])->findOrFail($id);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, int $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $request->validate(['message' => ['required', 'string', 'min:5']]);

        TicketReply::create([
            'ticket_id'      => $ticket->id,
            'user_id'        => Auth::id(),
            'message'        => $request->message,
            'is_staff_reply' => true,
        ]);

        $ticket->update(['status' => 'waiting_reply', 'last_reply_at' => now()]);

        // Notify the customer
        $reply = $ticket->replies()->latest()->first();
        Mail::to($ticket->user->email)->queue(
            new TicketReplyNotification($ticket, $reply, $ticket->user->name, true)
        );

        return back()->with('success', 'Reply sent.');
    }

    public function assign(Request $request, int $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $request->validate(['user_id' => ['nullable', 'exists:users,id']]);
        $ticket->update(['assigned_to' => $request->user_id, 'status' => 'in_progress']);
        return back()->with('success', 'Ticket assigned.');
    }

    public function close(int $id)
    {
        SupportTicket::findOrFail($id)->update(['status' => 'closed']);
        return back()->with('success', 'Ticket closed.');
    }

    public function escalate(int $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['priority' => 'urgent', 'status' => 'in_progress']);
        return back()->with('success', 'Ticket escalated to urgent.');
    }
}
