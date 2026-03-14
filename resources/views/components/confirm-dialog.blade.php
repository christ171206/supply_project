{{-- Modal de confirmation réutilisable --}}
<div id="confirmDialog"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4"
     style="display: none;">
    <div class="bg-white rounded-xl shadow-xl max-w-sm w-full animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-5 border-b border-[#efefed]">
            <h3 id="confirmTitle" class="text-[15px] font-semibold text-[#0a0a0a]">Confirmation</h3>
        </div>
        <div class="px-6 py-4">
            <p id="confirmMessage" class="text-[13px] text-[#666660] leading-relaxed"></p>
        </div>
        <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-[#efefed]">
            <button id="confirmCancel"
                    class="text-[12px] font-medium text-[#666660] border border-[#e0e0dc] px-4 py-2 rounded-lg
                           hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                Annuler
            </button>
            <button id="confirmSubmit"
                    class="bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-lg
                           hover:opacity-85 transition-opacity flex items-center gap-1.5">
                <svg id="confirmIcon" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                <span id="confirmButtonText">Confirmer</span>
            </button>
        </div>
    </div>
</div>

<script>
    // Configuration globale des confirmations
    const confirmDialog = {
        element: document.getElementById('confirmDialog'),
        titleEl: document.getElementById('confirmTitle'),
        messageEl: document.getElementById('confirmMessage'),
        submitBtn: document.getElementById('confirmSubmit'),
        cancelBtn: document.getElementById('confirmCancel'),
        iconEl: document.getElementById('confirmIcon'),
        buttonTextEl: document.getElementById('confirmButtonText'),
        form: null,
        
        config: {
            title: 'Confirmation',
            message: 'Êtes-vous sûr?',
            buttonText: 'Confirmer',
            type: 'default', // 'default', 'danger', 'warning'
        },

        show(form, config = {}) {
            this.form = form;
            this.config = { ...this.config, ...config };
            
            // Mettre à jour le contenu
            this.titleEl.textContent = this.config.title;
            this.messageEl.textContent = this.config.message;
            this.buttonTextEl.textContent = this.config.buttonText;
            
            // Appliquer les styles selon le type
            this.updateStyle(this.config.type);
            
            // Afficher le dialog
            this.element.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        },

        updateStyle(type) {
            // Réinitialiser les styles
            this.submitBtn.className = 'bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-lg hover:opacity-85 transition-opacity flex items-center gap-1.5';
            
            if (type === 'danger') {
                this.submitBtn.className = 'bg-[#dc2626] text-white text-[12px] font-medium px-4 py-2 rounded-lg hover:bg-[#991b1b] transition-colors flex items-center gap-1.5';
            } else if (type === 'warning') {
                this.submitBtn.className = 'bg-[#ea580c] text-white text-[12px] font-medium px-4 py-2 rounded-lg hover:bg-[#c2410c] transition-colors flex items-center gap-1.5';
            }
        },

        hide() {
            this.element.style.display = 'none';
            document.body.style.overflow = '';
            this.form = null;
        },

        submit() {
            if (this.form) {
                if (this.form.tagName === 'FORM') {
                    this.form.submit();
                } else if (this.form.href) {
                    window.location.href = this.form.href;
                } else if (typeof this.form === 'function') {
                    this.form();
                }
            }
            this.hide();
        }
    };

    // Event listeners
    confirmDialog.submitBtn.addEventListener('click', () => confirmDialog.submit());
    confirmDialog.cancelBtn.addEventListener('click', () => confirmDialog.hide());
    confirmDialog.element.addEventListener('click', (e) => {
        if (e.target === confirmDialog.element) {
            confirmDialog.hide();
        }
    });

    // Fermer avec Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && confirmDialog.element.style.display !== 'none') {
            confirmDialog.hide();
        }
    });

    // Intercepter les formulaires avec data-confirm
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (form.hasAttribute('data-confirm')) {
            e.preventDefault();
            
            const config = {
                title: form.getAttribute('data-confirm-title') || 'Confirmation requise',
                message: form.getAttribute('data-confirm') || 'Êtes-vous sûr?',
                buttonText: form.getAttribute('data-confirm-button') || 'Confirmer',
                type: form.getAttribute('data-confirm-type') || 'default'
            };
            
            confirmDialog.show(form, config);
        }
    });

    // Intercepter les liens avec data-confirm
    document.addEventListener('click', (e) => {
        const link = e.target.closest('[data-confirm-link]');
        if (link) {
            e.preventDefault();
            
            const config = {
                title: link.getAttribute('data-confirm-title') || 'Confirmation requise',
                message: link.getAttribute('data-confirm-link') || 'Êtes-vous sûr?',
                buttonText: link.getAttribute('data-confirm-button') || 'Confirmer',
                type: link.getAttribute('data-confirm-type') || 'default'
            };
            
            confirmDialog.show({ href: link.href }, config);
        }
    });
</script>
