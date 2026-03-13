@component('mail::layout')

{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => url('/')])
Supply
@endcomponent
@endslot

{{-- Body --}}

Bonjour,

Une nouvelle demande d'inscription vendeur a été reçue sur **Supply**.
Le vendeur doit encore vérifier son adresse email avant de pouvoir soumettre ses documents.

---

**Informations du vendeur**

| Champ | Valeur |
|---|---|
| Nom | {{ $vendor->name }} |
| Email | {{ $vendor->email }} |
| Boutique | {{ $vendor->shop_name ?? '—' }} |
| Téléphone | {{ $vendor->phone ?? '—' }} |
| Adresse | {{ $vendor->address ?? '—' }} |
| Pays | {{ $vendor->country ?? '—' }} |
| Inscrit le | {{ $vendor->created_at->format('d/m/Y à H:i') }} |
| Statut | En attente — vérification email |

---

**Étapes suivantes**

1. Le vendeur vérifie son adresse email (code à 6 chiffres)
2. Il soumet ses documents d'identité (CNI ou passeport, recto + verso)
3. Vous recevrez une notification pour approuver ou rejeter les documents

---

**Action requise**

Consultez le tableau de bord pour suivre cette demande et examiner les documents une fois soumis.

@component('mail::button', ['url' => $adminDashboardUrl, 'color' => 'primary'])
Voir le tableau de bord
@endcomponent

@if(isset($vendorDetailsUrl))
@component('mail::button', ['url' => $vendorDetailsUrl, 'color' => 'primary'])
Voir le profil du vendeur
@endcomponent
@endif

Vous recevrez une nouvelle notification dès que le vendeur aura soumis ses documents d'identité.

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name', 'Supply') }}. Cet email a été généré automatiquement.
@endcomponent
@endslot

@endcomponent
