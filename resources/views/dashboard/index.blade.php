@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    <div class="balance-section">
        <h2>Your total balance</h2>
        <p>Take a look at your statistics</p>
        <h1 class="balance">${{ number_format(467635, 0, '.', ',') }}</h1>
        <div class="balance-change positive">
            <i class="fas fa-arrow-up"></i> $2,330.00 (+2.5%)
        </div>
    </div>

    <div class="income-cards">
        <div class="income-card">
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="details">
                <h3>${{ number_format(324435, 0, '.', ',') }}</h3>
                <p>Income from investments</p>
            </div>
        </div>
        <div class="income-card">
            <div class="icon">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="details">
                <h3>${{ number_format(143200, 0, '.', ',') }}</h3>
                <p>Income from accounts</p>
            </div>
        </div>
    </div>

    <div class="money-flow">
        <div class="section-header">
            <div>
                <h2>Money Flow</h2>
                <p>Cost and usage by instant type</p>
            </div>
            <div class="time-filter">
                <span class="time-option">1d</span>
                <span class="time-option">7d</span>
                <span class="time-option active">30d</span>
                <span class="time-option">16m</span>
                <span class="time-option">Max</span>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="moneyFlowChart"></canvas>
        </div>
        <div class="expense-categories">
            <div class="category">
                <div class="dot internet"></div>
                <span>Internet $187</span>
            </div>
            <div class="category">
                <div class="dot food"></div>
                <span>Food $142</span>
            </div>
            <div class="category">
                <div class="dot shopping"></div>
                <span>Shopping $165</span>
            </div>
            <div class="category">
                <div class="dot vacation"></div>
                <span>Vacation $134</span>
            </div>
            <div class="category">
                <div class="dot health"></div>
                <span>Health $169</span>
            </div>
        </div>
    </div>

    <!-- Add recent transactions section -->
    <div class="recent-transactions">
        <div class="section-header">
            <div>
                <h2>Recent Transactions</h2>
                <p>Your latest financial activities</p>
            </div>
            <a href="{{ route('transaction.index') }}" class="view-all">View All</a>
        </div>

        <div class="transactions-preview">
            <!-- Transaction Items -->
            <div class="transaction-preview-item">
                <div class="transaction-icon deposit">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div class="transaction-details">
                    <h4>Salary Deposit</h4>
                    <p>Sep 15, 2023</p>
                </div>
                <div class="transaction-amount positive">
                    +$5,250.00
                </div>
            </div>

            <div class="transaction-preview-item">
                <div class="transaction-icon payment">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="transaction-details">
                    <h4>Amazon.com</h4>
                    <p>Sep 14, 2023</p>
                </div>
                <div class="transaction-amount negative">
                    -$128.50
                </div>
            </div>

            <div class="transaction-preview-item">
                <div class="transaction-icon transfer">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="transaction-details">
                    <h4>Transfer to Savings</h4>
                    <p>Sep 10, 2023</p>
                </div>
                <div class="transaction-amount negative">
                    -$1,000.00
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Money Flow Chart
    const moneyFlowCtx = document.getElementById('moneyFlowChart').getContext('2d');
    const moneyFlowChart = new Chart(moneyFlowCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                type: 'line',
                label: 'Income',
                data: [300, 350, 300, 250, 300, 350, 400, 350, 300, 350, 300, 350],
                borderColor: '#0066ff',
                tension: 0.4,
                fill: false
            }, {
                type: 'line',
                label: 'Expenses',
                data: [250, 300, 250, 200, 250, 300, 350, 300, 250, 300, 250, 300],
                borderColor: '#ffc107',
                tension: 0.4,
                fill: false
            }, {
                type: 'bar',
                label: 'Cash Flow',
                data: [200, 250, 200, 300, 200, 250, 350, 250, 300, 250, 300, 250],
                backgroundColor: '#0066ff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection

@section('styles')
<style>
    .dashboard-container {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 20px;
        padding-bottom: 30px;
        width: 100%;
        max-width: 100%;
    }

    .balance-section {
        grid-column: span 12;
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .income-cards {
        grid-column: span 12;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .money-flow {
        grid-column: span 12;
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .recent-transactions {
        grid-column: span 12;
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .transactions-preview {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 15px;
    }

    .transaction-preview-item {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .transaction-preview-item:last-child {
        border-bottom: none;
    }

    .transaction-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: white;
    }

    .transaction-icon.deposit {
        background-color: #00c853;
    }

    .transaction-icon.payment {
        background-color: #ff3d00;
    }

    .transaction-icon.transfer {
        background-color: #0066ff;
    }

    .transaction-details {
        flex: 1;
    }

    .transaction-details h4 {
        font-size: 14px;
        margin: 0 0 5px 0;
    }

    .transaction-details p {
        font-size: 12px;
        color: #888;
        margin: 0;
    }

    .transaction-amount {
        font-weight: 600;
        font-size: 14px;
    }

    .transaction-amount.positive {
        color: #00c853;
    }

    .transaction-amount.negative {
        color: #ff3d00;
    }

    /* Responsive adjustments for larger screens */
    @media (min-width: 992px) {
        .balance-section {
            grid-column: span 4;
        }

        .income-cards {
            grid-column: span 8;
        }

        .money-flow {
            grid-column: span 8;
        }

        .recent-transactions {
            grid-column: span 4;
        }
    }

    /* Ensure content doesn't overflow */
    .main-content {
        overflow-y: auto;
        padding-bottom: 50px;
    }

    .chart-container {
        height: 300px;
        position: relative;
        width: 100%;
        max-width: 100%;
    }

    /* Make sure the view-all link is styled properly */
    .view-all {
        color: #0066ff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }

    .view-all:hover {
        text-decoration: underline;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
</style>
@endsection
