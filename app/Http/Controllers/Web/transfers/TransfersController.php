<?php

namespace App\Http\Controllers\Web\transfers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransfersController extends Controller
{
    /**
     * Display the transfers page with user accounts
     */
    public function index()
    {
        $userId = Auth::id();

        // Get authenticated user's accounts using DB facade
        $accounts = DB::table('accounts')
            ->join('account_types', 'accounts.account_type_id', '=', 'account_types.id')
            ->where('accounts.user_id', $userId)
            ->select('accounts.*', 'account_types.name as account_type_name')
            ->get();

        // Get recent transfers using DB facade with joins
        $recentTransfers = DB::table('transfers')
            ->join('accounts as source_accounts', 'transfers.source_account_id', '=', 'source_accounts.id')
            ->leftJoin('accounts as dest_accounts', 'transfers.destination_account_id', '=', 'dest_accounts.id')
            ->leftJoin('account_types as source_types', 'source_accounts.account_type_id', '=', 'source_types.id')
            ->leftJoin('account_types as dest_types', function($join) {
                $join->on('dest_accounts.account_type_id', '=', 'dest_types.id')
                    ->whereNotNull('dest_accounts.id');
            })
            ->join('transactions', 'transfers.transaction_id', '=', 'transactions.id')
            ->where('source_accounts.user_id', $userId)
            ->select(
                'transfers.*',
                'source_accounts.account_number as from_account_number',
                'source_types.name as from_account_type_name',
                'dest_accounts.account_number as to_account_number',
                'dest_types.name as to_account_type_name',
                'transactions.amount as transaction_amount',
                'transactions.currency as transaction_currency'
            )
            ->orderBy('transfers.created_at', 'desc')
            ->limit(4);
        // Log the query
        \Log::info($recentTransfers->toSql());
        // Get the recent transfers
        $recentTransfers = $recentTransfers->get();

        return view('transfers.index', [
            'accounts' => $accounts,
            'recentTransfers' => $recentTransfers
        ]);
    }

    /**
     * Process internal transfer between user's accounts
     */
    public function internalTransfer(Request $request)
    {
        $userId = Auth::id();

        // Validate the request
        $validated = $request->validate([
            'from_account' => 'required|exists:accounts,id',
            'to_account' => 'required|exists:accounts,id|different:from_account',
            'amount' => 'required|numeric|min:0.01',
            'transfer_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        // Get the accounts using DB facade
        $fromAccount = DB::table('accounts')->where('id', $validated['from_account'])->first();
        $toAccount = DB::table('accounts')->where('id', $validated['to_account'])->first();

        if (!$fromAccount || !$toAccount) {
            return response()->json(['success' => false, 'message' => 'One or both accounts not found.'], 404);
        }

        // Check if user owns both accounts
        if ($fromAccount->user_id != $userId || $toAccount->user_id != $userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to accounts.'], 403);
        }

        // Check if from account has sufficient balance
        if ($fromAccount->balance < $validated['amount']) {
            return response()->json(['success' => false, 'message' => 'Insufficient funds.'], 400);
        }

        try {
            DB::beginTransaction();

            // Update account balances
            DB::table('accounts')
                ->where('id', $fromAccount->id)
                ->decrement('balance', $validated['amount']);

            DB::table('accounts')
                ->where('id', $toAccount->id)
                ->increment('balance', $validated['amount']);

            // Generate a transaction group ID for linking related transactions
            $transactionGroupId = (string) Str::uuid();

            // Create debit transaction record for source account
            $debitTransactionId = DB::table('transactions')->insertGetId([
                'user_id' => $userId,
                'transaction_type' => 'transfer',
                'entry_type' => 'debit',
                'amount' => $validated['amount'],
                'currency' => $fromAccount->currency ?? 'USD',
                'account_id' => $fromAccount->id,
                'contra_account_id' => $toAccount->id,
                'transaction_group_id' => $transactionGroupId,
                'reference_id' => 'INT-' . time() . '-' . rand(1000, 9999),
                'description' => $validated['description'] ?? 'Internal Transfer to ' . $toAccount->account_number,
                'status' => 'completed',
                'category' => 'transfer',
                'source' => 'account',
                'destination' => 'account',
                'fee' => 0.00,
                'balance_after' => $fromAccount->balance - $validated['amount'],
                'created_at' => now(),
                'updated_at' => now(),
                'settled_at' => now(),
            ]);

            // Create credit transaction record for destination account
            $creditTransactionId = DB::table('transactions')->insertGetId([
                'user_id' => $userId,
                'transaction_type' => 'transfer',
                'entry_type' => 'credit',
                'amount' => $validated['amount'],
                'currency' => $toAccount->currency ?? 'USD',
                'account_id' => $toAccount->id,
                'contra_account_id' => $fromAccount->id,
                'transaction_group_id' => $transactionGroupId,
                'reference_id' => 'INT-' . time() . '-' . rand(1000, 9999),
                'description' => 'Internal Transfer from ' . $fromAccount->account_number,
                'status' => 'completed',
                'category' => 'transfer',
                'source' => 'account',
                'destination' => 'account',
                'fee' => 0.00,
                'balance_after' => $toAccount->balance + $validated['amount'],
                'created_at' => now(),
                'updated_at' => now(),
                'settled_at' => now(),
            ]);

            // Create transfer record with the transaction ID (if you still need the transfers table)
            $transferId = DB::table('transfers')->insertGetId([
                'transaction_id' => $debitTransactionId, // Link to the debit transaction
                'source_account_id' => $fromAccount->id,
                'destination_account_id' => $toAccount->id,
                'destination_account_name' => $toAccount->account_name ?? 'Own Account',
                'destination_account_number' => $toAccount->account_number,
                'transfer_type' => 'INTERNAL',
                'reference_note' => $validated['description'] ?? 'Internal Transfer',
                'fee_amount' => 0.00, // No fee for internal transfers
                'scheduled_date' => $validated['transfer_date'],
                'completed_date' => now(),
                'recurring' => 0, // Not recurring by default
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            // Get the created transfer for response
            $transfer = DB::table('transfers')->where('id', $transferId)->first();

            return response()->json([
                'success' => true,
                'message' => 'Internal transfer completed successfully.',
                'transfer' => $transfer
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Transfer failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Process domestic transfer to another bank
     */
    public function domesticTransfer(Request $request)
    {
        $userId = Auth::id();

        // Validate the request
        $validated = $request->validate([
            'from_account' => 'required|exists:accounts,id',
            'recipient_name' => 'required|string|max:255',
            'recipient_bank' => 'required|string',
            'account_number' => 'required|string',
            'routing_number' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'transfer_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        // Get the from account using DB facade
        $fromAccount = DB::table('accounts')->where('id', $validated['from_account'])->first();

        if (!$fromAccount) {
            return response()->json(['success' => false, 'message' => 'Account not found.'], 404);
        }

        // Check if user owns the account
        if ($fromAccount->user_id != $userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to account.'], 403);
        }

        // Check if account has sufficient balance
        if ($fromAccount->balance < $validated['amount']) {
            return response()->json(['success' => false, 'message' => 'Insufficient funds.'], 400);
        }

        try {
            DB::beginTransaction();

            // Update account balance
            DB::table('accounts')
                ->where('id', $fromAccount->id)
                ->decrement('balance', $validated['amount']);

            // Generate reference ID and trace number
            $referenceId = 'DOM-' . time() . '-' . rand(1000, 9999);
            $traceNumber = 'TRC' . date('Ymd') . rand(100000, 999999);

            // Create transaction record
            $transactionId = DB::table('transactions')->insertGetId([
                'user_id' => $userId,
                'transaction_type' => 'domestic_transfer',
                'entry_type' => 'debit',
                'amount' => $validated['amount'],
                'currency' => $fromAccount->currency ?? 'USD',
                'account_id' => $fromAccount->id,
                'external_account_id' => $validated['account_number'],
                'reference_id' => $referenceId,
                'trace_number' => $traceNumber,
                'description' => $validated['description'] ?? 'Domestic transfer to ' . $validated['recipient_name'],
                'status' => 'pending', // Domestic transfers may take time to process
                'category' => 'transfer',
                'source' => 'account',
                'destination' => 'external',
                'merchant_name' => $validated['recipient_name'],
                'fee' => 0.00, // Could add a fee if needed
                'balance_after' => $fromAccount->balance - $validated['amount'],
                'metadata' => json_encode([
                    'recipient_bank' => $validated['recipient_bank'],
                    'routing_number' => $validated['routing_number'],
                    'transfer_date' => $validated['transfer_date']
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'settled_at' => null, // Will be updated when the transfer is settled
            ]);

            // Create transfer record
            $transferId = DB::table('transfers')->insertGetId([
                'transaction_id' => $transactionId,
                'source_account_id' => $fromAccount->id,
                'destination_account_number' => $validated['account_number'],
                'destination_account_name' => $validated['recipient_name'],
                'destination_bank_name' => $validated['recipient_bank'],
                'routing_number' => $validated['routing_number'],
                'transfer_type' => 'DOMESTIC',
                'reference_note' => $validated['description'] ?? 'Domestic Transfer',
                'fee_amount' => 0.00, // Could add a fee if needed
                'scheduled_date' => $validated['transfer_date'],
                'completed_date' => null, // Will be updated when the transfer is completed
                'recurring' => 0, // Not recurring by default
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            // Get the created transfer for response
            $transfer = DB::table('transfers')->where('id', $transferId)->first();

            return response()->json([
                'success' => true,
                'message' => 'Domestic transfer initiated successfully.',
                'transfer' => $transfer
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Transfer failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Process international wire transfer
     */
    public function internationalTransfer(Request $request)
    {
        $userId = Auth::id();

        // Validate the request
        $validated = $request->validate([
            'from_account' => 'required|exists:accounts,id',
            'recipient_name' => 'required|string|max:255',
            'recipient_address' => 'required|string',
            'recipient_country' => 'required|string',
            'recipient_bank_name' => 'required|string',
            'swift_code' => 'required|string',
            'iban' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string',
            'transfer_purpose' => 'required|string',
            'transfer_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        // Get the from account using DB facade
        $fromAccount = DB::table('accounts')->where('id', $validated['from_account'])->first();

        if (!$fromAccount) {
            return response()->json(['success' => false, 'message' => 'Account not found.'], 404);
        }

        // Check if user owns the account
        if ($fromAccount->user_id != $userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to account.'], 403);
        }

        // Calculate fee (example: $25 for international transfers)
        $fee = 25.00;
        $totalAmount = $validated['amount'] + $fee;

        // Check if account has sufficient balance including fee
        if ($fromAccount->balance < $totalAmount) {
            return response()->json(['success' => false, 'message' => 'Insufficient funds including transfer fee.'], 400);
        }

        try {
            DB::beginTransaction();

            // Update account balance
            DB::table('accounts')
                ->where('id', $fromAccount->id)
                ->decrement('balance', $totalAmount);

            // Determine exchange rate if needed
            $exchangeRate = 1.0; // Default to 1.0 if same currency
            if ($fromAccount->currency != $validated['currency']) {
                // In a real application, you would fetch the current exchange rate from a service
                $exchangeRate = $this->getExchangeRate($fromAccount->currency, $validated['currency']);
            }

            // Generate reference ID and trace number
            $referenceId = 'INT-' . time() . '-' . rand(1000, 9999);
            $traceNumber = 'TRC' . date('Ymd') . rand(100000, 999999);

            // Create transaction record
            $transactionId = DB::table('transactions')->insertGetId([
                'user_id' => $userId,
                'transaction_type' => 'international_transfer',
                'entry_type' => 'debit',
                'amount' => $totalAmount, // Including fee
                'currency' => $fromAccount->currency ?? 'USD',
                'account_id' => $fromAccount->id,
                'external_account_id' => $validated['iban'],
                'reference_id' => $referenceId,
                'trace_number' => $traceNumber,
                'description' => $validated['description'] ?? 'International wire to ' . $validated['recipient_name'] . ' (includes $' . $fee . ' fee)',
                'status' => 'pending', // International transfers take time to process
                'category' => 'transfer',
                'source' => 'account',
                'destination' => 'international',
                'merchant_name' => $validated['recipient_name'],
                'fee' => $fee,
                'balance_after' => $fromAccount->balance - $totalAmount,
                'metadata' => json_encode([
                    'recipient_address' => $validated['recipient_address'],
                    'recipient_country' => $validated['recipient_country'],
                    'recipient_bank' => $validated['recipient_bank_name'],
                    'swift_code' => $validated['swift_code'],
                    'transfer_purpose' => $validated['transfer_purpose'],
                    'exchange_rate' => $exchangeRate,
                    'destination_currency' => $validated['currency'],
                    'transfer_date' => $validated['transfer_date']
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'settled_at' => null, // Will be updated when the transfer is settled
            ]);

            // Create transfer record
            $transferId = DB::table('transfers')->insertGetId([
                'transaction_id' => $transactionId,
                'source_account_id' => $fromAccount->id,
                'destination_account_number' => $validated['iban'],
                'destination_account_name' => $validated['recipient_name'],
                'destination_bank_name' => $validated['recipient_bank_name'],
                'destination_bank_code' => $validated['swift_code'],
                'swift_code' => $validated['swift_code'],
                'iban' => $validated['iban'],
                'transfer_type' => 'INTERNATIONAL',
                'reference_note' => $validated['description'] ?? 'International Wire Transfer - ' . $validated['transfer_purpose'],
                'fee_amount' => $fee,
                'exchange_rate' => $exchangeRate,
                'scheduled_date' => $validated['transfer_date'],
                'completed_date' => null, // Will be updated when the transfer is completed
                'recurring' => 0, // Not recurring by default
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            // Get the created transfer for response
            $transfer = DB::table('transfers')->where('id', $transferId)->first();

            return response()->json([
                'success' => true,
                'message' => 'International wire transfer initiated successfully.',
                'transfer' => $transfer
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Transfer failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get exchange rate between two currencies
     * In a real application, this would call an external API
     */
    private function getExchangeRate($fromCurrency, $toCurrency)
    {
        // Sample exchange rates (in a real app, these would come from an API)
        $rates = [
            'USD' => [
                'EUR' => 0.85,
                'GBP' => 0.75,
                'CAD' => 1.25,
                'AUD' => 1.35,
                'JPY' => 110.0
            ],
            'EUR' => [
                'USD' => 1.18,
                'GBP' => 0.88,
                'CAD' => 1.47,
                'AUD' => 1.59,
                'JPY' => 129.5
            ],
            // Add more currencies as needed
        ];

        // Normalize currency codes
        $fromCurrency = strtoupper($fromCurrency);
        $toCurrency = strtoupper($toCurrency);

        // If same currency, rate is 1
        if ($fromCurrency === $toCurrency) {
            return 1.0;
        }

        // Check if we have the rate
        if (isset($rates[$fromCurrency][$toCurrency])) {
            return $rates[$fromCurrency][$toCurrency];
        }

        // If we have the inverse rate, use its reciprocal
        if (isset($rates[$toCurrency][$fromCurrency])) {
            return 1 / $rates[$toCurrency][$fromCurrency];
        }

        // Default fallback rate
        return 1.0;
    }
}
