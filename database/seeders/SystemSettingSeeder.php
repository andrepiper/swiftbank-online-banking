<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'setting_key' => 'site_name',
                'setting_value' => 'SwiftBank',
                'description' => 'The name of the banking application',
                'is_public' => 1,
            ],
            [
                'setting_key' => 'site_logo',
                'setting_value' => '/assets/images/logo.png',
                'description' => 'The logo image path for the banking application',
                'is_public' => 1,
            ],
            [
                'setting_key' => 'primary_color',
                'setting_value' => '#3490dc',
                'description' => 'Primary color for the application theme',
                'is_public' => 1,
            ],
            [
                'setting_key' => 'secondary_color',
                'setting_value' => '#38c172',
                'description' => 'Secondary color for the application theme',
                'is_public' => 1,
            ],
            [
                'setting_key' => 'maintenance_mode',
                'setting_value' => '0',
                'description' => 'Whether the application is in maintenance mode (1 for yes, 0 for no)',
                'is_public' => 1,
            ],
            [
                'setting_key' => 'support_email',
                'setting_value' => 'support@swiftbank.example',
                'description' => 'Email address for customer support',
                'is_public' => 1,
            ],
            [
                'setting_key' => 'support_phone',
                'setting_value' => '+1-800-SWIFT-BANK',
                'description' => 'Phone number for customer support',
                'is_public' => 1,
            ],
            [
                'setting_key' => 'transaction_fee_percentage',
                'setting_value' => '1.5',
                'description' => 'Default percentage fee for transactions',
                'is_public' => 0,
            ],
            [
                'setting_key' => 'minimum_transaction_fee',
                'setting_value' => '0.50',
                'description' => 'Minimum fee amount for any transaction',
                'is_public' => 0,
            ],
            [
                'setting_key' => 'maximum_transaction_fee',
                'setting_value' => '25.00',
                'description' => 'Maximum fee amount for any transaction',
                'is_public' => 0,
            ],
            [
                'setting_key' => 'daily_transfer_limit',
                'setting_value' => '10000.00',
                'description' => 'Default daily transfer limit for standard accounts',
                'is_public' => 1,
            ],
            [
                'setting_key' => 'monthly_transfer_limit',
                'setting_value' => '50000.00',
                'description' => 'Default monthly transfer limit for standard accounts',
                'is_public' => 1,
            ],
            [
                'setting_key' => 'default_currency',
                'setting_value' => 'USD',
                'description' => 'Default currency for the application',
                'is_public' => 1,
            ],
            [
                'setting_key' => 'allowed_currencies',
                'setting_value' => 'USD,EUR,GBP,CAD,AUD,JPY',
                'description' => 'Comma-separated list of allowed currencies',
                'is_public' => 1,
            ],
            [
                'setting_key' => 'terms_and_conditions_url',
                'setting_value' => '/legal/terms',
                'description' => 'URL to the terms and conditions page',
                'is_public' => 1,
            ],
            [
                'setting_key' => 'privacy_policy_url',
                'setting_value' => '/legal/privacy',
                'description' => 'URL to the privacy policy page',
                'is_public' => 1,
            ],
            [
                'setting_key' => 'session_timeout',
                'setting_value' => '30',
                'description' => 'Session timeout in minutes',
                'is_public' => 0,
            ],
            [
                'setting_key' => 'enable_two_factor_auth',
                'setting_value' => '1',
                'description' => 'Whether two-factor authentication is enabled (1 for yes, 0 for no)',
                'is_public' => 1,
            ],
            [
                'setting_key' => 'minimum_password_length',
                'setting_value' => '8',
                'description' => 'Minimum password length for user accounts',
                'is_public' => 1,
            ],
            [
                'setting_key' => 'password_requires_special_char',
                'setting_value' => '1',
                'description' => 'Whether passwords require special characters (1 for yes, 0 for no)',
                'is_public' => 1,
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('system_settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
