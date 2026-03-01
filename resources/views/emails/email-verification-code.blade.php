<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4f46e5; color: white; padding: 20px; text-align: center; border-radius: 5px; }
        .content { padding: 20px; background: #f9fafb; margin: 20px 0; border-radius: 5px; }
        .code-box { 
            background: white; 
            border: 2px solid #4f46e5; 
            padding: 20px; 
            text-align: center; 
            font-size: 32px; 
            letter-spacing: 5px; 
            font-weight: bold; 
            margin: 20px 0;
            border-radius: 5px;
        }
        .button { 
            display: inline-block; 
            background: #4f46e5; 
            color: white; 
            padding: 12px 30px; 
            text-decoration: none; 
            border-radius: 5px; 
            margin: 20px 0;
        }
        .footer { 
            text-align: center; 
            font-size: 12px; 
            color: #666; 
            margin-top: 30px; 
            border-top: 1px solid #ddd; 
            padding-top: 20px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Bienvenue sur Supply!</h1>
        </div>
        
        <div class="content">
            <p>Bonjour {{ $userName }},</p>
            
            <p>Merci de vous être inscrit sur <strong>Supply</strong>.</p>
            
            <p>Pour vérifier votre adresse email et terminer votre inscription, veuillez entrer le code de vérification ci-dessous :</p>
            
            <div class="code-box">
                {{ $verificationCode }}
            </div>
            
            <p><strong>Important :</strong> Ce code expire dans 10 minutes.</p>
            
            <p>
                <a href="{{ route('verification.code.show') }}" class="button">
                    Vérifier mon email
                </a>
            </p>
            
            <p>Si vous n'avez pas créé de compte, veuillez ignorer cet email.</p>
            
            <p>Cordialement,<br>L'équipe Supply</p>
        </div>
        
        <div class="footer">
            <p>© 2026 Supply. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
