@component('mail::message')
# Félicitations !

Bonjour {{ $vendor->name }},

Nous sommes heureux de vous annoncer que **votre demande d'inscription en tant que vendeur a été approuvée** !

## Détails

| Info | Valeur |
|------|--------|
| **Boutique** | {{ $vendor->shop_name }} |
| **Email** | {{ $vendor->email }} |
| **Statut** | Approuvé |

@if($reviewNotes)
## 📝 Commentaire des modérateurs

{{ $reviewNotes }}
@endif

## 🚀 Prochaines étapes

Vous pouvez maintenant :
1. Accéder à votre **tableau de bord vendeur**
2. Créer et gérer vos **produits**
3. Gérer vos **commandes** et **stocks**
4. Consulter vos **statistiques de ventes**

@component('mail::button', ['url' => $dashboardUrl, 'color' => 'success'])
Accéder à mon tableau de bord
@endcomponent

## 📞 Support

Si vous avez des questions ou besoin d'aide, n'hésitez pas à nous contacter.

Bienvenue dans la communauté Supply ! 🙌

Cordialement,

**L'équipe Supply**

---

*Cet email a été généré automatiquement. Veuillez ne pas répondre directement.*
@endcomponent
