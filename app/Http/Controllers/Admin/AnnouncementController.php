<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('createdBy')->latest()->paginate(20);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => ['required', 'string', 'max:200'],
            'content' => ['required', 'string'],
            'status'  => ['required', 'in:draft,published'],
        ]);
        $data['created_by']   = Auth::id();
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        Announcement::create($data);
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement saved.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'title'   => ['required', 'string', 'max:200'],
            'content' => ['required', 'string'],
            'status'  => ['required', 'in:draft,published,archived'],
        ]);
        if ($data['status'] === 'published' && !$announcement->published_at) {
            $data['published_at'] = now();
        }
        $announcement->update($data);
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted.');
    }
}
