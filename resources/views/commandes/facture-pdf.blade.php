<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ str_pad($commande->id, 6, '0', STR_PAD_LEFT) }} — Supply</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist:wght@300;400;500&family=Geist+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Geist', -apple-system, sans-serif;
            font-weight: 300;
            color: #0a0a0a;
            line-height: 1.6;
            background: #f7f7f5;
        }

        /* ── Toolbar (screen only) ── */
        .toolbar {
            position: sticky;
            top: 0;
            background: #ffffff;
            border-bottom: 1px solid #e0e0dc;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 100;
        }
        .toolbar-label {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #a0a09a;
            margin-right: 8px;
        }
        .toolbar button {
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 500;
            font-family: 'Geist', sans-serif;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-primary {
            background: #0a0a0a;
            color: #fff;
            border: 1px solid #0a0a0a;
        }
        .btn-primary:hover { opacity: 0.85; }
        .btn-ghost {
            background: transparent;
            color: #666660;
            border: 1px solid #e0e0dc;
        }
        .btn-ghost:hover { border-color: #2a2a28; color: #0a0a0a; }
        .toolbar .spacer { flex: 1; }

        /* ── Page ── */
        .page {
            max-width: 820px;
            margin: 32px auto;
            background: #ffffff;
            border: 1px solid #e0e0dc;
            border-radius: 12px;
            overflow: hidden;
        }

        /* ── Header ── */
        .page-header {
            background: #0a0a0a;
            padding: 32px 40px 28px;
        }
        .page-header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .invoice-brand {
            font-family: 'Instrument Serif', serif;
            font-size: 28px;
            color: #ffffff;
            letter-spacing: -0.5px;
            line-height: 1;
        }
        .invoice-brand-sub {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            margin-top: 6px;
        }
        .invoice-meta {
            text-align: right;
        }
        .invoice-number {
            font-family: 'Geist Mono', monospace;
            font-size: 15px;
            font-weight: 500;
            color: #ffffff;
        }
        .invoice-date {
            font-family: 'Geist Mono', monospace;
            font-size: 11px;
            color: rgba(255,255,255,0.4);
            margin-top: 4px;
        }

        /* ── Body ── */
        .page-body { padding: 36px 40px; }

        /* ── Info blocks ── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 36px;
            padding-bottom: 32px;
            border-bottom: 1px solid #efefed;
        }
        .info-block-label {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #a0a09a;
            margin-bottom: 10px;
        }
        .info-block p {
            font-size: 13px;
            color: #2a2a28;
            line-height: 1.7;
        }
        .info-block p strong {
            font-weight: 500;
            color: #0a0a0a;
        }

        /* ── Table ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 28px;
        }
        thead tr {
            background: #f7f7f5;
            border-bottom: 1px solid #e0e0dc;
        }
        th {
            padding: 10px 12px;
            text-align: left;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #a0a09a;
        }
        th.r { text-align: right; }
        th.c { text-align: center; }
        td {
            padding: 12px;
            border-bottom: 1px solid #efefed;
            font-size: 13px;
            color: #2a2a28;
            vertical-align: top;
        }
        tr:last-child td { border-bottom: none; }
        td.r { text-align: right; }
        td.c { text-align: center; }
        .td-name {
            font-weight: 500;
            color: #0a0a0a;
        }
        .td-desc {
            font-size: 11px;
            color: #a0a09a;
            margin-top: 2px;
            font-weight: 300;
        }
        .td-mono {
            font-family: 'Geist Mono', monospace;
            font-size: 12px;
            font-weight: 400;
        }

        /* ── Totaux ── */
        .totals {
            width: 260px;
            margin-left: auto;
            margin-bottom: 28px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 9px 0;
            border-bottom: 1px solid #efefed;
            font-size: 12px;
            color: #666660;
        }
        .total-row span:last-child {
            font-family: 'Geist Mono', monospace;
            font-size: 12px;
            color: #0a0a0a;
        }
        .total-row.final {
            border-top: 1px solid #0a0a0a;
            border-bottom: none;
            padding-top: 12px;
            font-size: 13px;
            font-weight: 500;
            color: #0a0a0a;
        }
        .total-row.final span:last-child {
            font-size: 15px;
            font-weight: 500;
        }

        /* ── Paiement ── */
        .payment-block {
            border: 1px solid #e0e0dc;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 36px;
        }
        .payment-header {
            background: #f7f7f5;
            padding: 10px 16px;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #a0a09a;
            border-bottom: 1px solid #e0e0dc;
        }
        .payment-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 16px;
            border-bottom: 1px solid #efefed;
            font-size: 12px;
        }
        .payment-row:last-child { border-bottom: none; }
        .payment-row span:first-child { color: #a0a09a; }
        .payment-row span:last-child {
            font-weight: 500;
            color: #0a0a0a;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 500;
            font-family: 'Geist Mono', monospace;
        }
        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }
        .badge-ok   { background: #f0fdf4; color: #15803d; }
        .badge-ok   .badge-dot { background: #22c55e; }
        .badge-warn { background: #fdf6ec; color: #b45309; }
        .badge-warn .badge-dot { background: #f59e0b; }

        /* ── Footer ── */
        .page-footer {
            border-top: 1px solid #efefed;
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .page-footer span {
            font-size: 10px;
            color: #a0a09a;
            font-weight: 300;
        }
        .page-footer .footer-brand {
            font-family: 'Geist Mono', monospace;
            font-size: 11px;
            font-weight: 500;
            color: #666660;
            letter-spacing: 0.06em;
        }

        /* ── Print ── */
        @media print {
            body { background: #fff; }
            .toolbar { display: none; }
            .page {
                margin: 0;
                border: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>

    {{-- Toolbar (screen only) --}}
    <div class="toolbar">
        <span class="toolbar-label">Facture</span>
        <button class="btn-primary" onclick="window.print()">Imprimer / PDF</button>
        <button class="btn-ghost" onclick="downloadHTML()">Télécharger HTML</button>
        <div class="spacer"></div>
        <button class="btn-ghost" onclick="window.history.back()">Fermer ×</button>
    </div>

    <div class="page">

        {{-- Header noir --}}
        <div class="page-header">
            <div class="page-header-top">
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                        <div style="width: 32px; height: 32px; background: white; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                            <span style="color: #0a0a0a; font-weight: bold; font-size: 18px; font-family: 'Instrument Serif';">S</span>
                        </div>
                        <div class="invoice-brand">Supply</div>
                    </div>
                    <div class="invoice-brand-sub">Marketplace B2B • Tech & Informatique</div>
                </div>
                <div class="invoice-meta">
                    <div class="invoice-number">#{{ str_pad($commande->id, 6, '0', STR_PAD_LEFT) }}</div>
                    <div class="invoice-date">{{ $commande->created_at->format('d/m/Y · H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="page-body">

            {{-- Client + Livraison --}}
            <div class="info-grid">
                <div>
                    <div class="info-block-label">Client</div>
                    <p><strong>{{ auth()->user()->name }}</strong></p>
                    <p>{{ auth()->user()->email }}</p>
                    <p>{{ auth()->user()->phone ?? '—' }}</p>
                </div>
                <div>
                    <div class="info-block-label">Adresse de livraison</div>
                    <p>{{ $commande->adresse_livraison }}</p>
                    @if($commande->adresse_detail)
                        <p style="color:#a0a09a; margin-top:4px; font-size:12px;">{{ $commande->adresse_detail }}</p>
                    @endif
                    @if($commande->telephone_livraison)
                        <p style="font-family:'Geist Mono',monospace; font-size:11px; color:#a0a09a; margin-top:4px;">{{ $commande->telephone_livraison }}</p>
                    @endif
                </div>
            </div>

            {{-- Table produits --}}
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th class="c">Qté</th>
                        <th class="r">Prix unitaire</th>
                        <th class="r">Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lignes as $ligne)
                        <tr>
                            <td>
                                <div class="td-name">{{ $ligne->produit->nom }}</div>
                                @if($ligne->produit->description)
                                    <div class="td-desc">{{ Str::limit($ligne->produit->description, 55) }}</div>
                                @endif
                            </td>
                            <td class="c td-mono">{{ $ligne->quantite }}</td>
                            <td class="r td-mono">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                            <td class="r td-mono">{{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color:#a0a09a; font-weight:300;">Aucun produit</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Totaux --}}
            <div class="totals">
                <div class="total-row">
                    <span>Sous-total</span>
                    <span>{{ number_format($sousTotal, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="total-row">
                    <span>Livraison</span>
                    <span>{{ $frais == 0 ? 'Gratuite' : number_format($frais, 0, ',', ' ') . ' FCFA' }}</span>
                </div>
                <div class="total-row final">
                    <span>Total TTC</span>
                    <span>{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            {{-- Paiement --}}
            @if($payment)
            <div class="payment-block">
                <div class="payment-header">Paiement</div>
                <div class="payment-row">
                    <span>Méthode</span>
                    <span>{{ ucfirst(str_replace('_', ' ', $commande->payment_method)) }}</span>
                </div>
                <div class="payment-row">
                    <span>Statut</span>
                    <span>
                        @if($payment->statut === 'confirme')
                            <span class="badge badge-ok">
                                <span class="badge-dot"></span>Payé
                            </span>
                        @else
                            <span class="badge badge-warn">
                                <span class="badge-dot"></span>En attente
                            </span>
                        @endif
                    </span>
                </div>
                @if($payment->reference)
                    <div class="payment-row">
                        <span>Référence</span>
                        <span style="font-family:'Geist Mono',monospace; font-size:12px;">{{ $payment->reference }}</span>
                    </div>
                @endif
                @if($payment->date_paiement)
                    <div class="payment-row">
                        <span>Date paiement</span>
                        <span style="font-family:'Geist Mono',monospace; font-size:12px;">{{ $payment->date_paiement->format('d/m/Y · H:i') }}</span>
                    </div>
                @endif
            </div>
            @endif

        </div>{{-- /page-body --}}

        {{-- Footer --}}
        <div class="page-footer">
            <span class="footer-brand">SUPPLY</span>
            <span>Facture générée le {{ now()->format('d/m/Y à H:i') }}</span>
            <span>© 2026 Supply. Tous droits réservés.</span>
        </div>

    </div>{{-- /page --}}

    <script>
    function downloadHTML() {
        const html = `<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Facture #{{ str_pad($commande->id, 6, '0', STR_PAD_LEFT) }}</title></head><body>${document.querySelector('.page').outerHTML}</body></html>`;
        const blob = new Blob([html], { type: 'text/html' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'facture_{{ str_pad($commande->id, 6, '0', STR_PAD_LEFT) }}.html';
        a.click();
    }
    </script>
</body>
</html>
