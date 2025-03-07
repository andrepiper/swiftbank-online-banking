@extends('layouts.app')

@section('title', 'Transaction Group Details')

@section('content')
<div class="admin-transaction-group-container">
    <div class="page-header">
        <div class="header-content">
            <h1>{{ $transactionGroup->name }}</h1>
            <p>
                <a href="{{ route('admin.transaction-groups') }}" class="text-muted">Transaction Groups</a> / Group Details
            </p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.transaction-groups.edit', $transactionGroup->obfuscated_id) }}" class="btn-primary">
                <i class="fas fa-edit"></i> Edit Group
            </a>
            @if($transactionGroup->status === 'active')
            <form action="{{ route('admin.transaction-groups.destroy', $transactionGroup->obfuscated_id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger" onclick="return confirm('Are you sure you want to deactivate this group?')">
                    <i class="fas fa-power-off"></i> Deactivate
                </button>
            </form>
            @else
            <a href="#" class="btn-success">
                <i class="fas fa-check-circle"></i> Reactivate
            </a>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <button type="button" class="close-alert">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
        <button type="button" class="close-alert">&times;</button>
    </div>
    @endif

    <div class="group-content">
        <div class="group-info-section">
            <div class="info-card">
                <div class="card-header">
                    <h2>Group Information</h2>
                </div>
                <div class="card-body">
                    <div class="group-header">
                        <div class="group-icon">
                            <i class="fas fa-{{ strtolower($transactionGroup->type) === 'deposit' ? 'arrow-from-bottom' : (strtolower($transactionGroup->type) === 'withdrawal' ? 'arrow-from-top' : (strtolower($transactionGroup->type) === 'transfer' ? 'transfer' : 'credit-card')) }}"></i>
                        </div>
                        <div class="group-badges">
                            <span class="type-badge {{ strtolower($transactionGroup->type) }}">
                                {{ $transactionGroup->type }}
                            </span>
                            <span class="status-badge {{ $transactionGroup->status }}">
                                {{ ucfirst($transactionGroup->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Description</div>
                        <div class="info-value">{{ $transactionGroup->description ?? 'No description provided' }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Group ID</div>
                        <div class="info-value id-value">
                            <code>{{ $transactionGroup->obfuscated_id }}</code>
                            <button class="copy-btn" onclick="copyToClipboard('{{ $transactionGroup->obfuscated_id }}')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-col">
                            <div class="info-label">Created</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($transactionGroup->created_at)->format('M d, Y') }}</div>
                            <div class="info-meta">{{ \Carbon\Carbon::parse($transactionGroup->created_at)->diffForHumans() }}</div>
                        </div>
                        <div class="info-col">
                            <div class="info-label">Last Updated</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($transactionGroup->updated_at)->format('M d, Y') }}</div>
                            <div class="info-meta">{{ \Carbon\Carbon::parse($transactionGroup->updated_at)->diffForHumans() }}</div>
                        </div>
                    </div>

                    @if($transactionGroup->metadata)
                    <div class="info-item">
                        <div class="info-label">
                            Metadata
                            <button class="copy-btn" onclick="copyToClipboard('{{ $transactionGroup->metadata }}')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <div class="info-value metadata">
                            <pre><code>{{ $transactionGroup->metadata }}</code></pre>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="info-card">
                <div class="card-header">
                    <h2>Transaction Summary</h2>
                </div>
                <div class="card-body">
                    @php
                        $transactionCount = count($transactions);
                        $totalAmount = $transactions->sum('amount');
                        $creditCount = $transactions->where('entry_type', 'credit')->count();
                        $debitCount = $transactions->where('entry_type', 'debit')->count();
                    @endphp
                    <div class="summary-item">
                        <div class="summary-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="summary-details">
                            <div class="summary-value">{{ $transactionCount }}</div>
                            <div class="summary-label">Total Transactions</div>
                        </div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-col">
                            <div class="summary-mini-icon credit">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="summary-mini-details">
                                <div class="summary-mini-value">{{ $creditCount }}</div>
                                <div class="summary-mini-label">Credits</div>
                            </div>
                        </div>
                        <div class="summary-col">
                            <div class="summary-mini-icon debit">
                                <i class="fas fa-minus"></i>
                            </div>
                            <div class="summary-mini-details">
                                <div class="summary-mini-value">{{ $debitCount }}</div>
                                <div class="summary-mini-label">Debits</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="transactions-section">
            <div class="transactions-card">
                <div class="card-header">
                    <h2>Transactions</h2>
                    <div class="header-actions">
                        <div class="search-bar">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchTransactions" placeholder="Search transactions...">
                        </div>
                        <div class="filter-dropdown">
                            <button class="filter-btn" id="filterDropdownBtn">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <div class="filter-menu" id="filterMenu">
                                <div class="filter-option" data-filter="all">All Transactions</div>
                                <div class="filter-divider"></div>
                                <div class="filter-header">By Type</div>
                                <div class="filter-option" data-filter="credit">Credit Transactions</div>
                                <div class="filter-option" data-filter="debit">Debit Transactions</div>
                                <div class="filter-divider"></div>
                                <div class="filter-header">By Status</div>
                                <div class="filter-option" data-filter="completed">Completed</div>
                                <div class="filter-option" data-filter="pending">Pending</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($transactions) > 0)
                    <div class="transactions-table-container">
                        <table class="transactions-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Account</th>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                <tr data-type="{{ $transaction->entry_type }}" data-status="{{ $transaction->status }}">
                                    <td>
                                        <div class="transaction-date">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}</div>
                                        <div class="transaction-time">{{ \Carbon\Carbon::parse($transaction->created_at)->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <div class="account-info">
                                            <div class="account-icon {{ strtolower($transaction->transaction_type) }}">
                                                <i class="fas fa-{{ strtolower($transaction->transaction_type) === 'deposit' ? 'arrow-from-bottom' : (strtolower($transaction->transaction_type) === 'withdrawal' ? 'arrow-from-top' : (strtolower($transaction->transaction_type) === 'transfer' ? 'transfer' : 'credit-card')) }}"></i>
                                            </div>
                                            <div class="account-details">
                                                <div class="account-name">{{ $transaction->account_name }}</div>
                                                <div class="account-number">{{ substr($transaction->account_number, -4) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="user-id">{{ $transaction->user_id }}</span>
                                    </td>
                                    <td>
                                        <span class="type-badge {{ strtolower($transaction->transaction_type) }}">
                                            {{ $transaction->transaction_type }}
                                        </span>
                                    </td>
                                    <td class="amount {{ $transaction->entry_type === 'credit' ? 'credit' : 'debit' }}">
                                        {{ $transaction->entry_type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $transaction->status }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('transaction.show', \App\Helpers\IdObfuscator::encode($transaction->id)) }}" class="action-btn view" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($transaction->contra_account_id)
                                            <a href="#" class="action-btn link" title="View contra account: {{ $transaction->contra_account_name }}">
                                                <i class="fas fa-link"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container">
                        {{ $transactions->links() }}
                    </div>
                    @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <h3>No transactions found</h3>
                        <p>This transaction group doesn't have any transactions yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .admin-transaction-group-container {
        width: 100%;
        max-width: 100%;
        padding-bottom: 30px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .header-content h1 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .header-content p {
        color: #888;
        margin: 0;
    }

    .header-content a {
        text-decoration: none;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn-primary, .btn-danger, .btn-success {
        padding: 10px 15px;
        border-radius: 5px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        text-decoration: none;
        border: none;
    }

    .btn-primary {
        background-color: #0066ff;
        color: white;
    }

    .btn-danger {
        background-color: #ea5455;
        color: white;
    }

    .btn-success {
        background-color: #28c76f;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0052cc;
    }

    .btn-danger:hover {
        background-color: #d43535;
    }

    .btn-success:hover {
        background-color: #1da15a;
    }

    .alert-success, .alert-danger {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background-color: #e3fcef;
        color: #28c76f;
        border: 1px solid #d2f9e6;
    }

    .alert-danger {
        background-color: #fceaea;
        color: #ea5455;
        border: 1px solid #f9d2d2;
    }

    .close-alert {
        background: none;
        border: none;
        color: inherit;
        font-size: 18px;
        cursor: pointer;
        margin-left: auto;
    }

    .group-content {
        display: flex;
        gap: 20px;
    }

    .group-info-section {
        width: 30%;
    }

    .transactions-section {
        width: 70%;
    }

    .info-card, .transactions-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h2 {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
        color: #333;
    }

    .card-body {
        padding: 20px;
    }

    .group-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .group-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        background-color: #f5f7fb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 15px;
        color: #0066ff;
    }

    .group-badges {
        display: flex;
        gap: 10px;
    }

    .type-badge, .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
    }

    .type-badge.deposit, .status-badge.active {
        background-color: #e3fcef;
        color: #28c76f;
    }

    .type-badge.withdrawal, .status-badge.inactive {
        background-color: #fceaea;
        color: #ea5455;
    }

    .type-badge.transfer {
        background-color: #e3f2fd;
        color: #0066ff;
    }

    .type-badge.payment {
        background-color: #fff8e1;
        color: #ff9800;
    }

    .status-badge.pending {
        background-color: #fff8e1;
        color: #ff9800;
    }

    .info-item {
        margin-bottom: 15px;
    }

    .info-label {
        font-size: 12px;
        color: #888;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 14px;
        color: #333;
    }

    .id-value {
        display: flex;
        align-items: center;
    }

    .id-value code {
        background-color: #f5f7fb;
        padding: 5px 8px;
        border-radius: 4px;
        font-size: 12px;
        margin-right: 5px;
    }

    .copy-btn {
        background: none;
        border: none;
        color: #888;
        cursor: pointer;
        font-size: 14px;
    }

    .copy-btn:hover {
        color: #0066ff;
    }

    .info-row {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
    }

    .info-col {
        flex: 1;
    }

    .info-meta {
        font-size: 12px;
        color: #888;
    }

    .metadata {
        background-color: #f5f7fb;
        padding: 10px;
        border-radius: 5px;
        overflow-x: auto;
    }

    .metadata pre {
        margin: 0;
        font-size: 12px;
    }

    .summary-item {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .summary-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background-color: #e3f2fd;
        color: #0066ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-right: 15px;
    }

    .summary-details {
        flex: 1;
    }

    .summary-value {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }

    .summary-label {
        font-size: 12px;
        color: #888;
    }

    .summary-row {
        display: flex;
        gap: 15px;
    }

    .summary-col {
        flex: 1;
        display: flex;
        align-items: center;
    }

    .summary-mini-icon {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        margin-right: 10px;
    }

    .summary-mini-icon.credit {
        background-color: #e3fcef;
        color: #28c76f;
    }

    .summary-mini-icon.debit {
        background-color: #fceaea;
        color: #ea5455;
    }

    .summary-mini-value {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .summary-mini-label {
        font-size: 12px;
        color: #888;
    }

    .search-bar {
        position: relative;
        width: 250px;
    }

    .search-bar input {
        width: 100%;
        padding: 8px 15px 8px 35px;
        border: 1px solid #eee;
        border-radius: 5px;
        background-color: #f5f7fb;
        font-size: 14px;
    }

    .search-bar i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
    }

    .filter-dropdown {
        position: relative;
    }

    .filter-btn {
        background-color: #f5f7fb;
        border: 1px solid #eee;
        border-radius: 5px;
        padding: 8px 15px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .filter-menu {
        position: absolute;
        top: 100%;
        right: 0;
        width: 200px;
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        z-index: 10;
        display: none;
    }

    .filter-option {
        padding: 10px 15px;
        font-size: 14px;
        cursor: pointer;
    }

    .filter-option:hover {
        background-color: #f5f7fb;
    }

    .filter-divider {
        height: 1px;
        background-color: #eee;
        margin: 5px 0;
    }

    .filter-header {
        padding: 5px 15px;
        font-size: 12px;
        font-weight: 600;
        color: #888;
    }

    .transactions-table-container {
        overflow-x: auto;
    }

    .transactions-table {
        width: 100%;
        border-collapse: collapse;
    }

    .transactions-table th,
    .transactions-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #f0f0f0;
    }

    .transactions-table th {
        font-size: 12px;
        font-weight: 600;
        color: #888;
        background-color: #f9f9f9;
    }

    .transactions-table tr:last-child td {
        border-bottom: none;
    }

    .transaction-date {
        font-size: 14px;
        color: #333;
    }

    .transaction-time {
        font-size: 12px;
        color: #888;
    }

    .account-info {
        display: flex;
        align-items: center;
    }

    .account-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        margin-right: 10px;
    }

    .account-icon.deposit {
        background-color: #e3fcef;
        color: #28c76f;
    }

    .account-icon.withdrawal {
        background-color: #fceaea;
        color: #ea5455;
    }

    .account-icon.transfer {
        background-color: #e3f2fd;
        color: #0066ff;
    }

    .account-icon.payment {
        background-color: #fff8e1;
        color: #ff9800;
    }

    .account-details {
        font-size: 14px;
    }

    .account-name {
        color: #333;
    }

    .account-number {
        font-size: 12px;
        color: #888;
    }

    .user-id {
        display: inline-block;
        padding: 3px 8px;
        background-color: #f5f7fb;
        border-radius: 4px;
        font-size: 12px;
        color: #555;
    }

    .amount {
        font-weight: 600;
    }

    .amount.credit {
        color: #28c76f;
    }

    .amount.debit {
        color: #ea5455;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .action-btn {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f5f7fb;
        color: #555;
        text-decoration: none;
        font-size: 14px;
    }

    .action-btn:hover {
        background-color: #e3f2fd;
        color: #0066ff;
    }

    .pagination-container {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-icon {
        font-size: 60px;
        color: #0066ff;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #888;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Copy to clipboard function
        window.copyToClipboard = function(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Copied to clipboard!');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        };

        // Filter dropdown toggle
        const filterBtn = document.getElementById('filterDropdownBtn');
        const filterMenu = document.getElementById('filterMenu');

        if (filterBtn && filterMenu) {
            filterBtn.addEventListener('click', function() {
                filterMenu.style.display = filterMenu.style.display === 'block' ? 'none' : 'block';
            });

            // Close the dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!filterBtn.contains(event.target) && !filterMenu.contains(event.target)) {
                    filterMenu.style.display = 'none';
                }
            });
        }

        // Filter options
        const filterOptions = document.querySelectorAll('.filter-option');
        const transactionRows = document.querySelectorAll('.transactions-table tbody tr');

        filterOptions.forEach(option => {
            option.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');

                transactionRows.forEach(row => {
                    if (filter === 'all') {
                        row.style.display = '';
                    } else if (filter === 'credit' || filter === 'debit') {
                        row.style.display = row.getAttribute('data-type') === filter ? '' : 'none';
                    } else {
                        row.style.display = row.getAttribute('data-status') === filter ? '' : 'none';
                    }
                });

                filterMenu.style.display = 'none';
            });
        });

        // Search functionality
        const searchInput = document.getElementById('searchTransactions');

        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();

                transactionRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }

        // Close alert buttons
        const closeButtons = document.querySelectorAll('.close-alert');

        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        });
    });
</script>
@endsection
