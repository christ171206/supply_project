<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $commande->id }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&family=Geist+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #0a0a0a;
            line-height: 1.6;
            background: #f7f7f5;
        }
        .print-only {
            display: none;
        }
        .screen-only {
            display: block;
        }
        @media print {
            .print-only {
                display: block;
            }
            .screen-only {
                display: none;
            }
            body {
                background: white;
            }
        }
        .print-toolbar {
            position: sticky;
            top: 0;
            background: white;
            padding: 16px 20px;
            border-bottom: 1px solid #e0e0dc;
            display: flex;
            gap: 12px;
            align-items: center;
            z-index: 100;
        }
        .print-toolbar button {
            padding: 10px 20px;
            border: 1px solid #0a0a0a;
            background: transparent;
            color: #0a0a0a;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            font-size: 13px;
            transition: all 0.2s;
        }
        .print-toolbar button:hover {
            background: #0a0a0a;
            color: #fff;
        }
        .print-toolbar .close-btn {
            margin-left: auto;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            background: white;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 1px solid #e0e0dc;
            padding-bottom: 24px;
        }
        .header-left h1 {
            font-family: 'Instrument Serif', serif;
            font-size: 32px;
            color: #0a0a0a;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .header-left p {
            font-size: 13px;
            color: #666660;
        }
        .header-right {
            text-align: right;
        }
        .header-right .invoice-number {
            font-size: 13px;
            color: #666660;
            margin-bottom: 8px;
        }
        .header-right .invoice-date {
            font-size: 12px;
            color: #a0a09a;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .info-block {
            width: 45%;
        }
        .info-block h3 {
            font-size: 11px;
            color: #0a0a0a;
            text-transform: uppercase;
            margin-bottom: 12px;
            font-weight: 600;
            letter-spacing: 0.05em;
        }
        .info-block p {
            font-size: 13px;
            margin-bottom: 6px;
            line-height: 1.8;
            color: #0a0a0a;
        }
        .info-block strong {
            font-weight: 600;
        }
        .table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .table thead {
            background-color: #f7f7f5;
            border-bottom: 1px solid #e0e0dc;
        }
        .table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            color: #0a0a0a;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0dc;
            font-size: 13px;
            color: #0a0a0a;
        }
        .table tbody tr:hover {
            background-color: #f7f7f5;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .product-name {
            font-weight: 600;
            color: #0a0a0a;
        }
        .product-desc {
            color: #a0a09a;
            font-size: 12px;
        }
        .quantity {
            font-family: 'Geist Mono', monospace;
            font-weight: 500;
        }
        .price {
            font-family: 'Geist Mono', monospace;
            font-weight: 600;
            color: #0a0a0a;
        }
        .totals-section {
            width: 50%;
            margin-left: auto;
            margin-bottom: 30px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0dc;
            font-size: 13px;
            color: #0a0a0a;
        }
        .total-row span:last-child {
            font-family: 'Geist Mono', monospace;
            font-weight: 500;
        }
        .total-row.final {
            border-bottom: 1px solid #0a0a0a;
            padding: 16px 0;
            font-size: 14px;
            font-weight: 700;
            color: #0a0a0a;
        }
        .total-row.final span:last-child {
            font-weight: 700;
        }
        .payment-info {
            background-color: #f7f7f5;
            border-left: 1px solid #e0e0dc;
            padding: 16px;
            margin-top: 30px;
            font-size: 13px;
        }
        .payment-info h3 {
            color: #0a0a0a;
            margin-bottom: 10px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .payment-info div {
            margin-bottom: 6px;
            color: #0a0a0a;
        }
        .payment-info strong {
            font-weight: 600;
        }
        .payment-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0dc;
            color: #a0a09a;
            font-size: 11px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .status-pending {
            background-color: #fefce8;
            color: #92400e;
        }
        .status-paid {
            background-color: #f0fdf4;
            color: #15803d;
        }
    </style>
</head>
<body>
    <!-- Barre d'outils pour l'impression -->
    <div class="screen-only print-toolbar">
        <button class="print-btn" onclick="window.print()">
            Imprimer / Télécharger
        </button>
        <button onclick="downloadAsHTML()">
            Télécharger HTML
        </button>
        <button class="close-btn" onclick="window.history.back()">
            Fermer
        </button>
    </div>

    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <div class="header-left">
                <h1>FACTURE</h1>
                <p>#{{ str_pad($commande->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="header-right">
                <div class="invoice-number">
                    <strong>Date:</strong> {{ $commande->created_at->format('d/m/Y') }}
                </div>
                <div class="invoice-date">
                    <strong>Heure:</strong> {{ $commande->created_at->format('H:i') }}
                </div>
            </div>
        </div>

        <!-- Informations Client et Livraison -->
        <div class="info-section">
            <div class="info-block">
                <h3>Client</h3>
                <p><strong>{{ auth()->user()->name }}</strong></p>
                <p>{{ auth()->user()->email }}</p>
                <p>{{ auth()->user()->phone ?? 'Non renseigné' }}</p>
            </div>
            <div class="info-block">
                <h3>Adresse de Livraison</h3>
                <p>{{ $commande->adresse_livraison }}</p>
            </div>
        </div>

        <!-- Table des produits -->
        <table class="table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th class="text-center">Quantité</th>
                    <th class="text-right">Prix Unitaire</th>
                    <th class="text-right">Sous-Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lignes as $ligne)
                    <tr>
                        <td>
                            <div class="product-name">{{ $ligne->produit->nom }}</div>
                            @if($ligne->produit->description)
                                <div class="product-desc">{{ Str::limit($ligne->produit->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="text-center quantity">{{ $ligne->quantite }}</td>
                        <td class="text-right price">{{ number_format($ligne->prix_unitaire, 0, '', ' ') }} F</td>
                        <td class="text-right price">{{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, '', ' ') }} F</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Aucun produit</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Totaux -->
        <div class="totals-section">
            <div class="total-row">
                <span>Sous-total</span>
                <span>{{ number_format($sousTotal, 0, '', ' ') }} F</span>
            </div>
            <div class="total-row">
                <span>Frais de livraison</span>
                <span>
                    @if($frais == 0)
                        Gratuit
                    @else
                        {{ number_format($frais, 0, '', ' ') }} F
                    @endif
                </span>
            </div>
            <div class="total-row final">
                <span>TOTAL TTC</span>
                <span>{{ number_format($total, 0, '', ' ') }} F</span>
            </div>
        </div>

        <!-- Informations de paiement -->
        @if($payment)
            <div class="payment-info">
                <h3>Paiement</h3>
                <div class="payment-details">
                    <span><strong>Méthode:</strong> {{ ucfirst(str_replace('_', ' ', $commande->payment_method)) }}</span>
                    <span>
                        @if($payment->statut === 'confirme')
                            <span class="status-badge status-paid">Payé</span>
                        @else
                            <span class="status-badge status-pending">En Attente</span>
                        @endif
                    </span>
                </div>
                @if($payment->reference)
                    <div><strong>Référence:</strong> {{ $payment->reference }}</div>
                @endif
                @if($payment->date_paiement)
                    <div><strong>Date de paiement:</strong> {{ $payment->date_paiement->format('d/m/Y H:i') }}</div>
                @endif
            </div>
        @endif

        <!-- Pied de page -->
        <div class="footer">
            <p>
                Merci pour votre achat | Facture générée le {{ now()->format('d/m/Y H:i') }} | Gestion E-commerce Supply
            </p>
        </div>
    </div>

    <script>
        function downloadAsHTML() {
            const element = document.querySelector('.container');
            const html = element.innerHTML;
            const blob = new Blob([html], { type: 'text/html' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'facture_{{ str_pad($commande->id, 6, '0', STR_PAD_LEFT) }}.html';
            link.click();
        }
    </script>
</body>
</html>
