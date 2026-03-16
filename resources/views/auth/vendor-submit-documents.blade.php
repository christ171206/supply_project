<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification d'identité - Supply</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-[#f7f7f5]">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-2xl mx-auto">
            <a href="{{ route('accueil') }}" class="inline-flex items-center gap-2 text-sm text-[#666660] hover:text-[#0a0a0a] mb-8 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7"/>
                </svg>
                Retour
            </a>

            <div class="bg-white border border-[#e0e0dc] rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="border-b border-[#e0e0dc] p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-[#0a0a0a] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12h6m-6 4h6m2-13H7a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="font-serif text-2xl text-[#0a0a0a]">Vérification d'identité</h1>
                            <p class="text-xs text-[#a0a09a]">Étape 2 sur 3 - Documents requis</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-8 space-y-6">
                    <div class="bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg p-4 text-sm">
                        <strong class="text-[#0a0a0a]">Important :</strong> <span class="text-[#666660]">Fournissez une copie claire des deux côtés (recto et verso) de votre document d'identité.</span>
                    </div>

                    <form method="POST" action="{{ route('vendor.documents.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Document type -->
                        <div>
                            <label class="block text-sm font-medium text-[#0a0a0a] mb-3">Type de document</label>
                            <div class="grid grid-cols-3 gap-3">
                                @foreach(['cni' => 'Carte ID', 'cmu' => 'CMU', 'passport' => 'Passeport'] as $type => $label)
                                    <label class="relative flex items-center p-3 border border-[#e0e0dc] rounded-lg cursor-pointer hover:bg-[#f7f7f5] transition">
                                        <input type="radio" name="id_type" value="{{ $type }}" {{ old('id_type') === $type ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-[#0a0a0a]">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('id_type')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Document number -->
                        <div>
                            <label class="text-sm font-medium mb-2 block text-[#0a0a0a]">Numéro du document</label>
                            <input type="text" name="id_number" value="{{ old('id_number') }}" required
                                placeholder="Ex: AB123456"
                                class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-sm focus:border-[#0a0a0a] focus:outline-none bg-white" />
                            @error('id_number')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Front photo -->
                        <div>
                            <label class="text-sm font-medium mb-2 block text-[#0a0a0a]">Recto (avant) 📸</label>
                            <input type="file" name="id_front" accept="image/*" required onchange="previewImage(this, 'preview_front')"
                                class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-sm" />
                            <img id="preview_front" class="hidden mt-3 w-full rounded-lg border border-[#e0e0dc] max-h-40 object-cover" />
                            <p class="text-xs text-[#a0a09a] mt-1.5">📋 Votre carte bien visible, tous les coins</p>
                            @error('id_front')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Back photo -->
                        <div>
                            <label class="text-sm font-medium mb-2 block text-[#0a0a0a]">Verso (arrière) 📸</label>
                            <input type="file" name="id_back" accept="image/*" required onchange="previewImage(this, 'preview_back')"
                                class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-sm" />
                            <img id="preview_back" class="hidden mt-3 w-full rounded-lg border border-[#e0e0dc] max-h-40 object-cover" />
                            <p class="text-xs text-[#a0a09a] mt-1.5">📋 Numéro du document visible, bien éclairé</p>
                            @error('id_back')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        @if ($errors->any() && !$errors->has(['id_type', 'id_number', 'id_front', 'id_back']))
                            <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <!-- Buttons -->
                        <div class="flex gap-3 pt-4 border-t border-[#e0e0dc]">
                            <button type="submit" class="flex-1 bg-[#0a0a0a] text-white py-2 rounded-lg hover:opacity-85 font-medium text-sm transition">
                                Soumettre
                            </button>
                            <a href="{{ route('accueil') }}" class="flex-1 text-center border border-[#e0e0dc] text-[#0a0a0a] py-2 rounded-lg hover:bg-[#f7f7f5] font-medium text-sm transition">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tip -->
            <div class="mt-6 p-4 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg text-xs text-[#666660]">
                <strong>Conseil :</strong> Photos claires, fond blanc, tous les coins visibles, lisible.
            </div>
        </div>
    </div>

    <script>
        function previewImage(input, previewId) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById(previewId).src = e.target.result;
                    document.getElementById(previewId).classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
