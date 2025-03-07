@extends('layouts.app')

@section('title', 'Transaction Details')

@section('styles')
<style>
    .account-header {
        margin-bottom: 1.5rem;
    }
    .account-title {
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: #333;
    }
    .account-number {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    .action-links {
        margin-bottom: 1.5rem;
    }
    .action-links a {
        margin-right: 1.25rem;
        font-size: 0.9rem;
        text-decoration: none;
    }
    .action-links a:hover {
        text-decoration: underline;
    }
    .action-links a.danger {
        color: #dc3545;
    }
    .account-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 50rem;
        background-color: #e8f5e9;
        color: #2e7d32;
        text-transform: uppercase;
    }
    .balance-card {
        background-color: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: none;
    }
    .balance-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    .balance-amount {
        font-size: 1.75rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0;
    }
    .info-card {
        background-color: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border: none;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        color: #6c757d;
        font-size: 0.9rem;
    }
    .info-value {
        font-weight: 500;
        color: #333;
        font-size: 0.9rem;
    }
    .action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.25rem;
        font-size: 0.9rem;
        font-weight: 500;
        border-radius: 0.375rem;
        text-decoration: none;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .action-button.primary {
        background-color: #0d6efd;
        color: #fff;
    }
    .action-button.secondary {
        background-color: #f8f9fa;
        color: #333;
        border: 1px solid #dee2e6;
    }
    .action-button i {
        margin-right: 0.5rem;
    }
    .transaction-history {
        margin-top: 2rem;
    }
    .transaction-history-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .transaction-history-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0;
    }
    .transaction-item {
        display: flex;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f0f0f0;
        background-color: #fff;
    }
    .transaction-item:first-child {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    .transaction-item:last-child {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
        border-bottom: none;
    }
    .transaction-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e8f5e9;
        color: #2e7d32;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }
    .transaction-icon.deposit {
        background-color: #e8f5e9;
        color: #2e7d32;
    }
    .transaction-icon.withdrawal {
        background-color: #ffebee;
        color: #c62828;
    }
    .transaction-details {
        flex: 1;
    }
    .transaction-title {
        font-weight: 500;
        color: #333;
        margin-bottom: 0.25rem;
    }
    .transaction-date {
        font-size: 0.85rem;
        color: #6c757d;
    }
    .transaction-amount {
        font-weight: 600;
        text-align: right;
    }
    .transaction-amount.positive {
        color: #2e7d32;
    }
    .transaction-amount.negative {
        color: #c62828;
    }
    .transaction-balance {
        font-size: 0.85rem;
        color: #6c757d;
        text-align: right;
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Account Header -->
    <div class="account-header">
        <h1 class="account-title">{{ $transaction->transaction_type }}</h1>
        <div class="account-number">
            Transaction ID: {{ $transaction->obfuscated_id }} • {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y H:i') }}
        </div>

        <div class="action-links">
            <a href="{{ route('transaction.index') }}" class="text-primary">
                <i class="fas fa-arrow-back"></i> Back to Transactions
            </a>
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

    <div class="row">
        <div class="col-lg-8">
            <!-- Transaction Amount -->
            <div class="balance-card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="balance-label">Transaction Amount</div>
                        <div class="balance-amount {{ $transaction->entry_type === 'credit' ? 'text-success' : 'text-danger' }}">
                            {{ $transaction->entry_type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="balance-label">Balance After Transaction</div>
                        <div class="balance-amount">{{ number_format($transaction->balance_after, 2) }} {{ $transaction->currency }}</div>
                    </div>
                </div>
            </div>

            <!-- Transaction Details -->
            <div class="info-card">
                <h5 class="mb-3">Transaction Details</h5>

                <div class="info-row">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="account-badge" style="background-color: {{ $transaction->status === 'completed' ? '#e8f5e9' : ($transaction->status === 'pending' ? '#fff8e1' : '#ffebee') }}; color: {{ $transaction->status === 'completed' ? '#2e7d32' : ($transaction->status === 'pending' ? '#f57f17' : '#c62828') }};">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Account</div>
                    <div class="info-value">{{ $transaction->account_name }} ({{ substr($transaction->account_number, -4) }})</div>
                </div>

                @if($transaction->contra_account_name)
                <div class="info-row">
                    <div class="info-label">Contra Account</div>
                    <div class="info-value">{{ $transaction->contra_account_name }} ({{ substr($transaction->contra_account_number, -4) }})</div>
                </div>
                @endif

                <div class="info-row">
                    <div class="info-label">Description</div>
                    <div class="info-value">{{ $transaction->description ?: 'No description provided' }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Reference</div>
                    <div class="info-value">{{ $transaction->reference ?? 'N/A' }}</div>
                </div>

                @if($transaction->fee)
                <div class="info-row">
                    <div class="info-label">Fee</div>
                    <div class="info-value">{{ number_format($transaction->fee, 2) }} {{ $transaction->currency }}</div>
                </div>
                @endif

                <div class="info-row">
                    <div class="info-label">Settled At</div>
                    <div class="info-value">{{ $transaction->settled_at ? \Carbon\Carbon::parse($transaction->settled_at)->format('M d, Y H:i') : 'Pending' }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Created</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y H:i') }}</div>
                </div>
            </div>

            @if($transaction->transaction_group_id && isset($transaction->group_name))
            <!-- Transaction Group -->
            <div class="info-card">
                <h5 class="mb-3">Transaction Group</h5>

                <div class="d-flex align-items-center mb-3">
                    <div class="transaction-icon">
                        <i class="fas fa-folder"></i>
                    </div>
                    <div>
                        <div class="transaction-title">{{ $transaction->group_name }}</div>
                        <div class="transaction-date">{{ $transaction->group_description ?? 'No description' }}</div>
                    </div>
                </div>

                <div class="alert alert-info d-flex align-items-center mb-0 p-2">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>Transaction groups are managed by administrators.</div>
                </div>
            </div>
            @endif

            @if(count($relatedTransactions) > 0)
            <!-- Related Transactions -->
            <div class="transaction-history">
                <div class="transaction-history-header">
                    <h5 class="transaction-history-title">Related Transactions</h5>
                    <span class="account-badge">{{ count($relatedTransactions) }}</span>
                </div>

                @foreach($relatedTransactions as $relatedTransaction)
                <div class="transaction-item">
                    <div class="transaction-icon {{ strtolower($relatedTransaction->transaction_type) }}">
                        <i class="bx {{ strtolower($relatedTransaction->transaction_type) === 'deposit' ? 'bx-arrow-down' : (strtolower($relatedTransaction->transaction_type) === 'withdrawal' ? 'bx-arrow-up' : (strtolower($relatedTransaction->transaction_type) === 'transfer' ? 'bx-transfer' : 'bx-credit-card')) }}"></i>
                    </div>
                    <div class="transaction-details">
                        <div class="transaction-title">{{ $relatedTransaction->transaction_type }}</div>
                        <div class="transaction-date">{{ \Carbon\Carbon::parse($relatedTransaction->created_at)->format('M d, Y') }} • {{ $relatedTransaction->account_name }}</div>
                    </div>
                    <div>
                        <div class="transaction-amount {{ $relatedTransaction->entry_type === 'credit' ? 'positive' : 'negative' }}">
                            {{ $relatedTransaction->entry_type === 'credit' ? '+' : '-' }}{{ number_format($relatedTransaction->amount, 2) }}
                        </div>
                        <div class="transaction-balance">
                            <a href="{{ route('transaction.show', $relatedTransaction->obfuscated_id) }}" class="text-primary">
                                View
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Account Information -->
            <div class="info-card">
                <h5 class="mb-3">Account Information</h5>

                <div class="d-flex align-items-center mb-3">
                    <div class="transaction-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <div class="transaction-title">{{ $transaction->account_name }}</div>
                        <div class="transaction-date">Account #: xxxx-xxxx-{{ substr($transaction->account_number, -4) }}</div>
                    </div>
                </div>

                <a href="{{ route('account.show', \App\Helpers\IdObfuscator::encode($transaction->account_id)) }}" class="btn btn-outline-primary w-100">
                    <i class="fas fa-show-alt me-1"></i> View Account Details
                </a>
            </div>

            <!-- Actions -->
            <div class="info-card">
                <h5 class="mb-3">Actions</h5>

                <div class="d-grid gap-2">
                    <button type="button" class="action-button primary w-100">
                        <i class="fas fa-printer"></i> Print Receipt
                    </button>
                    <button type="button" class="action-button secondary w-100">
                        <i class="fas fa-download"></i> Download PDF
                    </button>
                    <button type="button" class="action-button secondary w-100">
                        <i class="fas fa-envelope"></i> Email Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
