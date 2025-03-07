<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General settings
            [
                'key' => 'site_name',
                'value' => 'SwiftBank',
                'type' => 'string',
                'group' => 'general',
                'is_public' => true,
                'description' => 'The name of the banking system',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_description',
                'value' => 'A modern banking platform for all your financial needs',
                'type' => 'string',
                'group' => 'general',
                'is_public' => true,
                'description' => 'Short description of the banking system',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'contact_email',
                'value' => 'support@swiftbank.example.com',
                'type' => 'string',
                'group' => 'general',
                'is_public' => true,
                'description' => 'Contact email for support',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'contact_phone',
                'value' => '+1-800-123-4567',
                'type' => 'string',
                'group' => 'general',
                'is_public' => true,
                'description' => 'Contact phone number for support',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Security settings
            [
                'key' => 'login_attempts_limit',
                'value' => '5',
                'type' => 'integer',
                'group' => 'security',
                'is_public' => false,
                'description' => 'Maximum number of failed login attempts before account lockout',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'account_lockout_duration',
                'value' => '30',
                'type' => 'integer',
                'group' => 'security',
                'is_public' => false,
                'description' => 'Duration in minutes for account lockout after failed login attempts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'password_expiry_days',
                'value' => '90',
                'type' => 'integer',
                'group' => 'security',
                'is_public' => false,
                'description' => 'Number of days before password expires and needs to be changed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'two_factor_auth_required',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'security',
                'is_public' => true,
                'description' => 'Whether two-factor authentication is required for all users',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Transaction settings
            [
                'key' => 'default_transaction_fee',
                'value' => '0.00',
                'type' => 'decimal',
                'group' => 'transactions',
                'is_public' => true,
                'description' => 'Default fee for standard transactions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'international_transaction_fee',
                'value' => '5.00',
                'type' => 'decimal',
                'group' => 'transactions',
                'is_public' => true,
                'description' => 'Fee for international transactions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'daily_transfer_limit',
                'value' => '10000.00',
                'type' => 'decimal',
                'group' => 'transactions',
                'is_public' => true,
                'description' => 'Maximum amount that can be transferred in a day',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'minimum_balance',
                'value' => '100.00',
                'type' => 'decimal',
                'group' => 'transactions',
                'is_public' => true,
                'description' => 'Minimum balance required in accounts',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Notification settings
            [
                'key' => 'email_notifications_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'notifications',
                'is_public' => true,
                'description' => 'Whether email notifications are enabled',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sms_notifications_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'notifications',
                'is_public' => true,
                'description' => 'Whether SMS notifications are enabled',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'push_notifications_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'notifications',
                'is_public' => true,
                'description' => 'Whether push notifications are enabled',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'transaction_notification_threshold',
                'value' => '1000.00',
                'type' => 'decimal',
                'group' => 'notifications',
                'is_public' => true,
                'description' => 'Threshold amount for transaction notifications',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Maintenance settings
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'maintenance',
                'is_public' => true,
                'description' => 'Whether the system is in maintenance mode',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'maintenance_message',
                'value' => 'The system is currently undergoing scheduled maintenance. Please check back later.',
                'type' => 'string',
                'group' => 'maintenance',
                'is_public' => true,
                'description' => 'Message to display during maintenance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'system_version',
                'value' => '1.0.0',
                'type' => 'string',
                'group' => 'maintenance',
                'is_public' => true,
                'description' => 'Current version of the system',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }
    }
}
