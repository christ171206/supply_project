@extends('vendeur.layout-dashboard')

@section('content')
<div class="px-6 py-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-serif text-[#0a0a0a]">Gestion du stock</h1>
            <p class="text-[#666660] text-sm font-light mt-2">Gérez vos inventaires et seuils</p>
        </div>
        <a href="{{ route('vendeur.produits.create') }}" class="px-4 py-2.5 bg-[#0a0a0a] text-white text-sm font-medium rounded-lg hover:opacity-85 transition">
            Ajouter produit
        </a>
    </div>

    <!-- Tableau Stock -->
    <div class="bg-white border border-[#e0e0dc] rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#f7f7f5] border-b border-[#e0e0dc]">
                    <tr>
                        <th class="px-4 py-3 text-left text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">Produit</th>
                        <th class="px-4 py-3 text-left text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">Stock actuel</th>
                        <th class="px-4 py-3 text-left text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">Seuil min</th>
                        <th class="px-4 py-3 text-left text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">État</th>
                        <th class="px-4 py-3 text-center text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e0e0dc]">
                    @forelse($produits as $produit)
                        @php
                            $etat = 'OK';
                            if ($produit->stock == 0) {
                                $etat = 'Rupture';
                            } elseif ($produit->stock <= $produit->stock_minimum) {
                                $etat = 'Faible';
                            }
                        @endphp
                        <tr class="hover:bg-[#f7f7f5] transition">
                            <td class="px-4 py-3 font-medium text-[#0a0a0a]">{{ $produit->nom }}</td>
                            <td class="px-4 py-3 font-mono font-bold text-[#0a0a0a]">{{ $produit->stock }}</td>
                            <td class="px-4 py-3 font-mono text-[#a0a09a]">{{ $produit->stock_minimum }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-1.5 text-xs font-medium rounded
                                    @if($produit->stock == 0)
                                        bg-[#fef2f2] text-[#dc2626]
                                    @elseif($produit->stock <= $produit->stock_minimum)
                                        bg-[#fef3c7] text-[#92400e]
                                    @else
                                        bg-[#f0fdf4] text-[#15803d]
                                    @endif">
                                    @if($produit->stock == 0)
                                        Rupture
                                    @elseif($produit->stock <= $produit->stock_minimum)
                                        Faible
                                    @else
                                        OK
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('vendeur.produits.edit', $produit->id) }}" class="text-[#0a0a0a] hover:text-[#666660] font-medium text-xs transition">
                                    Modifier →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center">
                                <p class="text-[#a0a09a] text-sm font-light">Aucun produit</p>
                                <p class="text-[#666660] text-xs font-light mt-1">Commencez par ajouter un produit à votre boutique</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Conseil -->
    <div class="mt-6 p-4 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg">
        <p class="text-xs text-[#666660] font-light">
            <strong class="text-[#0a0a0a] font-medium">Conseil:</strong>
            L'état "Faible" indique que le stock approche du seuil minimum.
            "Rupture" signifie que le stock est à 0.
        </p>
    </div>
</div>
@endsection
