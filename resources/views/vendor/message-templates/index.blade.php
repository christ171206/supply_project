@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f7f7f5]">
<div class="max-w-4xl mx-auto px-4 py-10">

    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('vendeur.dashboard') }}" class="inline-flex items-center gap-1 text-[12px] text-[#a0a09a] hover:text-[#0a0a0a] mb-4">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Tableau de bord
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-serif text-[32px] text-[#0a0a0a] mb-1">Messages modèles</h1>
                <p class="text-[13px] text-[#a0a09a]">Gérez vos messages prêts pour les promotions et le support</p>
            </div>
            <a href="{{ route('vendor.message-templates.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0a0a0a] text-white text-[13px] font-medium rounded-lg hover:opacity-90">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Ajouter
            </a>
        </div>
    </div>

    {{-- Empty State --}}
    @if($templates->isEmpty())
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-10 text-center">
            <svg class="w-12 h-12 text-[#a0a09a] mx-auto mb-3 opacity-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <p class="text-[13px] text-[#a0a09a] mb-4">Aucun message modèle pour le moment</p>
            <a href="{{ route('vendor.message-templates.create') }}" class="text-[12px] font-medium text-[#0a0a0a] hover:underline">
                Créer votre premier modèle →
            </a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($templates as $template)
                <div class="bg-white border border-[#e0e0dc] rounded-lg p-5 hover:border-[#0a0a0a] transition-all">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="font-medium text-[13px] text-[#0a0a0a]">{{ $template->title }}</h3>
                                <span class="inline-block px-2 py-0.5 bg-[#f7f7f5] text-[#666660] text-[10px] font-medium rounded">
                                    {{ $template->category }}
                                </span>
                                @if(!$template->is_active)
                                    <span class="inline-block px-2 py-0.5 bg-[#fee2e2] text-[#991b1b] text-[10px] font-medium rounded">
                                        Désactivé
                                    </span>
                                @endif
                            </div>
                            <p class="text-[12px] text-[#666660] leading-relaxed line-clamp-2">{{ $template->content }}</p>
                        </div>
                        <div class="flex items-center gap-2 ml-4 flex-shrink-0">
                            <a href="{{ route('vendor.message-templates.edit', $template) }}"
                               class="text-[12px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors">
                                Éditer
                            </a>
                            <form method="POST" action="{{ route('vendor.message-templates.toggle', $template) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-[12px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors">
                                    {{ $template->is_active ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('vendor.message-templates.destroy', $template) }}" class="inline"
                                  onsubmit="return confirm('Êtes-vous sûr?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[12px] text-red-600 hover:text-red-700 transition-colors">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
</div>
@endsection
