<?php

namespace Database\Seeders;

use App\Models\AdminRole;
use App\Models\SystemConfiguration;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les rôles admin
        $adminPermissions = [
            'manage_users',
            'verify_documents',
            'ban_users',
            'manage_products',
            'manage_stock',
            'manage_orders',
            'manage_disputes',
            'manage_configuration',
            'view_reports',
        ];

        $stockManagerPermissions = [
            'manage_products',
            'manage_stock',
        ];

        $orderManagerPermissions = [
            'manage_orders',
            'manage_disputes',
            'view_reports',
        ];

        $financialManagerPermissions = [
            'view_reports',
        ];

        AdminRole::firstOrCreate(
            ['name' => 'super_admin'],
            [
                'description' => 'Administrateur avec tous les droits',
                'permissions' => $adminPermissions,
            ]
        );

        AdminRole::firstOrCreate(
            ['name' => 'stock_manager'],
            [
                'description' => 'Gestionnaire de stock et catalogue',
                'permissions' => $stockManagerPermissions,
            ]
        );

        AdminRole::firstOrCreate(
            ['name' => 'order_manager'],
            [
                'description' => 'Gestionnaire de commandes et litiges',
                'permissions' => $orderManagerPermissions,
            ]
        );

        AdminRole::firstOrCreate(
            ['name' => 'financial_manager'],
            [
                'description' => 'Gestionnaire financier',
                'permissions' => $financialManagerPermissions,
            ]
        );

        // Créer les configurations par défaut
        SystemConfiguration::firstOrCreate(
            ['key' => 'delivery_base_fee'],
            [
                'value' => '2500',
                'type' => 'number',
                'description' => 'Frais de livraison de base en XOF',
            ]
        );

        SystemConfiguration::firstOrCreate(
            ['key' => 'default_delivery_days'],
            [
                'value' => '2',
                'type' => 'number',
                'description' => 'Nombre de jours de livraison par défaut',
            ]
        );

        SystemConfiguration::firstOrCreate(
            ['key' => 'currency_exchange_rate'],
            [
                'value' => '655.957',
                'type' => 'number',
                'description' => 'Taux de change EUR/XOF',
            ]
        );

        SystemConfiguration::firstOrCreate(
            ['key' => 'platform_commission_rate'],
            [
                'value' => '5',
                'type' => 'number',
                'description' => 'Pourcentage de commission de la plateforme (%)',
            ]
        );

        SystemConfiguration::firstOrCreate(
            ['key' => 'stock_alert_enabled'],
            [
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Activer les alertes de stock critique',
            ]
        );

        $this->command->info('Rôles et configurations admin créés avec succès!');
    }
}
