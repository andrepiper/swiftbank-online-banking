<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $adminId = DB::table('users')->insertGetId([
            'firstname' => 'Admin',
            'middlename' => '',
            'lastname' => 'User',
            'email' => 'admin@swiftbank.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'admin',
            'profile_picture' => null,
            'created_at' => now()->subMonths(6),
            'updated_at' => now(),
        ]);

        DB::table('user_profiles')->insert([
            'user_id' => $adminId,
            'date_of_birth' => '1985-01-15',
            'address_line1' => '123 Admin Street',
            'address_line2' => 'Suite 100',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'country' => 'USA',
            'nationality' => 'American',
            'tax_id' => '123-45-6789',
            'occupation' => 'System Administrator',
            'employer' => 'SwiftBank',
            'annual_income' => 120000.00,
            'source_of_funds' => 'Employment',
            'risk_profile' => 'LOW',
            'created_at' => now()->subMonths(6),
            'updated_at' => now(),
        ]);

        DB::table('user_preferences')->insert([
            'user_id' => $adminId,
            'language' => 'en',
            'timezone' => 'America/New_York',
            'notification_email' => true,
            'notification_sms' => true,
            'notification_push' => true,
            'two_factor_enabled' => true,
            'created_at' => now()->subMonths(6),
            'updated_at' => now(),
        ]);

        // Create regular users
        $users = [
            [
                'firstname' => 'John',
                'middlename' => '',
                'lastname' => 'Doe',
                'email' => 'john@example.com',
                'profile' => [
                    'date_of_birth' => '1990-05-20',
                    'address_line1' => '456 Main Street',
                    'address_line2' => 'Apt 303',
                    'city' => 'Boston',
                    'state' => 'MA',
                    'postal_code' => '02108',
                    'country' => 'USA',
                    'nationality' => 'American',
                    'tax_id' => '234-56-7890',
                    'occupation' => 'Software Engineer',
                    'employer' => 'Tech Solutions Inc',
                    'annual_income' => 95000.00,
                    'source_of_funds' => 'Employment',
                    'risk_profile' => 'MEDIUM',
                ]
            ],
            [
                'firstname' => 'Jane',
                'middlename' => '',
                'lastname' => 'Smith',
                'email' => 'jane@example.com',
                'profile' => [
                    'date_of_birth' => '1988-11-12',
                    'address_line1' => '789 Oak Avenue',
                    'address_line2' => '',
                    'city' => 'San Francisco',
                    'state' => 'CA',
                    'postal_code' => '94105',
                    'country' => 'USA',
                    'nationality' => 'American',
                    'tax_id' => '345-67-8901',
                    'occupation' => 'Consultant',
                    'employer' => 'Self-Employed',
                    'annual_income' => 110000.00,
                    'source_of_funds' => 'Business Income',
                    'risk_profile' => 'MEDIUM',
                ]
            ],
            [
                'firstname' => 'Michael',
                'middlename' => 'J',
                'lastname' => 'Johnson',
                'email' => 'michael@example.com',
                'profile' => [
                    'date_of_birth' => '1985-07-30',
                    'address_line1' => '321 Pine Street',
                    'address_line2' => 'Unit 505',
                    'city' => 'Chicago',
                    'state' => 'IL',
                    'postal_code' => '60601',
                    'country' => 'USA',
                    'nationality' => 'American',
                    'tax_id' => '456-78-9012',
                    'occupation' => 'Financial Analyst',
                    'employer' => 'Global Finance Corp',
                    'annual_income' => 135000.00,
                    'source_of_funds' => 'Employment',
                    'risk_profile' => 'HIGH',
                ]
            ],
            [
                'firstname' => 'Emily',
                'middlename' => '',
                'lastname' => 'Wilson',
                'email' => 'emily@example.com',
                'profile' => [
                    'date_of_birth' => '1992-03-15',
                    'address_line1' => '654 Maple Road',
                    'address_line2' => '',
                    'city' => 'Austin',
                    'state' => 'TX',
                    'postal_code' => '73301',
                    'country' => 'USA',
                    'nationality' => 'American',
                    'tax_id' => '567-89-0123',
                    'occupation' => 'Graduate Student',
                    'employer' => 'University of Texas',
                    'annual_income' => 25000.00,
                    'source_of_funds' => 'Scholarship',
                    'risk_profile' => 'LOW',
                ]
            ],
        ];

        foreach ($users as $userData) {
            $createdAt = now()->subMonths(rand(1, 5));

            $userId = DB::table('users')->insertGetId([
                'firstname' => $userData['firstname'],
                'middlename' => $userData['middlename'],
                'lastname' => $userData['lastname'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'user',
                'profile_picture' => null,
                'created_at' => $createdAt,
                'updated_at' => now(),
            ]);

            $profile = $userData['profile'];
            DB::table('user_profiles')->insert([
                'user_id' => $userId,
                'date_of_birth' => $profile['date_of_birth'],
                'address_line1' => $profile['address_line1'],
                'address_line2' => $profile['address_line2'],
                'city' => $profile['city'],
                'state' => $profile['state'],
                'postal_code' => $profile['postal_code'],
                'country' => $profile['country'],
                'nationality' => $profile['nationality'],
                'tax_id' => $profile['tax_id'],
                'occupation' => $profile['occupation'],
                'employer' => $profile['employer'],
                'annual_income' => $profile['annual_income'],
                'source_of_funds' => $profile['source_of_funds'],
                'risk_profile' => $profile['risk_profile'],
                'created_at' => $createdAt,
                'updated_at' => now(),
            ]);

            DB::table('user_preferences')->insert([
                'user_id' => $userId,
                'language' => 'en',
                'timezone' => 'America/New_York',
                'notification_email' => true,
                'notification_sms' => rand(0, 1) ? true : false,
                'notification_push' => rand(0, 1) ? true : false,
                'two_factor_enabled' => rand(0, 1) ? true : false,
                'created_at' => $createdAt,
                'updated_at' => now(),
            ]);
        }
    }
}
