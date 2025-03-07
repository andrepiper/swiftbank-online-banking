<?php

namespace App\Http\Controllers\Web\account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\ObfuscatesIds;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    use ObfuscatesIds;

    public function index()
    {
        // Get user accounts with account type information
        $accounts = DB::table('accounts')
            ->join('account_types', 'accounts.account_type_id', '=', 'account_types.id')
            ->select(
                'accounts.*',
                'account_types.name as account_type_name'
            )
            ->where('accounts.user_id', Auth::id())
            ->where('accounts.status', 'ACTIVE')
            ->orderBy('accounts.created_at', 'desc')
            ->get();

        // Get recent transactions
        $recentTransactions = DB::table('transactions')
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->leftJoin('accounts as contra_accounts', 'transactions.contra_account_id', '=', 'contra_accounts.id')
            ->select(
                'transactions.*',
                'accounts.account_name',
                'accounts.account_number',
                'contra_accounts.account_name as contra_account_name'
            )
            ->where('transactions.user_id', Auth::id())
            ->where('transactions.status', 'completed')
            ->orderBy('transactions.created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($transaction) {
                $transaction->icon = $this->getTransactionIcon($transaction->transaction_type);
                $transaction->display_amount = $transaction->entry_type === 'debit' ?
                    -$transaction->amount :
                    $transaction->amount;
                $transaction->total_amount = $transaction->amount + ($transaction->fee ?? 0);
                return $transaction;
            });

        // Calculate total balance across all accounts
        $totalBalance = DB::table('accounts')
            ->where('user_id', Auth::id())
            ->where('status', 'ACTIVE')
            ->sum('balance');

        return view('account.index', compact('accounts', 'recentTransactions', 'totalBalance'));
    }

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

    public function create()
    {
        $accountTypes = DB::table('account_types')->get();
        return view('account.create', compact('accountTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_type_id' => 'required|exists:account_types,id',
            'initial_balance' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3'
        ]);

        $accountNumber = $this->generateAccountNumber();

        $accountId = DB::table('accounts')->insertGetId([
            'user_id' => Auth::id(),
            'account_name' => $validated['account_name'],
            'account_type_id' => $validated['account_type_id'],
            'balance' => $validated['initial_balance'],
            'available_balance' => $validated['initial_balance'],
            'currency' => $validated['currency'],
            'account_number' => $accountNumber,
            'status' => 'ACTIVE',
            'opened_at' => now(),
            'last_activity_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if ($validated['initial_balance'] > 0) {
            // Generate a UUID for transaction_group_id
            $transactionGroupId = (string) Str::uuid();

            DB::table('transactions')->insert([
                'user_id' => Auth::id(),
                'account_id' => $accountId,
                'transaction_type' => 'DEPOSIT',
                'entry_type' => 'credit',
                'amount' => $validated['initial_balance'],
                'currency' => $validated['currency'],
                'description' => 'Initial deposit',
                'status' => 'completed',
                'source' => 'Opening Balance',
                'balance_after' => $validated['initial_balance'],
                'transaction_group_id' => $transactionGroupId,
                'created_at' => now(),
                'updated_at' => now(),
                'settled_at' => now()
            ]);
        }

        return redirect()->route('account.index')
            ->with('success', 'Account created successfully');
    }

    public function show($id)
    {
        // Deobfuscate the ID
        $realId = $this->deobfuscateId($id);

        if (!$realId) {
            abort(404);
        }

        $account = DB::table('accounts')
            ->join('account_types', 'accounts.account_type_id', '=', 'account_types.id')
            ->select('accounts.*', 'account_types.name as account_type_name')
            ->where('accounts.id', $realId)
            ->where('accounts.user_id', Auth::id())
            ->first();

        if (!$account) {
            abort(404);
        }

        // Store the obfuscated ID for use in views
        $account->obfuscated_id = $id;

        $transactions = DB::table('transactions')
            ->where('account_id', $realId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('account.show', compact('account', 'transactions'));
    }

    public function edit($id)
    {
        // Deobfuscate the ID
        $realId = $this->deobfuscateId($id);

        if (!$realId) {
            abort(404);
        }

        $account = DB::table('accounts')
            ->where('id', $realId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$account) {
            abort(404);
        }

        // Store the obfuscated ID for use in views
        $account->obfuscated_id = $id;

        $accountTypes = DB::table('account_types')->get();

        return view('account.edit', compact('account', 'accountTypes'));
    }

    public function update(Request $request, $id)
    {
        // Deobfuscate the ID
        $realId = $this->deobfuscateId($id);

        if (!$realId) {
            abort(404);
        }

        $account = DB::table('accounts')
            ->where('id', $realId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$account) {
            abort(404);
        }

        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_type_id' => 'required|exists:account_types,id'
        ]);

        DB::table('accounts')
            ->where('id', $realId)
            ->update([
                'account_name' => $validated['account_name'],
                'account_type_id' => $validated['account_type_id'],
                'updated_at' => now()
            ]);

        // Use obfuscated ID in the redirect
        return redirect()->route('account.show', $id)
            ->with('success', 'Account updated successfully');
    }

    public function destroy($id)
    {
        // Deobfuscate the ID
        $realId = $this->deobfuscateId($id);

        if (!$realId) {
            abort(404);
        }

        $account = DB::table('accounts')
            ->where('id', $realId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$account) {
            abort(404);
        }

        if ($account->balance > 0) {
            return back()->with('error', 'Cannot delete account with positive balance');
        }

        DB::table('accounts')
            ->where('id', $realId)
            ->update([
                'status' => 'CLOSED',
                'closed_at' => now(),
                'updated_at' => now()
            ]);

        return redirect()->route('account.index')
            ->with('success', 'Account closed successfully');
    }

    private function generateAccountNumber()
    {
        do {
            $number = mt_rand(1000000000, 9999999999);
        } while (DB::table('accounts')->where('account_number', $number)->exists());

        return $number;
    }

    public function statistics($id)
    {
        // Deobfuscate the ID
        $realId = $this->deobfuscateId($id);

        if (!$realId) {
            abort(404);
        }

        $account = DB::table('accounts')
            ->join('account_types', 'accounts.account_type_id', '=', 'account_types.id')
            ->select('accounts.*', 'account_types.name as account_type_name')
            ->where('accounts.id', $realId)
            ->where('accounts.user_id', Auth::id())
            ->first();

        if (!$account) {
            abort(404);
        }

        // Store the obfuscated ID for use in views
        $account->obfuscated_id = $id;

        $monthlyTransactions = DB::table('transactions')
            ->where('account_id', $realId)
            ->selectRaw('MONTH(created_at) as month,
                         SUM(CASE WHEN entry_type = "credit" THEN amount ELSE 0 END) as credits,
                         SUM(CASE WHEN entry_type = "debit" THEN amount ELSE 0 END) as debits')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Calculate total deposits
        $totalDeposits = DB::table('transactions')
            ->where('account_id', $realId)
            ->where('transaction_type', 'DEPOSIT')
            ->sum('amount');

        // Calculate total withdrawals
        $totalWithdrawals = DB::table('transactions')
            ->where('account_id', $realId)
            ->where('transaction_type', 'WITHDRAWAL')
            ->sum('amount');

        // Calculate average transaction
        $transactionCount = DB::table('transactions')
            ->where('account_id', $realId)
            ->count();

        $averageTransaction = $transactionCount > 0
            ? DB::table('transactions')
                ->where('account_id', $realId)
                ->sum('amount') / $transactionCount
            : 0;

        return view('account.statistics', compact('account', 'monthlyTransactions', 'totalDeposits', 'totalWithdrawals', 'averageTransaction'));
    }

    public function transactions(Request $request)
    {
        // Deobfuscate the ID
        $accountId = $this->deobfuscateId($request->id);

        if (!$accountId) {
            abort(404);
        }

        $account = DB::table('accounts')
            ->join('account_types', 'accounts.account_type_id', '=', 'account_types.id')
            ->select('accounts.*', 'account_types.name as account_type_name')
            ->where('accounts.id', $accountId)
            ->where('accounts.user_id', Auth::id())
            ->first();

        if (!$account) {
            abort(404);
        }

        $account->obfuscated_id = $request->id;

        $transactions = DB::table('transactions')
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->leftJoin('accounts as contra_accounts', 'transactions.contra_account_id', '=', 'contra_accounts.id')
            ->select(
                'transactions.*',
                'accounts.account_name',
                'accounts.account_number',
                'contra_accounts.account_name as contra_account_name'
            )
            ->where('transactions.user_id', Auth::id())
            ->orderBy('transactions.created_at', 'desc')
            ->paginate(20);

        return view('account.transactions', compact('transactions', 'account'));
    }
}
