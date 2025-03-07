<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Beneficiary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BeneficiarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        // Common banks
        $banks = [
            'Chase Bank' => '021000021',
            'Bank of America' => '026009593',
            'Wells Fargo' => '121000248',
            'Citibank' => '021000089',
            'US Bank' => '123103729',
            'PNC Bank' => '043000096',
            'TD Bank' => '031201360',
            'Capital One' => '056073502',
            'HSBC Bank' => '022000020',
            'Truist Bank' => '061000104',
        ];

        // Each user gets 2-5 beneficiaries
        foreach ($users as $user) {
            $numBeneficiaries = rand(2, 5);

            for ($i = 0; $i < $numBeneficiaries; $i++) {
                // Select a random bank
                $bankName = array_rand($banks);
                $routingNumber = $banks[$bankName];

                // Generate a random account number
                $accountNumber = $this->generateAccountNumber();

                // Create beneficiary
                Beneficiary::create([
                    'user_id' => $user->id,
                    'name' => $this->generateBeneficiaryName(),
                    'account_number' => $accountNumber,
                    'routing_number' => $routingNumber,
                    'bank_name' => $bankName,
                    'account_type' => $this->getRandomAccountType(),
                    'email' => $this->generateEmail(),
                    'phone_number' => $this->generatePhoneNumber(),
                    'address' => $this->generateAddress(),
                    'relationship' => $this->getRandomRelationship(),
                    'status' => 'active',
                    'is_favorite' => rand(0, 10) > 8,
                    'notes' => rand(0, 10) > 7 ? $this->generateNotes() : null,
                    'created_at' => now()->subDays(rand(1, 180)),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Generate a random account number
     */
    private function generateAccountNumber(): string
    {
        return mt_rand(1000000000, 9999999999);
    }

    /**
     * Generate a random beneficiary name
     */
    private function generateBeneficiaryName(): string
    {
        $firstNames = [
            'James', 'Mary', 'Robert', 'Patricia', 'John', 'Jennifer', 'Michael', 'Linda',
            'William', 'Elizabeth', 'David', 'Susan', 'Richard', 'Jessica', 'Joseph', 'Sarah',
            'Thomas', 'Karen', 'Charles', 'Nancy', 'Christopher', 'Lisa', 'Daniel', 'Margaret',
            'Matthew', 'Betty', 'Anthony', 'Sandra', 'Mark', 'Ashley', 'Donald', 'Dorothy',
        ];

        $lastNames = [
            'Smith', 'Johnson', 'Williams', 'Jones', 'Brown', 'Davis', 'Miller', 'Wilson',
            'Moore', 'Taylor', 'Anderson', 'Thomas', 'Jackson', 'White', 'Harris', 'Martin',
            'Thompson', 'Garcia', 'Martinez', 'Robinson', 'Clark', 'Rodriguez', 'Lewis', 'Lee',
            'Walker', 'Hall', 'Allen', 'Young', 'Hernandez', 'King', 'Wright', 'Lopez',
        ];

        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    /**
     * Get a random account type
     */
    private function getRandomAccountType(): string
    {
        $types = ['checking', 'savings', 'business'];
        return $types[array_rand($types)];
    }

    /**
     * Generate a random email
     */
    private function generateEmail(): string
    {
        $domains = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'aol.com', 'icloud.com'];
        return strtolower(Str::random(8)) . '@' . $domains[array_rand($domains)];
    }

    /**
     * Generate a random phone number
     */
    private function generatePhoneNumber(): string
    {
        return '+1' . rand(200, 999) . rand(100, 999) . rand(1000, 9999);
    }

    /**
     * Generate a random address
     */
    private function generateAddress(): string
    {
        $streetNumbers = [123, 456, 789, 1024, 555, 777, 888, 999, 1111, 2222];
        $streetNames = ['Main St', 'Oak Ave', 'Maple Rd', 'Washington Blvd', 'Park Ave', 'Broadway', 'Cedar Ln', 'Lake St', 'River Rd', 'Highland Ave'];
        $cities = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'San Jose'];
        $states = ['NY', 'CA', 'IL', 'TX', 'AZ', 'PA', 'TX', 'CA', 'TX', 'CA'];
        $zipCodes = ['10001', '90001', '60601', '77001', '85001', '19101', '78201', '92101', '75201', '95101'];

        $index = array_rand($streetNumbers);

        return $streetNumbers[$index] . ' ' . $streetNames[$index] . ', ' . $cities[$index] . ', ' . $states[$index] . ' ' . $zipCodes[$index];
    }

    /**
     * Get a random relationship
     */
    private function getRandomRelationship(): string
    {
        $relationships = ['family', 'friend', 'business', 'other'];
        return $relationships[array_rand($relationships)];
    }

    /**
     * Generate random notes
     */
    private function generateNotes(): string
    {
        $notes = [
            'Monthly rent payment',
            'Business partner',
            'Shared expenses',
            'Family member',
            'Regular transfers',
            'Emergency contact',
            'Investment account',
            'Joint venture',
            'Loan repayment',
            'Subscription payment',
        ];

        return $notes[array_rand($notes)];
    }
}
