<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $commande->numero }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #0a0a0a;
            line-height: 1.6;
            padding: 40px;
            background: white;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            border-bottom: 3px solid #e0e0dc;
            padding-bottom: 20px;
        }
        .company-info h1 {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .invoice-num {
            font-size: 18px;
            font-family: 'Courier New', monospace;
            color: #666660;
            margin-bottom: 10px;
        }
        .invoice-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-livree { background: #90EE90; color: #0a0a0a; }
        .status-en-preparation { background: #FFD700; color: #0a0a0a; }
        .status-annulee { background: #FFB6C6; color: #0a0a0a; }

        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }
        .section-title {
            font-size: 11px;
            text-transform: uppercase;
            color: #a0a09a;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .client-info, .delivery-info {
            border: 1px solid #e0e0dc;
            padding: 15px;
            border-radius: 4px;
        }
        .client-info p, .delivery-info p {
            margin-bottom: 5px;
            font-size: 13px;
        }
        .client-info p strong, .delivery-info p strong {
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            margin-top: 20px;
        }
        thead {
            border-top: 2px solid #e0e0dc;
            border-bottom: 2px solid #e0e0dc;
        }
        th {
            padding: 12px 0;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            color: #a0a09a;
            font-weight: bold;
        }
        th:last-child, td:last-child {
            text-align: right;
        }
        td {
            padding: 12px 0;
            border-bottom: 1px solid #e0e0dc;
            font-size: 13px;
        }
        .product-name {
            font-weight: 600;
            color: #0a0a0a;
            margin-bottom: 3px;
        }
        .vendor-name {
            font-size: 11px;
            color: #a0a09a;
        }
        .quantity { font-family: 'Courier New', monospace; }
        .price { font-family: 'Courier New', monospace; color: #666660; }
        .total { font-weight: bold; font-family: 'Courier New', monospace; }

        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }
        .totals-box {
            width: 300px;
        }
        .total-line {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-top: 1px solid #e0e0dc;
            font-size: 13px;
        }
        .total-line.final {
            border-top: 3px solid #e0e0dc;
            border-bottom: 3px solid #e0e0dc;
            padding: 15px 0;
            background: #f7f7f5;
            font-weight: bold;
            font-size: 16px;
        }
        .total-amount {
            font-family: 'Courier New', monospace;
        }

        .payment-info {
            border: 1px solid #e0e0dc;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 40px;
        }

        footer {
            border-top: 1px solid #e0e0dc;
            padding-top: 20px;
            text-align: center;
            font-size: 11px;
            color: #a0a09a;
            margin-top: 40px;
        }

        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header>
            <div class="company-info">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                    <div style="width: 32px; height: 32px; background: #0a0a0a; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                        <span style="color: white; font-weight: bold; font-size: 20px; font-family: 'Instrument Serif';">S</span>
                    </div>
                    <h1 style="font-family: 'Instrument Serif', serif; font-size: 24px; font-weight: bold; margin: 0;">Supply</h1>
                </div>
                <div class="invoice-num">Facture #{{ $commande->numero }}</div>
            </div>
            <div style="text-align: right;">
                <div class="invoice-status status-{{ str_replace('_', '-', $commande->statut) }}">
                    {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                </div>
                <p style="margin-top: 10px; font-size: 13px; color: #666660;">
                    <strong>Émise le:</strong> {{ $commande->created_at->format('d/m/Y') }}<br>
                    @if($commande->delivered_at)
                    <strong>Livrée le:</strong> {{ $commande->delivered_at->format('d/m/Y') }}
                    @endif
                </p>
            </div>
        </header>

        <!-- Client & Delivery Info -->
        <div class="content">
            <div>
                <div class="section-title">Client</div>
                <div class="client-info">
                    <p><strong>{{ $commande->client->name }}</strong></p>
                    <p>{{ $commande->client->email }}</p>
                    <p>{{ $commande->client->phone }}</p>
                </div>
            </div>
            <div>
                <div class="section-title">Adresse de Livraison</div>
                <div class="delivery-info">
                    <p>{{ $commande->client->address }}</p>
                    <p>{{ $commande->deliveryLocation?->city ?? 'N/A' }}, {{ $commande->pays }}</p>
                    @if($commande->deliveryLocation)
                    <p>{{ $commande->deliveryLocation->postal_code }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Produit</th>
                    <th style="width: 10%;">Quantité</th>
                    <th style="width: 20%;">Prix Unitaire</th>
                    <th style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commande->ligneCommandes as $ligne)
                <tr>
                    <td>
                        <div class="product-name">{{ $ligne->produit->nom }}</div>
                        <div class="vendor-name">par {{ $ligne->produit->vendeur->shop_name ?? $ligne->produit->vendeur->name }}</div>
                    </td>
                    <td class="quantity">{{ $ligne->quantite }}</td>
                    <td class="price">{{ number_format($ligne->prix_unitaire, 0) }} F</td>
                    <td class="total">{{ number_format($ligne->prix_unitaire * $ligne->quantite, 0) }} F</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-box">
                @php
                    $subtotal = $commande->ligneCommandes->sum(fn($l) => $l->prix_unitaire * $l->quantite);
                    $tva = $subtotal * 0.18;
                    $total = $subtotal + $tva;
                @endphp
                <div class="total-line">
                    <span>Sous-total</span>
                    <span class="total-amount">{{ number_format($subtotal, 0) }} F</span>
                </div>
                <div class="total-line">
                    <span>TVA (18%)</span>
                    <span class="total-amount">{{ number_format($tva, 0) }} F</span>
                </div>
                <div class="total-line final">
                    <span>TOTAL À PAYER</span>
                    <span class="total-amount">{{ number_format($total, 0) }} F</span>
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        @if($commande->paiementMethod)
        <div class="payment-info">
            <div class="section-title">Méthode de Paiement</div>
            <p><strong>{{ ucfirst($commande->paiementMethod->type_paiement) }}</strong></p>
            <p>Référence: {{ $commande->paiementMethod->reference_transaction ?? 'N/A' }}</p>
        </div>
        @endif

        <footer>
            <p>Supply - Plateforme de Commerce Électronique</p>
            <p>Ce document est une facture générée automatiquement. Pour plus d'informations, visitez www.supply-ci.com</p>
        </footer>
    </div>
</body>
</html>
