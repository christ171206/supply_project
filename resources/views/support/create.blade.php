@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f7f7f5] flex flex-col">

    {{-- Header --}}
    <div class="bg-white border-b border-[#e0e0dc] px-4 py-3 sticky top-0 z-10">
        <div class="max-w-2xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('support.index') }}"
                   class="inline-flex items-center justify-center w-8 h-8 text-[#a0a09a] hover:text-[#0a0a0a] hover:bg-[#f7f7f5] rounded-lg transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                </a>
                <div>
                    <h1 class="text-[14px] font-medium text-[#0a0a0a]">Centre de support</h1>
                    <p class="text-[11px] text-[#a0a09a]">Créer une demande</p>
                </div>
            </div>
            <div class="flex gap-1">
                <div class="h-1 w-8 bg-[#0a0a0a] rounded-full"></div>
                <div class="h-1 w-8 bg-[#efefed] rounded-full step-indicator"></div>
                <div class="h-1 w-8 bg-[#efefed] rounded-full step-indicator"></div>
            </div>
        </div>
    </div>

    {{-- Chat --}}
    <div class="flex-1 overflow-y-auto">
    <div class="max-w-2xl mx-auto w-full px-4 py-6">

        <div id="chat-messages" class="space-y-4">
            <div class="flex gap-3 animate-fadeIn">
                <div class="flex-shrink-0 w-7 h-7 rounded-md bg-[#0a0a0a] flex items-center justify-center text-white text-[11px] font-medium">S</div>
                <div class="flex-1">
                    <div class="bg-white border border-[#e0e0dc] rounded-xl p-4">
                        <p class="text-[13px] text-[#0a0a0a] leading-relaxed">
                            Bonjour, je suis ici pour vous aider. <strong>Quel type de problème rencontrez-vous ?</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Options --}}
        <div id="options-container" class="mt-6 space-y-2">
            @php
            $options = [
                ['type' => 'produit',   'label' => 'Problème avec un produit',   'sub' => 'Description, qualité, défaut…'],
                ['type' => 'commande',  'label' => 'Problème avec ma commande',  'sub' => 'Suivi, modification, annulation…'],
                ['type' => 'paiement',  'label' => 'Problème de paiement',       'sub' => 'Transaction, remboursement…'],
                ['type' => 'livraison', 'label' => 'Problème de livraison',      'sub' => 'Délai, adresse, colis endommagé…'],
                ['type' => 'compte',    'label' => 'Problème de compte',         'sub' => 'Connexion, données, sécurité…'],
                ['type' => 'autre',     'label' => 'Autre',                      'sub' => 'Tout ce qui ne rentre pas dans les catégories'],
            ];
            @endphp
            @foreach($options as $opt)
            <button type="button" data-type="{{ $opt['type'] }}"
                    class="chat-option w-full text-left bg-white border border-[#e0e0dc] hover:border-[#2a2a28] hover:bg-[#f7f7f5] rounded-xl p-4 transition-all group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-medium text-[#0a0a0a]">{{ $opt['label'] }}</p>
                        <p class="text-[11px] text-[#a0a09a] mt-1">{{ $opt['sub'] }}</p>
                    </div>
                    <svg class="w-4 h-4 text-[#a0a09a] group-hover:text-[#0a0a0a] transition-colors flex-shrink-0 ml-4"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
            </button>
            @endforeach
        </div>

        {{-- Form --}}
        <form id="support-form" method="POST" action="{{ route('support.store') }}" class="hidden mt-6">
            @csrf
            <input type="hidden" id="support_type" name="support_type">

            {{-- Sujet --}}
            <div id="subject-section" class="hidden">
                <div class="flex gap-3 mb-4 animate-fadeIn">
                    <div class="flex-shrink-0 w-7 h-7 rounded-md bg-[#0a0a0a] flex items-center justify-center text-white text-[11px] font-medium">S</div>
                    <div class="flex-1">
                        <div class="bg-white border border-[#e0e0dc] rounded-xl p-4">
                            <p class="text-[13px] text-[#0a0a0a]"><strong>Quel est le sujet principal ?</strong></p>
                        </div>
                    </div>
                </div>
                <input type="text" name="subject" id="subject" placeholder="Titre court…" maxlength="200" required
                       class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-xl px-4 py-3 text-[13px] text-[#0a0a0a]
                              placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
            </div>

            {{-- Description --}}
            <div id="description-section" class="hidden mt-6">
                <div class="flex gap-3 mb-4 animate-fadeIn">
                    <div class="flex-shrink-0 w-7 h-7 rounded-md bg-[#0a0a0a] flex items-center justify-center text-white text-[11px] font-medium">S</div>
                    <div class="flex-1">
                        <div class="bg-white border border-[#e0e0dc] rounded-xl p-4">
                            <p class="text-[13px] text-[#0a0a0a]"><strong>Décrivez le problème en détail</strong></p>
                            <p class="text-[11px] text-[#a0a09a] mt-2">Plus vous nous donnez de détails, plus vite nous pourrons vous aider</p>
                        </div>
                    </div>
                </div>
                <textarea name="description" id="description" placeholder="Je rencontre…" rows="4" maxlength="2000" required
                          class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-xl px-4 py-3 text-[13px] text-[#0a0a0a]
                                 placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all resize-none"></textarea>
            </div>

            {{-- Contact --}}
            <div id="contact-section" class="hidden mt-6">
                <div class="flex gap-3 mb-4 animate-fadeIn">
                    <div class="flex-shrink-0 w-7 h-7 rounded-md bg-[#0a0a0a] flex items-center justify-center text-white text-[11px] font-medium">S</div>
                    <div class="flex-1">
                        <div class="bg-white border border-[#e0e0dc] rounded-xl p-4">
                            <p class="text-[13px] text-[#0a0a0a]"><strong>Comment souhaitez-vous être contacté ?</strong></p>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="flex items-center gap-3 bg-white border border-[#e0e0dc] hover:border-[#2a2a28] hover:bg-[#f7f7f5]
                                  rounded-xl p-4 cursor-pointer transition-all has-[:checked]:border-[#0a0a0a] has-[:checked]:bg-[#f7f7f5]">
                        <input type="radio" name="contact_method" value="plateforme" checked class="accent-[#0a0a0a] w-3.5 h-3.5">
                        <span class="text-[13px] text-[#0a0a0a] font-medium">Via la plateforme</span>
                    </label>
                    <label class="flex items-center gap-3 bg-white border border-[#e0e0dc] hover:border-[#2a2a28] hover:bg-[#f7f7f5]
                                  rounded-xl p-4 cursor-pointer transition-all has-[:checked]:border-[#0a0a0a] has-[:checked]:bg-[#f7f7f5]">
                        <input type="radio" name="contact_method" value="whatsapp" class="accent-[#0a0a0a] w-3.5 h-3.5">
                        <span class="text-[13px] text-[#0a0a0a] font-medium">Via WhatsApp</span>
                    </label>
                </div>
                <div id="whatsapp-input" class="hidden mt-4">
                    <div class="flex gap-3 mb-3">
                        <div class="flex-shrink-0 w-7 h-7"></div>
                        <p class="text-[12px] text-[#a0a09a] self-center">Quel est votre numéro WhatsApp ?</p>
                    </div>
                    <input type="tel" name="whatsapp_number" id="whatsapp_number"
                           placeholder="Ex : 0102030405" pattern="[0-9]{10,15}"
                           class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-xl px-4 py-3 text-[13px] text-[#0a0a0a]
                                  font-mono placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
                </div>
            </div>

            {{-- Priorité --}}
            <div id="priority-section" class="hidden mt-6">
                <div class="flex gap-3 mb-4 animate-fadeIn">
                    <div class="flex-shrink-0 w-7 h-7 rounded-md bg-[#0a0a0a] flex items-center justify-center text-white text-[11px] font-medium">S</div>
                    <div class="flex-1">
                        <div class="bg-white border border-[#e0e0dc] rounded-xl p-4">
                            <p class="text-[13px] text-[#0a0a0a]"><strong>Quelle est l'urgence ?</strong></p>
                            <p class="text-[11px] text-[#a0a09a] mt-1">Optionnel — nous aiderons dans tous les cas</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    @foreach(['basse' => 'Basse', 'normale' => 'Normale', 'haute' => 'Haute', 'urgente' => 'Urgente'] as $val => $label)
                    <label class="flex items-center gap-2 bg-white border border-[#e0e0dc] hover:border-[#2a2a28] rounded-xl p-3
                                  cursor-pointer transition-all has-[:checked]:border-[#0a0a0a] has-[:checked]:bg-[#f7f7f5]">
                        <input type="radio" name="priority" value="{{ $val }}" {{ $val === 'normale' ? 'checked' : '' }}
                               class="accent-[#0a0a0a] w-3.5 h-3.5">
                        <span class="text-[12px] text-[#0a0a0a]">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Submit --}}
            <div id="submit-section" class="hidden mt-8">
                <button type="submit"
                        class="w-full bg-[#0a0a0a] text-white text-[13px] font-medium py-3 rounded-xl
                               hover:opacity-85 transition-opacity">
                    Créer mon ticket de support
                </button>
            </div>
        </form>

    </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn { animation: fadeIn 0.25s ease-out; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatMessages     = document.getElementById('chat-messages');
    const optionsContainer = document.getElementById('options-container');
    const form             = document.getElementById('support-form');
    const supportTypeInput = document.getElementById('support_type');
    const progressDots     = document.querySelectorAll('.step-indicator');
    let currentStep = 0;

    const typeLabels = {
        produit:   'Problème avec un produit',
        commande:  'Problème avec ma commande',
        paiement:  'Problème de paiement',
        livraison: 'Problème de livraison',
        compte:    'Problème de compte',
        autre:     'Autre',
    };

    document.querySelectorAll('.chat-option').forEach(btn => {
        btn.addEventListener('click', function () {
            selectType(this.dataset.type);
        });
    });

    document.querySelectorAll('input[name="contact_method"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const waInput = document.getElementById('whatsapp-input');
            const waNumber = document.getElementById('whatsapp_number');

            if (this.value === 'whatsapp') {
                waInput.classList.remove('hidden');
                waNumber.required = true;
            } else {
                waInput.classList.add('hidden');
                waNumber.required = false;
            }
        });
    });

    function selectType(type) {
        supportTypeInput.value = type;
        optionsContainer.classList.add('hidden');
        form.classList.remove('hidden');

        addMessage(typeLabels[type] || type, true);

        currentStep = 1;
        updateProgress();

        setTimeout(() => reveal('subject-section'),     300);
        setTimeout(() => reveal('description-section'), 800);
        setTimeout(() => reveal('contact-section'),    1300);
        setTimeout(() => reveal('priority-section'),   1800);
        setTimeout(() => reveal('submit-section'),     2300);
    }

    function reveal(id) {
        document.getElementById(id).classList.remove('hidden');
        scrollDown();
    }

    function addMessage(text, isUser = false) {
        const row = document.createElement('div');
        row.className = 'flex gap-3 animate-fadeIn' + (isUser ? ' flex-row-reverse' : '');

        const avatar = document.createElement('div');
        avatar.className = 'flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center text-white text-[11px] font-medium '
            + (isUser ? 'bg-[#e0e0dc] text-[#0a0a0a]' : 'bg-[#0a0a0a]');
        avatar.textContent = isUser ? 'V' : 'S';

        const wrap = document.createElement('div');
        wrap.className = 'flex-1';
        const bubble = document.createElement('div');
        bubble.className = (isUser
            ? 'bg-[#0a0a0a] text-white'
            : 'bg-white border border-[#e0e0dc] text-[#0a0a0a]')
            + ' rounded-xl p-4';
        bubble.innerHTML = `<p class="text-[13px] leading-relaxed">${text}</p>`;
        wrap.appendChild(bubble);

        row.appendChild(avatar);
        row.appendChild(wrap);
        chatMessages.appendChild(row);
    }

    function scrollDown() {
        setTimeout(() => window.scrollBy({ top: 300, behavior: 'smooth' }), 80);
    }

    function updateProgress() {
        progressDots.forEach((dot, i) => {
            if (i < currentStep) {
                dot.classList.replace('bg-[#efefed]', 'bg-[#0a0a0a]');
            }
        });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const subject     = document.getElementById('subject').value.trim();
        const description = document.getElementById('description').value.trim();
        const contactMethod = document.querySelector('input[name="contact_method"]:checked').value;
        const supportType = document.getElementById('support_type').value;

        if (!subject || !description) return;

        addMessage('Création du ticket en cours…', false);

        const formData = new FormData(this);

        fetch('{{ route("support.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async response => {
            const data = await response.json();

            // Gestion des erreurs de validation
            if (!response.ok) {
                console.error('Erreur validation:', data);
                if (data.errors) {
                    let errorMsg = 'Erreurs:\n';
                    Object.keys(data.errors).forEach(field => {
                        errorMsg += `- ${field}: ${data.errors[field].join(', ')}\n`;
                    });
                    addMessage('❌ ' + errorMsg, false);
                }
                return;
            }

            if (data.success) {
                // Si WhatsApp, ouvrir WhatsApp puis rediriger
                if (contactMethod === 'whatsapp') {
                    addMessage('Veuillez répondre sur WhatsApp…', false);

                    const message = encodeURIComponent(
                        `*Nouveau ticket de support* 📋\n\n` +
                        `*Type:* ${typeLabels[supportType]}\n` +
                        `*Sujet:* ${subject}\n` +
                        `*Description:*\n${description}`
                    );
                    const whatsappUrl = `https://wa.me/{{ config('services.whatsapp.contact_phone') }}?text=${message}`;
                    window.open(whatsappUrl, '_blank');

                    setTimeout(() => {
                        window.location.href = data.ticket_url;
                    }, 1500);
                } else {
                    // Via plateforme, rediriger après succès
                    addMessage('Ticket créé! Redirection…', false);
                    setTimeout(() => {
                        window.location.href = data.ticket_url;
                    }, 800);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            addMessage('❌ Erreur réseau: ' + error.message, false);
        });
    });
});
</script>

@endsection
