@extends('layouts.admin-layout')

@section('title', 'Nouvelle catégorie — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp;
    <a href="{{ route('admin.categories.index') }}" class="hover:text-[#0a0a0a] transition-colors">Catégories</a>
    &nbsp;/&nbsp; Créer
@endsection

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <a href="{{ route('admin.categories.index') }}"
           class="inline-flex items-center gap-1.5 text-[11px] text-white/40 hover:text-white/70 transition-colors mb-4">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Retour aux catégories
        </a>
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-2">Administration</div>
        <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">Nouvelle catégorie</h1>
    </div>

    <div class="flex justify-center px-8">
        <div class="max-w-xl w-full">

            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-6 py-5 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Informations</span>
                </div>

                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-6 py-5 space-y-5">

                        {{-- Nom --}}
                        <div>
                            <label for="nom" class="block text-[11px] font-medium text-[#666660] mb-1.5">
                                Nom <span class="text-[#dc2626]">*</span>
                            </label>
                            <input type="text" name="nom" id="nom"
                                   value="{{ old('nom') }}"
                                   placeholder="Ex : Électroniques"
                                   required
                                   class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2.5 text-[13px] text-[#0a0a0a]
                                          placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all
                                          @error('nom') border-[#f87171] bg-[#fef2f2] @enderror">
                            @error('nom')
                                <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Image --}}
                        <div>
                            <label for="image" class="block text-[11px] font-medium text-[#666660] mb-1.5">
                                Image de la catégorie
                            </label>
                            <div class="relative">
                                <input type="file" name="image" id="image"
                                       accept="image/*"
                                       class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2.5 text-[13px] text-[#0a0a0a]
                                              placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all
                                              file:mr-3 file:py-2 file:px-3 file:rounded-md file:bg-[#0a0a0a] file:text-white file:cursor-pointer
                                              file:text-[11px] file:font-medium file:border-0
                                              @error('image') border-[#f87171] bg-[#fef2f2] @enderror">
                                <p class="text-[11px] text-[#a0a09a] mt-1">Format: JPG, PNG, WebP — Max 2 MB</p>
                            </div>
                            @error('image')
                                <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-[11px] font-medium text-[#666660] mb-1.5">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="4"
                                      placeholder="Décrire cette catégorie…"
                                      class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2.5 text-[13px] text-[#0a0a0a]
                                             placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all resize-none
                                             @error('description') border-[#f87171] bg-[#fef2f2] @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Toggle actif --}}
                        <div class="flex items-center gap-3">
                            <button type="button" id="toggle-active"
                                    onclick="toggleActive()"
                                    class="relative w-9 h-5 rounded-full transition-colors duration-200 {{ old('is_active', true) ? 'bg-[#0a0a0a]' : 'bg-[#e0e0dc]' }}"
                                    data-active="{{ old('is_active', true) ? '1' : '0' }}">
                                <span id="toggle-thumb"
                                      class="absolute top-0.5 w-4 h-4 bg-white rounded-full transition-transform duration-200
                                             {{ old('is_active', true) ? 'translate-x-4' : 'translate-x-0.5' }}"></span>
                            </button>
                            <input type="hidden" name="is_active" id="is_active-input"
                                   value="{{ old('is_active', true) ? '1' : '0' }}">
                            <span class="text-[13px] text-[#0a0a0a] cursor-pointer select-none" onclick="toggleActive()">
                                Actif <span class="text-[11px] text-[#a0a09a] font-light">(visible aux clients)</span>
                            </span>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-[#efefed]">
                        <a href="{{ route('admin.categories.index') }}"
                           class="text-[12px] font-medium text-[#666660] border border-[#e0e0dc] px-4 py-2 rounded-lg
                                  hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                            Annuler
                        </a>
                        <button type="submit"
                                class="bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-lg
                                       hover:opacity-85 transition-opacity flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Créer la catégorie
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@section('scripts')
<script>
function toggleActive() {
    const btn   = document.getElementById('toggle-active');
    const thumb = document.getElementById('toggle-thumb');
    const input = document.getElementById('is_active-input');
    const isOn  = btn.dataset.active === '1';
    if (isOn) {
        btn.dataset.active = '0';
        btn.style.backgroundColor = '#e0e0dc';
        thumb.style.transform = 'translateX(2px)';
        input.value = '0';
    } else {
        btn.dataset.active = '1';
        btn.style.backgroundColor = '#0a0a0a';
        thumb.style.transform = 'translateX(16px)';
        input.value = '1';
    }
}
// Init
(function() {
    const btn = document.getElementById('toggle-active');
    const thumb = document.getElementById('toggle-thumb');
    if (btn.dataset.active === '1') {
        btn.style.backgroundColor = '#0a0a0a';
        thumb.style.transform = 'translateX(16px)';
    } else {
        btn.style.backgroundColor = '#e0e0dc';
        thumb.style.transform = 'translateX(2px)';
    }
})();
</script>
@endsection

@endsection
