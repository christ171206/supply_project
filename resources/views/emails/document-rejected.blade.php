<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document rejeté</title>
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

        .intro { font-size: 13px; color: #666660; font-weight: 300; line-height: 1.7; margin-bottom: 24px; }
        .intro strong { color: #0a0a0a; font-weight: 500; }

        .section-label {
            font-size: 10px; font-weight: 600; letter-spacing: 0.1em;
            text-transform: uppercase; color: #a0a09a; margin-bottom: 10px;
        }

        .divider { border: none; border-top: 1px solid #efefed; margin: 24px 0; }

        /* Document info row */
        .doc-row {
            display: flex; align-items: center; gap: 12px;
            background: #fef2f2; border: 1px solid #fecaca;
            border-radius: 8px; padding: 14px 16px; margin-bottom: 24px;
        }
        .doc-dot { width: 6px; height: 6px; border-radius: 50%; background: #f87171; flex-shrink: 0; }
        .doc-type { font-size: 12px; font-weight: 500; color: #dc2626; }
        .doc-badge {
            margin-left: auto; font-size: 10px; font-family: 'Courier New', monospace;
            font-weight: 600; color: #dc2626; background: #fef2f2;
            border: 1px solid #fecaca; border-radius: 4px; padding: 2px 8px;
        }

        /* Rejection reason */
        .reason-block {
            background: #fef2f2; border: 1px solid #fecaca;
            border-radius: 8px; padding: 16px; margin-bottom: 24px;
        }
        .reason-title { font-size: 11px; font-weight: 600; color: #dc2626; margin-bottom: 6px; }
        .reason-text { font-size: 13px; color: #0a0a0a; font-weight: 400; line-height: 1.6; }

        /* Steps */
        .steps { margin-bottom: 24px; }
        .step {
            display: flex; gap: 12px; align-items: flex-start;
            padding: 9px 0; border-bottom: 1px solid #efefed;
        }
        .step:last-child { border-bottom: none; }
        .step-num {
            width: 20px; height: 20px; border: 1px solid #e0e0dc; border-radius: 4px;
            font-family: 'Courier New', monospace; font-size: 10px; font-weight: 600;
            color: #a0a09a; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; margin-top: 1px;
        }
        .step-text { font-size: 12px; color: #666660; font-weight: 300; line-height: 1.5; }
        .step-text strong { color: #0a0a0a; font-weight: 500; }

        /* Tips */
        .tips { background: #f7f7f5; border: 1px solid #e0e0dc; border-radius: 8px; padding: 16px; margin-bottom: 24px; }
        .tip { font-size: 12px; color: #666660; font-weight: 300; padding: 4px 0; line-height: 1.5; }
        .tip::before { content: "—"; color: #a0a09a; margin-right: 8px; }

        /* CTA */
        .cta-wrap { text-align: center; margin: 4px 0 0; }
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
        <div class="header-label">Vérification vendeur</div>
        <div class="header-title">Document rejeté</div>
        <div class="header-sub">Une action de votre part est requise</div>
    </div>

    <div class="card">

        <p class="intro">
            Bonjour <strong>{{ $user->name }}</strong>,<br><br>
            Nous avons examiné votre demande d'inscription en tant que vendeur.
            Malheureusement, un document a été <strong>rejeté</strong> et nécessite une correction avant de poursuivre.
        </p>

        {{-- Document concerné --}}
        <div class="section-label">Document concerné</div>
        <div class="doc-row">
            <span class="doc-dot"></span>
            <span class="doc-type">{{ $documentType }} — {{ $documentSide }}</span>
            <span class="doc-badge">Rejeté</span>
        </div>

        {{-- Motif --}}
        <div class="section-label">Motif du rejet</div>
        <div class="reason-block">
            <div class="reason-title">Raison communiquée par l'administrateur</div>
            <div class="reason-text">{{ $rejectionReason }}</div>
        </div>

        <hr class="divider">

        {{-- Étapes --}}
        <div class="section-label">Que faire maintenant</div>
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-text"><strong>Lire</strong> attentivement le motif du rejet ci-dessus</div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-text"><strong>Corriger</strong> le problème soulevé (nouvelle photo, meilleure qualité, document valide…)</div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-text"><strong>Soumettre à nouveau</strong> le document corrigé depuis votre espace personnel</div>
            </div>
        </div>

        {{-- Conseils --}}
        <div class="tips">
            <div class="section-label" style="margin-bottom:8px;">Conseils</div>
            <div class="tip">Assurez-vous que le document est clair et lisible</div>
            <div class="tip">Utilisez une bonne lumière lors de la prise de photo</div>
            <div class="tip">Toutes les informations doivent être visibles</div>
            <div class="tip">Pour les pièces recto/verso, envoyez les deux faces</div>
            <div class="tip">Vérifiez que le document n'est pas expiré</div>
        </div>

        {{-- CTA --}}
        <div class="cta-wrap">
            <a href="{{ $supportUrl }}" class="btn">Contacter le support</a>
        </div>

    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Supply. Cet email a été généré automatiquement.</p>
    </div>

</div>
</body>
</html>