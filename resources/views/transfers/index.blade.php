@extends('layouts.app')

@section('title', 'Transfers')

@section('content')
<div class="transfers-container">
    <div class="page-header">
        <h1>Transfers</h1>
        <p>Send money to your accounts or other recipients</p>
    </div>

    <div class="transfer-types">
        <div class="transfer-type-tabs">
            <button class="tab-btn active" data-target="internal-transfer">
                <i class="fas fa-exchange-alt"></i> Between My Accounts
            </button>
            <button class="tab-btn" data-target="domestic-transfer">
                <i class="fas fa-university"></i> Domestic Transfer
            </button>
            <button class="tab-btn" data-target="international-transfer">
                <i class="fas fa-globe"></i> International Wire
            </button>
        </div>

        <!-- Internal Transfer Form -->
        <div class="transfer-form-container active" id="internal-transfer">
            <div class="transfer-form-header">
                <h2>Transfer Between My Accounts</h2>
                <p>Move money between your SwiftBank accounts</p>
            </div>

            <form class="transfer-form" action="{{ route('transfers.internal') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="from-account">From Account</label>
                    <select id="from-account" name="from_account" required>
                        <option value="">Select account</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->account_type_name }} - {{ $account->account_number }} ({{ $account->currency }} {{ number_format($account->balance, 2) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="to-account">To Account</label>
                    <select id="to-account" name="to_account" required>
                        <option value="">Select account</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->account_type_name }} - {{ $account->account_number }} ({{ $account->currency }} {{ number_format($account->balance, 2) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="amount">Amount</label>
                    <div class="amount-input">
                        <span class="currency-symbol">$</span>
                        <input type="number" id="amount" name="amount" placeholder="0.00" step="0.01" min="0.01" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="transfer-date">Transfer Date</label>
                    <input type="date" id="transfer-date" name="transfer_date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="description">Description (Optional)</label>
                    <input type="text" id="description" name="description" placeholder="e.g., Monthly savings transfer">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Transfer Funds</button>
                </div>
            </form>
        </div>

        <!-- Domestic Transfer Form -->
        <div class="transfer-form-container" id="domestic-transfer">
            <div class="transfer-form-header">
                <h2>Domestic Bank Transfer</h2>
                <p>Send money to another bank account within the country</p>
            </div>

            <form class="transfer-form" action="{{ route('transfers.domestic') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="domestic-from-account">From Account</label>
                    <select id="domestic-from-account" name="from_account" required>
                        <option value="">Select account</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->account_type_name }} - {{ $account->account_number }} ({{ $account->currency }} {{ number_format($account->balance, 2) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="recipient-name">Recipient Name</label>
                    <input type="text" id="recipient-name" name="recipient_name" placeholder="Full name of recipient" required>
                </div>

                <div class="form-group">
                    <label for="recipient-bank">Recipient Bank</label>
                    <select id="recipient-bank" name="recipient_bank" required>
                        <option value="">Select bank</option>
                        <option value="chase">Chase Bank</option>
                        <option value="bofa">Bank of America</option>
                        <option value="wells">Wells Fargo</option>
                        <option value="citi">Citibank</option>
                        <option value="other">Other Bank</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="account-number">Account Number</label>
                    <input type="text" id="account-number" name="account_number" placeholder="Recipient's account number" required>
                </div>

                <div class="form-group">
                    <label for="routing-number">Routing Number</label>
                    <input type="text" id="routing-number" name="routing_number" placeholder="9-digit routing number" required>
                </div>

                <div class="form-group">
                    <label for="domestic-amount">Amount</label>
                    <div class="amount-input">
                        <span class="currency-symbol">$</span>
                        <input type="number" id="domestic-amount" name="amount" placeholder="0.00" step="0.01" min="0.01" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="domestic-transfer-date">Transfer Date</label>
                    <input type="date" id="domestic-transfer-date" name="transfer_date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="domestic-description">Description (Optional)</label>
                    <input type="text" id="domestic-description" name="description" placeholder="e.g., Rent payment">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Transfer Funds</button>
                </div>
            </form>
        </div>

        <!-- International Transfer Form -->
        <div class="transfer-form-container" id="international-transfer">
            <div class="transfer-form-header">
                <h2>International Wire Transfer</h2>
                <p>Send money to bank accounts in other countries</p>
            </div>

            <form class="transfer-form" action="{{ route('transfers.international') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="international-from-account">From Account</label>
                    <select id="international-from-account" name="from_account" required>
                        <option value="">Select account</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->account_type_name }} - {{ $account->account_number }} ({{ $account->currency }} {{ number_format($account->balance, 2) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="international-recipient-name">Recipient Name</label>
                    <input type="text" id="international-recipient-name" name="recipient_name" placeholder="Full name as it appears on the account" required>
                </div>

                <div class="form-group">
                    <label for="recipient-address">Recipient Address</label>
                    <textarea id="recipient-address" name="recipient_address" placeholder="Full address of the recipient" required></textarea>
                </div>

                <div class="form-group">
                    <label for="recipient-country">Recipient Country</label>
                    <select id="recipient-country" name="recipient_country" required>
                        <option value="">Select country</option>
                        <option value="uk">United Kingdom</option>
                        <option value="canada">Canada</option>
                        <option value="australia">Australia</option>
                        <option value="germany">Germany</option>
                        <option value="france">France</option>
                        <option value="japan">Japan</option>
                        <option value="china">China</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="recipient-bank-name">Recipient Bank Name</label>
                    <input type="text" id="recipient-bank-name" name="recipient_bank_name" placeholder="Full name of the bank" required>
                </div>

                <div class="form-group">
                    <label for="swift-code">SWIFT/BIC Code</label>
                    <input type="text" id="swift-code" name="swift_code" placeholder="Bank's SWIFT or BIC code" required>
                </div>

                <div class="form-group">
                    <label for="iban">IBAN/Account Number</label>
                    <input type="text" id="iban" name="iban" placeholder="International Bank Account Number" required>
                </div>

                <div class="form-group">
                    <label for="international-amount">Amount</label>
                    <div class="amount-input-group">
                        <select id="currency" name="currency" required>
                            <option value="usd">USD</option>
                            <option value="eur">EUR</option>
                            <option value="gbp">GBP</option>
                            <option value="cad">CAD</option>
                            <option value="aud">AUD</option>
                            <option value="jpy">JPY</option>
                        </select>
                        <div class="amount-input">
                            <input type="number" id="international-amount" name="amount" placeholder="0.00" step="0.01" min="0.01" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="transfer-purpose">Purpose of Transfer</label>
                    <select id="transfer-purpose" name="transfer_purpose" required>
                        <option value="">Select purpose</option>
                        <option value="family">Family Support</option>
                        <option value="gift">Gift</option>
                        <option value="business">Business Payment</option>
                        <option value="property">Property Purchase</option>
                        <option value="education">Education Fees</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="international-transfer-date">Transfer Date</label>
                    <input type="date" id="international-transfer-date" name="transfer_date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="international-description">Description (Optional)</label>
                    <input type="text" id="international-description" name="description" placeholder="e.g., Tuition payment">
                </div>

                <div class="fee-notice">
                    <p><i class="fas fa-info-circle"></i> International wire transfers incur a fee of $25 plus any correspondent bank charges.</p>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Transfer Funds</button>
                </div>
            </form>
        </div>
    </div>

    <div class="recent-transfers">
        <div class="section-header">
            <h2>Recent Transfers</h2>
            <a href="{{ route('transaction.index') }}" class="view-all">View All</a>
        </div>

        <div class="transfers-list">
            @if(count($recentTransfers) > 0)
                @foreach($recentTransfers as $transfer)
                    <div class="transfer-item">
                        <div class="transfer-icon {{ strtolower($transfer->transfer_type) }}">
                            @if($transfer->transfer_type == 'INTERNAL')
                                <i class="fas fa-exchange-alt"></i>
                            @elseif($transfer->transfer_type == 'DOMESTIC')
                                <i class="fas fa-university"></i>
                            @else
                                <i class="fas fa-globe"></i>
                            @endif
                        </div>
                        <div class="transfer-details">
                            <h3>{{ $transfer->reference_note }}</h3>
                            @if($transfer->transfer_type == 'INTERNAL')
                                <p>From {{ $transfer->from_account_type_name }} **** {{ substr($transfer->from_account_number, -4) }} to {{ $transfer->to_account_type_name }} **** {{ substr($transfer->to_account_number, -4) }}</p>
                            @elseif($transfer->transfer_type == 'DOMESTIC')
                                <p>To {{ $transfer->destination_account_name }} - {{ $transfer->destination_bank_name }}</p>
                            @else
                                <p>To {{ $transfer->destination_account_name }} - {{ $transfer->destination_bank_name }}</p>
                            @endif
                            <span class="transfer-date">{{ date('M d, Y', strtotime($transfer->scheduled_date)) }}</span>
                        </div>
                        <div class="transfer-amount">
                            <h3>${{ number_format($transfer->transaction_amount ?? 0, 2) }} {{ $transfer->transaction_currency ?? 'USD' }}</h3>
                            <span class="transfer-status {{ $transfer->completed_date ? 'completed' : 'processing' }}">
                                {{ $transfer->completed_date ? 'Completed' : 'Processing' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-transfers">
                    <p>No recent transfers found.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .transfers-container {
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

    .transfer-types {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .transfer-type-tabs {
        display: flex;
        border-bottom: 1px solid #f0f0f0;
    }

    .tab-btn {
        flex: 1;
        padding: 15px;
        background: none;
        border: none;
        font-size: 14px;
        font-weight: 500;
        color: #555;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .tab-btn:hover {
        background-color: #f9f9f9;
    }

    .tab-btn.active {
        color: #0066ff;
        border-bottom: 2px solid #0066ff;
    }

    .transfer-form-container {
        padding: 25px;
        display: none;
    }

    .transfer-form-container.active {
        display: block;
    }

    .transfer-form-header {
        margin-bottom: 25px;
    }

    .transfer-form-header h2 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .transfer-form-header p {
        color: #888;
        margin: 0;
    }

    .transfer-form {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group {
        margin-bottom: 5px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        color: #555;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }

    .form-group textarea {
        height: 80px;
        resize: vertical;
    }

    .amount-input {
        position: relative;
    }

    .currency-symbol {
        position: absolute;
        left: 12px;
        top: 10px;
        color: #555;
    }

    .amount-input input {
        padding-left: 25px;
    }

    .amount-input-group {
        display: flex;
        gap: 10px;
    }

    .amount-input-group select {
        width: 100px;
    }

    .amount-input-group .amount-input {
        flex: 1;
    }

    .form-actions {
        grid-column: span 2;
        display: flex;
        justify-content: flex-end;
        margin-top: 10px;
    }

    .btn-submit {
        background-color: #0066ff;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        background-color: #0052cc;
    }

    .fee-notice {
        grid-column: span 2;
        background-color: #f8f9fa;
        border-radius: 5px;
        padding: 10px 15px;
        margin-top: 10px;
    }

    .fee-notice p {
        color: #666;
        font-size: 13px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .fee-notice i {
        color: #0066ff;
    }

    .recent-transfers {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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
        margin: 0;
    }

    .view-all {
        color: #0066ff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }

    .view-all:hover {
        text-decoration: underline;
    }

    .transfers-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .transfer-item {
        display: flex;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .transfer-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .transfer-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #0066ff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: white;
    }

    .transfer-icon.domestic {
        background-color: #00c853;
    }

    .transfer-icon.international {
        background-color: #9c27b0;
    }

    .transfer-details {
        flex: 1;
    }

    .transfer-details h3 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .transfer-details p {
        font-size: 12px;
        color: #666;
        margin: 0 0 3px 0;
    }

    .transfer-date {
        font-size: 12px;
        color: #888;
    }

    .transfer-amount {
        text-align: right;
    }

    .transfer-amount h3 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .transfer-status {
        font-size: 12px;
        font-weight: 500;
        padding: 2px 8px;
        border-radius: 12px;
        display: inline-block;
    }

    .transfer-status.completed {
        background-color: #e8f5e9;
        color: #00c853;
    }

    .transfer-status.processing {
        background-color: #fff8e1;
        color: #ff9800;
    }

    .transfer-status.failed {
        background-color: #ffebee;
        color: #ff3d00;
    }

    .no-transfers {
        text-align: center;
        padding: 20px;
        color: #888;
    }

    @media (max-width: 768px) {
        .transfer-form {
            grid-template-columns: 1fr;
        }

        .form-actions {
            grid-column: 1;
        }

        .fee-notice {
            grid-column: 1;
        }

        .tab-btn {
            font-size: 12px;
            padding: 12px 8px;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching functionality
        const tabButtons = document.querySelectorAll('.tab-btn');
        const formContainers = document.querySelectorAll('.transfer-form-container');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons and containers
                tabButtons.forEach(btn => btn.classList.remove('active'));
                formContainers.forEach(container => container.classList.remove('active'));

                // Add active class to clicked button and corresponding container
                this.classList.add('active');
                const targetId = this.getAttribute('data-target');
                document.getElementById(targetId).classList.add('active');
            });
        });

        // Form validation and submission
        const transferForms = document.querySelectorAll('.transfer-form');

        transferForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const actionUrl = this.action;

                fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Transfer initiated successfully!');
                        this.reset();
                        // Reload the page to show the new transfer in the recent transfers list
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
        });

        // Prevent selecting the same account for from and to in internal transfers
        const fromAccount = document.getElementById('from-account');
        const toAccount = document.getElementById('to-account');

        if (fromAccount && toAccount) {
            fromAccount.addEventListener('change', function() {
                const selectedValue = this.value;

                Array.from(toAccount.options).forEach(option => {
                    if (option.value === selectedValue) {
                        option.disabled = true;
                    } else {
                        option.disabled = false;
                    }
                });

                if (toAccount.value === selectedValue) {
                    toAccount.value = '';
                }
            });
        }
    });
</script>
@endsection
