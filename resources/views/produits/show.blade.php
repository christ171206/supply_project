@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 mb-8 text-sm text-gray-600">
        <a href="{{ route('accueil') }}" class="hover:text-blue-600">Accueil</a>
        <span>/</span>
        <a href="{{ route('produits.catalogue') }}" class="hover:text-blue-600">Catalogue</a>
        <span>/</span>
        @if($produit->categorie)
            <a href="{{ route('produits.catalogue', ['categorie' => $produit->categorie->id]) }}" class="hover:text-blue-600">
                {{ $produit->categorie->nom }}
            </a>
            <span>/</span>
        @endif
        <span class="text-gray-900 font-semibold">{{ $produit->nom }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Image Produit -->
        <div class="lg:col-span-1">
            <div class="bg-gray-200 rounded-lg overflow-hidden sticky top-24">
                @if($produit->image)
                    <img src="{{ asset('storage/produits/' . $produit->image) }}" alt="{{ $produit->nom }}" class="w-full h-auto object-cover">
                @else
                    <div class="w-full aspect-square flex items-center justify-center bg-gray-300">
                        <span class="text-6xl">📦</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Détails Produit -->
        <div class="lg:col-span-2">
            <!-- Catégorie -->
            @if($produit->categorie)
                <div class="mb-4">
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                        {{ $produit->categorie->nom }}
                    </span>
                </div>
            @endif

            <!-- Titre -->
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $produit->nom }}</h1>

            <!-- Vendeur - Amélioration -->
            @if($produit->vendeur)
                <div class="mb-8 pb-8 border-b-2 border-gray-200">
                    <div class="bg-gradient-to-r from-primary-50 to-secondary-50 rounded-xl p-6 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary-400 to-secondary-400 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                {{ strtoupper(substr($produit->vendeur->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm font-semibold">🏪 VENDU PAR</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $produit->vendeur->shop_name ?? $produit->vendeur->name }}</p>
                                <p class="text-gray-600 text-sm mt-1">{{ $produit->vendeur->address ?? 'Boutique en ligne' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center gap-2 justify-end mb-2">
                                <span class="text-yellow-500">⭐</span>
                                <span class="font-bold text-gray-900">4.7/5</span>
                                <span class="text-gray-600 text-sm">(145 avis)</span>
                            </div>

                        </div>
                    </div>
                </div>
            @endif

            <!-- Prix -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <div class="flex items-baseline gap-4">
                    <span class="text-5xl font-bold text-gray-900">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
                    @if($produit->prix_original && $produit->prix_original > $produit->prix)
                        <span class="text-2xl text-gray-500 line-through">
                            {{ number_format($produit->prix_original, 2, ',', ' ') }} €
                        </span>
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-bold">
                            -{{ round(((($produit->prix_original - $produit->prix) / $produit->prix_original) * 100)) }}%
                        </span>
                    @endif
                </div>
            </div>

            <!-- Stock -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                @if($produit->stock > 0)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-green-600 font-semibold">{{ $produit->stock }} en stock</span>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-red-600 font-semibold">Rupture de stock</span>
                    </div>
                @endif
            </div>

            <!-- Description -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Description</h2>
                <p class="text-gray-700 text-lg leading-relaxed">{{ $produit->description }}</p>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 mb-8">
                @if($produit->stock > 0)
                    <button
                        type="button"
                        onclick="openQuantityModal({{ $produit->id }}, '{{ $produit->nom }}', {{ $produit->stock }}, {{ $produit->prix }})"
                        class="flex-1 px-8 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold text-lg"
                    >
                        🛒 Ajouter au Panier
                    </button>
                @else
                    <button disabled class="flex-1 px-8 py-4 bg-gray-400 text-white rounded-lg cursor-not-allowed font-bold text-lg">
                        Indisponible
                    </button>
                @endif
                <button class="px-8 py-4 border-2 border-gray-300 text-gray-700 rounded-lg hover:border-gray-400 transition font-bold text-lg">
                    ❤️
                </button>
            </div>

            <!-- Caractéristiques -->
            <div class="bg-gray-50 p-6 rounded-lg mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Informations</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Référence</p>
                        <p class="text-gray-900 font-semibold">{{ $produit->id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Catégorie</p>
                        <p class="text-gray-900 font-semibold">{{ $produit->categorie?->nom ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Stock Disponible</p>
                        <p class="text-gray-900 font-semibold">{{ $produit->stock }} unités</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Actif</p>
                        <p class="text-gray-900 font-semibold">{{ $produit->est_actif ? '✓ Oui' : '✗ Non' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Avis Clients - Section Complète -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 mb-12">
        <!-- Gauche: Résumé des Avis -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-2xl p-8 border border-yellow-200 sticky top-24">
                <!-- Étoiles Globales -->
                <div class="mb-6">
                    <div class="flex items-end gap-3 mb-4">
                        <span class="text-5xl font-bold text-gray-900">{{ number_format($produit->note_moyenne ?? 4.5, 1) }}</span>
                        <span class="text-gray-600 text-sm mb-1">/ 5</span>
                    </div>
                    <div class="flex gap-1 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-2xl">{{ $i <= round($produit->note_moyenne ?? 4.5) ? '⭐' : '☆' }}</span>
                        @endfor
                    </div>
                    <p class="text-gray-600 text-sm">{{ $produit->nombre_avis ?? 0 }} avis</p>
                </div>

                <hr class="my-6 border-yellow-200">

                <!-- Répartition des Notes -->
                <div class="space-y-3">
                    @for($i = 5; $i >= 1; $i--)
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-700 w-8">{{ $i }} ⭐</span>
                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-yellow-500 rounded-full" style="width: {{ rand(10, 80) }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600 w-8 text-right">{{ rand(2, 15) }}</span>
                        </div>
                    @endfor
                </div>

                <!-- CTA Avis -->
                @auth
                    <button
                        onclick="document.getElementById('form-avis').scrollIntoView({ behavior: 'smooth' })"
                        class="w-full mt-6 px-4 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-lg hover:from-primary-600 hover:to-primary-700 transition duration-200"
                    >
                        ✍️ Donner votre avis
                    </button>
                @endauth

                @guest
                    <a
                        href="{{ route('login') }}"
                        class="block w-full mt-6 px-4 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-lg hover:from-primary-600 hover:to-primary-700 transition duration-200 text-center"
                    >
                        🔑 Se connecter pour avis
                    </a>
                @endguest
            </div>
        </div>

        <!-- Droite: Liste des Avis -->
        <div class="lg:col-span-2">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Avis Clients</h2>

            @if($avis && $avis->count() > 0)
                <div class="space-y-6">
                    @foreach($avis as $av)
                        <div class="bg-white rounded-xl border-2 border-gray-100 p-6 hover:shadow-lg transition duration-200">
                            <!-- Header Avis -->
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $av->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $av->created_at->locale('fr')->diffForHumans() }}</p>
                                </div>
                                <div class="flex gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="text-xl">{{ $i <= $av->note ? '⭐' : '☆' }}</span>
                                    @endfor
                                </div>
                            </div>

                            <!-- Contenu Avis -->
                            <p class="text-gray-700 leading-relaxed">{{ $av->commentaire }}</p>

                            <!-- Actions Avis -->
                            @auth
                                @if(auth()->id() === $av->user_id)
                                    <div class="mt-4 flex gap-2">
                                        <form action="{{ route('avis.destroy', $av->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                onclick="return confirm('Êtes-vous sûr?')"
                                                class="text-sm text-red-600 hover:text-red-700 font-medium"
                                            >
                                                🗑️ Supprimer
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($avis->hasPages())
                    <div class="mt-8">
                        {{ $avis->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                    <p class="text-4xl mb-3">💬</p>
                    <p class="text-gray-600 text-lg">Aucun avis pour le moment</p>
                    <p class="text-gray-500 text-sm mt-1">Soyez le premier à donner votre avis !</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Formulaire Ajouter Avis -->
    @auth
        <div id="form-avis" class="bg-gradient-to-br from-primary-50 to-secondary-50 rounded-2xl p-8 mb-12 border-2 border-primary-200">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">✍️ Votre Avis</h3>
            <p class="text-gray-600 mb-6">Aidez les autres clients à faire le bon choix</p>

            <form action="{{ route('avis.store') }}" method="POST" class="space-y-6">
                @csrf

                <input type="hidden" name="produit_id" value="{{ $produit->id }}">

                <!-- Note -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Votre Note</label>
                    <div class="flex gap-3" id="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer group" data-rating="{{ $i }}">
                                <input type="radio" name="note" value="{{ $i }}" class="hidden rating-input" required>
                                <span class="text-5xl group-hover:scale-125 transition duration-200 inline-block rating-star">
                                    ☆
                                </span>
                            </label>
                        @endfor
                    </div>
                    @error('note')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const ratingStars = document.getElementById('rating-stars');
                        const ratingInputs = ratingStars.querySelectorAll('.rating-input');
                        const ratingSpans = ratingStars.querySelectorAll('.rating-star');
                        const labels = ratingStars.querySelectorAll('label');

                        // Hover effect
                        labels.forEach((label, index) => {
                            label.addEventListener('mouseenter', () => {
                                ratingSpans.forEach((span, i) => {
                                    span.textContent = i <= index ? '⭐' : '☆';
                                });
                            });

                            // Click to select
                            label.addEventListener('click', () => {
                                ratingInputs[index].checked = true;
                                updateStars(index);
                            });
                        });

                        // Reset when leaving
                        ratingStars.addEventListener('mouseleave', () => {
                            const selectedIndex = Array.from(ratingInputs).findIndex(input => input.checked);
                            updateStars(selectedIndex);
                        });

                        function updateStars(selectedIndex) {
                            ratingSpans.forEach((span, i) => {
                                span.textContent = i <= selectedIndex ? '⭐' : '☆';
                            });
                        }
                    });
                </script>

                <!-- Commentaire -->
                <div>
                    <label for="commentaire" class="block text-sm font-semibold text-gray-700 mb-2">Votre Avis</label>
                    <textarea
                        id="commentaire"
                        name="commentaire"
                        rows="5"
                        placeholder="Partagez votre expérience avec ce produit... (min. 10 caractères)"
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition @error('commentaire') border-red-500 @enderror"
                    ></textarea>
                    @error('commentaire')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons -->
                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-lg hover:from-primary-600 hover:to-primary-700 transition duration-200 shadow-lg"
                    >
                        📤 Publier mon Avis
                    </button>
                    <button
                        type="reset"
                        class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition duration-200"
                    >
                        ↺ Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl p-8 mb-12 text-center">
            <p class="text-blue-900 mb-4">🔐 Vous devez être connecté pour laisser un avis</p>
            <a
                href="{{ route('login') }}"
                class="inline-block px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-lg hover:from-primary-600 hover:to-primary-700 transition duration-200"
            >
                🔑 Se Connecter
            </a>
        </div>
    @endauth

    <!-- Contacter le Vendeur -->
    @if($produit->vendeur)
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-8 mb-12 border-2 border-purple-200">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-12 bg-purple-200 rounded-full flex items-center justify-center text-2xl">
                    📧
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Contacter le Vendeur</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- Info Vendeur -->
                <div class="md:col-span-1 bg-white rounded-xl p-6 border border-purple-100">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-3xl">
                            👤
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Vendeur</p>
                            <p class="text-lg font-bold text-gray-900">{{ $produit->vendeur->name }}</p>
                        </div>
                    </div>

                    @if($produit->vendeur->phone)
                        <div class="mb-4 pb-4 border-b border-gray-200">
                            <p class="text-xs text-gray-600 mb-2">📞 Téléphone</p>
                            <a href="tel:{{ $produit->vendeur->phone }}" class="text-purple-600 font-semibold hover:text-purple-700">
                                {{ $produit->vendeur->phone }}
                            </a>
                        </div>
                    @endif

                    <div>
                        <p class="text-xs text-gray-600 mb-2">📧 Email</p>
                        <a href="mailto:{{ $produit->vendeur->email }}" class="text-purple-600 font-semibold hover:text-purple-700 break-all">
                            {{ $produit->vendeur->email }}
                        </a>
                    </div>
                </div>

                <!-- Formulaire de Contact -->
                <div class="md:col-span-2">
                    @auth
                        <form action="{{ route('messages.store') }}" method="POST" class="bg-white rounded-xl p-6 border border-purple-100 space-y-4">
                            @csrf
                            <input type="hidden" name="destinataire_id" value="{{ $produit->vendeur->id }}">
                            <input type="hidden" name="produit_id" value="{{ $produit->id }}">

                            <!-- Sujet -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Sujet</label>
                                <input
                                    type="text"
                                    name="sujet"
                                    value="{{ old('sujet', 'Demande d\'information sur : ' . $produit->nom) }}"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('sujet') border-red-500 @enderror"
                                >
                                @error('sujet')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Message -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Message</label>
                                <textarea
                                    name="contenu"
                                    rows="4"
                                    placeholder="Votre message..."
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none @error('contenu') border-red-500 @enderror"
                                >{{ old('contenu') }}</textarea>
                                @error('contenu')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bouton -->
                            <button
                                type="submit"
                                class="w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-3 rounded-lg transition transform hover:scale-105 shadow-lg"
                            >
                                📤 Envoyer le Message
                            </button>
                        </form>
                    @else
                        <div class="bg-white rounded-xl p-8 border border-purple-100 text-center">
                            <p class="text-gray-600 mb-6">🔐 Connectez-vous pour contacter le vendeur</p>
                            <div class="flex gap-4 justify-center">
                                <a
                                    href="{{ route('login') }}"
                                    class="inline-block px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-bold rounded-lg hover:from-purple-600 hover:to-pink-600 transition"
                                >
                                    🔑 Se Connecter
                                </a>
                                <a
                                    href="{{ route('register') }}"
                                    class="inline-block px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition"
                                >
                                    📝 S'inscrire
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    @endif

    <!-- Produits Recommandés -->
    @if($produitsSimilaires && count($produitsSimilaires) > 0)
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Produits Similaires</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($produitsSimilaires as $similaire)
                    @include('components.carte-produit', ['produit' => $similaire])
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
