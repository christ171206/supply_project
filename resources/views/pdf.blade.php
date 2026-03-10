<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Vendeur</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Geist", -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif; background: #f7f7f5; }
        .print-container { max-width: 900px; margin: 20px auto; background: white; padding: 40px; }
        h1 { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #0a0a0a; padding-bottom: 15px; font-size: 28px; color: #0a0a0a; }
        h2 { margin-top: 30px; margin-bottom: 15px; background-color: #f7f7f5; padding: 12px; border-left: 4px solid #0a0a0a; font-size: 16px; color: #0a0a0a; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        table th, table td { padding: 12px; text-align: left; border: 1px solid #e0e0dc; }
        table th { background-color: #f7f7f5; font-weight: 600; color: #0a0a0a; }
        table td { color: #0a0a0a; }
        .kpi-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 25px; }
        .kpi-box { padding: 15px; border: 1px solid #e0e0dc; border-radius: 6px; }
        .kpi-label { font-size: 11px; color: #a0a09a; text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em; }
        .kpi-value { font-size: 22px; font-weight: 700; color: #0a0a0a; margin-top: 8px; font-family: "Geist Mono", monospace; }
        .meta { text-align: center; color: #a0a09a; font-size: 12px; margin: 25px 0; }
        .print-controls { text-align: center; margin-bottom: 20px; }
        .print-btn { padding: 10px 20px; background: #0a0a0a; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: 600; }
        .print-btn:hover { opacity: 0.85; }
        @media print {
            body { background: white; }
            .print-container { margin: 0; padding: 20px; }
            .print-controls { display: none; }
            h1 { border-bottom: 2px solid #0a0a0a; }
        }
    </style>
</head>
<body>
    <div class="print-controls">
        <button class="print-btn" onclick="window.print()">Imprimer / Enregistrer en PDF</button>
    </div>

    <div class="print-container">
        {!! $html !!}
    </div>
</body>
</html>
