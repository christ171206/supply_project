@component('mail::message')
# 📌 Nouvelle demande d'inscription vendeur

Bonjour,

Une nouvelle demande d'inscription de **vendeur** a été reçue sur Supply.

## Détails du vendeur

| Info | Valeur |
|------|--------|
| **Nom** | {{ $vendor->name }} |
| **Email** | {{ $vendor->email }} |
| **Boutique** | {{ $vendor->shop_name ?? 'Non spécifiée' }} |
| **Téléphone** | {{ $vendor->phone ?? 'Non fourni' }} |
| **Adresse** | {{ $vendor->address ?? 'Non fournie' }} |
| **Date d'inscription** | {{ $vendor->created_at->format('d/m/Y à H:i') }} |

## 📎 Documents

@if($vendor->id_document)
Document d'identité (CNI) uploadé
@else
**Aucun document d'identité fourni**
@endif

## Actions requises

Veuillez examiner et approuver ou rejeter cette demande au plus tôt dans votre panneau d'administration.

@component('mail::button', ['url' => $adminDashboardUrl, 'color' => 'primary'])
Voir les demandes en attente
@endcomponent

Cordialement,

**L'équipe Supply**

---

*Cet email a été généré automatiquement. Veuillez ne pas répondre directement.*
@endcomponent
