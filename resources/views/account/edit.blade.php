@extends('layouts.app')

@section('title', 'Edit Account')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">Edit Account</h1>
        <p class="page-subtitle">Update your account details</p>
    </div>

    <form action="{{ route('account.update', $account->obfuscated_id) }}" method="POST" class="account-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="account_name">Account Name</label>
            <input type="text" id="account_name" name="account_name" value="{{ old('account_name', $account->account_name) }}" required>
            @error('account_name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="account_type_id">Account Type</label>
            <select id="account_type_id" name="account_type_id" required>
                <option value="">Select Account Type</option>
                @foreach($accountTypes as $type)
                    <option value="{{ $type->id }}" {{ old('account_type_id', $account->account_type_id) == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('account_type_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="currency">Currency</label>
            <select id="currency" name="currency" required>
                <option value="USD" {{ old('currency', $account->currency) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                <option value="EUR" {{ old('currency', $account->currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                <option value="GBP" {{ old('currency', $account->currency) == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                <option value="CAD" {{ old('currency', $account->currency) == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                <option value="AUD" {{ old('currency', $account->currency) == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
            </select>
            @error('currency')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="ACTIVE" {{ old('status', $account->status) == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                <option value="INACTIVE" {{ old('status', $account->status) == 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
                <option value="FROZEN" {{ old('status', $account->status) == 'FROZEN' ? 'selected' : '' }}>Frozen</option>
            </select>
            @error('status')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('account.show', $account->obfuscated_id) }}" class="back-link">← Back to Account</a>
            <button type="submit" class="btn-primary">Update Account</button>
        </div>
    </form>

    <div class="danger-zone">
        <h3>Danger Zone</h3>
        <div class="danger-action">
            <div>
                <h4>Close Account</h4>
                <p>Once you close an account, there is no going back. Please be certain.</p>
            </div>
            <button type="button" class="btn-danger" id="closeAccountBtn">
                Close Account
            </button>
        </div>
    </div>
</div>

<!-- Close Account Modal -->
<div class="modal fade" id="closeAccountModal" tabindex="-1" aria-labelledby="closeAccountModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="closeAccountModalLabel">Close Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
    .container-fluid {
        padding: 30px;
        max-width: 100%;
        background-color: #f8f9fa;
        min-height: calc(100vh - 60px);
    }

    .page-header {
        margin-bottom: 30px;
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

    .account-form {
        max-width: 600px;
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 15px;
        transition: border-color 0.2s;
    }

    .form-group input[type="text"]:focus,
    .form-group input[type="number"]:focus,
    .form-group select:focus {
        border-color: #0066ff;
        outline: none;
        box-shadow: 0 0 0 2px rgba(0, 102, 255, 0.1);
    }

    .error-message {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        align-items: center;
        margin-top: 30px;
    }

    .back-link {
        color: #555;
        text-decoration: none;
        margin-right: auto;
        display: flex;
        align-items: center;
    }

    .back-link:hover {
        color: #333;
    }

    .btn-primary {
        background-color: #0066ff;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-primary:hover {
        background-color: #0052cc;
    }

    .danger-zone {
        margin-top: 40px;
        border-top: 1px solid #eee;
        padding-top: 20px;
        max-width: 600px;
    }

    .danger-zone h3 {
        color: #dc3545;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .danger-action {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fff8f8;
        border: 1px solid #ffebee;
        border-radius: 4px;
        padding: 15px;
    }

    .danger-action h4 {
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .danger-action p {
        color: #666;
        margin: 0;
        font-size: 14px;
        max-width: 400px;
    }

    .btn-danger {
        background-color: #fff;
        color: #dc3545;
        border: 1px solid #dc3545;
        border-radius: 4px;
        padding: 8px 16px;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-danger:hover {
        background-color: #dc3545;
        color: white;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 20px;
        }

        .account-form {
            padding: 20px;
        }
    }
</style>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var closeAccountModal = new bootstrap.Modal(document.getElementById('closeAccountModal'));

        document.getElementById('closeAccountBtn').addEventListener('click', function() {
            closeAccountModal.show();
        });

        document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function(button) {
            button.addEventListener('click', function() {
                closeAccountModal.hide();
            });
        });
    });
</script>
@endsection
