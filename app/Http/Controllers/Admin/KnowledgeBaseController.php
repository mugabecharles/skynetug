<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KnowledgeBaseController extends Controller
{
    // ── Public routes ──────────────────────────────────────────────
    public function publicIndex(Request $request)
    {
        $query = KnowledgeBase::where('status', 'published');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($b) => $b->where('title', 'like', "%$q%")->orWhere('content', 'like', "%$q%"));
        }

        $articles = $query->latest()->paginate(15);
        return view('marketing.knowledge-base.index', compact('articles'));
    }

    public function publicShow(string $slug)
    {
        $article = KnowledgeBase::where('slug', $slug)->where('status', 'published')->firstOrFail();
        $article->increment('views');
        return view('marketing.knowledge-base.show', compact('article'));
    }

    // ── Admin routes ───────────────────────────────────────────────
    public function index()
    {
        $articles = KnowledgeBase::with('createdBy')->latest()->paginate(20);
        return view('admin.kb.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.kb.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'    => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:100'],
            'content'  => ['required', 'string'],
            'status'   => ['required', 'in:draft,published'],
        ]);
        $data['slug']       = Str::slug($data['title']) . '-' . Str::random(4);
        $data['created_by'] = Auth::id();

        KnowledgeBase::create($data);
        return redirect()->route('admin.kb.index')->with('success', 'Article saved.');
    }

    public function edit(KnowledgeBase $kb)
    {
        return view('admin.kb.edit', compact('kb'));
    }

    public function update(Request $request, KnowledgeBase $kb)
    {
        $data = $request->validate([
            'title'    => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:100'],
            'content'  => ['required', 'string'],
            'status'   => ['required', 'in:draft,published'],
        ]);
        $kb->update($data);
        return redirect()->route('admin.kb.index')->with('success', 'Article updated.');
    }

    public function destroy(KnowledgeBase $kb)
    {
        $kb->delete();
        return redirect()->route('admin.kb.index')->with('success', 'Article deleted.');
    }
}
