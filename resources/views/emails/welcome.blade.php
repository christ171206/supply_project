@component('mail::message')
# 🎉 Bienvenue sur Supply, {{ $userName }}!

Merci de t'être inscrit sur **Supply** - la plateforme e-commerce la plus dynamique pour les produits informatiques en Afrique de l'Ouest.

## Accès à Ton Compte

Ton compte a été créé avec succès! Voici tes informations:

- **Nom complet**: {{ $userName }}
- **Email**: {{ $userEmail }}
- **Type de compte**: {{ $userRole }}

@if($userRole === 'Vendeur')
## ⏳ En Attente de Vérification

Ton compte vendeur est actuellement en attente de vérification par notre équipe. Cela prend généralement 24-48 heures.

Une fois vérifié, tu pourras:
- ✅ Ajouter tes produits informatiques
- ✅ Gérer tes commandes
- ✅ Recevoir les paiements directement
- ✅ Bénéficier d'une boutique professionnelle
@else
## 🛍️ Prêt à Commencer?

Bienvenue dans notre communauté! Tu peux maintenant:
- 🔍 Explorer notre catalogue de produits
- ❤️ Ajouter tes favoris
- 🛒 Ajouter des articles à ton panier
- 💳 Effectuer tes achats en toute sécurité
@endif

## Besoin d'Aide?

Si tu as des questions ou besoin d'assistance:
- 📞 Contacte-nous via WhatsApp: {{ config('services.whatsapp.phone') }}
- 📧 Email: support@supply.local
- 💬 Messages directs depuis ton compte Supply

## Sécurité de Ton Compte

- 🔐 Garde ton mot de passe secret
- ✅ Vérifie ton adresse email pour active toutes les fonctionnalités
- 🛡️ Supply utilise le chiffrement de haut niveau pour protéger tes données

---

Merci de faire partie de la famille Supply!

@component('mail::button', ['url' => route('accueil')])
👉 Visiter Supply
@endcomponent

Cordialement,
**L'équipe Supply**

P.S. Tu reçois cet email car tu t'es inscrit sur Supply. Si ce n'était pas toi, n'hésitez pas à nous contacter.
@endcomponent
