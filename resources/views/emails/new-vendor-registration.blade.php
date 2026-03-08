<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f9fafb; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h1 { margin: 0; font-size: 24px; font-weight: bold; }
        .header p { margin: 10px 0 0 0; opacity: 0.9; }
        .content-box { background: white; padding: 30px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .detail-box { background: #f0f4ff; padding: 20px; border-left: 4px solid #667eea; margin: 20px 0; border-radius: 4px; }
        .detail-row { display: flex; margin: 10px 0; font-size: 14px; }
        .detail-label { font-weight: bold; width: 120px; color: #667eea; }
        .detail-value { flex: 1; color: #555; }
        .status-badge { display: inline-block; background: #fef3c7; color: #92400e; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; margin: 10px 0; }
        .button { 
            display: inline-block; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            padding: 14px 32px; 
            text-decoration: none; 
            border-radius: 6px; 
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
        }
        .button:hover { opacity: 0.9; }
        .action-needed { 
            background: #fee2e2; 
            border-left: 4px solid #dc2626; 
            padding: 20px; 
            margin: 20px 0; 
            border-radius: 4px;
        }
        .action-needed h3 { margin: 0 0 10px 0; color: #991b1b; font-size: 16px; }
        .steps { margin: 15px 0; }
        .step { margin: 8px 0; padding-left: 25px; position: relative; }
        .step:before { content: counter(step-counter); counter-increment: step-counter; position: absolute; left: 0; background: #667eea; color: white; width: 20px; height: 20px; border-radius: 50%; text-align: center; line-height: 20px; font-size: 12px; font-weight: bold; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px; }
        .timestamp { color: #999; font-size: 12px; margin-top: 10px; }
        counter-reset: step-counter 1;
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📌 Nouvelle demande d'inscription vendeur</h1>
            <p>Une nouvelle demande vient d'être reçue</p>
        </div>
        
        <div class="content-box">
            <p>Bonjour Admin,</p>
            
            <p>Une nouvelle demande d'inscription de <strong>vendeur</strong> a été reçue sur <strong>Supply</strong>.</p>
            
            <h2 style="color: #667eea; margin-top: 30px;">📋 Détails du vendeur</h2>
            <div class="detail-box">
                <div class="detail-row">
                    <div class="detail-label">Nom :</div>
                    <div class="detail-value">{{ $vendor->name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email :</div>
                    <div class="detail-value">{{ $vendor->email }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Boutique :</div>
                    <div class="detail-value">{{ $vendor->shop_name ?? 'Non spécifiée' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Téléphone :</div>
                    <div class="detail-value">{{ $vendor->phone ?? 'Non fourni' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Adresse :</div>
                    <div class="detail-value">{{ $vendor->address ?? 'Non fournie' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Pays :</div>
                    <div class="detail-value">{{ $vendor->country ?? 'Non spécifié' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Date :</div>
                    <div class="detail-value">{{ $vendor->created_at->format('d/m/Y à H:i') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Statut :</div>
                    <div class="detail-value"><span class="status-badge">⏳ En attente de vérification email</span></div>
                </div>
            </div>
            
            <h2 style="color: #667eea; margin-top: 30px;">🔄 Étapes suivantes</h2>
            <div class="steps" style="counter-reset: step-counter;">
                <div class="step">Le vendeur doit d'abord vérifier son email (code à 6 chiffres)</div>
                <div class="step">Il soumettra ensuite ses documents d'identité (CN I/CMU/Passeport recto + verso)</div>
                <div class="step">Vous recevrez une notification pour examiner et approuver/rejeter les documents</div>
            </div>
            
            <div class="action-needed">
                <h3>🎯 Votre action requise</h3>
                <p>Consultez régulièrement votre tableau de bord pour examiner les documents d'identité soumis et prendre une décision (approbation ou rejet).</p>
                <a href="{{ $adminDashboardUrl }}" class="button">Voir le tableau de bord</a>
            </div>
            
            <p style="color: #666; font-size: 14px; margin-top: 20px;">Vous recevrez une autre notification une fois que le vendeur aura soumis ses documents d'identité.</p>
        </div>
        
        <div class="footer">
            <p>© 2026 Supply - Plateforme E-commerce</p>
            <p style="color: #999;">Cet email a été généré automatiquement. Veuillez ne pas répondre directement.</p>
        </div>
    </div>
</body>
</html>
