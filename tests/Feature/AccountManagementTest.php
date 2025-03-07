<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test authenticated user can view their accounts.
     *
     * @return void
     */
    public function test_user_can_view_accounts()
    {
        $user = User::factory()->create();
        $accountType = AccountType::factory()->create(['name' => 'Savings Account']);

        $account = Account::factory()->create([
            'user_id' => $user->id,
            'account_type_id' => $accountType->id,
            'account_name' => 'Test Savings',
            'balance' => 1000.00,
        ]);

        $response = $this->actingAs($user)->get('/accounts');

        $response->assertStatus(200);
        $response->assertSee('Test Savings');
        $response->assertSee('1,000.00');
    }

    /**
     * Test user can view account details.
     *
     * @return void
     */
    public function test_user_can_view_account_details()
    {
        $user = User::factory()->create();
        $accountType = AccountType::factory()->create(['name' => 'Checking Account']);

        $account = Account::factory()->create([
            'user_id' => $user->id,
            'account_type_id' => $accountType->id,
            'account_name' => 'Test Checking',
            'account_number' => '1234567890',
            'balance' => 2500.00,
        ]);

        $response = $this->actingAs($user)->get('/accounts/' . $account->id);

        $response->assertStatus(200);
        $response->assertSee('Test Checking');
        $response->assertSee('1234567890');
        $response->assertSee('2,500.00');
    }

    /**
     * Test user cannot view another user's account.
     *
     * @return void
     */
    public function test_user_cannot_view_another_users_account()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $accountType = AccountType::factory()->create();

        $account = Account::factory()->create([
            'user_id' => $user2->id,
            'account_type_id' => $accountType->id,
        ]);

        $response = $this->actingAs($user1)->get('/accounts/' . $account->id);

        $response->assertStatus(403);
    }
}
