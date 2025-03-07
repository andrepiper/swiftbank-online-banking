@extends('layouts.app')

@section('title', 'Edit Transaction Group')

@section('content')
<div class="admin-transaction-group-container">
    <div class="page-header">
        <div class="header-content">
            <h1>Edit Transaction Group</h1>
            <p>
                <a href="{{ route('admin.transaction-groups') }}" class="text-muted">Transaction Groups</a> /
                <a href="{{ route('admin.transaction-groups.show', $transactionGroup->obfuscated_id) }}" class="text-muted">{{ $transactionGroup->name }}</a> /
                Edit
            </p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.transaction-groups.show', $transactionGroup->obfuscated_id) }}" class="btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Details
            </a>
            <a href="{{ route('admin.transaction-groups') }}" class="btn-outline">
                <i class="fas fa-list"></i> All Groups
            </a>
        </div>
    </div>

    <div class="group-content">
        <div class="form-section">
            <div class="form-card">
                <div class="card-header">
                    <h2>Edit Group Information</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.transaction-groups.update', $transactionGroup->obfuscated_id) }}" method="POST" id="editGroupForm">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Group Name <span class="required">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name', $transactionGroup->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div class="help-text">Choose a descriptive name for this transaction group</div>
                            </div>

                            <div class="form-group">
                                <label for="type">Group Type</label>
                                <input type="text" class="form-control" value="{{ $transactionGroup->type }}" disabled>
                                <input type="hidden" name="type" value="{{ $transactionGroup->type }}">
                                <div class="help-text">Group type cannot be changed after creation</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $transactionGroup->description) }}</textarea>
                            @error('description')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                            <div class="help-text">Provide a detailed description of this transaction group's purpose</div>
                        </div>

                        <div class="form-group">
                            <label for="metadata">Metadata (JSON)</label>
                            <textarea id="metadata" name="metadata" class="form-control @error('metadata') is-invalid @enderror" rows="4" placeholder='{"key": "value"}'>{{ old('metadata', $transactionGroup->metadata) }}</textarea>
                            @error('metadata')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                            <div class="help-text">Optional JSON metadata for additional configuration</div>
                        </div>

                        <div class="form-group">
                            <label>Transaction Type <span class="required">*</span></label>
                            <div class="transaction-type-grid">
                                <div class="type-option" data-type="deposit">
                                    <input type="radio" name="transaction_type" id="type_deposit" value="deposit" class="type-radio" {{ strtolower($transactionGroup->type) == 'deposit' ? 'checked' : '' }}>
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
                                    <input type="radio" name="transaction_type" id="type_withdrawal" value="withdrawal" class="type-radio" {{ strtolower($transactionGroup->type) == 'withdrawal' ? 'checked' : '' }}>
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
                                    <input type="radio" name="transaction_type" id="type_transfer" value="transfer" class="type-radio" {{ strtolower($transactionGroup->type) == 'transfer' ? 'checked' : '' }}>
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
                                    <input type="radio" name="transaction_type" id="type_payment" value="payment" class="type-radio" {{ strtolower($transactionGroup->type) == 'payment' ? 'checked' : '' }}>
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
                                <i class="fas fa-redo"></i> Reset
                            </button>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-card">
                <div class="card-header">
                    <h2>Group Status</h2>
                </div>
                <div class="card-body">
                    <div class="status-display">
                        <div class="status-icon {{ $transactionGroup->status }}">
                            <i class="fas {{ $transactionGroup->status === 'active' ? 'fa-check-circle' : 'fa-x-circle' }}"></i>
                        </div>
                        <div class="status-info">
                            <div class="status-name">{{ ucfirst($transactionGroup->status) }}</div>
                            <div class="status-meta">Last updated {{ \Carbon\Carbon::parse($transactionGroup->updated_at)->diffForHumans() }}</div>
                        </div>
                    </div>

                    @if($transactionGroup->status === 'active')
                    <form action="{{ route('admin.transaction-groups.destroy', $transactionGroup->obfuscated_id) }}" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger-outline w-100" onclick="return confirm('Are you sure you want to deactivate this group?')">
                            <i class="fas fa-power-off"></i> Deactivate Group
                        </button>
                        <div class="help-text text-center mt-2">
                            Deactivating will prevent new transactions from using this group.
                        </div>
                    </form>
                    @else
                    <a href="#" class="btn-success-outline w-100 mt-4">
                        <i class="fas fa-check-circle"></i> Reactivate Group
                    </a>
                    <div class="help-text text-center mt-2">
                        Reactivating will allow new transactions to use this group.
                    </div>
                    @endif
                </div>
            </div>

            <div class="info-card">
                <div class="card-header">
                    <h2>Group Information</h2>
                </div>
                <div class="card-body">
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
                            <div class="info-label">Transactions</div>
                            <div class="info-value">{{ $transactionGroup->transactions_count ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header">
                    <h2>Tips</h2>
                </div>
                <div class="card-body">
                    <ul class="info-list">
                        <li>Use clear, descriptive names for transaction groups</li>
                        <li>Add detailed descriptions to help other administrators</li>
                        <li>Use metadata for advanced configuration options</li>
                        <li>Group type cannot be changed after creation</li>
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

    .btn-primary, .btn-outline, .btn-danger-outline, .btn-success-outline {
        padding: 10px 15px;
        border-radius: 5px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
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

    .btn-danger-outline {
        background-color: transparent;
        color: #ea5455;
        border: 1px solid #ea5455;
    }

    .btn-success-outline {
        background-color: transparent;
        color: #28c76f;
        border: 1px solid #28c76f;
    }

    .btn-primary:hover {
        background-color: #0052cc;
    }

    .btn-outline:hover {
        background-color: #f5f7fb;
    }

    .btn-danger-outline:hover {
        background-color: rgba(234, 84, 85, 0.1);
    }

    .btn-success-outline:hover {
        background-color: rgba(40, 199, 111, 0.1);
    }

    .w-100 {
        width: 100%;
    }

    .mt-4 {
        margin-top: 20px;
    }

    .mt-2 {
        margin-top: 10px;
    }

    .text-center {
        text-align: center;
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

    .form-control:disabled {
        background-color: #f5f7fb;
        cursor: not-allowed;
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

    .status-display {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .status-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 15px;
    }

    .status-icon.active {
        background-color: #e3fcef;
        color: #28c76f;
    }

    .status-icon.inactive {
        background-color: #fceaea;
        color: #ea5455;
    }

    .status-info {
        flex: 1;
    }

    .status-name {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .status-meta {
        font-size: 12px;
        color: #888;
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
        // Copy to clipboard function
        window.copyToClipboard = function(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Copied to clipboard!');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        };

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
        const form = document.getElementById('editGroupForm');

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
