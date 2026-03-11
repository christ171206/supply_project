@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => url('/')])
{{ config('app.name') }} - Admin
@endcomponent
@endslot

{{-- Body --}}
# 📌 Nouvelle demande d'inscription vendeur

Bonjour,

Une nouvelle demande d'inscription vendeur a été reçue et attend votre approbation.

## 👤 Informations du vendeur

| Information | Détail |
|------------|--------|
| **Nom** | {{ $vendor->name }} |
| **Email** | {{ $vendor->email }} |
| **Numéro de tél** | {{ $vendor->phone ?? 'N/A' }} |
| **Nom de boutique** | {{ $vendor->shop_name ?? 'N/A' }} |
| **Date d\'inscription** | {{ $vendor->created_at->locale('fr')->format('d M Y à H:i') }} |

## 📝 Description

{{ $vendor->description ?? 'Aucune description fournie' }}

## 🔍 Action requise

Veuillez examiner les documents du vendeur (CNI recto/verso, autres pièces justificatives) et approuver ou rejeter leur demande.

Vous pouvez accéder au tableau de bord des vendeurs ou consulter les détails du demandeur en utilisant les boutons ci-dessous :

@component('mail::button', ['url' => $adminDashboardUrl, 'color' => 'primary'])
Voir tous les demandeurs
@endcomponent

@component('mail::button', ['url' => $vendorDetailsUrl, 'color' => 'info'])
Voir les détails du vendeur
@endcomponent

---

**Rappels importants :**
- Vérifiez que tous les documents requis sont fournis
- Assurez-vous que les informations sont complètes et exactes
- Approuvez ou rejetez la demande avec des commentaires clairs si rejet

---

Cordialement,
**L'équipe Supply**

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
@endcomponent
@endslot
@endcomponent
