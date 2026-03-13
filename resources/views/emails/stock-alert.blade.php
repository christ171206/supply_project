@extends('layouts.email')

@section('content')
<table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f7f7f5; padding: 20px 0;">
    <tr>
        <td align="center" style="padding: 20px;">
            <table width="100%" maxwidth="600" cellpadding="0" cellspacing="0" style="background-color: white; border: 1px solid #e0e0dc; border-radius: 8px; overflow: hidden;">

                {{-- Header --}}
                <tr>
                    <td style="background-color: #0a0a0a; padding: 30px; text-align: center; color: white;">
                        <h1 style="margin: 0; font-size: 24px; font-weight: bold;">⚠️ Alerte Stock</h1>
                        <p style="margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;">
                            {{ $alertType === 'critical' ? 'Rupture de stock détectée' : 'Stock faible signalé' }}
                        </p>
                    </td>
                </tr>

                {{-- Content --}}
                <tr>
                    <td style="padding: 30px; border-bottom: 1px solid #e0e0dc;">
                        <p style="margin: 0 0 20px 0; font-size: 16px; color: #0a0a0a;">
                            Bonjour {{ $vendor->prenom }} {{ $vendor->nom }},
                        </p>

                        <div style="background-color: {{ $alertType === 'critical' ? '#fef2f2' : '#fef3c7' }}; border-left: 4px solid {{ $alertType === 'critical' ? '#dc2626' : '#f59e0b' }}; padding: 15px; margin: 20px 0; border-radius: 4px;">
                            <p style="margin: 0; font-size: 14px; color: #0a0a0a; font-weight: bold;">
                                {{ $alertType === 'critical' ? '🚨 ALERTE CRITIQUE' : '⚠️ ALERTE IMPORTANTE' }}
                            </p>
                            <p style="margin: 8px 0 0 0; font-size: 14px; color: #666660;">
                                Le produit <strong>{{ $produit->nom }}</strong> nécessite une action immédiate.
                            </p>
                        </div>

                        {{-- Details --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin: 20px 0;">
                            <tr style="background-color: #f7f7f5;">
                                <td style="padding: 12px; border: 1px solid #e0e0dc; font-weight: bold; color: #0a0a0a;">Stock Actuel</td>
                                <td style="padding: 12px; border: 1px solid #e0e0dc; color: {{ $currentStock === 0 ? '#dc2626' : '#f59e0b' }}; font-weight: bold; font-size: 18px;">
                                    {{ $currentStock }} unités
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; border: 1px solid #e0e0dc; font-weight: bold; color: #0a0a0a;">Seuil Minimum</td>
                                <td style="padding: 12px; border: 1px solid #e0e0dc; color: #666660;">
                                    {{ $minimumThreshold }} unités
                                </td>
                            </tr>
                            <tr style="background-color: #f7f7f5;">
                                <td style="padding: 12px; border: 1px solid #e0e0dc; font-weight: bold; color: #0a0a0a;">Écart</td>
                                <td style="padding: 12px; border: 1px solid #e0e0dc; color: #dc2626; font-weight: bold;">
                                    {{ $minimumThreshold - $currentStock }} unités manquantes
                                </td>
                            </tr>
                        </table>

                        <p style="margin: 20px 0 0 0; font-size: 14px; color: #666660;">
                            Nous vous recommandons de réapprovisionner rapidement votre stock pour éviter les ruptures et les clients insatisfaits.
                        </p>
                    </td>
                </tr>

                {{-- Action Buttons --}}
                <tr>
                    <td style="padding: 20px; text-align: center; background-color: #f7f7f5; border-top: 1px solid #e0e0dc;">
                        <a href="{{ $vendorDashboardUrl }}" style="display: inline-block; background-color: #0a0a0a; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-right: 10px;">
                            Voir Dashboard
                        </a>
                        <a href="{{ $productManageUrl }}" style="display: inline-block; background-color: white; color: #0a0a0a; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: bold; border: 1px solid #e0e0dc;">
                            Gérer Produit
                        </a>
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="padding: 15px; text-align: center; color: #a0a09a; font-size: 12px; background-color: white;">
                        <p style="margin: 0;">
                            Cette alerte a été générée automatiquement. Merci d'agir rapidement pour maintenir votre catalogue à jour.
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
