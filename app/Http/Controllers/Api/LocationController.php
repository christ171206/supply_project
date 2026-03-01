<?php

namespace App\Http\Controllers\Api;

use App\Models\CiRegion;
use App\Models\CiDistrict;
use App\Models\CiCommune;
use App\Models\CiQuartier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    /**
     * Get all regions
     */
    public function getRegions()
    {
        return response()->json(CiRegion::all());
    }

    /**
     * Get districts by region
     */
    public function getDistrictsByRegion($regionId)
    {
        $districts = CiDistrict::where('region_id', $regionId)->get();
        return response()->json($districts);
    }

    /**
     * Get communes by district
     */
    public function getCommunesByDistrict($districtId)
    {
        $communes = CiCommune::where('district_id', $districtId)->get();
        return response()->json($communes);
    }

    /**
     * Get quartiers by commune
     */
    public function getQuartiersByCommune($communeId)
    {
        $quartiers = CiQuartier::where('commune_id', $communeId)->get();
        return response()->json($quartiers);
    }

    /**
     * Search locations by name with improved results
     */
    public function searchLocations(Request $request)
    {
        $search = $request->input('q', '');
        $type = $request->input('type', 'all'); // all, region, district, commune, quartier

        if (strlen($search) < 1) {
            return response()->json([
                'status' => 'success',
                'data' => [],
                'message' => 'Minimum 1 caractère requis',
            ]);
        }

        $results = [];
        $searchPattern = "%{$search}%";

        // Rechercher dans les régions
        if ($type === 'all' || $type === 'region') {
            $regions = CiRegion::where('name', 'ILIKE', $searchPattern)
                ->limit(10)
                ->get(['id', 'name', 'code']);

            foreach ($regions as $region) {
                $results[] = [
                    'id' => $region->id,
                    'type' => 'region',
                    'name' => $region->name,
                    'code' => $region->code,
                    'label' => '🏛️ ' . $region->name . ' (Région)',
                    'display' => $region->name,
                    'breadcrumb' => $region->name,
                ];
            }
        }

        // Rechercher dans les districts
        if ($type === 'all' || $type === 'district') {
            $districts = CiDistrict::where('name', 'ILIKE', $searchPattern)
                ->with('region')
                ->limit(10)
                ->get();

            foreach ($districts as $district) {
                $results[] = [
                    'id' => $district->id,
                    'type' => 'district',
                    'name' => $district->name,
                    'region_id' => $district->region_id,
                    'region_name' => $district->region->name ?? null,
                    'label' => '🗺️ ' . $district->name . ' (' . ($district->region->name ?? '') . ')',
                    'display' => $district->name,
                    'breadcrumb' => ($district->region->name ?? '') . ' > ' . $district->name,
                ];
            }
        }

        // Rechercher dans les communes
        if ($type === 'all' || $type === 'commune') {
            $communes = CiCommune::where('name', 'ILIKE', $searchPattern)
                ->with('district.region')
                ->limit(10)
                ->get();

            foreach ($communes as $commune) {
                $results[] = [
                    'id' => $commune->id,
                    'type' => 'commune',
                    'name' => $commune->name,
                    'district_id' => $commune->district_id,
                    'district_name' => $commune->district->name ?? null,
                    'region_name' => $commune->district->region->name ?? null,
                    'label' => '🏘️ ' . $commune->name . ' (' . ($commune->district->name ?? '') . ')',
                    'display' => $commune->name,
                    'breadcrumb' => ($commune->district->region->name ?? '') . ' > ' . ($commune->district->name ?? '') . ' > ' . $commune->name,
                ];
            }
        }

        // Rechercher dans les quartiers
        if ($type === 'all' || $type === 'quartier') {
            $quartiers = CiQuartier::where('name', 'ILIKE', $searchPattern)
                ->with('commune.district.region')
                ->limit(10)
                ->get();

            foreach ($quartiers as $quartier) {
                $results[] = [
                    'id' => $quartier->id,
                    'type' => 'quartier',
                    'name' => $quartier->name,
                    'commune_id' => $quartier->commune_id,
                    'commune_name' => $quartier->commune->name ?? null,
                    'district_name' => $quartier->commune->district->name ?? null,
                    'region_name' => $quartier->commune->district->region->name ?? null,
                    'label' => '🏠 ' . $quartier->name . ' (' . ($quartier->commune->name ?? '') . ')',
                    'display' => $quartier->name,
                    'breadcrumb' => ($quartier->commune->district->region->name ?? '') . ' > ' .
                        ($quartier->commune->district->name ?? '') . ' > ' .
                        ($quartier->commune->name ?? '') . ' > ' . $quartier->name,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'query' => $search,
            'type_filter' => $type,
            'count' => count($results),
            'data' => array_slice($results, 0, 20), // Limiter à 20 résultats
        ]);
    }
}

