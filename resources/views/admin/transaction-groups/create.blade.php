@extends('layouts.app')

@section('title', 'Create Transaction Group')

@section('content')
<div class="admin-transaction-group-container">
    <div class="page-header">
        <div class="header-content">
            <h1>Create Transaction Group</h1>
            <p>
                <a href="{{ route('admin.transaction-groups') }}" class="text-muted">Transaction Groups</a> / Create New
            </p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.transaction-groups') }}" class="btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Groups
            </a>
        </div>
    </div>

    <div class="group-content">
        <div class="form-section">
            <div class="form-card">
                <div class="card-header">
                    <h2>Group Information</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.transaction-groups.store') }}" method="POST" id="createGroupForm">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Group Name <span class="required">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div class="help-text">Choose a descriptive name for this transaction group</div>
                            </div>

                            <div class="form-group">
                                <label for="type">Group Type <span class="required">*</span></label>
                                <select id="type" name="type" class="form-control @error('type') is-invalid @enderror" required>
                                    <option value="" selected disabled>Select a type</option>
                                    <option value="DEPOSIT" {{ old('type') == 'DEPOSIT' ? 'selected' : '' }}>Deposit</option>
                                    <option value="WITHDRAWAL" {{ old('type') == 'WITHDRAWAL' ? 'selected' : '' }}>Withdrawal</option>
                                    <option value="TRANSFER" {{ old('type') == 'TRANSFER' ? 'selected' : '' }}>Transfer</option>
                                    <option value="PAYMENT" {{ old('type') == 'PAYMENT' ? 'selected' : '' }}>Payment</option>
                                    <option value="FEE" {{ old('type') == 'FEE' ? 'selected' : '' }}>Fee</option>
                                    <option value="INTEREST" {{ old('type') == 'INTEREST' ? 'selected' : '' }}>Interest</option>
                                    <option value="OTHER" {{ old('type') == 'OTHER' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('type')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div class="help-text">Select the primary transaction type for this group</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                            <div class="help-text">Provide a detailed description of this transaction group's purpose</div>
                        </div>

                        <div class="form-group">
                            <label for="metadata">Metadata (JSON)</label>
                            <textarea id="metadata" name="metadata" class="form-control @error('metadata') is-invalid @enderror" rows="4" placeholder='{"key": "value"}'>{{ old('metadata') }}</textarea>
                            @error('metadata')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                            <div class="help-text">Optional JSON metadata for additional configuration</div>
                        </div>

                        <div class="form-group">
                            <label>Transaction Type <span class="required">*</span></label>
                            <div class="transaction-type-grid">
                                <div class="type-option" data-type="deposit">
                                    <input type="radio" name="transaction_type" id="type_deposit" value="deposit" class="type-radio" {{ old('transaction_type') == 'deposit' ? 'checked' : '' }}>
                                    <label for="type_deposit" class="type-label">
                                        <div class="type-icon deposit">
                                            <i class="fas fa-arrow-from-bottom"></i>
                                        </div>
                                        <div class="type-info">
                                            <div class="type-name">Deposit</div>
                                            <div class="type-desc">Money in</div>
                                        </div>
                                    </label>
                                </div>

                                <div class="type-option" data-type="withdrawal">
                                    <input type="radio" name="transaction_type" id="type_withdrawal" value="withdrawal" class="type-radio" {{ old('transaction_type') == 'withdrawal' ? 'checked' : '' }}>
                                    <label for="type_withdrawal" class="type-label">
                                        <div class="type-icon withdrawal">
                                            <i class="fas fa-arrow-from-top"></i>
                                        </div>
                                        <div class="type-info">
                                            <div class="type-name">Withdrawal</div>
                                            <div class="type-desc">Money out</div>
                                        </div>
                                    </label>
                                </div>

                                <div class="type-option" data-type="transfer">
                                    <input type="radio" name="transaction_type" id="type_transfer" value="transfer" class="type-radio" {{ old('transaction_type') == 'transfer' ? 'checked' : '' }}>
                                    <label for="type_transfer" class="type-label">
                                        <div class="type-icon transfer">
                                            <i class="fas fa-transfer"></i>
                                        </div>
                                        <div class="type-info">
                                            <div class="type-name">Transfer</div>
                                            <div class="type-desc">Between accounts</div>
                                        </div>
                                    </label>
                                </div>

                                <div class="type-option" data-type="payment">
                                    <input type="radio" name="transaction_type" id="type_payment" value="payment" class="type-radio" {{ old('transaction_type') == 'payment' ? 'checked' : '' }}>
                                    <label for="type_payment" class="type-label">
                                        <div class="type-icon payment">
                                            <i class="fas fa-credit-card"></i>
                                        </div>
                                        <div class="type-info">
                                            <div class="type-name">Payment</div>
                                            <div class="type-desc">Bills & services</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            @error('transaction_type')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="reset" class="btn-outline">
                                <i class="fas fa-reset"></i> Reset
                            </button>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-check"></i> Create Group
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-card">
                <div class="card-header">
                    <h2>About Transaction Groups</h2>
                </div>
                <div class="card-body">
                    <p>Transaction groups help organize and categorize financial transactions in the system. They define how transactions are processed, displayed, and reported.</p>

                    <div class="info-alert">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <h3>Important Information</h3>
                            <p>Once created, a transaction group's type cannot be changed. Choose carefully.</p>
                        </div>
                    </div>

                    <h3 class="info-title">Transaction Types</h3>
                    <ul class="info-list">
                        <li><strong>Deposit:</strong> Money coming into an account</li>
                        <li><strong>Withdrawal:</strong> Money leaving an account</li>
                        <li><strong>Transfer:</strong> Money moving between accounts</li>
                        <li><strong>Payment:</strong> Payments for bills or services</li>
                        <li><strong>Fee:</strong> Service charges or penalties</li>
                        <li><strong>Interest:</strong> Interest earned or paid</li>
                        <li><strong>Other:</strong> Miscellaneous transactions</li>
                    </ul>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header">
                    <h2>Tips</h2>
                </div>
                <div class="card-body">
                    <ul class="info-list">
                        <li>Use clear, descriptive names for transaction groups</li>
                        <li>Group similar transaction types together</li>
                        <li>Add detailed descriptions to help other administrators</li>
                        <li>Use metadata for advanced configuration options</li>
                    </ul>
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

    .btn-primary, .btn-outline {
        padding: 10px 15px;
        border-radius: 5px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary {
        background-color: #0066ff;
        color: white;
        border: none;
    }

    .btn-outline {
        background-color: transparent;
        color: #555;
        border: 1px solid #ddd;
    }

    .btn-primary:hover {
        background-color: #0052cc;
    }

    .btn-outline:hover {
        background-color: #f5f7fb;
    }

    .group-content {
        display: flex;
        gap: 20px;
    }

    .form-section {
        width: 65%;
    }

    .info-section {
        width: 35%;
    }

    .form-card, .info-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
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

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
        width: 100%;
    }

    .form-row .form-group {
        flex: 1;
    }

    label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
        color: #333;
    }

    .required {
        color: #ea5455;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        border-color: #0066ff;
        outline: none;
    }

    .form-control.is-invalid {
        border-color: #ea5455;
    }

    .error-message {
        color: #ea5455;
        font-size: 12px;
        margin-top: 5px;
    }

    .help-text {
        color: #888;
        font-size: 12px;
        margin-top: 5px;
    }

    .transaction-type-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .type-option {
        position: relative;
    }

    .type-radio {
        position: absolute;
        opacity: 0;
    }

    .type-label {
        display: flex;
        align-items: center;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .type-radio:checked + .type-label {
        border-color: #0066ff;
        background-color: rgba(0, 102, 255, 0.05);
    }

    .type-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-right: 15px;
    }

    .type-icon.deposit {
        background-color: #e3fcef;
        color: #28c76f;
    }

    .type-icon.withdrawal {
        background-color: #fceaea;
        color: #ea5455;
    }

    .type-icon.transfer {
        background-color: #e3f2fd;
        color: #0066ff;
    }

    .type-icon.payment {
        background-color: #fff8e1;
        color: #ff9800;
    }

    .type-name {
        font-weight: 500;
        color: #333;
    }

    .type-desc {
        font-size: 12px;
        color: #888;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 30px;
    }

    .info-alert {
        display: flex;
        padding: 15px;
        background-color: #e3f2fd;
        border-radius: 8px;
        margin: 15px 0;
    }

    .info-alert i {
        font-size: 24px;
        color: #0066ff;
        margin-right: 15px;
    }

    .info-alert h3 {
        font-size: 14px;
        font-weight: 600;
        margin: 0 0 5px 0;
        color: #333;
    }

    .info-alert p {
        font-size: 13px;
        margin: 0;
        color: #555;
    }

    .info-title {
        font-size: 15px;
        font-weight: 600;
        margin: 20px 0 10px 0;
        color: #333;
    }

    .info-list {
        padding-left: 20px;
        margin: 0;
    }

    .info-list li {
        margin-bottom: 8px;
        font-size: 13px;
        color: #555;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Type option selection
        const typeOptions = document.querySelectorAll('.type-option');

        typeOptions.forEach(option => {
            const radio = option.querySelector('.type-radio');

            option.addEventListener('click', function() {
                radio.checked = true;
            });
        });

        // Format JSON in metadata field
        const metadataField = document.getElementById('metadata');

        if (metadataField) {
            metadataField.addEventListener('blur', function() {
                try {
                    if (this.value.trim() !== '') {
                        const formatted = JSON.stringify(JSON.parse(this.value), null, 2);
                        this.value = formatted;
                    }
                } catch (e) {
                    // If not valid JSON, leave as is
                    console.log('Invalid JSON format');
                }
            });
        }

        // Form validation
        const form = document.getElementById('createGroupForm');

        if (form) {
            form.addEventListener('submit', function(event) {
                let isValid = true;

                // Check required fields
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                // Check transaction type is selected
                const transactionTypeSelected = form.querySelector('input[name="transaction_type"]:checked');
                if (!transactionTypeSelected) {
                    document.querySelector('.transaction-type-grid').classList.add('is-invalid');
                    isValid = false;
                } else {
                    document.querySelector('.transaction-type-grid').classList.remove('is-invalid');
                }

                if (!isValid) {
                    event.preventDefault();
                }
            });
        }
    });
</script>
@endsection
