<?php

namespace App\Http\Controllers\Admin;

use App\Models\BannedWord;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class AdminBannedWordController extends Controller
{
    /**
     * Afficher tous les mots bannissants
     */
    public function index(Request $request): View
    {
        $query = BannedWord::with('createdByAdmin');

        // Filtrer par statut
        if ($request->has('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filtrer par sévérité
        if ($request->has('severity') && $request->severity) {
            $query->where('severity', $request->severity);
        }

        // Recherche
        if ($request->has('search') && $request->search) {
            $query->where('word', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Tri
        $sortBy = $request->input('sort_by', 'severity');
        $sortOrder = $request->input('sort_order', 'DESC');
        $query->orderBy($sortBy, $sortOrder);

        $words = $query->paginate(20);

        return view('admin.banned-words.index', [
            'words' => $words,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Afficher formulaire créationde mot bannissant
     */
    public function create(): View
    {
        return view('admin.banned-words.create');
    }

    /**
     * Stocker un mot bannissant
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'word' => 'required|string|max:255|unique:banned_words,word',
            'description' => 'nullable|string|max:500',
            'severity' => 'required|in:low,medium,high',
            'is_active' => 'boolean',
        ]);

        $word = BannedWord::create([
            'word' => strtolower($validated['word']),
            'description' => $validated['description'] ?? null,
            'severity' => $validated['severity'],
            'is_active' => $validated['is_active'] ?? true,
            'created_by_admin' => auth()->id(),
        ]);

        AuditService::logCreate(
            'BannedWord',
            $word->id,
            $word->word,
            $word->toArray()
        );

        return redirect()->route('admin.banned-words.index')
            ->with('success', "Mot '{$word->word}' ajouté à la liste bannissante !");
    }

    /**
     * Afficher formulaire édition
     */
    public function edit(BannedWord $word): View
    {
        return view('admin.banned-words.edit', [
            'word' => $word,
        ]);
    }

    /**
     * Mettre à jour un mot bannissant
     */
    public function update(Request $request, BannedWord $word)
    {
        $validated = $request->validate([
            'word' => 'required|string|max:255|unique:banned_words,word,' . $word->id,
            'description' => 'nullable|string|max:500',
            'severity' => 'required|in:low,medium,high',
            'is_active' => 'boolean',
        ]);

        $oldValues = $word->only(['word', 'description', 'severity', 'is_active']);

        $word->update([
            'word' => strtolower($validated['word']),
            'description' => $validated['description'] ?? null,
            'severity' => $validated['severity'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        AuditService::logUpdate(
            'BannedWord',
            $word->id,
            $word->word,
            $oldValues,
            $word->only(['word', 'description', 'severity', 'is_active'])
        );

        return redirect()->route('admin.banned-words.index')
            ->with('success', "Mot '{$word->word}' mis à jour !");
    }

    /**
     * Supprimer un mot bannissant
     */
    public function destroy(BannedWord $word)
    {
        $wordName = $word->word;
        $wordId = $word->id;
        $deletedValues = $word->toArray();

        $word->delete();

        AuditService::logDelete(
            'BannedWord',
            $wordId,
            $wordName,
            $deletedValues
        );

        return redirect()->route('admin.banned-words.index')
            ->with('success', "Mot '{$wordName}' supprimé de la liste !");
    }

    /**
     * Toggle activation d'un mot bannissant
     */
    public function toggle(BannedWord $word)
    {
        $oldValues = $word->only(['is_active']);

        $word->update([
            'is_active' => !$word->is_active,
        ]);

        AuditService::logUpdate(
            'BannedWord',
            $word->id,
            $word->word,
            $oldValues,
            $word->only(['is_active'])
        );

        $action = $word->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Mot `{$word->word}` {$action} !");
    }

    /**
     * Importer liste de mots bannissants (JSON, CSV)
     */
    public function import(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:json,csv,txt',
        ]);

        $file = $validated['file'];
        $imported = 0;
        $skipped = 0;

        if ($file->extension() === 'json') {
            $content = json_decode(file_get_contents($file->path()), true);
            foreach ($content as $item) {
                if (is_array($item) && isset($item['word'])) {
                    if (!BannedWord::where('word', strtolower($item['word']))->exists()) {
                        BannedWord::create([
                            'word' => strtolower($item['word']),
                            'description' => $item['description'] ?? null,
                            'severity' => $item['severity'] ?? 'medium',
                            'is_active' => $item['is_active'] ?? true,
                            'created_by_admin' => auth()->id(),
                        ]);
                        $imported++;
                    } else {
                        $skipped++;
                    }
                }
            }
        } else {
            // CSV/TXT format
            $lines = explode("\n", file_get_contents($file->path()));
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line && !BannedWord::where('word', strtolower($line))->exists()) {
                    BannedWord::create([
                        'word' => strtolower($line),
                        'severity' => 'medium',
                        'is_active' => true,
                        'created_by_admin' => auth()->id(),
                    ]);
                    $imported++;
                } else {
                    $skipped++;
                }
            }
        }

        return back()->with('success', "$imported mots importés, $skipped ignorés.");
    }

    /**
     * Exporter liste des mots bannissants
     */
    public function export()
    {
        $words = BannedWord::all();
        
        $csv = "word,description,severity,is_active\n";
        foreach ($words as $word) {
            $csv .= "\"{$word->word}\",\"{$word->description}\",{$word->severity}," . ($word->is_active ? 'true' : 'false') . "\n";
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'banned-words-' . now()->format('Y-m-d-H-i-s') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
