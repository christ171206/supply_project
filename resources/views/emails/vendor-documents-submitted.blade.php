<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents à vérifier</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Roboto, -apple-system, sans-serif;
            background: #f7f7f5;
            color: #0a0a0a;
            -webkit-font-smoothing: antialiased;
        }
        .wrap { max-width: 560px; margin: 40px auto; }

        .header {
            background: #0a0a0a;
            padding: 28px 32px;
            border-radius: 12px 12px 0 0;
        }
        .logo { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
        .logo-mark {
            width: 28px; height: 28px; background: #fff;
            border-radius: 5px; display: flex; align-items: center; justify-content: center;
        }
        .logo-name { font-size: 15px; font-weight: 600; color: #fff; letter-spacing: -0.01em; }
        .header-label {
            font-size: 10px; font-weight: 600; letter-spacing: 0.12em;
            text-transform: uppercase; color: rgba(255,255,255,0.35); margin-bottom: 6px;
        }
        .header-title { font-size: 22px; font-weight: 600; color: #fff; letter-spacing: -0.02em; }
        .header-sub { font-size: 12px; color: rgba(255,255,255,0.4); margin-top: 4px; font-weight: 300; }

        .card {
            background: #fff;
            border: 1px solid #e0e0dc;
            border-top: none;
            padding: 32px;
        }

        .intro { font-size: 13px; color: #666660; font-weight: 300; line-height: 1.6; margin-bottom: 24px; }
        .intro strong { color: #0a0a0a; font-weight: 500; }

        .section-label {
            font-size: 10px; font-weight: 600; letter-spacing: 0.1em;
            text-transform: uppercase; color: #a0a09a; margin-bottom: 12px;
        }

        .details { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .details tr { border-bottom: 1px solid #efefed; }
        .details tr:last-child { border-bottom: none; }
        .details tr:nth-child(odd)  { background: #f7f7f5; }
        .details tr:nth-child(even) { background: #fff; }
        .details td { padding: 10px 14px; font-size: 12px; vertical-align: middle; }
        .details td:first-child {
            font-size: 10px; font-weight: 600; letter-spacing: 0.05em;
            text-transform: uppercase; color: #a0a09a; width: 34%;
        }
        .details td:last-child { color: #0a0a0a; }
        .mono { font-family: 'Courier New', monospace; font-size: 11px; }

        .divider { border: none; border-top: 1px solid #efefed; margin: 24px 0; }

        .action-block {
            background: #f7f7f5; border: 1px solid #e0e0dc; border-radius: 10px; padding: 20px;
        }
        .action-title { font-size: 12px; font-weight: 600; color: #0a0a0a; margin-bottom: 6px; }
        .action-text { font-size: 12px; color: #666660; font-weight: 300; line-height: 1.5; margin-bottom: 16px; }
        .btn {
            display: inline-block; background: #0a0a0a; color: #fff !important;
            text-decoration: none; font-size: 12px; font-weight: 600;
            padding: 10px 22px; border-radius: 7px; letter-spacing: -0.01em;
        }

        .footer {
            background: #f7f7f5; border: 1px solid #e0e0dc; border-top: none;
            border-radius: 0 0 12px 12px; padding: 18px 32px; text-align: center;
        }
        .footer p { font-size: 11px; color: #a0a09a; font-weight: 300; }
    </style>
</head>
<body>
<div class="wrap">

    <div class="header">
        <div class="logo">
            <div class="logo-mark">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0a0a0a" stroke-width="2.5">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                </svg>
            </div>
            <span class="logo-name">Supply</span>
        </div>
        <div class="header-label">Administration</div>
        <div class="header-title">Documents à vérifier</div>
        <div class="header-sub">Un vendeur a soumis ses pièces d'identité</div>
    </div>

    <div class="card">

        <p class="intro">
            Bonjour,<br><br>
            <strong>{{ $vendor->name }}</strong> a soumis ses documents d'identité sur <strong>Supply</strong>.
            Veuillez les examiner et approuver ou rejeter la demande.
        </p>

        <div class="section-label">Informations du vendeur</div>
        <table class="details">
            <tr>
                <td>Nom</td>
                <td>{{ $vendor->name }}</td>
            </tr>
            <tr>
                <td>Boutique</td>
                <td>{{ $vendor->shop_name ?? '—' }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td class="mono">{{ $vendor->email }}</td>
            </tr>
            <tr>
                <td>Téléphone</td>
                <td class="mono">{{ $vendor->phone ?? '—' }}</td>
            </tr>
            <tr>
                <td>Adresse</td>
                <td>{{ $vendor->address ?? '—' }}</td>
            </tr>
            <tr>
                <td>Pays</td>
                <td>{{ $vendor->country ?? '—' }}</td>
            </tr>
        </table>

        <div class="action-block">
            <div class="action-title">Action requise</div>
            <div class="action-text">
                Consultez les documents soumis et prenez une décision d'approbation ou de rejet avec commentaire si nécessaire.
            </div>
            <a href="{{ route('admin.users.documents', $vendor->id) }}" class="btn">Voir les documents</a>
        </div>

    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Supply. Cet email a été généré automatiquement.</p>
    </div>

</div>
</body>
</html>