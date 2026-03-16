<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmartSearchController extends Controller
{
    /**
     * Autocomplétion smart (nom produit + catégorie)
     */
    public function autocomplete(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 1) {
            return response()->json(['suggestions' => []]);
        }

        // Chercher dans les noms de produits ET les catégories
        $products = Produit::where('est_actif', true)
            ->where(function ($q) use ($query) {
                $q->where('nom', 'LIKE', "%{$query}%")
                    ->orWhereHas('categorie', function ($cat) use ($query) {
                        $cat->where('nom', 'LIKE', "%{$query}%");
                    });
            })
            ->with('categorie')
            ->limit(10)
            ->get();

        // Formater les suggestions
        $suggestions = $products->map(function ($p) {
            // Highlight du texte correspondant
            $highlighted = $this->highlightMatch($p->nom, request('q'));

            return [
                'id' => $p->id,
                'type' => 'product',
                'nom' => $p->nom,
                'highlighted' => $highlighted,
                'categorie' => $p->categorie?->nom,
                'prix' => $p->prix,
                'image' => $p->image,
                'badge' => $this->getProductBadge($p),
            ];
        });

        // Ajouter les catégories pour les résultats
        if (strlen($query) >= 2) {
            $categories = DB::table('categories')
                ->where('nom', 'LIKE', "%{$query}%")
                ->limit(3)
                ->get();

            foreach ($categories as $cat) {
                $suggestions->push([
                    'id' => $cat->id,
                    'type' => 'category',
                    'nom' => $cat->nom,
                    'highlighted' => $this->highlightMatch($cat->nom, request('q')),
                ]);
            }
        }

        return response()->json([
            'suggestions' => $suggestions->toArray(),
            'count' => $suggestions->count(),
        ]);
    }

    /**
     * Recherche avancée avec filtres
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $category = $request->input('category');
        $priceMin = $request->input('price_min', 0);
        $priceMax = $request->input('price_max', 999999999);
        $sortBy = $request->input('sort', 'relevance');
        $page = $request->input('page', 1);

        // Recherche de base
        $products = Produit::where('est_actif', true)
            ->where(function ($q) use ($query) {
                if ($query) {
                    $q->where('nom', 'LIKE', "%{$query}%")
                        ->orWhere('description', 'LIKE', "%{$query}%")
                        ->orWhereHas('categorie', function ($cat) use ($query) {
                            $cat->where('nom', 'LIKE', "%{$query}%");
                        });
                }
            });

        // Filtre par catégorie
        if ($category) {
            $products->where('categorie_id', $category);
        }

        // Filtre par prix
        $products->whereBetween('prix', [$priceMin, $priceMax]);

        // Tri
        match ($sortBy) {
            'price_asc' => $products->orderBy('prix', 'asc'),
            'price_desc' => $products->orderBy('prix', 'desc'),
            'newest' => $products->latest(),
            'popular' => $products->with('ligneCommandes')
                ->orderByRaw('(SELECT COUNT(*) FROM ligne_commandes WHERE produit_id = produits.id) DESC')
                ->orderByDesc('created_at'),
            'rating' => $products->with('avis')
                ->get()
                ->each(fn($p) => $p->avg_rating = $p->avis()->avg('note') ?? 0)
                ->sortByDesc('avg_rating'),
            default => $products->latest(),
        };

        $products = $products->with('categorie', 'avis', 'vendeur')
            ->paginate(12, ['*'], 'page', $page);

        // Enrichir avec les données
        $products->getCollection()->transform(function ($p) {
            $p->avg_rating = $p->avis()->avg('note') ?? 0;
            $p->review_count = $p->avis()->count();
            $p->badge = $this->getProductBadge($p);
            return $p;
        });

        return response()->json([
            'status' => 'success',
            'total' => $products->total(),
            'per_page' => $products->perPage(),
            'current_page' => $products->currentPage(),
            'data' => $products->items(),
        ]);
    }

    /**
     * Résultats de recherche formatés en HTML
     */
    public function results(Request $request)
    {
        $query = $request->input('q', '');
        $category = $request->input('category');
        $sort = $request->input('sort', 'relevance');

        $products = Produit::where('est_actif', true)
            ->where(function ($q) use ($query) {
                if ($query) {
                    $q->where('nom', 'LIKE', "%{$query}%")
                        ->orWhere('description', 'LIKE', "%{$query}%");
                }
            });

        if ($category) {
            $products->where('categorie_id', $category);
        }

        // Appliquer le tri
        if ($sort === 'price_asc') {
            $products->orderBy('prix', 'asc');
        } elseif ($sort === 'price_desc') {
            $products->orderBy('prix', 'desc');
        } elseif ($sort === 'newest') {
            $products->latest();
        } else {
            $products->latest();
        }

        $products = $products->with('categorie', 'avis', 'vendeur')
            ->paginate(12);

        // Enrichir
        $products->getCollection()->transform(function ($p) {
            $p->avg_rating = $p->avis()->avg('note') ?? 0;
            return $p;
        });

        return view('search.results', compact('products', 'query', 'category', 'sort'));
    }

    /**
     * Suggestions rapides (trending searches)
     */
    public function trendingSuggestions()
    {
        // Top 5 catégories par nombre de produits
        $trendingCategories = DB::table('categories')
            ->leftJoin('produits', 'categories.id', '=', 'produits.categorie_id')
            ->selectRaw('categories.id, categories.nom, COUNT(produits.id) as count')
            ->where('produits.est_actif', true)
            ->groupBy('categories.id', 'categories.nom')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Produits trending (plus vendus cette semaine)
        $sevenDaysAgo = now()->subDays(7);
        $trendingProducts = Produit::where('est_actif', true)
            ->with('ligneCommandes')
            ->get()
            ->map(function ($p) use ($sevenDaysAgo) {
                $recentSales = $p->ligneCommandes()
                    ->whereHas('commande', fn($q) => $q->where('created_at', '>=', $sevenDaysAgo))
                    ->count();
                $p->recent_sales = $recentSales;
                return $p;
            })
            ->where('recent_sales', '>', 0)
            ->sortByDesc('recent_sales')
            ->take(5)
            ->map(fn($p) => [
                'nom' => $p->nom,
                'type' => 'product',
                'badge' => '🔥 Tendance',
            ]);

        return response()->json([
            'categories' => $trendingCategories->map(fn($c) => [
                'nom' => $c->nom,
                'type' => 'category',
                'count' => $c->count,
            ])->toArray(),
            'trending' => $trendingProducts->toArray(),
        ]);
    }

    /**
     * Helper: Highlight la partie correspondante du texte
     */
    private function highlightMatch($text, $query)
    {
        if (!$query) return $text;

        $pos = stripos($text, $query);
        if ($pos === false) return $text;

        return substr($text, 0, $pos) .
            '<strong class="font-bold">' . substr($text, $pos, strlen($query)) . '</strong>' .
            substr($text, $pos + strlen($query));
    }

    /**
     * Helper: Obtenir un badge pour le produit
     */
    private function getProductBadge($product)
    {
        // Nouveau (dans les 7 derniers jours)
        if ($product->created_at->isAfter(now()->subDays(7))) {
            return '🆕 Nouveau';
        }

        // En tendance (ventes récentes)
        $recentSales = $product->ligneCommandes()
            ->whereHas('commande', fn($q) => $q->where('created_at', '>=', now()->subDays(7)))
            ->count();

        if ($recentSales >= 5) {
            return '🔥 Tendance';
        }

        // En rupture
        if ($product->stock === 0) {
            return '❌ Rupture';
        }

        // Stock faible
        if ($product->stock <= 3) {
            return '⚠️ Peu stock';
        }

        return null;
    }
}
