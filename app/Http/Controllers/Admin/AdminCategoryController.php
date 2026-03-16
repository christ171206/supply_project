<?php

namespace App\Http\Controllers\Admin;

use App\Models\Categorie;
use App\Services\AuditService;
use App\Helpers\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

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
        $sortBy = $request->input('sort_by', 'nom');
        $sortOrder = $request->input('sort_order', 'ASC');
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'boolean',
        ]);

        // Gérer l'upload d'image avec optimisation
        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                $imagePath = ImageOptimizer::optimizeCategory($request->file('image'));
            } catch (\Exception $e) {
                return back()->with('error', 'Erreur lors de l\'upload de l\'image: ' . $e->getMessage());
            }
        }

        $category = Categorie::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'boolean',
        ]);

        $oldValues = $category->only(['nom', 'description', 'is_active', 'image']);

        $updateData = [
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ];

        // Gérer l'upload d'image avec optimisation
        if ($request->hasFile('image')) {
            try {
                // Supprimer l'ancienne image
                if ($category->image) {
                    ImageOptimizer::delete($category->image);
                }
                // Optimiser et sauvegarder la nouvelle
                $updateData['image'] = ImageOptimizer::optimizeCategory($request->file('image'));
            } catch (\Exception $e) {
                return back()->with('error', 'Erreur lors de l\'upload de l\'image: ' . $e->getMessage());
            }
        }

        $category->update($updateData);

        // Log l'action
        AuditService::logUpdate(
            'Categorie',
            $category->id,
            $category->nom,
            $oldValues,
            $category->only(['nom', 'description', 'is_active', 'image'])
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
        $deletedValues = $category->only(['nom', 'description', 'is_active', 'image']);

        // Supprimer l'image si elle existe
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

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

        AuditService::logUpdate(
            'Categorie',
            $category->id,
            $category->nom,
            ['is_active' => $oldStatus],
            ['is_active' => $category->is_active],
            ($category->is_active ? 'Catégorie activée' : 'Catégorie désactivée')
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

        AuditService::logUpdate(
            'Categorie',
            0,
            'Catégories (Bulk)',
            ['ids' => $ids, 'is_active' => false],
            ['ids' => $ids, 'is_active' => true],
            'Activation en masse'
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

        AuditService::logUpdate(
            'Categorie',
            0,
            'Catégories (Bulk)',
            ['ids' => $ids, 'is_active' => true],
            ['ids' => $ids, 'is_active' => false],
            'Désactivation en masse'
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

        AuditService::logDelete(
            'Categorie',
            0,
            'Catégories (Bulk)',
            ['ids' => $ids],
            'Suppression en masse'
        );

        return back()->with('success', count($ids) . ' catégories supprimées !');
    }
}
