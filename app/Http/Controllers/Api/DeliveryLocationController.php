<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CiCommune;
use App\Models\CiDistrict;
use App\Models\CiQuartier;
use App\Models\CiRegion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeliveryLocationController extends Controller
{
    /**
     * Get all regions (villes)
     */
    public function getRegions(): JsonResponse
    {
        try {
            $regions = CiRegion::all(['id', 'name', 'code']);
            return response()->json([
                'status' => 'success',
                'data' => $regions,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur getRegions:', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors du chargement des régions',
            ], 500);
        }
    }

    /**
     * Get districts for a specific region
     */
    public function getDistricts(CiRegion $region): JsonResponse
    {
        try {
            $districts = $region->districts()->get(['id', 'name']);
            return response()->json([
                'status' => 'success',
                'data' => $districts,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur getDistricts:', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors du chargement des districts',
            ], 500);
        }
    }

    /**
     * Get communes for a specific district
     */
    public function getCommunes(CiDistrict $district): JsonResponse
    {
        try {
            $communes = $district->communes()->get(['id', 'name']);
            return response()->json([
                'status' => 'success',
                'data' => $communes,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur getCommunes:', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors du chargement des communes',
            ], 500);
        }
    }

    /**
     * Get quartiers for a specific commune
     */
    public function getQuartiers(CiCommune $commune): JsonResponse
    {
        try {
            $quartiers = $commune->quartiers()->get(['id', 'name']);
            return response()->json([
                'status' => 'success',
                'data' => $quartiers,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur getQuartiers:', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors du chargement des quartiers',
            ], 500);
        }
    }

    /**
     * Search locations by name (autocomplete)
     * Search across all location types with detailed information
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');

            if (strlen($query) < 1) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Entrez au moins 1 caractère',
                    'data' => [],
                ]);
            }

            $searchPattern = "%{$query}%";

            // Régions
            $regions = CiRegion::where('name', 'LIKE', $searchPattern)
                ->limit(10)
                ->get(['id', 'name', 'code']);

            // Districts
            $districts = CiDistrict::whereHas('region')
                ->where('name', 'LIKE', $searchPattern)
                ->with('region')
                ->limit(10)
                ->get()
                ->map(fn($d) => [
                    'id' => $d->id,
                    'name' => $d->name,
                    'type' => 'district',
                    'region_id' => $d->region_id,
                    'region' => $d->region->name ?? null,
                    'display' => $d->name . ' (' . ($d->region->name ?? '') . ')',
                    'breadcrumb' => ($d->region->name ?? '') . ' > ' . $d->name,
                ]);

            // Communes
            $communes = CiCommune::whereHas('district')
                ->where('name', 'LIKE', $searchPattern)
                ->with('district.region')
                ->limit(10)
                ->get()
                ->map(fn($c) => [
                    'id' => $c->id,
                    'name' => $c->name,
                    'type' => 'commune',
                    'district_id' => $c->district_id,
                    'district' => $c->district->name ?? null,
                    'region_id' => $c->district->region_id ?? null,
                    'region' => $c->district->region->name ?? null,
                    'display' => $c->name . ' (' . ($c->district->name ?? '') . ')',
                    'breadcrumb' => ($c->district->region->name ?? '') . ' > ' . ($c->district->name ?? '') . ' > ' . $c->name,
                ]);

            // Quartiers
            $quartiers = CiQuartier::whereHas('commune')
                ->where('name', 'LIKE', $searchPattern)
                ->with('commune.district.region')
                ->limit(10)
                ->get()
                ->map(fn($q) => [
                    'id' => $q->id,
                    'name' => $q->name,
                    'type' => 'quartier',
                    'commune_id' => $q->commune_id,
                    'commune' => $q->commune->name ?? null,
                    'district_id' => $q->commune->district_id ?? null,
                    'district' => $q->commune->district->name ?? null,
                    'region_id' => $q->commune->district->region_id ?? null,
                    'region' => $q->commune->district->region->name ?? null,
                    'display' => $q->name . ' (' . ($q->commune->name ?? '') . ')',
                    'breadcrumb' => ($q->commune->district->region->name ?? '') . ' > ' .
                        ($q->commune->district->name ?? '') . ' > ' .
                        ($q->commune->name ?? '') . ' > ' . $q->name,
                ]);

            $results = [];

            // Ajouter les résultats groupés
            if ($regions->isNotEmpty()) {
                $results[] = [
                    'group' => 'Régions (Villes)',
                    'items' => $regions->map(fn($r) => [
                        'id' => $r->id,
                        'name' => $r->name,
                        'type' => 'region',
                        'code' => $r->code,
                        'display' => $r->name,
                    ])->toArray(),
                ];
            }

            if ($districts->isNotEmpty()) {
                $results[] = [
                    'group' => 'Districts',
                    'items' => $districts->toArray(),
                ];
            }

            if ($communes->isNotEmpty()) {
                $results[] = [
                    'group' => 'Communes',
                    'items' => $communes->toArray(),
                ];
            }

            if ($quartiers->isNotEmpty()) {
                $results[] = [
                    'group' => 'Quartiers',
                    'items' => $quartiers->toArray(),
                ];
            }

            // S'il n'y a aucun résultat
            if (empty($results)) {
                return response()->json([
                    'status' => 'success',
                    'query' => $query,
                    'count' => 0,
                    'data' => [],
                    'message' => 'Aucun résultat trouvé pour "' . htmlspecialchars($query) . '"'
                ]);
            }

            return response()->json([
                'status' => 'success',
                'query' => $query,
                'count' => (count($regions) + count($districts) + count($communes) + count($quartiers)),
                'data' => $results,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur search:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la recherche: ' . $e->getMessage(),
            ], 500);
        }
    }
}
