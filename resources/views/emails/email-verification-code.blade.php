<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f9fafb; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 30px 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h1 { margin: 0; font-size: 28px; font-weight: bold; }
        .header p { margin: 10px 0 0 0; opacity: 0.9; }
        .content-box { background: white; padding: 30px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .code-box { 
            background: linear-gradient(135deg, #f0fdf4 0%, #dbeafe 100%);
            border: 2px solid #10b981; 
            padding: 25px; 
            text-align: center; 
            font-size: 36px; 
            letter-spacing: 8px; 
            font-weight: bold; 
            margin: 25px 0;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            color: #059669;
        }
        .button { 
            display: inline-block; 
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white; 
            padding: 14px 32px; 
            text-decoration: none; 
            border-radius: 6px; 
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
        }
        .button:hover { opacity: 0.9; }
        .info-box { background: #ecfdf5; border-left: 4px solid #10b981; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .warning-box { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px; }
        h2 { color: #10b981; font-size: 18px; margin-top: 25px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Vérification de votre email</h1>
            <p>Confirmez votre inscription sur Supply</p>
        </div>
        
        <div class="content-box">
            <p>Bonjour <strong>{{ $userName }}</strong>,</p>
            
            <p>Merci de vous être inscrit sur <strong>Supply</strong> ! 🎉</p>
            
            <p>Pour finaliser votre inscription et accéder à votre compte, veuillez vérifier votre adresse email en utilisant le code ci-dessous :</p>
            
            <div class="code-box">
                {{ $verificationCode }}
            </div>
            
            <p style="text-align: center; color: #666; font-size: 14px;">Copiez ce code et collez-le sur la page de vérification</p>
            
            <a href="{{ route('verification.code.show') }}" class="button" style="display: block; width: 200px; box-sizing: border-box; margin-left: auto; margin-right: auto;">
                ➜ Vérifier mon email
            </a>
            
            <div class="warning-box">
                <strong>⏱️ Important :</strong> Ce code expire dans <strong>10 minutes</strong>. Si vous ne l'avez pas utilisé, vous devrez en demander un nouveau.
            </div>
            
            <h2>📋 Prochaines étapes :</h2>
            <ol>
                <li>Vérifiez votre email avec le code ci-dessus</li>
                <li>Une fois vérifié, vous accèderez à votre espace personnel</li>
                <li>Préparez vos documents d'identité (CNI, CMU ou Passeport recto + verso)</li>
                <li>Soumettez vos documents pour validation (si vous êtes vendeur)</li>
            </ol>
            
            <div class="info-box">
                <strong>💡 Astuce :</strong> Gardez ce code à proximité au cas où vous en auriez besoin pendant la vérification.
            </div>
            
            <p style="margin-top: 30px; color: #666;">Si vous n'avez pas créé de compte Supply, veuillez ignorer cet email. Nous vous demandons de <strong>ne pas répondre</strong> à ce message.</p>
            
            <p style="color: #666;">Besoin d'aide ? Consultez notre centre d'assistance ou contactez notre support.</p>
            
            <p style="margin-top: 20px;">Cordialement,<br><strong>L'équipe Supply</strong></p>
        </div>
        
        <div class="footer">
            <p>© 2026 Supply - Plateforme E-commerce de confiance</p>
            <p style="color: #999;">Cet email a été généré automatiquement. Veuillez ne pas répondre directement.</p>
        </div>
    </div>
</body>
</html>
