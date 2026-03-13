<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; color: #0a0a0a; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #0a0a0a; color: #ffffff; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-family: 'Instrument Serif', serif; }
        .content { background-color: #f7f7f5; padding: 30px; border-radius: 0 0 8px 8px; border: 1px solid #e0e0dc; }
        .section { margin-bottom: 25px; }
        .section h2 { color: #0a0a0a; font-family: 'Instrument Serif', serif; font-size: 18px; margin: 0 0 15px 0; }
        table { width: 100%; border-collapse: collapse; background: white; border: 1px solid #e0e0dc; border-radius: 6px; overflow: hidden; }
        table th { background-color: #efefed; color: #0a0a0a; padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e0e0dc; }
        table td { padding: 12px; border-bottom: 1px solid #e0e0dc; }
        table tr:last-child td { border-bottom: none; }
        .label { color: #a0a09a; font-size: 12px; font-weight: 500; }
        .value { color: #0a0a0a; font-weight: 500; }
        .button { display: inline-block; background-color: #0a0a0a; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; margin-top: 15px; margin-right: 10px; }
        .button:hover { opacity: 0.85; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0dc; text-align: center; color: #a0a09a; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👤 Nouvelle inscription client</h1>
        </div>
        
        <div class="content">
            <div class="section">
                <p>Bonjour,</p>
                <p>Un nouvel utilisateur s'est inscrit en tant que client sur la plateforme Supply.</p>
            </div>

            <div class="section">
                <h2>Informations du client</h2>
                <table>
                    <tr>
                        <td class="label">Nom complet</td>
                        <td class="value">{{ $client->name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email</td>
                        <td class="value">{{ $client->email }}</td>
                    </tr>
                    <tr>
                        <td class="label">Téléphone</td>
                        <td class="value">{{ $client->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Pays</td>
                        <td class="value">{{ $client->country ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Localité</td>
                        <td class="value">{{ $client->localite ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date d'inscription</td>
                        <td class="value">{{ $client->created_at->locale('fr')->format('d M Y à H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Statut email</td>
                        <td class="value">{{ $client->email_verified_at ? '✅ Vérifié' : '⏳ En attente de vérification' }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <h2>Statistiques préliminaires</h2>
                <table>
                    <tr>
                        <td class="label">Commandes passées</td>
                        <td class="value">{{ $client->commandes()->count() ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td class="label">Adresses enregistrées</td>
                        <td class="value">{{ $client->addresses()->count() ?? 0 }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <p style="color: #666660; font-size: 14px;">Vous pouvez consulter le profil complet du client en accédant au tableau de bord administrateur.</p>
                <a href="{{ $adminDashboardUrl }}" class="button">Voir tous les utilisateurs</a>
            </div>

            <div class="footer">
                <p>© {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
                <p>Cet email a été généré automatiquement. Veuillez ne pas répondre à cet email.</p>
            </div>
        </div>
    </div>
</body>
</html>
