@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
<div class="transactions-container">
    <div class="page-header">
        <h1>Transactions</h1>
        <p>View and manage your transaction history</p>
    </div>

    <div class="transactions-filters">
        <div class="filter-section">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search transactions...">
            </div>

            <div class="filter-buttons">
                <div class="filter-dropdown">
                    <button class="filter-btn">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <div class="filter-dropdown-content">
                        <div class="filter-group">
                            <h4>Transaction Type</h4>
                            <label class="filter-checkbox">
                                <input type="checkbox" checked> All
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox"> Deposits
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox"> Withdrawals
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox"> Transfers
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox"> Payments
                            </label>
                        </div>
                        <div class="filter-group">
                            <h4>Account</h4>
                            <label class="filter-checkbox">
                                <input type="checkbox" checked> All Accounts
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox"> Checking Account
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox"> Savings Account
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox"> Investment Account
                            </label>
                        </div>
                        <div class="filter-actions">
                            <button class="btn-apply">Apply Filters</button>
                            <button class="btn-reset">Reset</button>
                        </div>
                    </div>
                </div>

                <div class="date-range">
                    <button class="date-btn">
                        <i class="fas fa-calendar"></i> Date Range
                    </button>
                    <div class="date-dropdown-content">
                        <div class="date-presets">
                            <button class="date-preset active">Last 7 days</button>
                            <button class="date-preset">Last 30 days</button>
                            <button class="date-preset">Last 90 days</button>
                            <button class="date-preset">This year</button>
                        </div>
                        <div class="custom-date-range">
                            <h4>Custom Range</h4>
                            <div class="date-inputs">
                                <div class="date-field">
                                    <label>From</label>
                                    <input type="date">
                                </div>
                                <div class="date-field">
                                    <label>To</label>
                                    <input type="date">
                                </div>
                            </div>
                        </div>
                        <div class="filter-actions">
                            <button class="btn-apply">Apply Date Range</button>
                            <button class="btn-reset">Reset</button>
                        </div>
                    </div>
                </div>

                <button class="export-btn">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>

        <div class="active-filters">
            <div class="filter-tag">
                Last 30 days <i class="fas fa-times"></i>
            </div>
            <div class="filter-tag">
                All Accounts <i class="fas fa-times"></i>
            </div>
            <div class="filter-tag">
                All Types <i class="fas fa-times"></i>
            </div>
            <button class="clear-all">Clear All</button>
        </div>
    </div>

    <div class="transactions-summary">
        <div class="summary-card income">
            <div class="summary-icon">
                <i class="fas fa-arrow-down"></i>
            </div>
            <div class="summary-details">
                <h3>Income</h3>
                <p>Last 30 days</p>
                <h2>$12,450.00</h2>
            </div>
        </div>

        <div class="summary-card expenses">
            <div class="summary-icon">
                <i class="fas fa-arrow-up"></i>
            </div>
            <div class="summary-details">
                <h3>Expenses</h3>
                <p>Last 30 days</p>
                <h2>$8,250.00</h2>
            </div>
        </div>

        <div class="summary-card net">
            <div class="summary-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="summary-details">
                <h3>Net Flow</h3>
                <p>Last 30 days</p>
                <h2 class="positive">+$4,200.00</h2>
            </div>
        </div>
    </div>

    <div class="transactions-list">
        <div class="transactions-header">
            <div class="transaction-date">Date</div>
            <div class="transaction-description">Description</div>
            <div class="transaction-category">Category</div>
            <div class="transaction-account">Account</div>
            <div class="transaction-amount">Amount</div>
            <div class="transaction-actions">Actions</div>
        </div>

        <!-- Transaction Items -->
        <div class="transaction-item">
            <div class="transaction-date">
                <div class="date-day">15</div>
                <div class="date-month">Sep</div>
            </div>
            <div class="transaction-description">
                <h4>Salary Deposit</h4>
                <p>Monthly salary payment</p>
            </div>
            <div class="transaction-category">
                <span class="category-tag income">Income</span>
            </div>
            <div class="transaction-account">Checking Account</div>
            <div class="transaction-amount positive">+$5,250.00</div>
            <div class="transaction-actions">
                <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>

        <div class="transaction-item">
            <div class="transaction-date">
                <div class="date-day">14</div>
                <div class="date-month">Sep</div>
            </div>
            <div class="transaction-description">
                <h4>Amazon.com</h4>
                <p>Online shopping</p>
            </div>
            <div class="transaction-category">
                <span class="category-tag shopping">Shopping</span>
            </div>
            <div class="transaction-account">Credit Card</div>
            <div class="transaction-amount negative">-$129.99</div>
            <div class="transaction-actions">
                <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>

        <div class="transaction-item">
            <div class="transaction-date">
                <div class="date-day">12</div>
                <div class="date-month">Sep</div>
            </div>
            <div class="transaction-description">
                <h4>Transfer to Savings</h4>
                <p>Monthly savings transfer</p>
            </div>
            <div class="transaction-category">
                <span class="category-tag transfer">Transfer</span>
            </div>
            <div class="transaction-account">Checking Account</div>
            <div class="transaction-amount negative">-$1,000.00</div>
            <div class="transaction-actions">
                <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>

        <div class="transaction-item">
            <div class="transaction-date">
                <div class="date-day">10</div>
                <div class="date-month">Sep</div>
            </div>
            <div class="transaction-description">
                <h4>Starbucks Coffee</h4>
                <p>Food & Beverage</p>
            </div>
            <div class="transaction-category">
                <span class="category-tag food">Food</span>
            </div>
            <div class="transaction-account">Debit Card</div>
            <div class="transaction-amount negative">-$5.75</div>
            <div class="transaction-actions">
                <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>

        <div class="transaction-item">
            <div class="transaction-date">
                <div class="date-day">08</div>
                <div class="date-month">Sep</div>
            </div>
            <div class="transaction-description">
                <h4>Netflix Subscription</h4>
                <p>Entertainment</p>
            </div>
            <div class="transaction-category">
                <span class="category-tag entertainment">Entertainment</span>
            </div>
            <div class="transaction-account">Credit Card</div>
            <div class="transaction-amount negative">-$14.99</div>
            <div class="transaction-actions">
                <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>

        <div class="transaction-item">
            <div class="transaction-date">
                <div class="date-day">05</div>
                <div class="date-month">Sep</div>
            </div>
            <div class="transaction-description">
                <h4>Apartment Rent</h4>
                <p>Monthly rent payment</p>
            </div>
            <div class="transaction-category">
                <span class="category-tag housing">Housing</span>
            </div>
            <div class="transaction-account">Checking Account</div>
            <div class="transaction-amount negative">-$1,850.00</div>
            <div class="transaction-actions">
                <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>

        <div class="transaction-item">
            <div class="transaction-date">
                <div class="date-day">03</div>
                <div class="date-month">Sep</div>
            </div>
            <div class="transaction-description">
                <h4>Interest Payment</h4>
                <p>Savings account interest</p>
            </div>
            <div class="transaction-category">
                <span class="category-tag interest">Interest</span>
            </div>
            <div class="transaction-account">Savings Account</div>
            <div class="transaction-amount positive">+$32.45</div>
            <div class="transaction-actions">
                <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>

        <div class="transaction-item">
            <div class="transaction-date">
                <div class="date-day">01</div>
                <div class="date-month">Sep</div>
            </div>
            <div class="transaction-description">
                <h4>Gym Membership</h4>
                <p>Monthly subscription</p>
            </div>
            <div class="transaction-category">
                <span class="category-tag health">Health</span>
            </div>
            <div class="transaction-account">Credit Card</div>
            <div class="transaction-amount negative">-$49.99</div>
            <div class="transaction-actions">
                <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>

        <!-- Add these transactions items after the existing ones -->

        <div class="transaction-item">
            <div class="transaction-date">
                <div class="date-day">12</div>
                <div class="date-month">Sep</div>
            </div>
            <div class="transaction-description">
                <h4>Starbucks Coffee</h4>
                <p>Coffee shop</p>
            </div>
            <div class="transaction-category">
                <span class="category-tag food">Food & Drink</span>
            </div>
            <div class="transaction-account">Checking Account</div>
            <div class="transaction-amount negative">-$4.75</div>
            <div class="transaction-actions">
                <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>

        <div class="transaction-item">
            <div class="transaction-date">
                <div class="date-day">10</div>
                <div class="date-month">Sep</div>
            </div>
            <div class="transaction-description">
                <h4>Transfer to Savings</h4>
                <p>Monthly savings transfer</p>
            </div>
            <div class="transaction-category">
                <span class="category-tag transfer">Transfer</span>
            </div>
            <div class="transaction-account">Checking Account</div>
            <div class="transaction-amount negative">-$1,000.00</div>
            <div class="transaction-actions">
                <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>

        <div class="transaction-item">
            <div class="transaction-date">
                <div class="date-day">10</div>
                <div class="date-month">Sep</div>
            </div>
            <div class="transaction-description">
                <h4>Transfer from Checking</h4>
                <p>Monthly savings transfer</p>
            </div>
            <div class="transaction-category">
                <span class="category-tag transfer">Transfer</span>
            </div>
            <div class="transaction-account">Savings Account</div>
            <div class="transaction-amount positive">+$1,000.00</div>
            <div class="transaction-actions">
                <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>

        <div class="transaction-item">
            <div class="transaction-date">
                <div class="date-day">08</div>
                <div class="date-month">Sep</div>
            </div>
            <div class="transaction-description">
                <h4>Whole Foods Market</h4>
                <p>Groceries</p>
            </div>
            <div class="transaction-category">
                <span class="category-tag shopping">Shopping</span>
            </div>
            <div class="transaction-account">Checking Account</div>
            <div class="transaction-amount negative">-$87.32</div>
            <div class="transaction-actions">
                <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>

        <div class="transaction-item">
            <div class="transaction-date">
                <div class="date-day">05</div>
                <div class="date-month">Sep</div>
            </div>
            <div class="transaction-description">
                <h4>Netflix Subscription</h4>
                <p>Monthly subscription</p>
            </div>
            <div class="transaction-category">
                <span class="category-tag entertainment">Entertainment</span>
            </div>
            <div class="transaction-account">Checking Account</div>
            <div class="transaction-amount negative">-$14.99</div>
            <div class="transaction-actions">
                <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>

        <div class="transaction-item">
            <div class="transaction-date">
                <div class="date-day">03</div>
                <div class="date-month">Sep</div>
            </div>
            <div class="transaction-description">
                <h4>Apartment Rent</h4>
                <p>Monthly rent payment</p>
            </div>
            <div class="transaction-category">
                <span class="category-tag housing">Housing</span>
            </div>
            <div class="transaction-account">Checking Account</div>
            <div class="transaction-amount negative">-$1,850.00</div>
            <div class="transaction-actions">
                <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>

        <div class="transaction-item">
            <div class="transaction-date">
                <div class="date-day">01</div>
                <div class="date-month">Sep</div>
            </div>
            <div class="transaction-description">
                <h4>Interest Payment</h4>
                <p>Monthly interest</p>
            </div>
            <div class="transaction-category">
                <span class="category-tag interest">Interest</span>
            </div>
            <div class="transaction-account">Savings Account</div>
            <div class="transaction-amount positive">+$32.41</div>
            <div class="transaction-actions">
                <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>

        <div class="transaction-item">
            <div class="transaction-date">
                <div class="date-day">28</div>
                <div class="date-month">Aug</div>
            </div>
            <div class="transaction-description">
                <h4>Uber Ride</h4>
                <p>Transportation</p>
            </div>
            <div class="transaction-category">
                <span class="category-tag transportation">Transportation</span>
            </div>
            <div class="transaction-account">Checking Account</div>
            <div class="transaction-amount negative">-$24.50</div>
            <div class="transaction-actions">
                <button class="btn-icon"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>
    </div>

    <div class="pagination-container">
        <div class="pagination-info">
            Showing 1-10 of 48 transactions
        </div>
        <div class="pagination">
            <button class="page-btn disabled">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <div class="page-ellipsis">...</div>
            <button class="page-btn">5</button>
            <button class="page-btn">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .transactions-container {
        padding-bottom: 30px;
    }

    .page-header {
        margin-bottom: 20px;
    }

    .page-header h1 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .page-header p {
        color: #666;
        margin: 0;
    }

    .transactions-filters {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .filter-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .search-bar {
        position: relative;
        flex: 1;
        max-width: 300px;
    }

    .search-bar input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        font-size: 14px;
    }

    .search-bar i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
    }

    .filter-btn, .date-btn, .export-btn {
        padding: 10px 15px;
        background-color: #f5f7fb;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .filter-btn:hover, .date-btn:hover, .export-btn:hover {
        background-color: #e9ecef;
    }

    .filter-dropdown, .date-range {
        position: relative;
    }

    .filter-dropdown-content, .date-dropdown-content {
        position: absolute;
        top: 100%;
        right: 0;
        width: 300px;
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 15px;
        z-index: 10;
        display: none;
    }

    .filter-dropdown:hover .filter-dropdown-content,
    .date-range:hover .date-dropdown-content {
        display: block;
    }

    .filter-group {
        margin-bottom: 15px;
    }

    .filter-group h4 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .filter-checkbox {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .filter-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
    }

    .btn-apply, .btn-reset {
        padding: 8px 15px;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
    }

    .btn-apply {
        background-color: #0066ff;
        color: white;
        border: none;
    }

    .btn-reset {
        background-color: transparent;
        border: 1px solid #e0e0e0;
    }

    .date-presets {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 15px;
    }

    .date-preset {
        padding: 8px 12px;
        background-color: #f5f7fb;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        font-size: 13px;
        cursor: pointer;
    }

    .date-preset.active {
        background-color: #0066ff;
        color: white;
        border-color: #0066ff;
    }

    .custom-date-range h4 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .date-inputs {
        display: flex;
        gap: 10px;
    }

    .date-field {
        flex: 1;
    }

    .date-field label {
        display: block;
        font-size: 13px;
        margin-bottom: 5px;
    }

    .date-field input {
        width: 100%;
        padding: 8px;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        font-size: 14px;
    }

    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .filter-tag {
        padding: 5px 10px;
        background-color: #f5f7fb;
        border-radius: 20px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .filter-tag i {
        cursor: pointer;
        font-size: 12px;
    }

    .clear-all {
        background: none;
        border: none;
        color: #0066ff;
        font-size: 13px;
        cursor: pointer;
    }

    .transactions-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .summary-card {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .summary-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
    }

    .summary-card.income .summary-icon {
        background-color: #00c853;
    }

    .summary-card.expenses .summary-icon {
        background-color: #ff3d00;
    }

    .summary-card.net .summary-icon {
        background-color: #0066ff;
    }

    .summary-details {
        flex: 1;
    }

    .summary-details h3 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .summary-details p {
        font-size: 12px;
        color: #888;
        margin-bottom: 5px;
    }

    .summary-details h2 {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }

    .summary-details h2.positive {
        color: #00c853;
    }

    .summary-details h2.negative {
        color: #ff3d00;
    }

    .transactions-list {
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .transactions-header {
        display: grid;
        grid-template-columns: 80px 2fr 1fr 1fr 1fr 80px;
        padding: 15px 20px;
        background-color: #f5f7fb;
        font-weight: 600;
        font-size: 14px;
        border-bottom: 1px solid #e0e0e0;
    }

    .transaction-item {
        display: grid;
        grid-template-columns: 80px 2fr 1fr 1fr 1fr 80px;
        padding: 15px 20px;
        align-items: center;
        border-bottom: 1px solid #f0f0f0;
    }

    .transaction-item:last-child {
        border-bottom: none;
    }

    .transaction-date {
        text-align: center;
    }

    .date-day {
        font-size: 18px;
        font-weight: 600;
    }

    .date-month {
        font-size: 13px;
        color: #888;
    }

    .transaction-description h4 {
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 3px;
    }

    .transaction-description p {
        font-size: 12px;
        color: #888;
        margin: 0;
    }

    .category-tag {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .category-tag.income {
        background-color: rgba(0, 200, 83, 0.1);
        color: #00c853;
    }

    .category-tag.shopping {
        background-color: rgba(255, 152, 0, 0.1);
        color: #ff9800;
    }

    .category-tag.transfer {
        background-color: rgba(0, 102, 255, 0.1);
        color: #0066ff;
    }

    .category-tag.food {
        background-color: #ff9800;
    }

    .category-tag.entertainment {
        background-color: #9c27b0;
    }

    .category-tag.housing {
        background-color: #795548;
    }

    .category-tag.interest {
        background-color: #4caf50;
    }

    .category-tag.health {
        background-color: rgba(233, 30, 99, 0.1);
        color: #e91e63;
    }

    .transaction-account {
        font-size: 14px;
        color: #555;
    }

    .transaction-amount {
        font-size: 14px;
        font-weight: 600;
    }

    .transaction-amount.positive {
        color: #00c853;
    }

    .transaction-amount.negative {
        color: #ff3d00;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: transparent;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #888;
    }

    .btn-icon:hover {
        background-color: #f5f7fb;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
    }

    .page-btn {
        width: 36px;
        height: 36px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: white;
        border: 1px solid #e0e0e0;
        font-size: 14px;
        cursor: pointer;
    }

    .page-btn.active {
        background-color: #0066ff;
        color: white;
        border-color: #0066ff;
    }

    .page-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .page-ellipsis {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    @media (max-width: 992px) {
        .transactions-header, .transaction-item {
            grid-template-columns: 80px 2fr 1fr 1fr 80px;
        }

        .transaction-category {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .filter-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .search-bar {
            max-width: 100%;
            width: 100%;
        }

        .filter-buttons {
            width: 100%;
            justify-content: space-between;
        }

        .transactions-header, .transaction-item {
            grid-template-columns: 80px 2fr 1fr 80px;
        }

        .transaction-account {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .transactions-header, .transaction-item {
            grid-template-columns: 60px 2fr 1fr;
        }

        .transaction-actions {
            display: none;
        }

        .date-day {
            font-size: 16px;
        }

        .date-month {
            font-size: 12px;
        }
    }

    .category-tag.food {
        background-color: #ff9800;
    }

    .category-tag.entertainment {
        background-color: #9c27b0;
    }

    .category-tag.housing {
        background-color: #795548;
    }

    .category-tag.transportation {
        background-color: #607d8b;
    }

    .category-tag.interest {
        background-color: #4caf50;
    }

    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    .pagination-info {
        font-size: 14px;
        color: #666;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter dropdown toggle
        const filterBtn = document.querySelector('.filter-btn');
        const filterDropdown = document.querySelector('.filter-dropdown-content');

        if (filterBtn && filterDropdown) {
            filterBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                filterDropdown.style.display = filterDropdown.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', function(e) {
                if (!filterDropdown.contains(e.target) && e.target !== filterBtn) {
                    filterDropdown.style.display = 'none';
                }
            });
        }

        // Date range dropdown toggle
        const dateBtn = document.querySelector('.date-btn');
        const dateDropdown = document.querySelector('.date-dropdown-content');

        if (dateBtn && dateDropdown) {
            dateBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dateDropdown.style.display = dateDropdown.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', function(e) {
                if (!dateDropdown.contains(e.target) && e.target !== dateBtn) {
                    dateDropdown.style.display = 'none';
                }
            });
        }

        // Date preset selection
        const datePresets = document.querySelectorAll('.date-preset');

        if (datePresets.length) {
            datePresets.forEach(preset => {
                preset.addEventListener('click', function() {
                    datePresets.forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        }

        // Filter tag removal
        const filterTags = document.querySelectorAll('.filter-tag i');

        if (filterTags.length) {
            filterTags.forEach(tag => {
                tag.addEventListener('click', function() {
                    this.parentElement.remove();
                });
            });
        }

        // Clear all filters
        const clearAllBtn = document.querySelector('.clear-all');

        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', function() {
                document.querySelectorAll('.filter-tag').forEach(tag => {
                    tag.remove();
                });
            });
        }

        // Pagination handling
        const pageButtons = document.querySelectorAll('.page-btn:not(.disabled)');

        if (pageButtons.length) {
            pageButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (!this.classList.contains('active')) {
                        document.querySelector('.page-btn.active').classList.remove('active');
                        this.classList.add('active');

                        // Here you would typically fetch new data for the selected page
                        // For demo purposes, we're just changing the active state
                    }
                });
            });
        }
    });
</script>
@endsection
