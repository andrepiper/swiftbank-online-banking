@extends('layouts.app')

@section('title', 'Create Transaction')

@section('styles')
<style>
    .container-fluid {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .page-header {
        margin-bottom: 20px;
    }

    .page-header h1 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 5px;
        color: #333;
    }

    .page-header p {
        color: #666;
        margin-bottom: 0;
    }

    .transaction-tabs {
        display: flex;
        border-bottom: 1px solid #e0e0e0;
        margin-bottom: 25px;
    }

    .transaction-tab {
        padding: 12px 20px;
        font-weight: 500;
        color: #666;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
        text-decoration: none;
    }

    .transaction-tab.active {
        color: #0066ff;
        border-bottom-color: #0066ff;
    }

    .transaction-tab:hover {
        color: #0066ff;
    }

    .transaction-section {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 25px;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 5px;
        color: #333;
    }

    .section-subtitle {
        color: #666;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 8px;
        display: block;
        color: #333;
    }

    .form-select, .form-control {
        width: 100%;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        background-color: #fff;
        color: #333;
        font-size: 14px;
    }

    .form-select:focus, .form-control:focus {
        border-color: #0066ff;
        box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.1);
        outline: none;
    }

    .input-group {
        display: flex;
        align-items: center;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border: none;
        padding: 12px 15px;
        color: #666;
        font-weight: 500;
    }

    .input-group .form-control {
        border: none;
        border-radius: 0;
    }

    .btn-primary {
        background-color: #0066ff;
        border: none;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        width: 100%;
    }

    .btn-primary:hover {
        background-color: #0052cc;
    }

    .transaction-type-selector {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .transaction-type-option {
        flex: 1;
        text-align: center;
        padding: 15px 10px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .transaction-type-option:hover {
        border-color: #0066ff;
        background-color: rgba(0, 102, 255, 0.05);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .transaction-type-option.active {
        border-color: #0066ff;
        background-color: rgba(0, 102, 255, 0.1);
        box-shadow: 0 4px 12px rgba(0, 102, 255, 0.15);
    }

    .transaction-type-option.active::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 20px 20px 0;
        border-color: transparent #0066ff transparent transparent;
    }

    .transaction-type-option i {
        font-size: 24px;
        margin-bottom: 8px;
        display: block;
    }

    .transaction-type-option.deposit i {
        color: #00c853;
    }

    .transaction-type-option.withdrawal i {
        color: #ff3d00;
    }

    .transaction-type-option.transfer i {
        color: #0066ff;
    }

    .transaction-type-option.payment i {
        color: #ff9800;
    }

    .transaction-type-option span {
        display: block;
        font-weight: 500;
    }

    .text-danger {
        color: #ff3d00;
        font-size: 13px;
        margin-top: 5px;
    }

    .invalid-feedback {
        display: block;
        color: #ff3d00;
        font-size: 13px;
        margin-top: 5px;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .transaction-type-selector {
            flex-wrap: wrap;
        }

        .transaction-type-option {
            flex: 1 0 45%;
            margin-bottom: 10px;
        }

        .transaction-tabs {
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 5px;
        }

        .transaction-tab {
            padding: 10px 15px;
        }
    }

    @media (max-width: 576px) {
        .transaction-type-option {
            flex: 1 0 100%;
        }
    }

    .cursor-pointer {
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid #e0e0e0;
    }

    .cursor-pointer:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-color: #d0d0d0;
    }

    .transaction-type-option.active {
        border-color: #696cff;
        background-color: #f0f0ff;
        box-shadow: 0 4px 8px rgba(105, 108, 255, 0.1);
    }

    .transaction-type-option.active::after {
        content: '';
        position: absolute;
        top: 10px;
        right: 10px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #696cff;
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <a href="{{ route('transaction.index') }}" class="text-muted fw-light">Transactions /</a>
            Create Transaction
        </h4>
        <div>
            <a href="{{ route('transaction.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-back me-1"></i> Back to Transactions
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

    <!-- Add this script tag right before the transaction section -->
    <script>
        // Function to handle transaction type selection
        function selectTransactionType(element, type) {
            // Remove active class from all options
            document.querySelectorAll('.transaction-type-option').forEach(opt => {
                opt.classList.remove('active');
            });

            // Add active class to selected option
            element.classList.add('active');

            // Set the hidden input value
            document.getElementById('transaction_type').value = type;

            // Hide error message
            document.getElementById('transaction_type_error').style.display = 'none';

            // Toggle transfer fields
            const isTransfer = type === 'TRANSFER';
            document.querySelectorAll('.transfer-field').forEach(field => {
                field.style.display = isTransfer ? 'block' : 'none';
            });

            if (isTransfer) {
                document.getElementById('contra_account_id').setAttribute('required', 'required');
            } else {
                document.getElementById('contra_account_id').removeAttribute('required');
            }
        }
    </script>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Create New Transaction</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('transaction.store') }}" method="POST">
                        @csrf

                        <!-- Hidden transaction type field -->
                        <input type="hidden" id="transaction_type" name="transaction_type" value="{{ old('transaction_type', '') }}" required>

                        <!-- Transaction Type Selector -->
                        <div class="form-group mb-4">
                            <label class="form-label">Transaction Type</label>
                            <div id="transaction_type_error" class="text-danger mb-2" style="display: none;">Please select a transaction type</div>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="card transaction-type-option h-100 cursor-pointer" data-type="DEPOSIT" onclick="selectTransactionType(this, 'DEPOSIT')">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="badge bg-label-success p-2 me-3">
                                                <i class="fas fa-arrow-down fs-3"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Deposit</h6>
                                                <small class="text-muted">Add funds to account</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card transaction-type-option h-100 cursor-pointer" data-type="WITHDRAWAL" onclick="selectTransactionType(this, 'WITHDRAWAL')">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="badge bg-label-danger p-2 me-3">
                                                <i class="fas fa-arrow-up fs-3"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Withdrawal</h6>
                                                <small class="text-muted">Remove funds from account</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card transaction-type-option h-100 cursor-pointer" data-type="TRANSFER" onclick="selectTransactionType(this, 'TRANSFER')">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="badge bg-label-info p-2 me-3">
                                                <i class="fas fa-transfer fs-3"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Transfer</h6>
                                                <small class="text-muted">Move funds between accounts</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card transaction-type-option h-100 cursor-pointer" data-type="PAYMENT" onclick="selectTransactionType(this, 'PAYMENT')">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="badge bg-label-primary p-2 me-3">
                                                <i class="fas fa-credit-card fs-3"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Payment</h6>
                                                <small class="text-muted">Pay bills or services</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_id" class="form-label">From Account</label>
                                    <select class="form-select @error('account_id') is-invalid @enderror" id="account_id" name="account_id" required>
                                        <option value="">Select account</option>
                                        @foreach($accounts as $account)
                                        <option value="{{ $account->obfuscated_id }}" {{ old('account_id') == $account->obfuscated_id ? 'selected' : '' }}>
                                            {{ $account->account_name }} - {{ substr($account->account_number, -4) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('account_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 transfer-field" style="display: none;">
                                <div class="form-group">
                                    <label for="contra_account_id" class="form-label">To Account</label>
                                    <select class="form-select @error('contra_account_id') is-invalid @enderror" id="contra_account_id" name="contra_account_id">
                                        <option value="">Select account</option>
                                        @foreach($accounts as $account)
                                        <option value="{{ $account->obfuscated_id }}" {{ old('contra_account_id') == $account->obfuscated_id ? 'selected' : '' }}>
                                            {{ $account->account_name }} - {{ substr($account->account_number, -4) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('contra_account_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount" class="form-label">Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="0.01" placeholder="0.00" required>
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transaction_date" class="form-label">Transaction Date</label>
                                    <input type="date" class="form-control" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2" placeholder="e.g. Monthly savings transfer">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">Create Transaction</button>
                            <a href="{{ route('transaction.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="transaction-section">
        <h2 class="section-title">Transaction Information</h2>
        <p class="section-subtitle">Learn about different transaction types</p>

        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="d-flex align-items-start">
                    <div style="color: #00c853; font-size: 24px; margin-right: 15px;">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div>
                        <h5 style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">Deposit</h5>
                        <p style="color: #666; margin-bottom: 0;">Add funds to your account from an external source.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="d-flex align-items-start">
                    <div style="color: #ff3d00; font-size: 24px; margin-right: 15px;">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div>
                        <h5 style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">Withdrawal</h5>
                        <p style="color: #666; margin-bottom: 0;">Remove funds from your account for external use.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="d-flex align-items-start">
                    <div style="color: #0066ff; font-size: 24px; margin-right: 15px;">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div>
                        <h5 style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">Transfer</h5>
                        <p style="color: #666; margin-bottom: 0;">Move funds between your SwiftBank accounts.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="d-flex align-items-start">
                    <div style="color: #ff9800; font-size: 24px; margin-right: 15px;">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div>
                        <h5 style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">Payment</h5>
                        <p style="color: #666; margin-bottom: 0;">Pay bills or make purchases from your account.</p>
                    </div>
                </div>
            </div>
        </div>

        <div style="background-color: #f8f9fa; border-radius: 8px; padding: 15px; margin-top: 10px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle" style="color: #0066ff; font-size: 20px; margin-right: 15px;"></i>
                <p style="margin-bottom: 0; color: #555;">Transactions are processed immediately and will update your account balance.</p>
            </div>
        </div>
    </div>
</div>

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize transaction type if there's an old input value
        const oldType = document.getElementById('transaction_type').value;
        if (oldType) {
            const option = document.querySelector(`.transaction-type-option[data-type="${oldType}"]`);
            if (option) {
                selectTransactionType(option, oldType);
            }
        }

        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const transactionType = document.getElementById('transaction_type').value;
            if (!transactionType) {
                e.preventDefault();
                document.getElementById('transaction_type_error').style.display = 'block';
                document.querySelector('.transaction-type-option').scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
</script>
@endsection
@endsection
