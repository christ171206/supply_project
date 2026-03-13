<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle demande vendeur</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Roboto, -apple-system, sans-serif;
            background: #f7f7f5;
            color: #0a0a0a;
            -webkit-font-smoothing: antialiased;
        }
        .wrap {
            max-width: 560px;
            margin: 40px auto;
            background: #f7f7f5;
        }

        /* Header */
        .header {
            background: #0a0a0a;
            padding: 28px 32px;
            border-radius: 12px 12px 0 0;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }
        .logo-mark {
            width: 28px; height: 28px;
            background: #fff;
            border-radius: 5px;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-name {
            font-size: 15px; font-weight: 600; color: #fff; letter-spacing: -0.01em;
        }
        .header-label {
            font-size: 10px; font-weight: 600; letter-spacing: 0.12em;
            text-transform: uppercase; color: rgba(255,255,255,0.35); margin-bottom: 6px;
        }
        .header-title {
            font-size: 22px; font-weight: 600; color: #fff;
            letter-spacing: -0.02em; line-height: 1.2;
        }

        /* Card */
        .card {
            background: #fff;
            border: 1px solid #e0e0dc;
            border-top: none;
            padding: 32px;
        }

        .intro {
            font-size: 13px; color: #666660; font-weight: 300; margin-bottom: 24px;
            line-height: 1.6;
        }
        .intro strong { color: #0a0a0a; font-weight: 500; }

        /* Section label */
        .section-label {
            font-size: 10px; font-weight: 600; letter-spacing: 0.1em;
            text-transform: uppercase; color: #a0a09a; margin-bottom: 12px;
        }

        /* Details table */
        .details {
            width: 100%; border-collapse: collapse;
            border: 1px solid #e0e0dc; border-radius: 8px; overflow: hidden;
            margin-bottom: 24px;
        }
        .details tr { border-bottom: 1px solid #efefed; }
        .details tr:last-child { border-bottom: none; }
        .details tr:nth-child(odd) { background: #f7f7f5; }
        .details tr:nth-child(even) { background: #fff; }
        .details td {
            padding: 10px 14px; font-size: 12px; vertical-align: middle;
        }
        .details td:first-child {
            font-size: 10px; font-weight: 600; letter-spacing: 0.05em;
            text-transform: uppercase; color: #a0a09a; width: 34%;
        }
        .details td:last-child { color: #0a0a0a; font-weight: 400; }
        .mono { font-family: 'Courier New', monospace; font-size: 11px; }

        /* Status badge */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 10px; font-family: 'Courier New', monospace; font-weight: 600;
            padding: 3px 8px; border-radius: 4px;
            background: #fdf6ec; color: #b45309;
        }
        .badge-dot {
            width: 5px; height: 5px; border-radius: 50%; background: #f59e0b; display: inline-block;
        }

        /* Divider */
        .divider { border: none; border-top: 1px solid #efefed; margin: 24px 0; }

        /* Steps */
        .steps { margin-bottom: 24px; }
        .step {
            display: flex; gap: 12px; align-items: flex-start;
            padding: 10px 0; border-bottom: 1px solid #efefed;
        }
        .step:last-child { border-bottom: none; }
        .step-num {
            width: 20px; height: 20px; border: 1px solid #e0e0dc; border-radius: 4px;
            font-family: 'Courier New', monospace; font-size: 10px; font-weight: 600;
            color: #a0a09a; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; margin-top: 1px;
        }
        .step-text { font-size: 12px; color: #666660; font-weight: 300; line-height: 1.5; }

        /* Action block */
        .action-block {
            background: #f7f7f5;
            border: 1px solid #e0e0dc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .action-title { font-size: 12px; font-weight: 600; color: #0a0a0a; margin-bottom: 6px; }
        .action-text { font-size: 12px; color: #666660; font-weight: 300; line-height: 1.5; margin-bottom: 16px; }
        .btn {
            display: inline-block; background: #0a0a0a; color: #fff !important;
            text-decoration: none; font-size: 12px; font-weight: 600;
            padding: 10px 22px; border-radius: 7px; letter-spacing: -0.01em;
        }

        /* Footer */
        .footer {
            background: #f7f7f5;
            border: 1px solid #e0e0dc; border-top: none;
            border-radius: 0 0 12px 12px;
            padding: 18px 32px; text-align: center;
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
        <div class="header-title">Nouvelle demande vendeur</div>
    </div>

    <div class="card">
        <p class="intro">
            Bonjour,<br><br>
            Une nouvelle demande d'inscription vendeur a été reçue sur <strong>Supply</strong>.
            Le vendeur doit encore vérifier son adresse email avant de pouvoir soumettre ses documents.
        </p>

        <div class="section-label">Informations du vendeur</div>
        <table class="details">
            <tr>
                <td>Nom</td>
                <td>{{ $vendor->name }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td class="mono">{{ $vendor->email }}</td>
            </tr>
            <tr>
                <td>Boutique</td>
                <td>{{ $vendor->shop_name ?? '—' }}</td>
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
            <tr>
                <td>Inscrit le</td>
                <td class="mono">{{ $vendor->created_at->format('d/m/Y · H:i') }}</td>
            </tr>
            <tr>
                <td>Statut</td>
                <td>
                    <span class="badge">
                        <span class="badge-dot"></span>
                        En attente — vérification email
                    </span>
                </td>
            </tr>
        </table>

        <div class="section-label">Étapes suivantes</div>
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-text">Le vendeur vérifie son adresse email (code à 6 chiffres)</div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-text">Il soumet ses documents d'identité (CNI ou passeport, recto + verso)</div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-text">Vous recevrez une notification pour approuver ou rejeter les documents</div>
            </div>
        </div>

        <div class="action-block">
            <div class="action-title">Action requise</div>
            <div class="action-text">
                Consultez le tableau de bord pour suivre l'avancement de cette demande et examiner les documents une fois soumis.
            </div>
            <a href="{{ $adminDashboardUrl }}" class="btn">Voir le tableau de bord</a>
        </div>

        <p style="font-size:11px; color:#a0a09a; font-weight:300; line-height:1.6;">
            Vous recevrez une nouvelle notification dès que le vendeur aura soumis ses documents d'identité.
        </p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Supply. Cet email a été généré automatiquement.</p>
    </div>

</div>
</body>
</html>
