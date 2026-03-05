@component('mail::message')
# Demande d'inscription rejetée

Bonjour {{ $vendor->name }},

Nous vous remercions de votre intérêt pour notre plateforme. Malheureusement, votre demande d'inscription en tant que vendeur a été **rejetée**.

## Détails

| Info | Valeur |
|------|--------|
| **Boutique** | {{ $vendor->shop_name }} |
| **Email** | {{ $vendor->email }} |
| **Statut** | Rejété |

## 📝 Raison du rejet

{{ $rejectionReason }}

## 🔄 Que faire maintenant ?

Vous pouvez :
- 📧 Nous contacter pour plus d'informations
- 🔄 Résoudre les problèmes soulevés et réessayer ultérieurement
- Demander des clarifications sur la décision

@component('mail::button', ['url' => $supportUrl, 'color' => 'primary'])
Contacter le support
@endcomponent

## 📞 Nous sommes là pour vous aider

Si vous pensez qu'il y a une erreur ou si vous avez des questions sur cette décision, veuillez nous contacter immédiatement.

Cordialement,

**L'équipe Supply**

---

*Cet email a été généré automatiquement. Veuillez ne pas répondre directement.*
@endcomponent
