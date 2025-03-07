<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserPreference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@swiftbank.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'admin',
            'status' => 'active',
            'created_at' => now()->subMonths(6),
            'updated_at' => now(),
        ]);

        UserProfile::create([
            'user_id' => $admin->id,
            'first_name' => 'Admin',
            'last_name' => 'User',
            'phone_number' => '+1234567890',
            'date_of_birth' => '1985-01-15',
            'address_line_1' => '123 Admin Street',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'country' => 'USA',
            'nationality' => 'American',
            'employment_status' => 'Employed',
            'occupation' => 'System Administrator',
            'created_at' => now()->subMonths(6),
            'updated_at' => now(),
        ]);

        UserPreference::create([
            'user_id' => $admin->id,
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
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'profile' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'phone_number' => '+1987654321',
                    'date_of_birth' => '1990-05-20',
                    'address_line_1' => '456 Main Street',
                    'city' => 'Boston',
                    'state' => 'MA',
                    'postal_code' => '02108',
                    'country' => 'USA',
                    'nationality' => 'American',
                    'employment_status' => 'Employed',
                    'occupation' => 'Software Engineer',
                ]
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'profile' => [
                    'first_name' => 'Jane',
                    'last_name' => 'Smith',
                    'phone_number' => '+1765432198',
                    'date_of_birth' => '1988-11-12',
                    'address_line_1' => '789 Oak Avenue',
                    'city' => 'San Francisco',
                    'state' => 'CA',
                    'postal_code' => '94105',
                    'country' => 'USA',
                    'nationality' => 'American',
                    'employment_status' => 'Self-Employed',
                    'occupation' => 'Consultant',
                ]
            ],
            [
                'name' => 'Michael Johnson',
                'email' => 'michael@example.com',
                'profile' => [
                    'first_name' => 'Michael',
                    'last_name' => 'Johnson',
                    'phone_number' => '+1654321987',
                    'date_of_birth' => '1985-07-30',
                    'address_line_1' => '321 Pine Street',
                    'city' => 'Chicago',
                    'state' => 'IL',
                    'postal_code' => '60601',
                    'country' => 'USA',
                    'nationality' => 'American',
                    'employment_status' => 'Employed',
                    'occupation' => 'Financial Analyst',
                ]
            ],
            [
                'name' => 'Emily Wilson',
                'email' => 'emily@example.com',
                'profile' => [
                    'first_name' => 'Emily',
                    'last_name' => 'Wilson',
                    'phone_number' => '+1543219876',
                    'date_of_birth' => '1992-03-15',
                    'address_line_1' => '654 Maple Road',
                    'city' => 'Austin',
                    'state' => 'TX',
                    'postal_code' => '73301',
                    'country' => 'USA',
                    'nationality' => 'American',
                    'employment_status' => 'Student',
                    'occupation' => 'Graduate Student',
                ]
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'user',
                'status' => 'active',
                'created_at' => now()->subMonths(rand(1, 5)),
                'updated_at' => now(),
            ]);

            $profile = $userData['profile'];
            UserProfile::create([
                'user_id' => $user->id,
                'first_name' => $profile['first_name'],
                'last_name' => $profile['last_name'],
                'phone_number' => $profile['phone_number'],
                'date_of_birth' => $profile['date_of_birth'],
                'address_line_1' => $profile['address_line_1'],
                'city' => $profile['city'],
                'state' => $profile['state'],
                'postal_code' => $profile['postal_code'],
                'country' => $profile['country'],
                'nationality' => $profile['nationality'],
                'employment_status' => $profile['employment_status'],
                'occupation' => $profile['occupation'],
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);

            UserPreference::create([
                'user_id' => $user->id,
                'language' => 'en',
                'timezone' => 'America/New_York',
                'notification_email' => true,
                'notification_sms' => rand(0, 1) ? true : false,
                'notification_push' => rand(0, 1) ? true : false,
                'two_factor_enabled' => rand(0, 1) ? true : false,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }
    }
}
