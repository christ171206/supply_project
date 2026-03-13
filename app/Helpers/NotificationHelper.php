<?php

if (!function_exists('getNotificationTypeLabel')) {
    /**
     * Obtenir le label français d'un type de notification
     */
    function getNotificationTypeLabel($type)
    {
        $labels = [
            'order_status_change' => 'État de commande',
            'vendor_order' => 'Nouvelle commande',
            'stock_alert_critical' => 'Alerte critique',
            'stock_alert_low' => 'Stock bas',
            'stock_alert_critical_admin' => 'Alerte critique (Admin)',
            'delivery_reminder' => 'Rappel livraison',
            'payment_confirmation' => 'Paiement confirmé',
            'refund' => 'Remboursement',
            'dispute_opened' => 'Litige ouvert',
            'dispute_resolved' => 'Litige résolu',
        ];

        return $labels[$type] ?? $type;
    }
}

if (!function_exists('getNotificationTypeColor')) {
    /**
     * Obtenir la couleur pour un type de notification
     */
    function getNotificationTypeColor($type)
    {
        $colors = [
            'order_status_change' => 'blue',
            'vendor_order' => 'green',
            'stock_alert_critical' => 'red',
            'stock_alert_low' => 'yellow',
            'stock_alert_critical_admin' => 'red',
            'delivery_reminder' => 'purple',
            'payment_confirmation' => 'green',
            'refund' => 'orange',
            'dispute_opened' => 'red',
            'dispute_resolved' => 'green',
        ];

        return $colors[$type] ?? 'gray';
    }
}

if (!function_exists('getNotificationTypeIcon')) {
    /**
     * Obtenir l'icône pour un type de notification
     */
    function getNotificationTypeIcon($type)
    {
        $icons = [
            'order_status_change' => '📦',
            'vendor_order' => '🛍️',
            'stock_alert_critical' => '🚨',
            'stock_alert_low' => '⚠️',
            'stock_alert_critical_admin' => '🚨',
            'delivery_reminder' => '📬',
            'payment_confirmation' => '✅',
            'refund' => '💰',
            'dispute_opened' => '⚖️',
            'dispute_resolved' => '✅',
        ];

        return $icons[$type] ?? '📢';
    }
}
