<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #0a0a0a; color: white; padding: 20px; text-align: center; border-radius: 4px; margin-bottom: 20px; }
        .content { background-color: #f9f9f9; padding: 20px; border-radius: 4px; margin-bottom: 20px; }
        .vendor-info { background-color: white; padding: 15px; border: 1px solid #ddd; border-radius: 4px; margin: 15px 0; }
        .label { font-weight: bold; color: #0a0a0a; }
        .button { display: inline-block; background-color: #0a0a0a; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; margin: 10px 0; }
        .footer { font-size: 12px; color: #666; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📄 Documents à Vérifier</h1>
            <p>Un vendeur a soumis ses documents d'identité</p>
        </div>

        <div class="content">
            <h2>Informations du Vendeur</h2>
            
            <div class="vendor-info">
                <p><span class="label">Nom :</span> {{ $vendor->name }}</p>
                <p><span class="label">Boutique :</span> {{ $vendor->shop_name ?? 'N/A' }}</p>
                <p><span class="label">Email :</span> {{ $vendor->email }}</p>
                <p><span class="label">Téléphone :</span> {{ $vendor->phone ?? 'N/A' }}</p>
                <p><span class="label">Adresse :</span> {{ $vendor->address ?? 'N/A' }}</p>
                <p><span class="label">Pays :</span> {{ $vendor->country ?? 'N/A' }}</p>
            </div>

            <p>Le vendeur a soumis ses documents d'identité pour vérification. Veuillez consulter l'admin pour réviser et approuver ou rejeter cette demande.</p>

            <a href="{{ config('app.url') }}/admin/users/{{ $vendor->id }}/documents" class="button">Voir et Vérifier</a>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement. Veuillez ne pas répondre directement à ce message.</p>
            <p>&copy; {{ date('Y') }} Supply - Boutique Informatique</p>
        </div>
    </div>
</body>
</html>
