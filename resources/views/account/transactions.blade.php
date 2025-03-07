@extends('layouts.app')

@section('title', $account->account_name . ' - Transactions')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">{{ $account->account_name }} - Transactions</h1>
        <p class="page-subtitle">Account Number: **** **** **** {{ substr($account->account_number, -4) }}</p>
    </div>

    <div class="account-actions-top">
        <a href="{{ route('account.show', $account->obfuscated_id) }}" class="action-link">← Back to Account</a>
        <a href="{{ route('account.statistics', $account->obfuscated_id) }}" class="action-link">View Statistics</a>
    </div>

    <div class="account-type-badge {{ strtolower($account->account_type_name) }}">
        <i class="fas {{ $account->account_type_name == 'Checking' ? 'fa-wallet' : ($account->account_type_name == 'Savings' ? 'fa-piggy-bank' : 'fa-chart-line') }}"></i>
        {{ $account->account_type_name }} Account
    </div>

    <div class="balance-summary">
        <div class="balance-item">
            <div class="balance-label">Current Balance</div>
            <div class="balance-amount">${{ number_format($account->balance, 2, '.', ',') }}</div>
        </div>
    </div>

    <div class="transactions-section">
        <div class="section-header">
            <h2>Transaction History</h2>
            <div class="transaction-filters">
                <div class="filter-group">
                    <label for="dateRange">Date Range:</label>
                    <select id="dateRange" class="filter-select">
                        <option value="all">All Time</option>
                        <option value="30d">Last 30 Days</option>
                        <option value="90d">Last 90 Days</option>
                        <option value="1y">Last Year</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="transactionType">Type:</label>
                    <select id="transactionType" class="filter-select">
                        <option value="all">All Types</option>
                        <option value="deposit">Deposits</option>
                        <option value="withdrawal">Withdrawals</option>
                        <option value="transfer">Transfers</option>
                        <option value="payment">Payments</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="sortOrder">Sort By:</label>
                    <select id="sortOrder" class="filter-select">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="amount-high">Amount (High to Low)</option>
                        <option value="amount-low">Amount (Low to High)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="transactions-list">
            @forelse($transactions as $transaction)
            <div class="transaction-item" data-type="{{ strtolower($transaction->transaction_type) }}">
                <div class="transaction-icon {{ strtolower($transaction->transaction_type) }}">
                    <i class="fas {{ $transaction->transaction_type == 'DEPOSIT' ? 'fa-arrow-down' :
                        ($transaction->transaction_type == 'WITHDRAWAL' ? 'fa-arrow-up' :
                        ($transaction->transaction_type == 'TRANSFER' ? 'fa-exchange-alt' :
                        ($transaction->transaction_type == 'PAYMENT' ? 'fa-credit-card' :
                        ($transaction->transaction_type == 'INTEREST' ? 'fa-percentage' : 'fa-circle')))) }}"></i>
                </div>
                <div class="transaction-details">
                    <h4>{{ $transaction->description }}</h4>
                    <p>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y g:i A') }}</p>
                    @if($transaction->source)
                    <p class="transaction-source">From: {{ $transaction->source }}</p>
                    @endif
                    @if($transaction->destination)
                    <p class="transaction-destination">To: {{ $transaction->destination }}</p>
                    @endif
                </div>
                <div class="transaction-amount {{ $transaction->entry_type === 'credit' ? 'positive' : 'negative' }}">
                    <h4>{{ $transaction->entry_type === 'credit' ? '+' : '-' }}${{ number_format($transaction->amount, 2, '.', ',') }}</h4>
                    <p>Balance: ${{ number_format($transaction->balance_after, 2, '.', ',') }}</p>
                </div>
            </div>
            @empty
            <div class="no-transactions">
                <p>No transactions found for this account.</p>
            </div>
            @endforelse
        </div>

        <div class="pagination-container">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .container-fluid {
        padding: 30px;
        max-width: 100%;
        background-color: #f8f9fa;
        min-height: calc(100vh - 60px);
    }

    .page-header {
        margin-bottom: 20px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 5px;
        color: #333;
    }

    .page-subtitle {
        color: #666;
        margin-bottom: 0;
    }

    .account-actions-top {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
    }

    .action-link {
        color: #0066ff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }

    .action-link:hover {
        text-decoration: underline;
    }

    .account-type-badge {
        display: inline-flex;
        align-items: center;
        padding: 8px 15px;
        border-radius: 20px;
        color: white;
        font-weight: 500;
        margin-bottom: 25px;
    }

    .account-type-badge i {
        margin-right: 8px;
    }

    .account-type-badge.checking {
        background-color: #0066ff;
    }

    .account-type-badge.savings {
        background-color: #00c853;
    }

    .account-type-badge.investment {
        background-color: #ff9800;
    }

    .balance-summary {
        margin-bottom: 30px;
    }

    .balance-item {
        margin-bottom: 15px;
    }

    .balance-label {
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }

    .balance-amount {
        font-size: 28px;
        font-weight: 700;
        color: #333;
    }

    .transactions-section {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 30px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .section-header h2 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .transaction-filters {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-group label {
        font-size: 14px;
        color: #555;
    }

    .filter-select {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        color: #333;
        background-color: white;
    }

    .transactions-list {
        border-top: 1px solid #eee;
    }

    .transaction-item {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }

    .transaction-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 15px;
        flex-shrink: 0;
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
        background-color: #ff3d00;
    }

    .transaction-icon.interest {
        background-color: #9c27b0;
    }

    .transaction-details {
        flex: 1;
    }

    .transaction-details h4 {
        font-size: 15px;
        font-weight: 500;
        margin-bottom: 3px;
        color: #333;
    }

    .transaction-details p {
        font-size: 13px;
        color: #888;
        margin: 0;
        margin-bottom: 2px;
    }

    .transaction-source,
    .transaction-destination {
        font-size: 13px;
        color: #666;
    }

    .transaction-amount {
        text-align: right;
        min-width: 120px;
    }

    .transaction-amount h4 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .transaction-amount.positive h4 {
        color: #00c853;
    }

    .transaction-amount.negative h4 {
        color: #ff3d00;
    }

    .transaction-amount p {
        font-size: 13px;
        color: #888;
        margin: 0;
    }

    .no-transactions {
        padding: 30px;
        text-align: center;
        color: #666;
    }

    .pagination-container {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 20px;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .transaction-filters {
            margin-top: 15px;
            width: 100%;
        }

        .filter-group {
            width: 100%;
        }

        .filter-select {
            width: 100%;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Transaction type filter functionality
        const typeFilter = document.getElementById('transactionType');
        const dateFilter = document.getElementById('dateRange');
        const sortFilter = document.getElementById('sortOrder');
        const transactionItems = document.querySelectorAll('.transaction-item');

        // Function to apply all filters
        function applyFilters() {
            const selectedType = typeFilter.value.toLowerCase();
            const selectedDate = dateFilter.value;
            const selectedSort = sortFilter.value;

            // First filter by type
            transactionItems.forEach(item => {
                const transactionType = item.dataset.type;

                if (selectedType === 'all' || transactionType === selectedType) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });

            // Here you would implement date filtering and sorting
            // For demonstration purposes, we're just showing the UI interaction
        }

        // Add event listeners to all filters
        typeFilter.addEventListener('change', applyFilters);
        dateFilter.addEventListener('change', applyFilters);
        sortFilter.addEventListener('change', applyFilters);
    });
</script>
@endsection
