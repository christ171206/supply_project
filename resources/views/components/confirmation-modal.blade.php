<!-- Modal Confirmation Réutilisable -->
<div id="confirmationModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 border border-gray-200 animate-scale-in">
        <!-- Icon -->
        <div id="confirmIcon" class="flex justify-center mb-6">
            <div id="iconWrapper" class="w-16 h-16 rounded-full flex items-center justify-center text-3xl">
                ⚠️
            </div>
        </div>

        <!-- Titre -->
        <h3 id="confirmTitle" class="text-2xl font-bold text-gray-900 text-center mb-3">
            Confirmation
        </h3>

        <!-- Message -->
        <p id="confirmMessage" class="text-gray-600 text-center mb-8">
            Êtes-vous sûr ?
        </p>

        <!-- Boutons -->
        <div class="flex gap-3">
            <button 
                type="button"
                onclick="closeConfirmationModal()"
                class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold transition">
                ✗ Annuler
            </button>
            <button 
                type="button"
                id="confirmButton"
                onclick="submitConfirmation()"
                class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition">
                Confirmer
            </button>
        </div>
    </div>
</div>

<script>
    let pendingForm = null;
    let confirmationType = 'warning'; // 'warning', 'danger', 'success', 'info'

    /**
     * Ouvrir la modal de confirmation
     * @param {string} title - Titre de la confirmation
     * @param {string} message - Message de confirmation
     * @param {HTMLFormElement} form - Formulaire à soumettre
     * @param {string} type - Type: 'warning', 'danger', 'success', 'info'
     * @param {string} buttonText - Texte du bouton de confirmation
     */
    window.showConfirmation = function(title, message, form, type = 'warning', buttonText = 'Confirmer') {
        pendingForm = form;
        confirmationType = type;

        // Mettre à jour le contenu
        document.getElementById('confirmTitle').textContent = title;
        document.getElementById('confirmMessage').textContent = message;
        document.getElementById('confirmButton').textContent = buttonText;

        // Mettre à jour les styles selon le type
        const iconWrapper = document.getElementById('iconWrapper');
        const confirmButton = document.getElementById('confirmButton');
        
        iconWrapper.classList.remove('bg-yellow-100', 'bg-red-100', 'bg-green-100', 'bg-blue-100', 'text-yellow-600', 'text-red-600', 'text-green-600', 'text-blue-600');
        confirmButton.classList.remove('bg-yellow-600', 'bg-red-600', 'bg-green-600', 'bg-blue-600', 'hover:bg-yellow-700', 'hover:bg-red-700', 'hover:bg-green-700', 'hover:bg-blue-700');
        
        switch(type) {
            case 'danger':
                iconWrapper.classList.add('bg-red-100', 'text-red-600');
                document.getElementById('confirmIcon').innerHTML = '<div class="text-4xl">🗑️</div>';
                confirmButton.classList.add('bg-red-600', 'hover:bg-red-700');
                break;
            case 'success':
                iconWrapper.classList.add('bg-green-100', 'text-green-600');
                document.getElementById('confirmIcon').innerHTML = '<div class="text-4xl">✓</div>';
                confirmButton.classList.add('bg-green-600', 'hover:bg-green-700');
                break;
            case 'info':
                iconWrapper.classList.add('bg-blue-100', 'text-blue-600');
                document.getElementById('confirmIcon').innerHTML = '<div class="text-4xl">ℹ️</div>';
                confirmButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
                break;
            case 'warning':
            default:
                iconWrapper.classList.add('bg-yellow-100', 'text-yellow-600');
                document.getElementById('confirmIcon').innerHTML = '<div class="text-4xl flex items-center justify-center"><x-heroicon-o-exclamation-triangle class="w-10 h-10" /></div>';
                confirmButton.classList.add('bg-yellow-600', 'hover:bg-yellow-700');
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
     * Soumettre la confirmation
     */
    window.submitConfirmation = function() {
        if (pendingForm) {
            pendingForm.submit();
        }
        closeConfirmationModal();
    };

    /**
     * Fermer la modal au clic sur le backdrop
     */
    document.getElementById('confirmationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmationModal();
        }
    });

    /**
     * Gérer les data-attributes pour la confirmation
     * Exemple: <form data-confirm="Êtes-vous sûr ?" data-confirm-title="Confirmation" data-confirm-type="danger">
     */
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.dataset.confirm) {
            e.preventDefault();
            
            const title = form.dataset.confirmTitle || 'Êtes-vous sûr ?';
            const message = form.dataset.confirm;
            const type = form.dataset.confirmType || 'warning';
            const buttonText = form.dataset.confirmButton || 'Confirmer';

            showConfirmation(title, message, form, type, buttonText);
        }
    });
</script>
