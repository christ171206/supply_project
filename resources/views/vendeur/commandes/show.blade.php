@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8 bg-white min-h-screen">
    <!-- Messages d'alerte -->
    @if($message = Session::get('success'))
        <div class="bg-[#f0fdf4] border border-[#bbf7d0] text-[#15803d] px-4 py-3 rounded-lg mb-6 text-[13px]">
            {{ $message }}
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="bg-[#fef2f2] border border-[#fecaca] text-[#dc2626] px-4 py-3 rounded-lg mb-6 text-[13px]">
            {{ $message }}
        </div>
    @endif

    <!-- En-tête avec retour -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('vendeur.commandes') }}" class="text-[#0a0a0a] hover:text-[#a0a09a] font-medium flex items-center gap-2 text-[13px]">
            ← Retour aux commandes
        </a>
    </div>

    <!-- Titre et info principale -->
    <div class="mb-8">
        <h1 class="text-3xl font-serif text-[#0a0a0a] mb-2">Commande #{{ $commande->id }}</h1>
        <p class="text-[13px] text-[#666660]">Passée le {{ $commande->created_at->format('d/m/Y à H:i') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale: Produits et détails -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Articles commandés -->
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <h2 class="text-lg font-medium text-[#0a0a0a] mb-6">Articles Commandés</h2>

                @if($commande->ligneCommandes->count() > 0)
                    <div class="space-y-4">
                        @foreach($commande->ligneCommandes as $ligne)
                            @php
                                $produit = $ligne->produit;
                                if($produit && $produit->user_id == auth()->id()) {
                            @endphp
                            <div class="p-4 border border-[#e0e0dc] rounded hover:border-[#0a0a0a] transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-[#0a0a0a] mb-1 text-[13px]">{{ $produit->nom }}</h3>
                                        <p class="text-[12px] text-[#a0a09a] mb-2">Catégorie: <span class="text-[#0a0a0a]">{{ $produit->categorie->nom ?? 'N/A' }}</span></p>

                                        <div class="grid grid-cols-3 gap-4 mt-4">
                                            <div>
                                                <p class="text-[11px] text-[#a0a09a] uppercase">Quantité</p>
                                                <p class="font-mono font-bold text-[#0a0a0a] text-[14px]">{{ $ligne->quantite }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[11px] text-[#a0a09a] uppercase">P.U.</p>
                                                <p class="font-mono font-bold text-[#0a0a0a] text-[14px]">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} CFA</p>
                                            </div>
                                            <div>
                                                <p class="text-[11px] text-[#a0a09a] uppercase">Sous-total</p>
                                                <p class="font-mono font-bold text-[#0a0a0a] text-[14px]">{{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, ',', ' ') }} CFA</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php } @endphp
                        @endforeach
                    </div>
                @else
                    <p class="text-[#a0a09a] text-center py-6 text-[13px]">Aucun article</p>
                @endif
            </div>

            <!-- Paiement -->
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <h2 class="text-lg font-medium text-[#0a0a0a] mb-6">Paiement</h2>

                @if($commande->payment)
                    <div class="space-y-3">
                        <div class="flex justify-between p-3 bg-[#f7f7f5] rounded text-[13px]">
                            <p class="text-[#666660]">Méthode</p>
                            <p class="font-medium text-[#0a0a0a]">{{ ucfirst($commande->payment->methode_paiement) }}</p>
                        </div>
                        <div class="flex justify-between p-3 bg-[#f7f7f5] rounded text-[13px]">
                            <p class="text-[#666660]">Statut</p>
                            <p class="font-medium">
                                @if($commande->payment->statut == 'complete')
                                    <span class="text-[#15803d]">Complété</span>
                                @else
                                    <span class="text-[#92400e]">En attente</span>
                                @endif
                            </p>
                        </div>
                    </div>
                @else
                    <p class="text-[#a0a09a] text-center py-6 text-[13px]">Pas d'information de paiement</p>
                @endif
            </div>
        </div>

        <!-- Colonne sidebar: Résumé et actions -->
        <div class="space-y-6">
            <!-- Résumé commande -->
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <h2 class="text-lg font-medium text-[#0a0a0a] mb-6">Résumé</h2>

                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-[#e0e0dc] text-[13px]">
                        <p class="text-[#666660]">Sous-total</p>
                        <p class="font-mono font-bold text-[#0a0a0a]">{{ number_format($commande->total, 0, ',', ' ') }} CFA</p>
                    </div>
                    <div class="flex justify-between py-3 text-[14px]">
                        <p class="font-medium text-[#0a0a0a]">Total</p>
                        <p class="font-mono font-bold text-[#0a0a0a]">{{ number_format($commande->total, 0, ',', ' ') }} CFA</p>
                    </div>
                </div>
            </div>

            <!-- Statut et Actions -->
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <h2 class="text-lg font-medium text-[#0a0a0a] mb-6">Statut</h2>

                @php
                    $colors = [
                        'en_attente' => 'bg-[#fef2f2] text-[#dc2626]',
                        'confirmee' => 'bg-[#fef3c7] text-[#92400e]',
                        'expediee' => 'bg-[#f0fdf4] text-[#15803d]',
                        'livree' => 'bg-[#f0fdf4] text-[#15803d]'
                    ];
                    $labels = [
                        'en_attente' => 'En Attente',
                        'confirmee' => 'Confirmée',
                        'expediee' => 'Expédiée',
                        'livree' => 'Livrée'
                    ];
                @endphp

                <div class="mb-6">
                    <p class="text-[11px] text-[#a0a09a] uppercase mb-2">Statut actuel</p>
                    <span class="inline-block px-3 py-2 rounded text-[12px] font-medium {{ $colors[$commande->statut] ?? 'bg-[#f7f7f5] text-[#a0a09a]' }}">
                        {{ $labels[$commande->statut] ?? $commande->statut }}
                    </span>
                </div>

                <!-- Boutons d'action -->
                <div class="space-y-2">
                    @if($commande->statut == 'en_attente')
                        <form method="POST" action="{{ route('vendeur.commandes.update-status', $commande->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="statut" value="confirmee">
                            <button type="submit" class="w-full px-4 py-2.5 bg-[#0a0a0a] text-white rounded hover:opacity-85 transition font-medium text-[12px]">
                                Confirmer
                            </button>
                        </form>
                    @endif

                    @if(in_array($commande->statut, ['confirmee']))
                        <form method="POST" action="{{ route('vendeur.commandes.update-status', $commande->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="statut" value="expediee">
                            <button type="submit" class="w-full px-4 py-2.5 bg-[#0a0a0a] text-white rounded hover:opacity-85 transition font-medium text-[12px]">
                                Expédier
                            </button>
                        </form>
                    @endif

                    @if(in_array($commande->statut, ['expediee']))
                        <form method="POST" action="{{ route('vendeur.commandes.update-status', $commande->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="statut" value="livree">
                            <button type="submit" class="w-full px-4 py-2.5 bg-[#0a0a0a] text-white rounded hover:opacity-85 transition font-medium text-[12px]">
                                Marquer livrée
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Infos Client -->
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <h2 class="text-lg font-medium text-[#0a0a0a] mb-4">Client</h2>

                <div class="space-y-3 text-[13px]">
                    <div>
                        <p class="text-[11px] text-[#a0a09a] uppercase">Nom</p>
                        <p class="font-medium text-[#0a0a0a]">{{ $commande->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-[#a0a09a] uppercase">Email</p>
                        <p class="font-medium text-[#0a0a0a]">{{ $commande->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-[#a0a09a] uppercase">Téléphone</p>
                        <p class="font-medium text-[#0a0a0a]">{{ $commande->user->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Order confirmation payment animation
document.addEventListener('DOMContentLoaded', function() {
    // Find the "Confirmer" form (changing status to "confirmee")
    const confirmForms = Array.from(document.querySelectorAll('form[action*="update-status"]'))
        .filter(form => {
            const hidden = form.querySelector('input[name="statut"]')
            return hidden && hidden.value === 'confirmee'
        })

    confirmForms.forEach(form => {
        const submitBtn = form.querySelector('button[type="submit"]')
        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault()

                // Show payment simulation modal
                Swal.fire({
                    title: 'Confirmer la commande',
                    html: `
                        <div style="text-align: center; padding: 20px;">
                            <p style="color: #666660; margin-bottom: 20px; font-family: Geist, sans-serif; font-size: 14px;">
                                Cette action confirmera la commande et simulera le traitement du paiement.
                            </p>
                        </div>
                    `,
                    icon: 'info',
                    iconColor: '#0a0a0a',
                    showCancelButton: true,
                    confirmButtonColor: '#0a0a0a',
                    cancelButtonColor: '#e0e0dc',
                    confirmButtonText: 'Confirmer',
                    cancelButtonText: 'Annuler',
                    customClass: {
                        popup: 'supply-order-confirm-modal',
                        title: 'supply-modal-title',
                        htmlContainer: 'supply-modal-content',
                        confirmButton: 'supply-btn-confirm',
                        cancelButton: 'supply-btn-cancel'
                    },
                    didOpen: (modal) => {
                        // Apply Supply theme styling
                        const popup = modal.querySelector('.supply-order-confirm-modal')
                        if (popup) {
                            popup.style.borderRadius = '8px'
                            popup.style.border = '1px solid #e0e0dc'
                            popup.style.backgroundColor = '#ffffff'
                        }

                        const title = modal.querySelector('.supply-modal-title')
                        if (title) {
                            title.style.color = '#0a0a0a'
                            title.style.fontFamily = 'Instrument Serif, serif'
                            title.style.fontSize = '20px'
                            title.style.marginBottom = '12px'
                        }

                        const confirmBtn = modal.querySelector('.supply-btn-confirm')
                        if (confirmBtn) {
                            confirmBtn.style.backgroundColor = '#0a0a0a'
                            confirmBtn.style.color = '#ffffff'
                            confirmBtn.style.border = 'none'
                            confirmBtn.style.borderRadius = '6px'
                            confirmBtn.style.padding = '10px 24px'
                            confirmBtn.style.fontWeight = '500'
                            confirmBtn.style.cursor = 'pointer'
                        }

                        const cancelBtn = modal.querySelector('.supply-btn-cancel')
                        if (cancelBtn) {
                            cancelBtn.style.backgroundColor = 'transparent'
                            cancelBtn.style.color = '#666660'
                            cancelBtn.style.border = '1px solid #e0e0dc'
                            cancelBtn.style.borderRadius = '6px'
                            cancelBtn.style.padding = '10px 24px'
                            cancelBtn.style.fontWeight = '500'
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading animation
                        Swal.fire({
                            title: 'Traitement du paiement',
                            html: `
                                <div style="text-align: center; padding: 40px 20px;">
                                    <svg style="width: 60px; height: 60px; animation: spin 2s linear infinite; margin-bottom: 20px;" viewBox="0 0 24 24" fill="none" stroke="#0a0a0a" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" stroke-dasharray="62.8" stroke-dashoffset="15.7" stroke-linecap="round"></circle>
                                    </svg>
                                    <p style="color: #666660; font-family: Geist, sans-serif; font-size: 14px; margin-bottom: 20px;">
                                        Vérification du paiement en cours...
                                    </p>
                                    <div style="width: 100%; height: 4px; background: #e0e0dc; border-radius: 2px; overflow: hidden;">
                                        <div style="width: 0%; height: 100%; background: #0a0a0a; animation: progress 3s ease-in-out forwards;"></div>
                                    </div>
                                </div>
                                <style>
                                    @keyframes spin {
                                        from { transform: rotate(0deg); }
                                        to { transform: rotate(360deg); }
                                    }
                                    @keyframes progress {
                                        from { width: 0%; }
                                        to { width: 100%; }
                                    }
                                </style>
                            `,
                            icon: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            customClass: {
                                popup: 'supply-loading-modal'
                            },
                            didOpen: (modal) => {
                                const popup = modal.querySelector('.supply-loading-modal')
                                if (popup) {
                                    popup.style.borderRadius = '8px'
                                    popup.style.border = '1px solid #e0e0dc'
                                    popup.style.backgroundColor = '#ffffff'
                                }
                            }
                        })

                        // Simulate payment processing (3 seconds)
                        setTimeout(() => {
                            // Show success message
                            Swal.fire({
                                title: 'Paiement confirmé! ✅',
                                html: `
                                    <div style="text-align: center; padding: 20px;">
                                        <p style="color: #666660; font-family: Geist, sans-serif; font-size: 14px; margin-bottom: 10px;">
                                            La commande a été confirmée et le paiement a été traité avec succès.
                                        </p>
                                        <p style="color: #15803d; font-family: Geist Mono, monospace; font-size: 12px; margin-top: 15px;">
                                            La page sera actualisée...
                                        </p>
                                    </div>
                                `,
                                icon: 'success',
                                iconColor: '#15803d',
                                confirmButtonColor: '#0a0a0a',
                                confirmButtonText: 'OK',
                                customClass: {
                                    popup: 'supply-success-modal',
                                    title: 'supply-modal-title'
                                },
                                didOpen: (modal) => {
                                    const popup = modal.querySelector('.supply-success-modal')
                                    if (popup) {
                                        popup.style.borderRadius = '8px'
                                        popup.style.border = '1px solid #e0e0dc'
                                        popup.style.backgroundColor = '#ffffff'
                                    }
                                }
                            }).then(() => {
                                // Submit the actual form after animation
                                form.submit()
                            })
                        }, 3000)
                    }
                })
            })
        }
    })
})
</script>
@endsection
