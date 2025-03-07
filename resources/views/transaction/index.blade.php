@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
<div class="transactions-container">
    <div class="page-header">
        <h1>Transactions</h1>
        <p>View and manage your account transactions</p>

        <div class="account-selector">
            <form action="{{ route('transaction.index') }}" method="GET" id="account-form">
                <select name="account_id" id="account-select" onchange="this.form.submit()">
                    @foreach($userAccounts as $userAccount)
                        <option value="{{ $userAccount->obfuscated_id }}" {{ $account->id == $userAccount->id ? 'selected' : '' }}>
                            {{ $userAccount->account_name }} - {{ substr($userAccount->account_number, -4) }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Account Summary Card -->
    <div class="account-summary">
        <div class="account-info">
            <h2>{{ $account->account_name }}</h2>
            <p class="account-number">Account Number: **** **** **** {{ substr($account->account_number, -4) }}</p>
            <div class="account-actions">
                <a href="{{ route('account.edit', $account->obfuscated_id) }}" class="action-link">Edit</a>
                <a href="{{ route('account.statistics', $account->obfuscated_id) }}" class="action-link">Statistics</a>
            </div>
        </div>
        <div class="account-tag">
            <span class="tag">{{ $account->account_type_name }}</span>
        </div>
        <div class="balance-container">
            <div class="balance-item">
                <span class="balance-label">Current Balance</span>
                <h3 class="balance-amount">{{ $account->currency }} {{ number_format($account->balance, 2) }}</h3>
            </div>
            <div class="balance-item">
                <span class="balance-label">Available Balance</span>
                <h3 class="balance-amount">{{ $account->currency }} {{ number_format($account->available_balance, 2) }}</h3>
            </div>
        </div>
        <div class="account-details">
            <div class="detail-item">
                <span class="detail-label">Currency</span>
                <span class="detail-value">{{ $account->currency }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Status</span>
                <span class="detail-value status-active">{{ $account->status }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Opened On</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($account->created_at)->format('M d, Y') }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Last Activity</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($lastActivityDate)->format('M d, Y g:i A') }}</span>
            </div>
        </div>
        <div class="account-actions-buttons">
            <a href="{{ route('transfers') }}" class="btn-action">
                <i class="fas fa-exchange-alt"></i> Transfer Money
            </a>
            <a href="#" class="btn-action secondary">
                <i class="fas fa-file-download"></i> Download Statement
            </a>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="transactions-list-container">
        <div class="transactions-header">
            <h2>Transaction History</h2>
            <div class="transactions-actions">
                <select class="transaction-filter">
                    <option>All Transactions</option>
                    <option>Deposits</option>
                    <option>Withdrawals</option>
                    <option>Transfers</option>
                    <option>Payments</option>
                </select>
                <a href="{{ route('transaction.create') }}" class="btn-new-transaction">
                    <i class="fas fa-plus"></i> New Transaction
                </a>
            </div>
        </div>

            @if(count($transactions) > 0)
        <div class="transactions-list">
                        @foreach($transactions as $transaction)
            <div class="transaction-item">
                <div class="transaction-icon {{ strtolower($transaction->transaction_type) }}">
                    @if(strtolower($transaction->transaction_type) == 'deposit')
                        <i class="fas fa-arrow-down"></i>
                    @elseif(strtolower($transaction->transaction_type) == 'withdrawal')
                        <i class="fas fa-arrow-up"></i>
                    @elseif(strtolower($transaction->transaction_type) == 'transfer')
                        <i class="fas fa-exchange-alt"></i>
                    @else
                        <i class="fas fa-credit-card"></i>
                    @endif
                </div>
                <div class="transaction-details">
                    <h3>
                        <a href="{{ route('transaction.show', $transaction->obfuscated_id) }}" class="transaction-link">
                            {{ $transaction->description }}
                        </a>
                    </h3>
                    <p>
                        @if($transaction->contra_account_name)
                            {{ $transaction->contra_account_name }}
                        @else
                            {{ $transaction->account_name }} - {{ substr($transaction->account_number, -4) }}
                        @endif
                    </p>
                    <span class="transaction-date">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}</span>
                </div>
                <div class="transaction-amount {{ $transaction->entry_type === 'credit' ? 'credit' : 'debit' }}">
                    <h3>{{ $transaction->entry_type === 'credit' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}</h3>
                    <span class="transaction-status {{ $transaction->status }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                </div>
            </div>
            @endforeach
        </div>
        <div class="pagination-container">
                {{ $transactions->links() }}
            </div>
            @else
        <div class="no-transactions">
            <div class="no-data-icon">
                <i class="fas fa-receipt"></i>
                </div>
            <h3>No transactions found</h3>
            <p>You haven't made any transactions yet.</p>
            <a href="{{ route('transaction.create') }}" class="btn-new-transaction">
                <i class="fas fa-plus"></i> New Transaction
                </a>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('styles')
<style>
    .transactions-container {
        width: 100%;
        max-width: 100%;
        padding-bottom: 30px;
    }

    .page-header {
        margin-bottom: 25px;
    }

    .page-header h1 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .page-header p {
        color: #888;
        margin: 0;
    }

    /* Account Summary Styles */
    .account-summary {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 25px;
        margin-bottom: 30px;
        position: relative;
    }

    .account-info {
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }

    .account-info h2 {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .account-number {
        color: #666;
        font-size: 14px;
        margin: 0;
    }

    .account-actions {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }

    .action-link {
        color: #0066ff;
        font-size: 14px;
        text-decoration: none;
    }

    .action-link.danger {
        color: #ff3d00;
    }

    .action-link:hover {
        text-decoration: underline;
    }

    .account-tag {
        position: absolute;
        top: 25px;
        right: 25px;
    }

    .tag {
        background-color: #e8f5e9;
        color: #00c853;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }

    .balance-container {
        display: flex;
        gap: 30px;
        margin-bottom: 25px;
    }

    .balance-item {
        flex: 1;
    }

    .balance-label {
        display: block;
        color: #666;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .balance-amount {
        font-size: 28px;
        font-weight: 600;
        margin: 0;
    }

    .account-details {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 25px;
        padding-bottom: 25px;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        color: #666;
        font-size: 13px;
        margin-bottom: 5px;
    }

    .detail-value {
        font-size: 15px;
        font-weight: 500;
    }

    .status-active {
        color: #00c853;
    }

    .account-actions-buttons {
        display: flex;
        gap: 15px;
    }

    .btn-action {
        background-color: #0066ff;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-action:hover {
        background-color: #0052cc;
        color: white;
        text-decoration: none;
    }

    .btn-action.secondary {
        background-color: #f5f5f5;
        color: #333;
    }

    .btn-action.secondary:hover {
        background-color: #e0e0e0;
        color: #333;
    }

    /* Transactions List Styles */
    .transactions-list-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 25px;
    }

    .transactions-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .transactions-header h2 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .transactions-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .transaction-filter {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        color: #555;
    }

    .btn-new-transaction {
        background-color: #0066ff;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 15px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-new-transaction:hover {
        background-color: #0052cc;
        color: white;
        text-decoration: none;
    }

    .view-all-link {
        color: #0066ff;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
    }

    .view-all-link:hover {
        text-decoration: underline;
    }

    .transactions-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .transaction-item {
        display: flex;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .transaction-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .transaction-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: white;
    }

    .transaction-icon.deposit {
        background-color: #00c853;
    }

    .transaction-icon.withdrawal {
        background-color: #ff3d00;
    }

    .transaction-icon.transfer {
        background-color: #0066ff;
    }

    .transaction-icon.payment {
        background-color: #9c27b0;
    }

    .transaction-details {
        flex: 1;
    }

    .transaction-details h3 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .transaction-link {
        color: inherit;
        text-decoration: none;
    }

    .transaction-link:hover {
        color: #0066ff;
        text-decoration: underline;
    }

    .transaction-details p {
        font-size: 12px;
        color: #666;
        margin: 0 0 3px 0;
    }

    .transaction-date {
        font-size: 12px;
        color: #888;
    }

    .transaction-amount {
        text-align: right;
    }

    .transaction-amount h3 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .transaction-amount.credit h3 {
        color: #00c853;
    }

    .transaction-amount.debit h3 {
        color: #ff3d00;
    }

    .transaction-status {
        font-size: 12px;
        font-weight: 500;
        padding: 2px 8px;
        border-radius: 12px;
        display: inline-block;
    }

    .transaction-status.completed {
        background-color: #e8f5e9;
        color: #00c853;
    }

    .transaction-status.pending {
        background-color: #fff8e1;
        color: #ff9800;
    }

    .transaction-status.failed {
        background-color: #ffebee;
        color: #ff3d00;
    }

    .pagination-container {
        margin-top: 25px;
        display: flex;
        justify-content: center;
    }

    .no-transactions {
        text-align: center;
        padding: 50px 0;
    }

    .no-data-icon {
        font-size: 48px;
        color: #ddd;
        margin-bottom: 15px;
    }

    .no-transactions h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .no-transactions p {
        color: #888;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .balance-container {
            flex-direction: column;
            gap: 15px;
        }

        .account-details {
            grid-template-columns: repeat(2, 1fr);
        }

        .account-actions-buttons {
            flex-direction: column;
        }

        .transactions-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .transactions-actions {
            width: 100%;
            flex-wrap: wrap;
        }
    }

    .account-selector {
        margin-top: 15px;
    }

    .account-selector select {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        width: 250px;
        color: #333;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add any JavaScript functionality here
    });
</script>
@endsection
