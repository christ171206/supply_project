<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification d'identité — Supply</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-[#f7f7f5]">
<div class="min-h-screen py-12 px-4">
<div class="max-w-2xl mx-auto">

    <a href="{{ route('accueil') }}"
       class="inline-flex items-center gap-2 text-[12px] text-[#666660] hover:text-[#0a0a0a] mb-8 transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path d="M15 19l-7-7 7-7"/>
        </svg>
        Retour
    </a>

    <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">

        {{-- Header --}}
        <div class="border-b border-[#e0e0dc] px-8 py-7">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#0a0a0a] rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path d="M9 12h6m-6 4h6m2-13H7a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="font-serif text-[22px] text-[#0a0a0a] leading-none">Vérification d'identité</h1>
                    <p class="text-[11px] text-[#a0a09a] mt-1">Étape 2 sur 3 — Documents requis</p>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="px-8 py-7 space-y-6">

            <div class="bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-4 py-3 text-[12px]">
                <span class="font-medium text-[#0a0a0a]">Important :</span>
                <span class="text-[#666660]"> Fournissez une copie claire des deux côtés (recto et verso) de votre document d'identité.</span>
            </div>

            <form method="POST" action="{{ route('vendor.documents.store') }}"
                  enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Type de document --}}
                <div>
                    <label class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">
                        Type de document
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach(['cni' => 'Carte ID', 'cmu' => 'CMU', 'passport' => 'Passeport'] as $type => $label)
                            <label class="relative flex items-center gap-2 p-3 border border-[#e0e0dc] rounded-lg cursor-pointer
                                          hover:bg-[#f7f7f5] hover:border-[#2a2a28] transition-all
                                          has-[:checked]:border-[#0a0a0a] has-[:checked]:bg-[#f7f7f5]">
                                <input type="radio" name="id_type" value="{{ $type }}"
                                       {{ old('id_type') === $type ? 'checked' : '' }}
                                       class="accent-[#0a0a0a] w-3.5 h-3.5 flex-shrink-0">
                                <span class="text-[13px] text-[#0a0a0a]">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('id_type')
                        <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Numéro --}}
                <div>
                    <label class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">
                        Numéro du document
                    </label>
                    <input type="text" name="id_number" value="{{ old('id_number') }}" required
                           placeholder="Ex : AB123456"
                           class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2.5
                                  text-[13px] text-[#0a0a0a] font-mono placeholder-[#a0a09a]
                                  focus:bg-white focus:border-[#0a0a0a] outline-none transition-all
                                  @error('id_number') border-[#f87171] bg-[#fef2f2] @enderror">
                    @error('id_number')
                        <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Recto --}}
                <div>
                    <label class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">
                        Recto (avant)
                    </label>
                    <div id="dropzone-front"
                         class="border border-dashed border-[#e0e0dc] rounded-xl p-6 text-center
                                hover:border-[#2a2a28] hover:bg-[#f7f7f5] transition-all cursor-pointer"
                         onclick="document.getElementById('id_front').click()">
                        <div class="w-8 h-8 border border-[#e0e0dc] rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-4 h-4 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="4" width="18" height="14" rx="2"/>
                                <circle cx="8.5" cy="9.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>
                        <p class="text-[12px] font-medium text-[#0a0a0a]">Face avant du document</p>
                        <p class="text-[11px] text-[#a0a09a] font-light mt-0.5">Cliquez pour sélectionner — tous les coins visibles</p>
                    </div>
                    <input type="file" id="id_front" name="id_front" accept="image/*" required
                           onchange="previewImage(this, 'preview_front', 'dropzone-front')"
                           class="hidden">
                    <img id="preview_front"
                         class="hidden mt-3 w-full rounded-xl border border-[#e0e0dc] max-h-44 object-cover">
                    @error('id_front')
                        <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Verso --}}
                <div>
                    <label class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">
                        Verso (arrière)
                    </label>
                    <div id="dropzone-back"
                         class="border border-dashed border-[#e0e0dc] rounded-xl p-6 text-center
                                hover:border-[#2a2a28] hover:bg-[#f7f7f5] transition-all cursor-pointer"
                         onclick="document.getElementById('id_back').click()">
                        <div class="w-8 h-8 border border-[#e0e0dc] rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-4 h-4 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="4" width="18" height="14" rx="2"/>
                                <line x1="7" y1="9" x2="17" y2="9"/><line x1="7" y1="12" x2="13" y2="12"/>
                            </svg>
                        </div>
                        <p class="text-[12px] font-medium text-[#0a0a0a]">Face arrière du document</p>
                        <p class="text-[11px] text-[#a0a09a] font-light mt-0.5">Numéro bien visible, bonne luminosité</p>
                    </div>
                    <input type="file" id="id_back" name="id_back" accept="image/*" required
                           onchange="previewImage(this, 'preview_back', 'dropzone-back')"
                           class="hidden">
                    <img id="preview_back"
                         class="hidden mt-3 w-full rounded-xl border border-[#e0e0dc] max-h-44 object-cover">
                    @error('id_back')
                        <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Erreurs globales --}}
                @if($errors->any() && !$errors->has(['id_type','id_number','id_front','id_back']))
                    <div class="flex items-start gap-3 bg-[#fef2f2] border border-[#fecaca] rounded-lg px-4 py-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#f87171] flex-shrink-0 mt-1.5"></span>
                        <div class="space-y-0.5">
                            @foreach($errors->all() as $error)
                                <p class="text-[12px] text-[#dc2626]">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="flex gap-2 pt-4 border-t border-[#efefed]">
                    <button type="submit"
                            class="flex-1 bg-[#0a0a0a] text-white text-[12px] font-medium py-2.5 rounded-lg
                                   hover:opacity-85 transition-opacity">
                        Soumettre les documents
                    </button>
                    <a href="{{ route('accueil') }}"
                       class="flex-1 text-center border border-[#e0e0dc] text-[#666660] text-[12px] font-medium py-2.5 rounded-lg
                              hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                        Annuler
                    </a>
                </div>

            </form>
        </div>
    </div>

    <div class="mt-5 px-4 py-3 bg-white border border-[#e0e0dc] rounded-xl text-[11px] text-[#666660] flex items-start gap-2.5">
        <svg class="w-3.5 h-3.5 text-[#a0a09a] flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span><span class="font-medium text-[#0a0a0a]">Conseil :</span> Photos claires, fond neutre, tous les coins visibles, document lisible.</span>
    </div>

</div>
</div>

<script>
function previewImage(input, previewId, dropzoneId) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById(previewId);
        img.src = e.target.result;
        img.classList.remove('hidden');
        const dz = document.getElementById(dropzoneId);
        dz.classList.add('border-[#0a0a0a]', 'bg-[#f7f7f5]');
        dz.classList.remove('border-dashed');
    };
    reader.readAsDataURL(file);
}
</script>
</body>
</html>
