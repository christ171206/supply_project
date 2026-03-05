<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $commande->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            background: #f5f5f5;
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
            padding: 15px;
            border-bottom: 1px solid #ddd;
            display: flex;
            gap: 10px;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 100;
        }
        .print-toolbar button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }
        .print-btn {
            background: #0ea5e9;
            color: white;
        }
        .print-btn:hover {
            background: #0284c7;
        }
        .download-btn {
            background: #22c55e;
            color: white;
        }
        .download-btn:hover {
            background: #16a34a;
        }
        .close-btn {
            background: #ef4444;
            color: white;
            margin-left: auto;
        }
        .close-btn:hover {
            background: #dc2626;
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
            border-bottom: 3px solid #0ea5e9;
            padding-bottom: 20px;
        }
        .header-left h1 {
            font-size: 28px;
            color: #0ea5e9;
            margin-bottom: 5px;
        }
        .header-left p {
            font-size: 12px;
            color: #666;
        }
        .header-right {
            text-align: right;
        }
        .header-right .invoice-number {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .header-right .invoice-date {
            font-size: 12px;
            color: #999;
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
            font-size: 12px;
            color: #0ea5e9;
            text-transform: uppercase;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .info-block p {
            font-size: 13px;
            margin-bottom: 5px;
            line-height: 1.8;
        }
        .table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .table thead {
            background-color: #f0f9ff;
            border-bottom: 2px solid #0ea5e9;
        }
        .table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 13px;
            color: #0ea5e9;
        }
        .table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }
        .table tbody tr:hover {
            background-color: #fafbfc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-section {
            width: 50%;
            margin-left: auto;
            margin-bottom: 30px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }
        .total-row.final {
            border-bottom: 2px solid #0ea5e9;
            padding: 15px 0;
            font-size: 16px;
            font-weight: bold;
            color: #0ea5e9;
        }
        .payment-info {
            background-color: #f0f9ff;
            border-left: 3px solid #0ea5e9;
            padding: 15px;
            margin-top: 30px;
            font-size: 13px;
        }
        .payment-info h3 {
            color: #0ea5e9;
            margin-bottom: 8px;
            font-size: 13px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #999;
            font-size: 11px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #fef08a;
            color: #854d0e;
        }
        .status-paid {
            background-color: #dcfce7;
            color: #166534;
        }
    </style>
</head>
<body>
    <!-- Barre d'outils pour l'impression -->
    <div class="screen-only print-toolbar">
        <button class="print-btn" onclick="window.print()">
            🖨️ Imprimer / Télécharger en PDF
        </button>
        <button class="download-btn" onclick="downloadAsHTML()">
            📥 Télécharger en HTML
        </button>
        <button class="close-btn" onclick="window.history.back()">
            ✕ Fermer
        </button>
    </div>

    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <div class="header-left">
                <h1>FACTURE</h1>
                <p>Numéro #{{ str_pad($commande->id, 6, '0', STR_PAD_LEFT) }}</p>
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
                <h3>Informations Client</h3>
                <p><strong>{{ auth()->user()->name }}</strong></p>
                <p>{{ auth()->user()->email }}</p>
                <p>{{ auth()->user()->phone ?? 'Non renseigné' }}</p>
            </div>
            <div class="info-block">
                <h3>🚚 Adresse de Livraison</h3>
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
                            <strong>{{ $ligne->produit->nom }}</strong>
                            @if($ligne->produit->description)
                                <br><span style="color: #999; font-size: 12px;">{{ Str::limit($ligne->produit->description, 50) }}</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $ligne->quantite }}</td>
                        <td class="text-right">{{ number_format($ligne->prix_unitaire, 0, '', ' ') }} F</td>
                        <td class="text-right"><strong>{{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, '', ' ') }} F</strong></td>
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
                <h3>💳 Paiement</h3>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span><strong>Méthode:</strong> {{ ucfirst(str_replace('_', ' ', $commande->payment_method)) }}</span>
                    <span>
                        @if($payment->statut === 'confirmé')
                            <span class="status-badge status-paid">✓ Payé</span>
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
                Merci pour votre achat ! |
                Facture générée le {{ now()->format('d/m/Y H:i') }} |
                Gestion E-commerce Supply
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
