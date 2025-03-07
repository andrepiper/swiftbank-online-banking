@extends('layouts.app')

@section('title', 'My Accounts')

@section('content')
<div class="accounts-container">
    <div class="page-header">
        <h1>My Accounts</h1>
        <p>Manage your bank accounts and cards</p>
    </div>

    <div class="accounts-overview">
        <div class="section-header">
            <h2>Accounts Overview</h2>
            <a href="{{ route('account.create') }}" class="btn-add-account">
                <i class="fas fa-plus"></i> Add New Account
            </a>
        </div>

        <div class="accounts-grid">
            @forelse($accounts as $account)
            <div class="account-card">
                <div class="account-header">
                    <div class="account-type {{ strtolower($account->account_type_name) }}">
                        <i class="fas {{ $account->account_type_name == 'Checking' ? 'fa-wallet' : ($account->account_type_name == 'Savings' ? 'fa-piggy-bank' : 'fa-chart-line') }}"></i>
                    </div>
                    <div class="account-actions">
                        <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                </div>
                <div class="account-details">
                    <h3>{{ $account->account_name }}</h3>
                    <p class="account-number">**** {{ substr($account->account_number, -4) }}</p>
                    <div class="account-balance">
                        <h4>${{ number_format($account->balance, 2, '.', ',') }}</h4>
                        <span class="balance-change {{ $account->balance >= 0 ? 'positive' : 'negative' }}">
                            <i class="fas {{ $account->balance >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                            {{ rand(1, 5) }}.{{ rand(0, 9) }}%
                        </span>
                    </div>
                </div>
                <div class="account-footer">
                    <a href="{{ route('account.show', app('App\Helpers\IdObfuscator')->encode($account->id)) }}" class="btn-transfer">
                        <i class="fas fa-exchange-alt"></i> Transfer
                    </a>
                    <a href="{{ route('account.show', app('App\Helpers\IdObfuscator')->encode($account->id)) }}" class="btn-details">
                        <i class="fas fa-info-circle"></i> Details
                    </a>
                </div>
            </div>
            @empty
            <div class="no-accounts">
                <p>You don't have any accounts yet. Click "Add New Account" to get started.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="cards-section">
        <div class="section-header">
            <h2>My Cards</h2>
            <button class="btn-add-card">
                <i class="fas fa-plus"></i> Add New Card
            </button>
        </div>

        <div class="cards-slider">
            <!-- Credit Card -->
            <div class="card-item credit-card">
                <div class="card-header">
                    <div class="card-logo">
                        <i class="fas fa-dove"></i> SWIFTBANK
                    </div>
                    <div class="card-chip">
                        <i class="fas fa-microchip"></i>
                    </div>
                </div>
                <div class="card-number">5022 **** **** 1246</div>
                <div class="card-details">
                    <div class="card-holder">
                        <span>CARD HOLDER</span>
                        <div>{{ Auth::user()->name }}</div>
                    </div>
                    <div class="card-expires">
                        <span>EXPIRES</span>
                        <div>09/24</div>
                    </div>
                    <div class="card-brand">
                        <span>VISA</span>
                    </div>
                </div>
            </div>

            <!-- Debit Card -->
            <div class="card-item debit-card">
                <div class="card-header">
                    <div class="card-logo">
                        <i class="fas fa-dove"></i> SWIFTBANK
                    </div>
                    <div class="card-chip">
                        <i class="fas fa-microchip"></i>
                    </div>
                </div>
                <div class="card-number">4211 **** **** 5467</div>
                <div class="card-details">
                    <div class="card-holder">
                        <span>CARD HOLDER</span>
                        <div>{{ Auth::user()->name }}</div>
                    </div>
                    <div class="card-expires">
                        <span>EXPIRES</span>
                        <div>11/25</div>
                    </div>
                    <div class="card-brand">
                        <span>MASTERCARD</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="recent-activity">
        <div class="section-header">
            <h2>Recent Activity</h2>
        </div>

        <div class="activity-list">
            @forelse($recentTransactions as $transaction)
            <div class="activity-item">
                <div class="activity-icon {{ strtolower($transaction->transaction_type) }}">
                    <i class="fas {{ $transaction->icon }}"></i>
                </div>
                <div class="activity-details">
                    <h4>{{ $transaction->description }}</h4>
                    <p>{{ $transaction->account_name }} **** {{ substr($transaction->account_number, -4) }}</p>
                </div>
                <div class="activity-amount {{ $transaction->display_amount > 0 ? 'positive' : ($transaction->display_amount < 0 ? 'negative' : 'neutral') }}">
                    <h4>{{ $transaction->display_amount > 0 ? '+' : '' }}${{ number_format(abs($transaction->display_amount), 2, '.', ',') }}</h4>
                    <p>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, g:i A') }}</p>
                </div>
            </div>
            @empty
            <div class="no-transactions">
                <p>No recent transactions found.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .accounts-container {
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

    .btn-add-account,
    .btn-add-card {
        background-color: #0066ff;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 15px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-add-account:hover,
    .btn-add-card:hover {
        background-color: #0052cc;
    }

    .accounts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .account-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
    }

    .account-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .account-type {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    .account-type.checking {
        background-color: #0066ff;
    }

    .account-type.savings {
        background-color: #00c853;
    }

    .account-type.investment {
        background-color: #ff9800;
    }

    .btn-icon {
        background: none;
        border: none;
        color: #888;
        cursor: pointer;
        font-size: 16px;
    }

    .account-details h3 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .account-number {
        color: #888;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .account-balance {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .account-balance h4 {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }

    .balance-change {
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .balance-change.positive {
        color: #00c853;
    }

    .balance-change.negative {
        color: #ff3d00;
    }

    .account-footer {
        display: flex;
        gap: 10px;
    }

    .btn-transfer,
    .btn-details {
        flex: 1;
        padding: 8px;
        border: 1px solid #ddd;
        background-color: #f5f5f5;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        transition: all 0.2s;
        text-decoration: none;
        color: #333;
    }

    .btn-transfer:hover,
    .btn-details:hover {
        background-color: #e9e9e9;
    }

    .cards-section {
        width: 100%;
        margin-top: 30px;
    }

    .cards-slider {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        padding: 10px 0;
        width: 100%;
    }

    .card-item {
        min-width: 340px;
        height: 200px;
        border-radius: 16px;
        padding: 20px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .credit-card {
        background: linear-gradient(135deg, #0066ff 0%, #2c7be5 100%);
    }

    .debit-card {
        background: linear-gradient(135deg, #00c853 0%, #009624 100%);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .card-logo {
        font-weight: 600;
        font-size: 14px;
        letter-spacing: 1px;
    }

    .card-logo i {
        margin-right: 5px;
    }

    .card-chip {
        font-size: 18px;
    }

    .card-number {
        font-size: 18px;
        letter-spacing: 2px;
        margin-bottom: 30px;
        font-weight: 500;
    }

    .card-details {
        display: flex;
        justify-content: space-between;
    }

    .card-holder,
    .card-expires,
    .card-brand {
        display: flex;
        flex-direction: column;
    }

    .card-holder span,
    .card-expires span,
    .card-brand span {
        font-size: 10px;
        opacity: 0.7;
        margin-bottom: 5px;
    }

    .recent-activity {
        width: 100%;
        margin-top: 30px;
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .view-all {
        color: #0066ff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .activity-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .activity-icon.deposit {
        background-color: #00c853;
    }

    .activity-icon.payment {
        background-color: #ff3d00;
    }

    .activity-icon.transfer {
        background-color: #0066ff;
    }

    .activity-icon.interest {
        background-color: #9c27b0;
    }

    .activity-icon.withdrawal {
        background-color: #ff3d00;
    }

    .activity-details {
        flex: 1;
    }

    .activity-details h4 {
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 3px;
    }

    .activity-details p {
        font-size: 12px;
        color: #888;
        margin: 0;
    }

    .activity-amount {
        text-align: right;
    }

    .activity-amount h4 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .activity-amount.positive h4 {
        color: #00c853;
    }

    .activity-amount.negative h4 {
        color: #ff3d00;
    }

    .activity-amount.neutral h4 {
        color: #0066ff;
    }

    .activity-amount p {
        font-size: 12px;
        color: #888;
        margin: 0;
    }

    .no-accounts,
    .no-transactions {
        padding: 20px;
        text-align: center;
        background-color: #f9f9f9;
        border-radius: 10px;
        color: #666;
    }

    @media (max-width: 768px) {
        .accounts-grid {
            grid-template-columns: 1fr;
        }

        .cards-slider {
            padding-bottom: 15px;
        }
    }
</style>
@endsection
