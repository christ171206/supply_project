@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f7f7f5]">
<div class="max-w-2xl mx-auto px-4 py-10">

    {{-- Header --}}
    <div class="mb-10">
        <a href="{{ route('vendor.message-templates.index') }}" class="inline-flex items-center gap-1 text-[12px] text-[#a0a09a] hover:text-[#0a0a0a] mb-4">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Retour aux modèles
        </a>
        <h1 class="font-serif text-[32px] text-[#0a0a0a] mb-2">Créer un message modèle</h1>
        <p class="text-[13px] text-[#a0a09a]">Préparez des messages pour vos promotions et communications</p>
    </div>

    {{-- Templates Suggestions --}}
    @if($defaultTemplates)
        <div class="mb-8">
            <p class="text-[11px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-3">Modèles suggérés</p>
            <div class="grid grid-cols-2 gap-3 mb-8">
                @foreach($defaultTemplates as $suggestion)
                    <button type="button" onclick="fillTemplate('{{ $suggestion['title'] }}', '{{ $suggestion['category'] }}', '{{ addslashes($suggestion['content']) }}')"
                            class="text-left bg-white border border-[#e0e0dc] rounded-lg p-3 hover:border-[#0a0a0a] transition-all">
                        <div class="text-[11px] text-[#a0a09a] uppercase tracking-wide">{{ $suggestion['category'] }}</div>
                        <div class="text-[12px] font-medium text-[#0a0a0a] mt-1">{{ $suggestion['title'] }}</div>
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('vendor.message-templates.store') }}" class="bg-white border border-[#e0e0dc] rounded-lg p-8 space-y-6">
        @csrf

        {{-- Title --}}
        <div>
            <label for="title" class="block text-[11px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                Titre du modèle
            </label>
            <input type="text" id="title" name="title" placeholder="Ex: Nouvelle promotion"
                   class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none @error('title') border-red-500 @enderror"
                   maxlength="100" required value="{{ old('title') }}">
            @error('title') <span class="text-[11px] text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Category --}}
        <div>
            <label for="category" class="block text-[11px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                Catégorie
            </label>
            <select id="category" name="category" required
                    class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none @error('category') border-red-500 @enderror">
                <option value="">Sélectionner</option>
                <option value="Promotion" {{ old('category') === 'Promotion' ? 'selected' : '' }}>Promotion</option>
                <option value="Service" {{ old('category') === 'Service' ? 'selected' : '' }}>Service</option>
                <option value="Autre" {{ old('category') === 'Autre' ? 'selected' : '' }}>Autre</option>
            </select>
            @error('category') <span class="text-[11px] text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Content --}}
        <div>
            <label for="content" class="block text-[11px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                Contenu du message
            </label>
            <textarea id="content" name="content" placeholder="Écrivez votre message..." rows="8" maxlength="2000"
                      class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none @error('content') border-red-500 @enderror"
                      required>{{ old('content') }}</textarea>
            <div class="flex items-center justify-between text-[11px] text-[#a0a09a] mt-1">
                <span>Min: 10 caractères, Max: 2000</span>
                <span><span id="char-count">0</span>/2000</span>
            </div>
            @error('content') <span class="text-[11px] text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex gap-3 pt-4">
            <a href="{{ route('vendor.message-templates.index') }}"
               class="flex-1 px-4 py-3 border border-[#e0e0dc] text-[#0a0a0a] text-[13px] font-medium rounded-lg hover:bg-[#f7f7f5] transition-colors text-center">
                Annuler
            </a>
            <button type="submit"
                    class="flex-1 px-4 py-3 bg-[#0a0a0a] text-white text-[13px] font-medium rounded-lg hover:opacity-90 transition-opacity">
                Créer le modèle
            </button>
        </div>
    </form>

</div>
</div>

<script>
function fillTemplate(title, category, content) {
    document.getElementById('title').value = title;
    document.getElementById('category').value = category;
    document.getElementById('content').value = content;
    updateCharCount();
    document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
}

const textarea = document.getElementById('content');
textarea.addEventListener('input', updateCharCount);

function updateCharCount() {
    const count = document.getElementById('content').value.length;
    document.getElementById('char-count').textContent = count;
}
</script>
@endsection
