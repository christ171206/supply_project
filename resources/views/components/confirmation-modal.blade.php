{{-- Modal Confirmation Réutilisable --}}
<div id="confirmationModal" 
     class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md border border-[#e0e0dc] animate-in fade-in zoom-in-95 duration-200">
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-[#efefed]">
            <h3 id="confirmTitle" class="text-[15px] font-semibold text-[#0a0a0a]">Confirmation</h3>
        </div>

        {{-- Content --}}
        <div class="px-6 py-4">
            <p id="confirmMessage" class="text-[13px] text-[#666660] leading-relaxed"></p>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-[#efefed]">
            <button 
                type="button"
                onclick="closeConfirmationModal()"
                class="text-[12px] font-medium text-[#666660] border border-[#e0e0dc] px-4 py-2 rounded-lg
                       hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                Annuler
            </button>
            <button 
                type="button"
                id="confirmButton"
                onclick="submitConfirmation()"
                class="bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-lg
                       hover:opacity-85 transition-opacity flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                <span id="confirmButtonText">Confirmer</span>
            </button>
        </div>
    </div>
</div>

<script>
    let pendingForm = null;
    let confirmationType = 'default'; // 'default', 'danger', 'warning'

    /**
     * Ouvrir la modal de confirmation
     */
    window.showConfirmation = function(title, message, form, type = 'default', buttonText = 'Confirmer') {
        pendingForm = form;
        confirmationType = type;

        // Mettre à jour le contenu
        document.getElementById('confirmTitle').textContent = title;
        document.getElementById('confirmMessage').textContent = message;
        document.getElementById('confirmButtonText').textContent = buttonText;

        // Mettre à jour les styles
        const confirmButton = document.getElementById('confirmButton');
        confirmButton.className = 'bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-lg hover:opacity-85 transition-opacity flex items-center gap-1.5';
        
        if (type === 'danger') {
            confirmButton.className = 'bg-[#dc2626] text-white text-[12px] font-medium px-4 py-2 rounded-lg hover:bg-[#991b1b] transition-colors flex items-center gap-1.5';
        } else if (type === 'warning') {
            confirmButton.className = 'bg-[#ea580c] text-white text-[12px] font-medium px-4 py-2 rounded-lg hover:bg-[#c2410c] transition-colors flex items-center gap-1.5';
        }

        // Afficher la modal
        document.getElementById('confirmationModal').classList.remove('hidden');
    };

    /**
     * Fermer la modal
     */
    window.closeConfirmationModal = function() {
        document.getElementById('confirmationModal').classList.add('hidden');
        pendingForm = null;
    };

    /**
     * Soumettre le formulaire
     */
    window.submitConfirmation = function() {
        if (pendingForm) {
            if (pendingForm.tagName === 'FORM') {
                pendingForm.submit();
            } else if (typeof pendingForm === 'function') {
                pendingForm();
            }
        }
        closeConfirmationModal();
    };

    /**
     * Intercepter les formulaires avec data-confirm
     */
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.hasAttribute('data-confirm')) {
            e.preventDefault();
            
            const config = {
                title: form.getAttribute('data-confirm-title') || 'Confirmation requise',
                message: form.getAttribute('data-confirm') || 'Êtes-vous sûr?',
                buttonText: form.getAttribute('data-confirm-button') || 'Confirmer',
                type: form.getAttribute('data-confirm-type') || 'default'
            };
            
            showConfirmation(config.title, config.message, form, config.type, config.buttonText);
        }
    });

    /**
     * Intercepter les liens avec data-confirm-link
     */
    document.addEventListener('click', function(e) {
        const link = e.target.closest('[data-confirm-link]');
        if (link) {
            e.preventDefault();
            
            const config = {
                title: link.getAttribute('data-confirm-title') || 'Confirmation requise',
                message: link.getAttribute('data-confirm-link') || 'Êtes-vous sûr?',
                buttonText: link.getAttribute('data-confirm-button') || 'Confirmer',
                type: link.getAttribute('data-confirm-type') || 'default'
            };
            
            showConfirmation(config.title, config.message, { href: link.href }, config.type, config.buttonText);
        }
    });

    /**
     * Fermer avec Escape
     */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('confirmationModal').classList.contains('hidden')) {
            closeConfirmationModal();
        }
    });

    /**
     * Fermer en cliquant en dehors de la modal
     */
    document.getElementById('confirmationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmationModal();
        }
    });
</script>
