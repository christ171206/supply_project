@extends('layouts.app')

@section('title', 'Facture #' . $commande->numero)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Header avec logo -->
    <div class="flex justify-between items-start mb-12 pb-8 border-b border-[#e0e0dc]">
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-[#0a0a0a] rounded flex items-center justify-center">
                    <span class="text-white font-serif font-bold text-lg">S</span>
                </div>
                <h1 class="text-3xl font-bold font-display text-black">Supply</h1>
            </div>
            <p class="text-xl font-mono text-gray-600 mt-2">#{{ $commande->numero }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-600">Statut:
                <span class="font-semibold px-3 py-1 rounded-full inline-block"
                    style="background: {{ $commande->statut === 'livree' ? '#90EE90' : ($commande->statut === 'en_preparation' ? '#FFD700' : '#FFB6C6') }}">
                    {{ ucfirst($commande->statut) }}
                </span>
            </p>
            <p class="text-sm text-gray-600 mt-4">Émise le: <strong>{{ $commande->created_at->format('d/m/Y') }}</strong></p>
        </div>
    </div>

    <!-- Client & Invoice Details -->
    <div class="grid grid-cols-2 gap-8 mb-12 border border-gray-200 p-6 rounded">
        <div>
            <p class="text-xs uppercase text-gray-400 tracking-wider font-semibold mb-2">Client</p>
            <p class="font-semibold text-black">{{ $commande->client->name }}</p>
            <p class="text-sm text-gray-600">{{ $commande->client->email }}</p>
            <p class="text-sm text-gray-600">{{ $commande->client->phone }}</p>
        </div>
        <div>
            <p class="text-xs uppercase text-gray-400 tracking-wider font-semibold mb-2">Adresse de Livraison</p>
            <p class="text-sm text-gray-600">{{ $commande->client->address }}</p>
            <p class="text-sm text-gray-600">
                {{ $commande->deliveryLocation?->city }}, {{ $commande->pays }}
            </p>
        </div>
    </div>

    <!-- Line Items -->
    <table class="w-full mb-8">
        <thead>
            <tr class="border-t-2 border-b-2 border-gray-200">
                <th class="text-left py-3 px-0 text-xs uppercase text-gray-400 font-semibold">Produit</th>
                <th class="text-center py-3 px-0 text-xs uppercase text-gray-400 font-semibold">QTÉ</th>
                <th class="text-right py-3 px-0 text-xs uppercase text-gray-400 font-semibold">P.U.</th>
                <th class="text-right py-3 px-0 text-xs uppercase text-gray-400 font-semibold">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commande->ligneCommandes as $ligne)
            <tr class="border-b border-gray-100">
                <td class="py-4 px-0">
                    <div>
                        <p class="font-semibold text-black">{{ $ligne->produit->nom }}</p>
                        <p class="text-xs text-gray-400">par {{ $ligne->produit->vendeur->shop_name ?? $ligne->produit->vendeur->name }}</p>
                    </div>
                </td>
                <td class="text-center py-4 px-0 font-mono">{{ $ligne->quantite }}</td>
                <td class="text-right py-4 px-0 font-mono text-gray-600">{{ number_format($ligne->prix_unitaire, 0) }} F</td>
                <td class="text-right py-4 px-0 font-semibold font-mono">{{ number_format($ligne->prix_unitaire * $ligne->quantite, 0) }} F</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="flex justify-end mb-12">
        <div class="w-64">
            @php
                $subtotal = $commande->ligneCommandes->sum(fn($l) => $l->prix_unitaire * $l->quantite);
                $tva = $subtotal * 0.18;
                $total = $subtotal + $tva;
            @endphp

            <div class="flex justify-between py-2 border-t border-gray-200">
                <span class="text-gray-600">Sous-total</span>
                <span class="font-mono">{{ number_format($subtotal, 0) }} F</span>
            </div>
            <div class="flex justify-between py-2 border-t border-gray-200">
                <span class="text-gray-600">TVA (18%)</span>
                <span class="font-mono">{{ number_format($tva, 0) }} F</span>
            </div>
            <div class="flex justify-between py-3 border-t-2 border-b-2 border-gray-200 bg-gray-50">
                <span class="font-bold text-black">TOTAL</span>
                <span class="font-bold font-mono text-xl">{{ number_format($total, 0) }} F</span>
            </div>
        </div>
    </div>

    <!-- Méthode de paiement -->
    @if($commande->paiementMethod)
    <div class="border border-gray-200 p-6 rounded mb-8">
        <p class="text-xs uppercase text-gray-400 tracking-wider font-semibold mb-2">Méthode de Paiement</p>
        <p class="font-semibold text-black">{{ ucfirst($commande->paiementMethod->type_paiement) }}</p>
        <p class="text-sm text-gray-600">Référence: {{ $commande->paiementMethod->reference_transaction ?? 'N/A' }}</p>
    </div>
    @endif

    <!-- Actions -->
    <div class="flex gap-4 mt-12">
        <a href="javascript:window.print()" class="px-6 py-3 border border-black rounded text-black hover:bg-gray-50 font-semibold transition">
            🖨️ Imprimer
        </a>
        <a href="{{ route('invoices.download', $commande) }}" class="px-6 py-3 bg-black text-white rounded font-semibold hover:bg-gray-800 transition">
            📥 Télécharger PDF
        </a>
        <a href="{{ route('commandes.show', $commande) }}" class="px-6 py-3 border border-gray-200 rounded text-gray-600 hover:border-black transition">
            ← Détails Commande
        </a>
    </div>
</div>
@endsection
