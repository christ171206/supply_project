<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle commande — Supply</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #2a2a28;
            background-color: #f7f7f5;
        }

        .wrapper {
            max-width: 560px;
            margin: 40px auto;
            background: #ffffff;
            border: 1px solid #e0e0dc;
            border-radius: 12px;
            overflow: hidden;
        }

        /* ── Header ── */
        .header {
            background: #0a0a0a;
            padding: 28px 32px 24px;
        }
        .header-label {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            margin-bottom: 10px;
        }
        .header-title {
            font-size: 22px;
            font-weight: 500;
            color: #ffffff;
            letter-spacing: -0.02em;
        }
        .header-sub {
            font-size: 12px;
            color: rgba(255,255,255,0.45);
            font-weight: 300;
            margin-top: 4px;
        }

        /* ── Order number strip ── */
        .order-strip {
            background: #f7f7f5;
            border-bottom: 1px solid #e0e0dc;
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .order-number {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: 500;
            color: #0a0a0a;
            letter-spacing: -0.01em;
        }
        .order-date {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            color: #a0a09a;
        }

        /* ── Body ── */
        .body {
            padding: 28px 32px;
        }

        .greeting {
            font-size: 14px;
            color: #2a2a28;
            margin-bottom: 6px;
        }
        .greeting-sub {
            font-size: 13px;
            color: #666660;
            font-weight: 300;
            margin-bottom: 28px;
        }

        /* ── Section label ── */
        .section-label {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #a0a09a;
            margin-bottom: 10px;
        }

        /* ── Client block ── */
        .client-block {
            border: 1px solid #e0e0dc;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 24px;
        }
        .client-row {
            display: flex;
            align-items: baseline;
            gap: 12px;
            padding: 10px 14px;
            border-bottom: 1px solid #efefed;
        }
        .client-row:last-child { border-bottom: none; }
        .client-key {
            font-size: 11px;
            color: #a0a09a;
            min-width: 90px;
            flex-shrink: 0;
        }
        .client-val {
            font-size: 13px;
            color: #0a0a0a;
            font-weight: 400;
        }

        /* ── Items table ── */
        .items-wrap {
            border: 1px solid #e0e0dc;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead tr {
            background: #f7f7f5;
        }
        th {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #a0a09a;
            padding: 10px 14px;
            text-align: left;
            border-bottom: 1px solid #e0e0dc;
        }
        th.right { text-align: right; }
        td {
            font-size: 13px;
            color: #2a2a28;
            padding: 11px 14px;
            border-bottom: 1px solid #efefed;
            vertical-align: top;
        }
        td.mono {
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }
        td.right { text-align: right; }
        tr:last-child td { border-bottom: none; }
        .total-row td {
            background: #f7f7f5;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            font-weight: 500;
            color: #0a0a0a;
            border-top: 1px solid #e0e0dc;
            border-bottom: none;
        }

        /* ── Payment info ── */
        .payment-block {
            border: 1px solid #e0e0dc;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 28px;
        }
        .payment-row {
            display: flex;
            align-items: baseline;
            gap: 12px;
            padding: 10px 14px;
            border-bottom: 1px solid #efefed;
        }
        .payment-row:last-child { border-bottom: none; }
        .payment-key {
            font-size: 11px;
            color: #a0a09a;
            min-width: 110px;
            flex-shrink: 0;
        }
        .payment-val {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: #0a0a0a;
            font-weight: 500;
        }
        .payment-val.secondary {
            font-family: inherit;
            font-weight: 400;
            color: #2a2a28;
        }

        /* ── Alert ── */
        .alert {
            background: #fdf6ec;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 24px;
            font-size: 12px;
            color: #b45309;
            line-height: 1.5;
        }
        .alert strong { font-weight: 500; }

        /* ── CTA button ── */
        .cta-wrap {
            text-align: center;
            margin-bottom: 24px;
        }
        .cta {
            display: inline-block;
            background: #0a0a0a;
            color: #ffffff;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            letter-spacing: 0.01em;
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1px solid #efefed;
            margin: 0 0 20px;
        }

        /* ── Sign-off ── */
        .signoff {
            font-size: 13px;
            color: #666660;
            font-weight: 300;
            line-height: 1.7;
        }

        /* ── Footer ── */
        .footer {
            background: #f7f7f5;
            border-top: 1px solid #e0e0dc;
            padding: 16px 32px;
            text-align: center;
        }
        .footer p {
            font-size: 11px;
            color: #a0a09a;
            font-weight: 300;
            line-height: 1.6;
        }
        .footer-brand {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            font-weight: 500;
            color: #666660;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <div class="header-label">Notification vendeur</div>
        <div class="header-title">Nouvelle commande reçue</div>
        <div class="header-sub">Une commande vient d'être passée sur votre boutique</div>
    </div>

    {{-- Order strip --}}
    <div class="order-strip">
        <span class="order-number">{{ $commande->numero ?? 'CMD-' . $commande->id }}</span>
        <span class="order-date">{{ $commande->created_at->format('d/m/Y · H:i') }}</span>
    </div>

    {{-- Body --}}
    <div class="body">

        <p class="greeting">Bonjour {{ $vendor->name }},</p>
        <p class="greeting-sub">Une nouvelle commande a été passée sur Supply et attend votre traitement.</p>

        {{-- Client --}}
        <div class="section-label">Informations client</div>
        <div class="client-block">
            <div class="client-row">
                <span class="client-key">Nom</span>
                <span class="client-val">{{ $client->name }}</span>
            </div>
            <div class="client-row">
                <span class="client-key">Email</span>
                <span class="client-val">{{ $client->email }}</span>
            </div>
            <div class="client-row">
                <span class="client-key">Téléphone</span>
                <span class="client-val">{{ $commande->telephone_livraison ?? 'Non fourni' }}</span>
            </div>
            <div class="client-row">
                <span class="client-key">Livraison</span>
                <span class="client-val">{{ $commande->adresse_livraison }}</span>
            </div>
        </div>

        {{-- Items --}}
        <div class="section-label">Articles commandés</div>
        <div class="items-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Qté</th>
                        <th>Prix unitaire</th>
                        <th class="right">Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $item['nom_produit'] }}</td>
                        <td class="mono">{{ $item['quantite'] }}</td>
                        <td class="mono">{{ number_format($item['prix_unitaire'], 0, ',', ' ') }} FCFA</td>
                        <td class="mono right">{{ number_format($item['sous_total'], 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3" style="text-align:right; font-weight:400; font-size:11px; letter-spacing:0.06em; text-transform:uppercase; color:#666660;">
                            Total votre part
                        </td>
                        <td class="right">{{ number_format($total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Paiement --}}
        <div class="section-label">Paiement</div>
        <div class="payment-block">
            <div class="payment-row">
                <span class="payment-key">Méthode</span>
                <span class="payment-val secondary">{{ ucfirst(str_replace('_', ' ', $commande->payment_method)) }}</span>
            </div>
            <div class="payment-row">
                <span class="payment-key">Statut commande</span>
                <span class="payment-val secondary">
                    <span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:#f59e0b; margin-right:6px; vertical-align:middle;"></span>
                    {{ ucfirst($commande->statut) }}
                </span>
            </div>
            <div class="payment-row">
                <span class="payment-key">Total commande</span>
                <span class="payment-val">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>

        {{-- Alert --}}
        <div class="alert">
            <strong>Action requise —</strong> Veuillez consulter cette commande et la traiter dans les meilleurs délais.
        </div>

        {{-- CTA --}}
        <div class="cta-wrap">
            <a href="{{ route('vendeur.commandes.show', $commande->id) }}" class="cta">
                Voir la commande →
            </a>
        </div>

        <hr class="divider">

        <p class="signoff">
            Merci d'être un vendeur fiable sur Supply.<br>
            L'équipe Supply
        </p>

    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-brand">SUPPLY</div>
        <p>© 2026 Supply. Tous droits réservés.<br>
        Cet email a été envoyé automatiquement — merci de ne pas y répondre.</p>
    </div>

</div>
</body>
</html>
