<?php

namespace App\Http\Controllers\Admin;

use App\Models\Categorie;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    /**
     * Afficher toutes les catégories
     */
    public function index(Request $request): View
    {
        $query = Categorie::withCount('produits');

        // Recherche
        if ($request->has('search') && $request->search) {
            $query->where('nom', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Tri
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'DESC');
        $query->orderBy($sortBy, $sortOrder);

        $categories = $query->paginate(15);

        return view('admin.categories.index', [
            'categories' => $categories,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Stocker une nouvelle catégorie
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $category = Categorie::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Log l'action
        AuditService::logCreate(
            'Categorie',
            $category->id,
            $category->nom,
            $category->toArray()
        );

        return redirect()->route('admin.categories.index')
            ->with('success', "Catégorie '{$category->nom}' créée avec succès !");
    }

    /**
     * Afficher une catégorie
     */
    public function show(Categorie $category): View
    {
        return view('admin.categories.show', [
            'category' => $category->load('produits'),
        ]);
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Categorie $category): View
    {
        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Mettre à jour une catégorie
     */
    public function update(Request $request, Categorie $category)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $oldValues = $category->only(['nom', 'description', 'is_active']);

        $category->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Log l'action
        AuditService::logUpdate(
            'Categorie',
            $category->id,
            $category->nom,
            $oldValues,
            $category->only(['nom', 'description', 'is_active'])
        );

        return back()->with('success', "Catégorie '{$category->nom}' mise à jour avec succès !");
    }

    /**
     * Supprimer une catégorie
     */
    public function destroy(Categorie $category)
    {
        // Vérifier qu'il n'y a pas de produits
        if ($category->produits()->count() > 0) {
            return back()->with('error', "Impossible de supprimer une catégorie qui contient des produits !");
        }

        $categoryName = $category->nom;
        $categoryId = $category->id;
        $deletedValues = $category->only(['nom', 'description', 'is_active']);

        $category->delete();

        // Log l'action
        AuditService::logDelete(
            'Categorie',
            $categoryId,
            $categoryName,
            $deletedValues
        );

        return back()->with('success', "Catégorie '{$categoryName}' supprimée avec succès !");
    }

    /**
     * Activer/désactiver une catégorie
     */
    public function toggle(Categorie $category)
    {
        $oldStatus = $category->is_active;
        $category->update(['is_active' => !$category->is_active]);

        AuditService::logAction(
            'toggle_category',
            'Categorie',
            $category->id,
            [
                'old_status' => $oldStatus,
                'new_status' => $category->is_active,
            ],
            "Admin a " . ($category->is_active ? 'activé' : 'désactivé') . " la catégorie '{$category->nom}'"
        );

        return back()->with('success', 'Catégorie mise à jour !');
    }

    /**
     * Bulk activer les catégories sélectionnées
     */
    public function bulkEnable(Request $request)
    {
        $ids = $request->input('ids', []);

        Categorie::whereIn('id', $ids)->update(['is_active' => true]);

        AuditService::logAction(
            'bulk_enable_categories',
            'Categorie',
            0,
            ['ids' => $ids],
            "Admin a activé " . count($ids) . " catégories"
        );

        return back()->with('success', count($ids) . ' catégories activées !');
    }

    /**
     * Bulk désactiver les catégories sélectionnées
     */
    public function bulkDisable(Request $request)
    {
        $ids = $request->input('ids', []);

        Categorie::whereIn('id', $ids)->update(['is_active' => false]);

        AuditService::logAction(
            'bulk_disable_categories',
            'Categorie',
            0,
            ['ids' => $ids],
            "Admin a désactivé " . count($ids) . " catégories"
        );

        return back()->with('success', count($ids) . ' catégories désactivées !');
    }

    /**
     * Bulk supprimer les catégories sélectionnées
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        // Vérifier qu'aucune catégorie n'a de produits
        $categoriesWithProducts = Categorie::whereIn('id', $ids)
            ->whereHas('produits')
            ->count();

        if ($categoriesWithProducts > 0) {
            return back()->with('error', 'Certaines catégories contiennent des produits et ne peuvent pas être supprimées !');
        }

        Categorie::whereIn('id', $ids)->delete();

        AuditService::logAction(
            'bulk_delete_categories',
            'Categorie',
            0,
            ['ids' => $ids],
            "Admin a supprimé " . count($ids) . " catégories"
        );

        return back()->with('success', count($ids) . ' catégories supprimées !');
    }
}
