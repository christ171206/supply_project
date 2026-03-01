import Swal from 'sweetalert2'

// Confirmation de suppression de compte
window.confirmDeleteAccount = function() {
    Swal.fire({
        title: '⚠️ Supprimer mon compte?',
        html: 'Cette action est <strong>irréversible</strong>. Toutes vos données seront supprimées:<br><ul style="text-align: left; margin-top: 10px;"><li>✗ Vos commandes</li><li>✗ Vos messages</li><li>✗ Vos favoris</li></ul>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Oui, supprimer définitivement',
        cancelButtonText: 'Annuler',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Entrez votre mot de passe',
                input: 'password',
                inputPlaceholder: 'Votre mot de passe',
                showCancelButton: true,
                confirmButtonText: 'Confirmer la suppression',
                cancelButtonText: 'Annuler',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Veuillez entrer votre mot de passe'
                    }
                }
            }).then((passwordResult) => {
                if (passwordResult.isConfirmed && passwordResult.value) {
                    // Le formulaire réel sera envoyé avec le mot de passe
                    document.getElementById('deleteAccountForm').submit()
                }
            })
        }
    })
    return false
}

// Confirmation d'ajout au panier
window.confirmAddToCart = function() {
    Swal.fire({
        title: '✓ Produit ajouté!',
        text: 'Produit ajouté à votre panier avec succès',
        icon: 'success',
        confirmButtonText: 'Continuer les achats',
        confirmButtonColor: '#3b82f6'
    }).then((result) => {
        if (result.isConfirmed) {
            // Rediriger vers le catalogue
            window.location.href = '/produits'
        }
    })
    return false
}

// Confirmation de suppression générique
window.confirmDelete = function(title = 'Êtes-vous sûr?', message = 'Cette action ne peut pas être annulée') {
    return Swal.fire({
        title: title,
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        return result.isConfirmed
    })
}

// Notification de succès
window.showSuccess = function(title = 'Succès!', message = 'Opération réussie') {
    Swal.fire({
        title: title,
        text: message,
        icon: 'success',
        timer: 3000,
        showConfirmButton: false
    })
}

// Notification d'erreur
window.showError = function(title = 'Erreur!', message = 'Une erreur s\'est produite') {
    Swal.fire({
        title: title,
        text: message,
        icon: 'error',
        confirmButtonColor: '#dc2626'
    })
}

// Toast notifications
window.showToast = function(message, icon = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    Toast.fire({
        icon: icon,
        title: message
    })
}

export { Swal }
