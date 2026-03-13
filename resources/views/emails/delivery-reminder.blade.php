@extends('layouts.email')

@section('content')
<table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f7f7f5; padding: 20px 0;">
    <tr>
        <td align="center" style="padding: 20px;">
            <table width="100%" maxwidth="600" cellpadding="0" cellspacing="0" style="background-color: white; border: 1px solid #e0e0dc; border-radius: 8px; overflow: hidden;">

                {{-- Header --}}
                <tr>
                    <td style="background-color: #0a0a0a; padding: 30px; text-align: center; color: white;">
                        <h1 style="margin: 0; font-size: 28px; font-weight: bold;">📦 Votre Colis Arrive</h1>
                        <p style="margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;">
                            Livraison dans {{ $daysUntilDelivery }} jour{{ $daysUntilDelivery > 1 ? 's' : '' }}
                        </p>
                    </td>
                </tr>

                {{-- Content --}}
                <tr>
                    <td style="padding: 30px; border-bottom: 1px solid #e0e0dc;">
                        <p style="margin: 0 0 20px 0; font-size: 16px; color: #0a0a0a;">
                            Bonjour {{ $client->prenom }} {{ $client->nom }},
                        </p>

                        <div style="background-color: #f0fdf4; border-left: 4px solid #15803d; padding: 15px; margin: 20px 0; border-radius: 4px;">
                            <p style="margin: 0; font-size: 16px; color: #0a0a0a; font-weight: bold;">
                                ✅ Votre commande est en route !
                            </p>
                            <p style="margin: 8px 0 0 0; font-size: 14px; color: #15803d;">
                                Vous recevrez votre colis dans les {{ $daysUntilDelivery }} prochain{{ $daysUntilDelivery > 1 ? 's' : '' }} jour{{ $daysUntilDelivery > 1 ? 's' : '' }}.
                            </p>
                        </div>

                        {{-- Order Details --}}
                        <h3 style="margin: 20px 0 10px 0; font-size: 14px; color: #0a0a0a; font-weight: bold;">Détails de la Commande</h3>

                        <table width="100%" cellpadding="0" cellspacing="0" style="margin: 10px 0;">
                            <tr style="background-color: #f7f7f5;">
                                <td style="padding: 12px; border: 1px solid #e0e0dc; font-weight: bold; color: #0a0a0a;">Numéro</td>
                                <td style="padding: 12px; border: 1px solid #e0e0dc; color: #666660;">
                                    {{ $orderNumber }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; border: 1px solid #e0e0dc; font-weight: bold; color: #0a0a0a;">Montant</td>
                                <td style="padding: 12px; border: 1px solid #e0e0dc; color: #0a0a0a;">
                                    {{ number_format($totalAmount, 0, ',', ' ') }} CFA
                                </td>
                            </tr>
                            <tr style="background-color: #f7f7f5;">
                                <td style="padding: 12px; border: 1px solid #e0e0dc; font-weight: bold; color: #0a0a0a;">Statut</td>
                                <td style="padding: 12px; border: 1px solid #e0e0dc; color: #15803d;">
                                    🚚 En livraison
                                </td>
                            </tr>
                        </table>

                        <p style="margin: 20px 0 0 0; font-size: 14px; color: #666660;">
                            Assurez-vous de préparer un endroit approprié pour recevoir votre colis. Le livreur vous contactera avant son arrivée.
                        </p>
                    </td>
                </tr>

                {{-- Tracking Info --}}
                <tr>
                    <td style="padding: 20px; background-color: #f7f7f5; border-bottom: 1px solid #e0e0dc;">
                        <h3 style="margin: 0 0 12px 0; font-size: 14px; color: #0a0a0a; font-weight: bold;">📍 Suivi en Temps Réel</h3>
                        <p style="margin: 0 0 12px 0; font-size: 13px; color: #666660;">
                            Suivez votre colis en temps réel et recevez des mises à jour de livraison.
                        </p>
                        <a href="{{ $trackingUrl }}" style="display: inline-block; background-color: #0a0a0a; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">
                            Suivre mon Colis
                        </a>
                    </td>
                </tr>

                {{-- Action Buttons --}}
                <tr>
                    <td style="padding: 20px; text-align: center;">
                        <a href="{{ $commandeDetails }}" style="display: inline-block; background-color: #0a0a0a; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-right: 10px;">
                            Voir Commande Complète
                        </a>
                        <a href="{{ route('faq', '#delivery') }}" style="display: inline-block; background-color: white; color: #0a0a0a; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: bold; border: 1px solid #e0e0dc;">
                            FAQ Livraison
                        </a>
                    </td>
                </tr>

                {{-- Tips --}}
                <tr>
                    <td style="padding: 20px; background-color: #fdf6ec; border-top: 1px solid #e0e0dc;">
                        <h3 style="margin: 0 0 12px 0; font-size: 14px; color: #b45309; font-weight: bold;">💡 Conseils</h3>
                        <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #b45309;">
                            <li style="margin: 6px 0;">Vérifier que quelqu'un sera présent pour recevoir le colis</li>
                            <li style="margin: 6px 0;">Préparer les frais de port si applicable (à la livraison)</li>
                            <li style="margin: 6px 0;">Vérifier l'intégrité du colis à la réception</li>
                        </ul>
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="padding: 15px; text-align: center; color: #a0a09a; font-size: 12px; background-color: white;">
                        <p style="margin: 0;">
                            Des questions ? Consultez notre centre d'aide ou contactez notre support.
                        </p>
                        <p style="margin: 10px 0 0 0; font-size: 11px;">
                            © {{ now()->year }} Supply - Plateforme E-commerce
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
@endsection
