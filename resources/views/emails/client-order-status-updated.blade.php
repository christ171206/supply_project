<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour de votre commande</title>
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
        }
        .logo-mark {
            width: 28px;
            height: 28px;
            background: #fff;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-name {
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            letter-spacing: -0.01em;
        }

        /* Card */
        .card {
            background: #fff;
            border: 1px solid #e0e0dc;
            border-top: none;
            padding: 32px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 600;
            color: #0a0a0a;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }
        .card-sub {
            font-size: 13px;
            color: #666660;
            font-weight: 300;
            margin-bottom: 24px;
        }

        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-family: 'Courier New', monospace;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 4px;
        }
        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid #efefed;
            margin: 24px 0;
        }

        /* Section label */
        .section-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #a0a09a;
            margin-bottom: 12px;
        }

        /* Details table */
        .details {
            width: 100%;
            border-collapse: collapse;
        }
        .details tr {
            border-bottom: 1px solid #efefed;
        }
        .details tr:last-child {
            border-bottom: none;
        }
        .details td {
            padding: 9px 0;
            font-size: 13px;
        }
        .details td:first-child {
            color: #a0a09a;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            width: 36%;
        }
        .details td:last-child {
            color: #0a0a0a;
            font-weight: 500;
        }
        .mono {
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }

        /* Items */
        .item {
            padding: 12px 0;
            border-bottom: 1px solid #efefed;
        }
        .item:last-child {
            border-bottom: none;
        }
        .item-name {
            font-size: 13px;
            font-weight: 500;
            color: #0a0a0a;
            margin-bottom: 4px;
        }
        .item-meta {
            font-size: 11px;
            color: #a0a09a;
            font-family: 'Courier New', monospace;
        }

        /* CTA */
        .cta-wrap {
            text-align: center;
            margin: 28px 0 4px;
        }
        .cta {
            display: inline-block;
            background: #0a0a0a;
            color: #fff !important;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            padding: 12px 28px;
            border-radius: 8px;
            letter-spacing: -0.01em;
        }

        /* Footer */
        .footer {
            background: #f7f7f5;
            border: 1px solid #e0e0dc;
            border-top: none;
            border-radius: 0 0 12px 12px;
            padding: 20px 32px;
            text-align: center;
        }
        .footer p {
            font-size: 11px;
            color: #a0a09a;
            font-weight: 300;
        }
    </style>
</head>
<body>
<div class="wrap">

    {{-- Header --}}
    <div class="header">
        <div class="logo">
            <div class="logo-mark">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0a0a0a" stroke-width="2.5">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                </svg>
            </div>
            <span class="logo-name">{{ config('app.name', 'Supply') }}</span>
        </div>
    </div>

    {{-- Card --}}
    <div class="card">

        {{-- Titre --}}
        <div class="card-title">Mise à jour de votre commande</div>
        <div class="card-sub">Bonjour <strong>{{ $commande->user->name }}</strong>, voici les informations concernant votre commande.</div>

        {{-- Badge statut --}}
        @php
            $badgeStyle = match(true) {
                str_contains(strtolower($statusLabel ?? ''), 'attente')  => 'background:#fdf6ec; color:#b45309;',
                str_contains(strtolower($statusLabel ?? ''), 'confirm')  => 'background:#eff6ff; color:#2563eb;',
                str_contains(strtolower($statusLabel ?? ''), 'expédi')   => 'background:#f5f3ff; color:#7c3aed;',
                str_contains(strtolower($statusLabel ?? ''), 'livr')     => 'background:#f0fdf4; color:#15803d;',
                str_contains(strtolower($statusLabel ?? ''), 'annul')    => 'background:#fef2f2; color:#dc2626;',
                default                                                   => 'background:#f7f7f5; color:#666660;',
            };
            $dotColor = match(true) {
                str_contains(strtolower($statusLabel ?? ''), 'attente') => '#f59e0b',
                str_contains(strtolower($statusLabel ?? ''), 'confirm') => '#60a5fa',
                str_contains(strtolower($statusLabel ?? ''), 'expédi')  => '#a78bfa',
                str_contains(strtolower($statusLabel ?? ''), 'livr')    => '#22c55e',
                str_contains(strtolower($statusLabel ?? ''), 'annul')   => '#f87171',
                default                                                  => '#a0a09a',
            };
        @endphp
        <span class="status-badge" style="{{ $badgeStyle }}">
            <span class="status-dot" style="background:{{ $dotColor }};"></span>
            {{ $statusLabel }}
        </span>

        <hr class="divider">

        {{-- Détails commande --}}
        <div class="section-label">Détails de la commande</div>
        <table class="details">
            <tr>
                <td>Numéro</td>
                <td class="mono">{{ $commande->numero ?? 'CMD-' . $commande->id }}</td>
            </tr>
            <tr>
                <td>Date</td>
                <td class="mono">{{ $commande->created_at->locale('fr')->format('d M Y · H:i') }}</td>
            </tr>
            <tr>
                <td>Montant</td>
                <td class="mono">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
            </tr>
        </table>

        <hr class="divider">

        {{-- Articles --}}
        <div class="section-label">Articles commandés</div>
        @foreach($commande->ligneCommandes as $ligne)
            <div class="item">
                <div class="item-name">{{ $ligne->produit->nom }} &times; {{ $ligne->quantite }}</div>
                <div class="item-meta">
                    {{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA &nbsp;·&nbsp;
                    Total&nbsp;: {{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, ',', ' ') }} FCFA
                </div>
            </div>
        @endforeach

        <hr class="divider">

        {{-- CTA --}}
        <div class="cta-wrap">
            <a href="{{ url('/commandes/' . $commande->id) }}" class="cta">Voir votre commande</a>
        </div>

        <p style="font-size:12px; color:#a0a09a; font-weight:300; text-align:center; margin-top:16px;">
            Une question ? Répondez directement à cet email.
        </p>

    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>© {{ date('Y') }} {{ config('app.name', 'Supply') }}. Tous droits réservés.</p>
    </div>

</div>
</body>
</html>
