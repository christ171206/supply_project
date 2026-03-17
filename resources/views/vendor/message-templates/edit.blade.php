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
        <h1 class="font-serif text-[32px] text-[#0a0a0a] mb-2">Éditer un message modèle</h1>
        <p class="text-[13px] text-[#a0a09a]">{{ $template->title }}</p>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('vendor.message-templates.update', $template) }}" class="bg-white border border-[#e0e0dc] rounded-lg p-8 space-y-6">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div>
            <label for="title" class="block text-[11px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                Titre du modèle
            </label>
            <input type="text" id="title" name="title"
                   class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none @error('title') border-red-500 @enderror"
                   maxlength="100" required value="{{ old('title', $template->title) }}">
            @error('title') <span class="text-[11px] text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Category --}}
        <div>
            <label for="category" class="block text-[11px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                Catégorie
            </label>
            <select id="category" name="category" required
                    class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none @error('category') border-red-500 @enderror">
                <option value="Promotion" {{ old('category', $template->category) === 'Promotion' ? 'selected' : '' }}>Promotion</option>
                <option value="Service" {{ old('category', $template->category) === 'Service' ? 'selected' : '' }}>Service</option>
                <option value="Autre" {{ old('category', $template->category) === 'Autre' ? 'selected' : '' }}>Autre</option>
            </select>
            @error('category') <span class="text-[11px] text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Content --}}
        <div>
            <label for="content" class="block text-[11px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                Contenu du message
            </label>
            <textarea id="content" name="content" rows="8" maxlength="2000"
                      class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none @error('content') border-red-500 @enderror"
                      required>{{ old('content', $template->content) }}</textarea>
            <div class="flex items-center justify-between text-[11px] text-[#a0a09a] mt-1">
                <span>Min: 10 caractères, Max: 2000</span>
                <span><span id="char-count">{{ strlen($template->content) }}</span>/2000</span>
            </div>
            @error('content') <span class="text-[11px] text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Status --}}
        <div class="bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg p-4">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" @if($template->is_active) checked @endif
                       class="w-4 h-4 border border-[#e0e0dc] rounded">
                <span class="text-[13px] text-[#0a0a0a]">Ce modèle est actif et peut être utilisé</span>
            </label>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-3 pt-4">
            <a href="{{ route('vendor.message-templates.index') }}"
               class="flex-1 px-4 py-3 border border-[#e0e0dc] text-[#0a0a0a] text-[13px] font-medium rounded-lg hover:bg-[#f7f7f5] transition-colors text-center">
                Annuler
            </a>
            <button type="submit"
                    class="flex-1 px-4 py-3 bg-[#0a0a0a] text-white text-[13px] font-medium rounded-lg hover:opacity-90 transition-opacity">
                Modifier le modèle
            </button>
        </div>
    </form>

</div>
</div>

<script>
const textarea = document.getElementById('content');
textarea.addEventListener('input', updateCharCount);

function updateCharCount() {
    const count = document.getElementById('content').value.length;
    document.getElementById('char-count').textContent = count;
}
</script>
@endsection
