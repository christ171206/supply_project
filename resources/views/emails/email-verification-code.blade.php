<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification email — Supply</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #f7f7f5;
            color: #0a0a0a;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper { max-width: 560px; margin: 40px auto; padding: 0 20px 60px; }

        /* Header */
        .header {
            padding: 32px 0 28px;
            border-bottom: 1px solid #e0e0dc;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 32px;
        }
        .logo-mark {
            width: 28px; height: 28px;
            background: #0a0a0a;
            border-radius: 6px;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .logo-name { font-size: 14px; font-weight: 500; color: #0a0a0a; }

        /* Card */
        .card {
            background: #ffffff;
            border: 1px solid #e0e0dc;
            border-radius: 12px;
            overflow: hidden;
        }
        .card-header {
            padding: 28px 32px 24px;
            border-bottom: 1px solid #efefed;
        }
        .card-header p {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #a0a09a;
            margin-bottom: 6px;
        }
        .card-header h1 {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 24px;
            font-weight: 400;
            color: #0a0a0a;
            line-height: 1.2;
        }
        .card-header h1 em { font-style: italic; color: #666660; }
        .card-body { padding: 28px 32px; }

        /* Greeting */
        .greeting { font-size: 14px; color: #0a0a0a; margin-bottom: 8px; }
        .subtext { font-size: 13px; color: #666660; font-weight: 300; line-height: 1.6; margin-bottom: 28px; }

        /* Code block */
        .code-wrapper {
            border: 1px solid #e0e0dc;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 24px;
        }
        .code-label {
            padding: 8px 16px;
            background: #f7f7f5;
            border-bottom: 1px solid #efefed;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #a0a09a;
        }
        .code-value {
            padding: 20px 16px;
            font-family: 'Courier New', 'Lucida Console', monospace;
            font-size: 32px;
            font-weight: 500;
            letter-spacing: 10px;
            color: #0a0a0a;
            text-align: center;
            background: #ffffff;
        }
        .code-hint {
            font-size: 11px;
            color: #a0a09a;
            text-align: center;
            margin-bottom: 24px;
            font-weight: 300;
        }

        /* Button */
        .btn {
            display: block;
            width: 100%;
            padding: 12px 24px;
            background: #0a0a0a;
            color: #ffffff;
            text-decoration: none;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
            border-radius: 7px;
            margin-bottom: 28px;
        }

        /* Divider */
        .divider { border: none; border-top: 1px solid #efefed; margin: 24px 0; }

        /* Info rows */
        .info-row {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 14px;
        }
        .info-icon {
            width: 28px; height: 28px;
            border: 1px solid #e0e0dc;
            border-radius: 6px;
            background: #f7f7f5;
            display: inline-flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .info-icon svg { width: 13px; height: 13px; color: #666660; }
        .info-text strong { font-size: 12px; font-weight: 500; color: #0a0a0a; display: block; }
        .info-text span { font-size: 11px; color: #a0a09a; font-weight: 300; line-height: 1.5; }

        /* Warning */
        .warning {
            padding: 12px 14px;
            background: #fdf6ec;
            border: 1px solid #f5e0bb;
            border-radius: 8px;
            font-size: 12px;
            color: #b45309;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        /* Steps */
        .steps-label {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #a0a09a;
            margin-bottom: 10px;
        }
        .step {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin-bottom: 8px;
        }
        .step-num {
            width: 18px; height: 18px;
            border: 1px solid #e0e0dc;
            border-radius: 4px;
            font-size: 10px;
            font-family: 'Courier New', monospace;
            font-weight: 500;
            color: #a0a09a;
            display: inline-flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .step-text { font-size: 12px; color: #666660; font-weight: 300; line-height: 1.5; }

        /* Footer */
        .card-footer {
            padding: 16px 32px;
            background: #f7f7f5;
            border-top: 1px solid #efefed;
        }
        .disclaimer { font-size: 11px; color: #a0a09a; font-weight: 300; line-height: 1.6; }

        /* Bottom footer */
        .bottom-footer { text-align: center; padding-top: 24px; }
        .bottom-footer p { font-size: 11px; color: #a0a09a; font-weight: 300; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Logo --}}
    <div class="header">
        <div class="logo-mark">
            <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" style="width:14px;height:14px">
                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
            </svg>
        </div>
        <span class="logo-name">Supply</span>
    </div>

    {{-- Card --}}
    <div class="card">

        <div class="card-header">
            <p>Sécurité du compte</p>
            <h1>Vérifiez votre<br><em>adresse email</em></h1>
        </div>

        <div class="card-body">

            <p class="greeting">Bonjour <strong>{{ $userName }}</strong>,</p>
            <p class="subtext">
                Merci de vous être inscrit sur Supply. Pour finaliser votre inscription,
                utilisez le code ci-dessous pour confirmer votre adresse email.
            </p>

            {{-- Code --}}
            <div class="code-wrapper">
                <div class="code-label">Code de vérification</div>
                <div class="code-value">{{ $verificationCode }}</div>
            </div>
            <p class="code-hint">Copiez ce code et collez-le sur la page de vérification</p>

            {{-- CTA --}}
            <a href="{{ route('verification.code.show') }}" class="btn">
                Vérifier mon email
            </a>

            {{-- Warning expiry --}}
            <div class="warning">
                Ce code expire dans <strong>10 minutes</strong>. Passé ce délai, vous devrez en demander un nouveau.
            </div>

            <hr class="divider">

            {{-- Steps --}}
            <p class="steps-label">Prochaines étapes</p>

            <div class="step">
                <div class="step-num">1</div>
                <p class="step-text">Vérifiez votre email avec le code ci-dessus</p>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <p class="step-text">Accédez à votre espace personnel</p>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <p class="step-text">Préparez vos documents d'identité (CNI, CMU ou Passeport recto + verso)</p>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <p class="step-text">Soumettez vos documents pour validation (si vous êtes vendeur)</p>
            </div>

            <hr class="divider">

            {{-- Info rows --}}
            <div class="info-row">
                <div class="info-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <div class="info-text">
                    <strong>Ce message est sécurisé</strong>
                    <span>Ne partagez jamais ce code avec quelqu'un d'autre.</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <div class="info-text">
                    <strong>Vous n'avez pas créé de compte ?</strong>
                    <span>Ignorez simplement cet email. Aucune action n'est requise.</span>
                </div>
            </div>

        </div>

        <div class="card-footer">
            <p class="disclaimer">
                Cet email a été envoyé automatiquement par Supply. Veuillez ne pas y répondre directement.
                Pour toute assistance, contactez notre support.
            </p>
        </div>

    </div>

    {{-- Bottom --}}
    <div class="bottom-footer">
        <p>© 2026 Supply — Plateforme e-commerce · Côte d'Ivoire</p>
    </div>

</div>
</body>
</html>