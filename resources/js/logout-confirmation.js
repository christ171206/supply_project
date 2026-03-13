import Swal from 'sweetalert2'

export function initLogoutConfirmation() {
    // Find all logout forms
    const logoutForms = document.querySelectorAll('form[action*="logout"]')

    logoutForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault()

            // Show SweetAlert confirmation modal
            Swal.fire({
                title: 'Déconnexion',
                html: 'Êtes-vous sûr de vouloir vous déconnecter ?',
                icon: 'warning',
                iconColor: '#0a0a0a',
                showCancelButton: true,
                confirmButtonColor: '#0a0a0a',
                cancelButtonColor: '#e0e0dc',
                confirmButtonText: 'Se déconnecter',
                cancelButtonText: 'Annuler',
                buttonsStyling: true,
                customClass: {
                    popup: 'supply-logout-modal',
                    title: 'supply-modal-title',
                    htmlContainer: 'supply-modal-content',
                    confirmButton: 'supply-btn-confirm',
                    cancelButton: 'supply-btn-cancel'
                },
                didOpen: (modal) => {
                    // Add Supply theme styling
                    const popup = modal.querySelector('.supply-logout-modal')
                    if (popup) {
                        popup.style.borderRadius = '8px'
                        popup.style.border = '1px solid #e0e0dc'
                        popup.style.backgroundColor = '#ffffff'
                    }

                    const title = modal.querySelector('.supply-modal-title')
                    if (title) {
                        title.style.color = '#0a0a0a'
                        title.style.fontFamily = 'Instrument Serif, serif'
                        title.style.fontSize = '20px'
                        title.style.marginBottom = '12px'
                    }

                    const content = modal.querySelector('.supply-modal-content')
                    if (content) {
                        content.style.color = '#666660'
                        content.style.fontSize = '14px'
                        content.style.fontFamily = 'Geist, sans-serif'
                    }

                    const confirmBtn = modal.querySelector('.supply-btn-confirm')
                    if (confirmBtn) {
                        confirmBtn.style.backgroundColor = '#0a0a0a'
                        confirmBtn.style.color = '#ffffff'
                        confirmBtn.style.border = 'none'
                        confirmBtn.style.borderRadius = '6px'
                        confirmBtn.style.padding = '10px 24px'
                        confirmBtn.style.fontWeight = '500'
                        confirmBtn.style.cursor = 'pointer'
                        confirmBtn.style.transition = 'opacity 0.2s ease'
                        confirmBtn.addEventListener('mouseenter', (e) => {
                            e.target.style.opacity = '0.85'
                        })
                        confirmBtn.addEventListener('mouseleave', (e) => {
                            e.target.style.opacity = '1'
                        })
                    }

                    const cancelBtn = modal.querySelector('.supply-btn-cancel')
                    if (cancelBtn) {
                        cancelBtn.style.backgroundColor = 'transparent'
                        cancelBtn.style.color = '#666660'
                        cancelBtn.style.border = '1px solid #e0e0dc'
                        cancelBtn.style.borderRadius = '6px'
                        cancelBtn.style.padding = '10px 24px'
                        cancelBtn.style.fontWeight = '500'
                        cancelBtn.style.cursor = 'pointer'
                        cancelBtn.style.transition = 'all 0.2s ease'
                        cancelBtn.addEventListener('mouseenter', (e) => {
                            e.target.style.borderColor = '#0a0a0a'
                            e.target.style.color = '#0a0a0a'
                        })
                        cancelBtn.addEventListener('mouseleave', (e) => {
                            e.target.style.borderColor = '#e0e0dc'
                            e.target.style.color = '#666660'
                        })
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form after confirmation
                    form.submit()
                }
            })
        })

        // Also prevent direct button submission
        const submitButtons = form.querySelectorAll('button[type="submit"]')
        submitButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault()
                form.dispatchEvent(new Event('submit'))
            })
        })
    })
}

// Auto-initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLogoutConfirmation)
} else {
    initLogoutConfirmation()
}
