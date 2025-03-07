<?php

namespace App\Http\Controllers\Web\transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\ObfuscatesIds;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    use ObfuscatesIds;

    /**
     * Display a listing of the transactions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Get all user accounts for the dropdown
        $userAccounts = DB::table('accounts')
            ->join('account_types', 'accounts.account_type_id', '=', 'account_types.id')
            ->where('accounts.user_id', $userId)
            ->where('accounts.status', 'ACTIVE')
            ->select(
                'accounts.*',
                'account_types.name as account_type_name'
            )
            ->get();

        if ($userAccounts->isEmpty()) {
            // If no accounts found, redirect to accounts page
            return redirect()->route('accounts.index')->with('error', 'No active accounts found.');
        }

        // Add obfuscated IDs to each account
        foreach ($userAccounts as $userAccount) {
            $userAccount->obfuscated_id = $this->obfuscateId($userAccount->id);
        }

        // Get the selected account or default to primary/first account
        $accountId = $request->input('account_id');
        $realAccountId = null;

        if ($accountId) {
            // Check if the account_id is obfuscated
            $realAccountId = $this->deobfuscateId($accountId);

            // If deobfuscation failed, it might be a raw ID (for backward compatibility)
            if (!$realAccountId && is_numeric($accountId)) {
                $realAccountId = (int)$accountId;
            }

            $account = $userAccounts->where('id', $realAccountId)->first();
        }

        if (empty($account)) {
            // If no valid account selected, use primary or first account
            $account = $userAccounts->where('is_primary', 1)->first() ?? $userAccounts->first();
            $realAccountId = $account->id;
        }

        // Get transactions for this account
        $transactions = DB::table('transactions')
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->leftJoin('accounts as contra_accounts', 'transactions.contra_account_id', '=', 'contra_accounts.id')
            ->select(
                'transactions.*',
                'accounts.account_name',
                'accounts.account_number',
                'contra_accounts.account_name as contra_account_name',
                'contra_accounts.account_number as contra_account_number'
            )
            ->where('transactions.account_id', $realAccountId)
            ->orderBy('transactions.created_at', 'desc')
            ->paginate(20);

        // Add obfuscated IDs to each transaction
        foreach ($transactions as $transaction) {
            $transaction->obfuscated_id = $this->obfuscateId($transaction->id);
        }

        // Get last activity date
        $lastActivity = DB::table('transactions')
            ->where('account_id', $realAccountId)
            ->orderBy('created_at', 'desc')
            ->first();

        $lastActivityDate = $lastActivity ? $lastActivity->created_at : $account->created_at;

        return view('transaction.index', [
            'account' => $account,
            'userAccounts' => $userAccounts,
            'transactions' => $transactions,
            'lastActivityDate' => $lastActivityDate
        ]);
    }

    /**
     * Show the form for creating a new transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = DB::table('accounts')
            ->where('user_id', Auth::id())
            ->where('status', 'ACTIVE')
            ->get();

        // Add obfuscated IDs to each account
        foreach ($accounts as $account) {
            $account->obfuscated_id = $this->obfuscateId($account->id);
        }

        $transactionTypes = ['DEPOSIT', 'WITHDRAWAL', 'TRANSFER', 'PAYMENT'];

        return view('transaction.create', compact('accounts', 'transactionTypes'));
    }

    /**
     * Store a newly created transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|string',
            'transaction_type' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'contra_account_id' => 'nullable|string',
            'transaction_date' => 'nullable|date',
        ]);

        // Deobfuscate account IDs
        $accountId = $this->deobfuscateId($validated['account_id']);
        $contraAccountId = isset($validated['contra_account_id']) ? $this->deobfuscateId($validated['contra_account_id']) : null;

        if (!$accountId) {
            return back()->with('error', 'Invalid account selected')->withInput();
        }

        if (isset($validated['contra_account_id']) && !$contraAccountId) {
            return back()->with('error', 'Invalid contra account selected')->withInput();
        }

        // Get account details
        $account = DB::table('accounts')
            ->where('id', $accountId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$account) {
            return back()->with('error', 'Account not found')->withInput();
        }

        // Generate a UUID for transaction_group_id
        $transactionGroupId = (string) Str::uuid();

        // Get transaction date or use current date
        $transactionDate = isset($validated['transaction_date']) ? $validated['transaction_date'] : now();
        $transactionDateTime = is_string($transactionDate) ? \Carbon\Carbon::parse($transactionDate) : $transactionDate;

        // Create transaction group
        DB::table('transaction_groups')->insert([
            'id' => $transactionGroupId,
            'name' => $validated['transaction_type'] . ' - ' . now()->format('Y-m-d H:i'),
            'description' => $validated['description'] ?? '',
            'type' => $validated['transaction_type'],
            'status' => 'active',
            'created_at' => $transactionDateTime,
            'updated_at' => now()
        ]);

        // Determine entry type based on transaction type
        $entryType = match(strtoupper($validated['transaction_type'])) {
            'DEPOSIT' => 'credit',
            'WITHDRAWAL' => 'debit',
            'TRANSFER' => $contraAccountId ? 'debit' : 'credit',
            'PAYMENT' => 'debit',
            default => 'credit'
        };

        // Calculate new balance
        $newBalance = $entryType === 'credit'
            ? $account->balance + $validated['amount']
            : $account->balance - $validated['amount'];

        // Insert transaction
        $transactionId = DB::table('transactions')->insertGetId([
            'user_id' => Auth::id(),
            'account_id' => $accountId,
            'transaction_type' => strtoupper($validated['transaction_type']),
            'entry_type' => $entryType,
            'amount' => $validated['amount'],
            'currency' => $account->currency,
            'description' => $validated['description'] ?? '',
            'status' => 'completed',
            'balance_after' => $newBalance,
            'contra_account_id' => $contraAccountId,
            'transaction_group_id' => $transactionGroupId,
            'created_at' => $transactionDateTime,
            'updated_at' => now(),
            'settled_at' => $transactionDateTime
        ]);

        // If it's a transfer and contra account is specified, create the corresponding transaction
        if (strtoupper($validated['transaction_type']) === 'TRANSFER' && $contraAccountId) {
            $contraAccount = DB::table('accounts')
                ->where('id', $contraAccountId)
                ->where('user_id', Auth::id())
                ->first();

            if ($contraAccount) {
                $contraNewBalance = $contraAccount->balance + $validated['amount'];

                DB::table('transactions')->insert([
                    'user_id' => Auth::id(),
                    'account_id' => $contraAccountId,
                    'transaction_type' => 'TRANSFER',
                    'entry_type' => 'credit',
                    'amount' => $validated['amount'],
                    'currency' => $contraAccount->currency,
                    'description' => 'Transfer from ' . $account->account_name,
                    'status' => 'completed',
                    'balance_after' => $contraNewBalance,
                    'contra_account_id' => $accountId,
                    'transaction_group_id' => $transactionGroupId,
                    'created_at' => $transactionDateTime,
                    'updated_at' => now(),
                    'settled_at' => $transactionDateTime
                ]);

                // Update contra account balance
                DB::table('accounts')
                    ->where('id', $contraAccountId)
                    ->update([
                        'balance' => $contraNewBalance,
                        'available_balance' => $contraNewBalance,
                        'last_activity_at' => $transactionDateTime,
                        'updated_at' => now()
                    ]);
            }
        }

        // Update account balance
        DB::table('accounts')
            ->where('id', $accountId)
            ->update([
                'balance' => $newBalance,
                'available_balance' => $newBalance,
                'last_activity_at' => $transactionDateTime,
                'updated_at' => now()
            ]);

        // Redirect to the transaction details page with obfuscated ID
        $obfuscatedId = $this->obfuscateId($transactionId);
        return redirect()->route('transaction.show', $obfuscatedId)
            ->with('success', 'Transaction created successfully');
    }

    /**
     * Display the specified transaction.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $realId = $this->deobfuscateId($id);

        if (!$realId) {
            abort(404);
        }

        $transaction = DB::table('transactions')
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->leftJoin('accounts as contra_accounts', 'transactions.contra_account_id', '=', 'contra_accounts.id')
            ->leftJoin('transaction_groups', 'transactions.transaction_group_id', '=', 'transaction_groups.id')
            ->select(
                'transactions.*',
                'accounts.account_name',
                'accounts.account_number',
                'contra_accounts.account_name as contra_account_name',
                'contra_accounts.account_number as contra_account_number',
                'transaction_groups.name as group_name',
                'transaction_groups.description as group_description'
            )
            ->where('transactions.id', $realId)
            ->where('transactions.user_id', Auth::id())
            ->first();

        if (!$transaction) {
            abort(404);
        }

        // Store the obfuscated ID for use in views
        $transaction->obfuscated_id = $id;

        // Get related transactions in the same group
        $relatedTransactions = [];
        if ($transaction->transaction_group_id) {
            $relatedTransactions = DB::table('transactions')
                ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
                ->select(
                    'transactions.*',
                    'accounts.account_name',
                    'accounts.account_number'
                )
                ->where('transactions.transaction_group_id', $transaction->transaction_group_id)
                ->where('transactions.id', '!=', $realId)
                ->where('transactions.user_id', Auth::id())
                ->get();

            // Add obfuscated IDs to related transactions
            foreach ($relatedTransactions as $relatedTransaction) {
                $relatedTransaction->obfuscated_id = $this->obfuscateId($relatedTransaction->id);
            }
        }

        return view('transaction.show', compact('transaction', 'relatedTransactions'));
    }

    /**
     * Get the icon for a transaction type.
     *
     * @param  string  $transactionType
     * @return string
     */
    private function getTransactionIcon($transactionType)
    {
        return match(strtolower($transactionType)) {
            'payment' => 'fa-credit-card',
            'transfer' => 'fa-exchange-alt',
            'deposit' => 'fa-arrow-down',
            'withdrawal' => 'fa-arrow-up',
            'fee' => 'fa-receipt',
            'refund' => 'fa-undo',
            'interest' => 'fa-percentage',
            default => 'fa-circle'
        };
    }
}
