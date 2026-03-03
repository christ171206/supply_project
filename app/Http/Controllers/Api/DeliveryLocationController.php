<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CiCommune;
use App\Models\CiDistrict;
use App\Models\CiQuartier;
use App\Models\CiRegion;
use Illuminate\Http\JsonResponse; // Correction : 'use' au lieu de 'uses'
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
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');

            if (empty($query) || strlen($query) < 1) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Entrez au moins 1 caractère',
                    'data' => [],
                ]);
            }

            $searchPattern = "%{$query}%";

            // 1. Régions
            $regions = CiRegion::where('name', 'LIKE', $searchPattern)
                ->limit(5)
                ->get(['id', 'name', 'code'])
                ->map(fn($r) => [
                    'id' => $r->id,
                    'name' => $r->name,
                    'type' => 'region',
                    'code' => $r->code,
                    'display' => $r->name,
                    'breadcrumb' => $r->name,
                ]);

            // 2. Districts
            $districts = CiDistrict::with('region')
                ->where('name', 'LIKE', $searchPattern)
                ->limit(5)
                ->get()
                ->map(fn($d) => [
                    'id' => $d->id,
                    'name' => $d->name,
                    'type' => 'district',
                    'region' => $d->region->name ?? null,
                    'display' => $d->name . ' (' . ($d->region->name ?? '') . ')',
                    'breadcrumb' => ($d->region->name ?? '') . ' > ' . $d->name,
                ]);

            // 3. Communes
            $communes = CiCommune::with('district.region')
                ->where('name', 'LIKE', $searchPattern)
                ->limit(5)
                ->get()
                ->map(fn($c) => [
                    'id' => $c->id,
                    'name' => $c->name,
                    'type' => 'commune',
                    'display' => $c->name . ' (' . ($c->district->name ?? '') . ')',
                    'breadcrumb' => ($c->district->region->name ?? '') . ' > ' . ($c->district->name ?? '') . ' > ' . $c->name,
                ]);

            // 4. Quartiers - Correction de la syntaxe ici
            $quartiers = CiQuartier::with('commune.district.region')
                ->where('name', 'LIKE', $searchPattern)
                ->limit(5)
                ->get()
                ->map(fn($q) => [
                    'id' => $q->id,
                    'name' => $q->name,
                    'type' => 'quartier',
                    'display' => $q->name . ' (' . ($q->commune->name ?? '') . ')',
                    'breadcrumb' => ($q->commune->district->region->name ?? '') . ' > ' .
                                    ($q->commune->district->name ?? '') . ' > ' .
                                    ($q->commune->name ?? '') . ' > ' . $q->name,
                ]);

            $results = [];
            if ($regions->isNotEmpty()) $results[] = ['group' => 'Régions (Villes)', 'items' => $regions];
            if ($districts->isNotEmpty()) $results[] = ['group' => 'Districts', 'items' => $districts];
            if ($communes->isNotEmpty()) $results[] = ['group' => 'Communes', 'items' => $communes];
            if ($quartiers->isNotEmpty()) $results[] = ['group' => 'Quartiers', 'items' => $quartiers];

            return response()->json([
                'status' => 'success',
                'query' => $query,
                'count' => (count($regions) + count($districts) + count($communes) + count($quartiers)),
                'data' => $results,
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur search:', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la recherche',
            ], 500);
        }
    }
}
