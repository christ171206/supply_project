<?php

namespace App\Http\Controllers\Admin;

use App\Models\SystemConfiguration;
use App\Models\DeliveryZone;
use App\Models\Quartier;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminConfigurationController extends Controller
{
    /**
     * Afficher la page de configuration
     */
    public function index()
    {
        $configurations = SystemConfiguration::all()->pluck('value', 'key');
        $deliveryZones = DeliveryZone::all();

        return view('admin.configuration.index', [
            'configurations' => $configurations,
            'deliveryZones' => $deliveryZones,
        ]);
    }

    /**
     * Mettre à jour les configurations du système
     */
    public function updateConfiguration(Request $request)
    {
        $request->validate([
            'delivery_base_fee' => 'nullable|numeric|min:0',
            'default_delivery_days' => 'nullable|integer|min:1',
            'currency_exchange_rate' => 'nullable|numeric|min:0',
            'platform_commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($request->filled('delivery_base_fee')) {
            SystemConfiguration::set('delivery_base_fee', $request->input('delivery_base_fee'), 'number');
        }

        if ($request->filled('default_delivery_days')) {
            SystemConfiguration::set('default_delivery_days', $request->input('default_delivery_days'), 'number');
        }

        if ($request->filled('currency_exchange_rate')) {
            SystemConfiguration::set('currency_exchange_rate', $request->input('currency_exchange_rate'), 'number');
        }

        if ($request->filled('platform_commission_rate')) {
            SystemConfiguration::set('platform_commission_rate', $request->input('platform_commission_rate'), 'number');
        }

        return redirect()->back()->with('success', 'Configurations mises à jour.');
    }

    /**
     * Gérer les zones de livraison
     */
    public function manageDeliveryZones()
    {
        $deliveryZones = DeliveryZone::with('quartiers')->paginate(10);
        $quartiers = Quartier::all();

        return view('admin.configuration.delivery-zones', [
            'deliveryZones' => $deliveryZones,
            'quartiers' => $quartiers,
        ]);
    }

    /**
     * Créer une zone de livraison
     */
    public function createDeliveryZone(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:delivery_zones',
            'delivery_fee' => 'required|numeric|min:0',
            'delivery_days' => 'required|integer|min:1',
            'quartier_ids' => 'required|array|min:1',
            'quartier_ids.*' => 'exists:quartiers,id',
        ]);

        $zone = DeliveryZone::create([
            'name' => $request->input('name'),
            'delivery_fee' => $request->input('delivery_fee'),
            'delivery_days' => $request->input('delivery_days'),
            'is_active' => true,
        ]);

        // Attacher les quartiers
        $zone->quartiers()->sync($request->input('quartier_ids'));

        return redirect()->back()->with('success', 'Zone de livraison créée.');
    }

    /**
     * Mettre à jour une zone de livraison
     */
    public function updateDeliveryZone(Request $request, DeliveryZone $zone)
    {
        $request->validate([
            'name' => 'required|string|unique:delivery_zones,name,' . $zone->id,
            'delivery_fee' => 'required|numeric|min:0',
            'delivery_days' => 'required|integer|min:1',
            'quartier_ids' => 'required|array|min:1',
            'quartier_ids.*' => 'exists:quartiers,id',
        ]);

        $zone->update([
            'name' => $request->input('name'),
            'delivery_fee' => $request->input('delivery_fee'),
            'delivery_days' => $request->input('delivery_days'),
        ]);

        // Mettre à jour les quartiers
        $zone->quartiers()->sync($request->input('quartier_ids'));

        return redirect()->back()->with('success', 'Zone de livraison mise à jour.');
    }

    /**
     * Supprimer une zone de livraison
     */
    public function deleteDeliveryZone(DeliveryZone $zone)
    {
        $zone->delete();

        return redirect()->back()->with('success', 'Zone de livraison supprimée.');
    }

    /**
     * Activer/Désactiver une zone de livraison
     */
    public function toggleDeliveryZone(DeliveryZone $zone)
    {
        $zone->update(['is_active' => !$zone->is_active]);

        return redirect()->back()->with('success', 'Zone mise à jour.');
    }
}
