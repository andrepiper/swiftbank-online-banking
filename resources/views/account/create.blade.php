@extends('layouts.app')

@section('title', 'Create New Account')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">Create New Account</h1>
        <p class="page-subtitle">Set up a new bank account</p>
    </div>

    <form action="{{ route('account.store') }}" method="POST" class="account-form">
        @csrf

        <div class="form-group">
            <label for="account_name">Account Name</label>
            <input type="text" id="account_name" name="account_name" value="{{ old('account_name') }}" required>
            @error('account_name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="account_type_id">Account Type</label>
            <select id="account_type_id" name="account_type_id" required>
                <option value="">Select Account Type</option>
                @foreach($accountTypes as $type)
                    <option value="{{ $type->id }}" {{ old('account_type_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('account_type_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="initial_balance">Initial Balance</label>
            <div class="currency-input">
                <span class="currency-symbol">$</span>
                <input type="number" id="initial_balance" name="initial_balance" value="{{ old('initial_balance', '0.00') }}"
                       step="0.01" min="0" style="color: #000;padding-left: 25px;" required>
            </div>
            @error('initial_balance')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="currency">Currency</label>
            <select id="currency" name="currency" required>
                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                <option value="CAD" {{ old('currency') == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                <option value="AUD" {{ old('currency') == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
            </select>
            @error('currency')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('accounts') }}" class="back-link">← Back to Accounts</a>
            <button type="submit" class="btn-primary">Create Account</button>
        </div>
    </form>
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

    .currency-input {
        position: relative;
    }

    .currency-symbol {
        position: absolute;
        left: 15px;
        top: 13px;
        color: #555;
    }

    .currency-input input {
        padding-left: 30px;
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
