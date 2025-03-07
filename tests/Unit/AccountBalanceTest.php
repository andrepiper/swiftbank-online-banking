<?php

namespace Tests\Unit;

use App\Models\Account;
use App\Models\Transaction;
use App\Services\AccountService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountBalanceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test account balance is updated correctly after a deposit.
     *
     * @return void
     */
    public function test_balance_increases_after_deposit()
    {
        $account = Account::factory()->create(['balance' => 1000.00]);
        $accountService = new AccountService();

        $newBalance = $accountService->processDeposit($account, 500.00);

        $this->assertEquals(1500.00, $newBalance);
        $this->assertEquals(1500.00, $account->fresh()->balance);
    }

    /**
     * Test account balance is updated correctly after a withdrawal.
     *
     * @return void
     */
    public function test_balance_decreases_after_withdrawal()
    {
        $account = Account::factory()->create(['balance' => 1000.00]);
        $accountService = new AccountService();

        $newBalance = $accountService->processWithdrawal($account, 300.00);

        $this->assertEquals(700.00, $newBalance);
        $this->assertEquals(700.00, $account->fresh()->balance);
    }

    /**
     * Test insufficient funds exception is thrown for withdrawal.
     *
     * @return void
     */
    public function test_insufficient_funds_exception()
    {
        $this->expectException(\App\Exceptions\InsufficientFundsException::class);

        $account = Account::factory()->create(['balance' => 100.00]);
        $accountService = new AccountService();

        $accountService->processWithdrawal($account, 500.00);
    }

    /**
     * Test transfer between accounts updates both balances correctly.
     *
     * @return void
     */
    public function test_transfer_between_accounts()
    {
        $sourceAccount = Account::factory()->create(['balance' => 1000.00]);
        $destinationAccount = Account::factory()->create(['balance' => 500.00]);
        $accountService = new AccountService();

        $result = $accountService->processTransfer($sourceAccount, $destinationAccount, 300.00);

        $this->assertTrue($result);
        $this->assertEquals(700.00, $sourceAccount->fresh()->balance);
        $this->assertEquals(800.00, $destinationAccount->fresh()->balance);
    }
}
