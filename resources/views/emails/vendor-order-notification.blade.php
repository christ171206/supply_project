<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #10b981; color: white; padding: 20px; text-align: center; border-radius: 5px; }
        .content { padding: 20px; background: #f9fafb; margin: 20px 0; border-radius: 5px; }
        .order-info {
            background: white;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 15px 0;
            border-radius: 3px;
        }
        .order-number {
            font-size: 24px;
            font-weight: bold;
            color: #10b981;
            margin: 10px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th {
            background: #f3f4f6;
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #e5e7eb;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .total-row {
            background: #f3f4f6;
            font-weight: bold;
        }
        .button {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .client-info {
            background: #f0fdf4;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📦 Nouvelle Commande!</h1>
        </div>

        <div class="content">
            <p>Bonjour {{ $vendor->name }},</p>

            <p>Une nouvelle commande a été passée sur <strong>Supply</strong>. Voici les détails :</p>

            <div class="order-info">
                <strong>Numéro de commande :</strong>
                <div class="order-number">#{{ $commande->id }}</div>

                <p><strong>Date :</strong> {{ $commande->created_at->format('d/m/Y à H:i') }}</p>
                <p><strong>Statut :</strong> <span style="color: #f97316; font-weight: bold;">{{ ucfirst($commande->statut) }}</span></p>
            </div>

            <h3>Informations Client</h3>
            <div class="client-info">
                <p><strong>Nom :</strong> {{ $client->name }}</p>
                <p><strong>Email :</strong> {{ $client->email }}</p>
                <p><strong>Téléphone :</strong> {{ $commande->telephone_livraison ?? 'Non fourni' }}</p>
                <p><strong>Adresse livraison :</strong> {{ $commande->adresse_livraison }}</p>
            </div>

            <h3>Articles commandés (vos produits)</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th style="text-align: right;">Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $item['nom_produit'] }}</td>
                        <td>{{ $item['quantite'] }}</td>
                        <td>{{ number_format($item['prix_unitaire'], 0, ',', ' ') }} FCFA</td>
                        <td style="text-align: right; font-weight: bold;">{{ number_format($item['sous_total'], 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">Total de votre part :</td>
                        <td style="text-align: right;">{{ number_format($total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tbody>
            </table>

            <div class="order-info">
                <p><strong>Méthode de paiement :</strong> {{ ucfirst(str_replace('_', ' ', $commande->payment_method)) }}</p>
                <p><strong>Montant total de la commande :</strong> {{ number_format($commande->total, 0, ',', ' ') }} FCFA</p>
            </div>

            <p>
                <a href="{{ route('vendeur.commandes.show', $commande->id) }}" class="button">
                    Voir les détails de la commande
                </a>
            </p>

            <p style="color: #dc2626; margin: 20px 0;">
                <strong>Action requise :</strong> Veuillez vérifier cette commande et la traiter rapidement. Votre client l'attend!
            </p>

            <p>Merci d'être un vendeur fiable sur Supply!</p>
            <p>Cordialement,<br>L'équipe Supply</p>
        </div>

        <div class="footer">
            <p>© 2026 Supply. Tous droits réservés.</p>
            <p>Cet email a été envoyé automatiquement. Veuillez ne pas le modifier.</p>
        </div>
    </div>
</body>
</html>
