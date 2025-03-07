<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\TransactionGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can view their transactions.
     *
     * @return void
     */
    public function test_user_can_view_transactions()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);
        $category = TransactionCategory::factory()->create(['type' => 'credit']);
        $group = TransactionGroup::factory()->create(['type' => 'deposit']);

        $transaction = Transaction::factory()->create([
            'account_id' => $account->id,
            'transaction_category_id' => $category->id,
            'transaction_group_id' => $group->id,
            'transaction_type' => 'deposit',
            'entry_type' => 'credit',
            'amount' => 500.00,
            'description' => 'Test Deposit',
        ]);

        $response = $this->actingAs($user)->get('/transactions');

        $response->assertStatus(200);
        $response->assertSee('Test Deposit');
        $response->assertSee('500.00');
    }

    /**
     * Test user can view transaction details.
     *
     * @return void
     */
    public function test_user_can_view_transaction_details()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);
        $category = TransactionCategory::factory()->create(['type' => 'debit']);
        $group = TransactionGroup::factory()->create(['type' => 'withdrawal']);

        $transaction = Transaction::factory()->create([
            'account_id' => $account->id,
            'transaction_category_id' => $category->id,
            'transaction_group_id' => $group->id,
            'transaction_type' => 'withdrawal',
            'entry_type' => 'debit',
            'amount' => 200.00,
            'description' => 'ATM Withdrawal',
            'reference' => 'REF123456',
        ]);

        $response = $this->actingAs($user)->get('/transactions/' . $transaction->id);

        $response->assertStatus(200);
        $response->assertSee('ATM Withdrawal');
        $response->assertSee('200.00');
        $response->assertSee('REF123456');
    }

    /**
     * Test user can create a new transaction.
     *
     * @return void
     */
    public function test_user_can_create_transaction()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'balance' => 1000.00,
        ]);
        $category = TransactionCategory::factory()->create(['type' => 'debit']);
        $group = TransactionGroup::factory()->create(['type' => 'payment']);

        $response = $this->actingAs($user)->post('/transactions', [
            'account_id' => $account->id,
            'transaction_category_id' => $category->id,
            'transaction_type' => 'payment',
            'amount' => 150.00,
            'description' => 'Online Purchase',
        ]);

        $response->assertRedirect('/transactions');
        $this->assertDatabaseHas('transactions', [
            'account_id' => $account->id,
            'transaction_category_id' => $category->id,
            'transaction_type' => 'payment',
            'amount' => 150.00,
            'description' => 'Online Purchase',
        ]);

        // Check that account balance was updated
        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'balance' => 850.00,
        ]);
    }
}
