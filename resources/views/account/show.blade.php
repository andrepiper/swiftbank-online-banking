@extends('layouts.app')

@section('title', $account->account_name)

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">{{ $account->account_name }}</h1>
        <p class="page-subtitle">Account Number: **** **** **** {{ substr($account->account_number, -4) }}</p>
    </div>

    <div class="account-actions-top">
        <a href="{{ route('account.edit', $account->obfuscated_id) }}" class="action-link">Edit</a>
        <a href="{{ route('account.statistics', $account->obfuscated_id) }}" class="action-link">Statistics</a>
        <a href="#" class="action-link text-danger" id="closeAccountLink">Close Account</a>
    </div>

    <div class="account-type-badge {{ strtolower($account->account_type_name) }}">
        <i class="fas {{ $account->account_type_name == 'Checking' ? 'fa-wallet' : ($account->account_type_name == 'Savings' ? 'fa-piggy-bank' : 'fa-chart-line') }}"></i>
        {{ $account->account_type_name }} Account
    </div>

    <div class="balance-section">
        <div class="balance-item">
            <div class="balance-label">Current Balance</div>
            <div class="balance-amount">${{ number_format($account->balance, 2, '.', ',') }}</div>
        </div>

        <div class="balance-item">
            <div class="balance-label">Available Balance</div>
            <div class="balance-amount">${{ number_format($account->available_balance, 2, '.', ',') }}</div>
        </div>
    </div>

    <div class="account-details-grid">
        <div class="detail-item">
            <div class="detail-label">Currency</div>
            <div class="detail-value">{{ $account->currency }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Status</div>
            <div class="detail-value">
                <span class="status-badge {{ strtolower($account->status) }}">{{ $account->status }}</span>
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Opened On</div>
            <div class="detail-value">{{ \Carbon\Carbon::parse($account->opened_at)->format('M d, Y') }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Last Activity</div>
            <div class="detail-value">{{ \Carbon\Carbon::parse($account->last_activity_at)->format('M d, Y g:i A') }}</div>
        </div>
    </div>

    <div class="account-actions-buttons">
        <a href="{{ route('transfers') }}" class="btn-transfer">
            <i class="fas fa-exchange-alt"></i> Transfer Money
        </a>
        <a href="#" class="btn-download" id="downloadStatementBtn">
            <i class="fas fa-file-download"></i> Download Statement
        </a>
    </div>

    <div class="transaction-history-section">
        <div class="section-header">
            <h2>Transaction History</h2>
            <div class="transaction-filters">
                <select id="transactionTypeFilter" class="filter-select">
                    <option value="all">All Transactions</option>
                    <option value="deposit">Deposits</option>
                    <option value="withdrawal">Withdrawals</option>
                    <option value="transfer">Transfers</option>
                    <option value="payment">Payments</option>
                </select>
                <a href="{{ route('account.transactions', ['id' =>$account->obfuscated_id]) }}" class="view-all-link">View All</a>
            </div>
        </div>

        <div class="transactions-list">
            @forelse($transactions as $transaction)
            <div class="transaction-item">
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

<!-- Close Account Modal -->
<div class="modal fade" id="closeAccountModal" tabindex="-1" aria-labelledby="closeAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="closeAccountModalLabel">Close Account</h5>
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to close this account? This action cannot be undone.</p>
                <p><strong>Note:</strong> You can only close accounts with a zero balance.</p>
                @if($account->balance > 0)
                <div class="alert alert-warning">
                    This account has a balance of ${{ number_format($account->balance, 2, '.', ',') }}.
                    Please transfer or withdraw all funds before closing.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary">Cancel</button>
                <form action="{{ route('account.destroy', $account->obfuscated_id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" {{ $account->balance > 0 ? 'disabled' : '' }}>
                        Close Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Ensure modal is hidden by default */
    #closeAccountModal {
        display: none;
    }

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

    .text-danger {
        color: #dc3545 !important;
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

    .balance-section {
        display: flex;
        gap: 40px;
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

    .account-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        color: #666;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .detail-value {
        font-weight: 500;
        color: #333;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-badge.active {
        background-color: #e8f5e9;
        color: #00c853;
    }

    .status-badge.inactive {
        background-color: #f5f5f5;
        color: #757575;
    }

    .status-badge.frozen {
        background-color: #e3f2fd;
        color: #2196f3;
    }

    .status-badge.closed {
        background-color: #ffebee;
        color: #f44336;
    }

    .account-actions-buttons {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }

    .btn-transfer, .btn-download {
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-transfer {
        background-color: #0066ff;
        color: white;
        border: none;
    }

    .btn-transfer:hover {
        background-color: #0052cc;
    }

    .btn-download {
        background-color: #f5f5f5;
        color: #333;
        border: 1px solid #ddd;
    }

    .btn-download:hover {
        background-color: #e9e9e9;
    }

    .transaction-history-section {
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
    }

    .section-header h2 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .transaction-filters {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .filter-select {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        color: #333;
        background-color: white;
    }

    .view-all-link {
        color: #0066ff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }

    .view-all-link:hover {
        text-decoration: underline;
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

    .transaction-item:last-child {
        border-bottom: none;
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

        .balance-section {
            flex-direction: column;
            gap: 15px;
        }

        .account-details-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .account-actions-buttons {
            flex-direction: column;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .transaction-filters {
            margin-top: 10px;
            width: 100%;
        }

        .filter-select {
            width: 100%;
        }
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 10px;
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        border-bottom: 1px solid #f0f0f0;
        padding: 15px 20px;
    }

    .modal-header .modal-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-body p {
        margin-bottom: 10px;
        color: #555;
    }

    .modal-body .alert-warning {
        background-color: #fff8e1;
        border-color: #ffe0b2;
        color: #ff9800;
        padding: 12px 15px;
        border-radius: 8px;
        margin-top: 15px;
    }

    .modal-footer {
        border-top: 1px solid #f0f0f0;
        padding: 15px 20px;
    }

    .btn-secondary {
        background-color: #f5f5f5;
        color: #333;
        border: 1px solid #ddd;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-secondary:hover {
        background-color: #e9e9e9;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .btn-danger:disabled {
        background-color: #f8d7da;
        color: #dc3545;
        cursor: not-allowed;
    }
</style>
@endsection

@section('scripts')
<script>
    // Function to open the close account modal
    function openCloseAccountModal(event) {
        event.preventDefault();

        // Get the modal element
        const modal = document.getElementById('closeAccountModal');

        // Remove the display:none style
        modal.style.display = '';

        // Add the necessary classes for Bootstrap modal
        modal.classList.add('show');
        modal.setAttribute('aria-modal', 'true');
        modal.setAttribute('role', 'dialog');
        modal.style.paddingRight = '17px';
        modal.style.display = 'block';

        // Add backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        document.body.appendChild(backdrop);

        // Add modal-open class to body
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = '17px';
    }

    // Function to close the modal
    function closeModal() {
        // Get the modal element
        const modal = document.getElementById('closeAccountModal');

        // Remove the show class
        modal.classList.remove('show');
        modal.removeAttribute('aria-modal');
        modal.removeAttribute('role');
        modal.style.display = 'none';

        // Remove backdrop
        const backdrops = document.getElementsByClassName('modal-backdrop');
        while(backdrops.length > 0) {
            backdrops[0].parentNode.removeChild(backdrops[0]);
        }

        // Remove modal-open class from body
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Remove any existing modal backdrops
        const backdrops = document.getElementsByClassName('modal-backdrop');
        while(backdrops.length > 0) {
            backdrops[0].parentNode.removeChild(backdrops[0]);
        }

        // Add event listeners to close buttons in the modal
        const closeButtons = document.querySelectorAll('#closeAccountModal .btn-close, #closeAccountModal .btn-secondary');
        closeButtons.forEach(button => {
            button.addEventListener('click', closeModal);
        });

        // Add event listener to the Close Account link
        const closeAccountLink = document.getElementById('closeAccountLink');
        if (closeAccountLink) {
            closeAccountLink.addEventListener('click', function(e) {
                e.preventDefault();
                openCloseAccountModal(e);
            });
        }

        // Initialize all Bootstrap modals
        var modals = document.querySelectorAll('.modal');
        modals.forEach(function(modalEl) {
            new bootstrap.Modal(modalEl);
        });

        // Transaction type filter functionality
        const typeFilter = document.getElementById('transactionTypeFilter');
        const transactionItems = document.querySelectorAll('.transaction-item');

        typeFilter.addEventListener('change', function() {
            const selectedType = this.value.toLowerCase();

            transactionItems.forEach(item => {
                const transactionType = item.querySelector('.transaction-icon').classList[1];

                if (selectedType === 'all' || transactionType === selectedType) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Add confirmation requirement
        const closeAccountModal = document.getElementById('closeAccountModal');
        const closeAccountSubmitBtn = closeAccountModal.querySelector('form').querySelector('button[type="submit"]');
        closeAccountSubmitBtn.addEventListener('click', function(e) {
            if (!confirm('Are you absolutely sure you want to close this account? This action cannot be undone.')) {
                e.preventDefault();
            }
        });

        // Transfer Money Button Functionality
        const transferBtn = document.querySelector('.btn-transfer');
        if (transferBtn) {
            transferBtn.addEventListener('click', function() {
                window.location.href = "{{ route('transfers') }}";
            });
        }

        // Download Statement Button Functionality
        const downloadBtn = document.getElementById('downloadStatementBtn');
        if (downloadBtn) {
            downloadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                // You can implement statement download functionality here
                alert('Statement download functionality will be implemented soon.');
            });
        }
    });
</script>
@endsection
