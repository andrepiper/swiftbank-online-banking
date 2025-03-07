@extends('layouts.app')

@section('title', $account->account_name . ' - Statistics')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">{{ $account->account_name }} - Statistics</h1>
        <p class="page-subtitle">Account Number: **** **** **** {{ substr($account->account_number, -4) }}</p>
    </div>

    <div class="account-actions-top">
        <a href="{{ route('account.show', $account->obfuscated_id) }}" class="action-link">← Back to Account</a>
        <a href="{{ route('account.edit', $account->obfuscated_id) }}" class="action-link">Edit Account</a>
    </div>

    <div class="account-type-badge {{ strtolower($account->account_type_name) }}">
        <i class="fas {{ $account->account_type_name == 'Checking' ? 'fa-wallet' : ($account->account_type_name == 'Savings' ? 'fa-piggy-bank' : 'fa-chart-line') }}"></i>
        {{ $account->account_type_name }} Account
    </div>

    <div class="statistics-summary">
        <div class="summary-item">
            <div class="summary-label">Current Balance</div>
            <div class="summary-value">${{ number_format($account->balance, 2, '.', ',') }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Total Deposits</div>
            <div class="summary-value">${{ number_format($totalDeposits, 2, '.', ',') }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Total Withdrawals</div>
            <div class="summary-value">${{ number_format($totalWithdrawals, 2, '.', ',') }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Average Transaction</div>
            <div class="summary-value">${{ number_format($averageTransaction, 2, '.', ',') }}</div>
        </div>
    </div>

    <div class="statistics-section">
        <div class="section-header">
            <h2>Money Flow</h2>
            <div class="time-filter">
                <button class="time-btn active" data-period="30d">30d</button>
                <button class="time-btn" data-period="90d">90d</button>
                <button class="time-btn" data-period="1y">1y</button>
                <button class="time-btn" data-period="all">All</button>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="moneyFlowChart"></canvas>
        </div>
    </div>

    <div class="statistics-section">
        <div class="section-header">
            <h2>Transaction Categories</h2>
        </div>

        <div class="chart-container">
            <canvas id="categoriesChart"></canvas>
        </div>
    </div>

    <div class="statistics-section">
        <div class="section-header">
            <h2>Monthly Activity</h2>
        </div>

        <div class="chart-container">
            <canvas id="monthlyActivityChart"></canvas>
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

    .statistics-summary {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .summary-item {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .summary-label {
        font-size: 14px;
        color: #666;
        margin-bottom: 8px;
    }

    .summary-value {
        font-size: 24px;
        font-weight: 600;
        color: #333;
    }

    .statistics-section {
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

    .time-filter {
        display: flex;
        gap: 5px;
    }

    .time-btn {
        background-color: #f0f0f0;
        border: none;
        border-radius: 5px;
        padding: 6px 12px;
        font-size: 13px;
        color: #555;
        cursor: pointer;
        transition: all 0.2s;
    }

    .time-btn:hover {
        background-color: #e0e0e0;
    }

    .time-btn.active {
        background-color: #0066ff;
        color: white;
    }

    .chart-container {
        height: 300px;
        position: relative;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 20px;
        }

        .statistics-summary {
            grid-template-columns: 1fr;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .time-filter {
            margin-top: 10px;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Money Flow Chart
        const moneyFlowCtx = document.getElementById('moneyFlowChart').getContext('2d');
        const moneyFlowChart = new Chart(moneyFlowCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Income',
                        data: [1200, 1900, 1500, 2200, 1800, 2500, 3000, 2400, 2800, 2600, 2900, 2500],
                        borderColor: '#0066ff',
                        backgroundColor: 'rgba(0, 102, 255, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Expenses',
                        data: [800, 1200, 950, 1500, 1300, 1800, 2100, 1600, 1900, 1700, 2000, 1800],
                        borderColor: '#ff9800',
                        backgroundColor: 'rgba(255, 152, 0, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Categories Chart
        const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
        const categoriesChart = new Chart(categoriesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Housing', 'Food', 'Transportation', 'Entertainment', 'Utilities', 'Other'],
                datasets: [{
                    data: [35, 20, 15, 10, 15, 5],
                    backgroundColor: [
                        '#0066ff',
                        '#00c853',
                        '#ff9800',
                        '#9c27b0',
                        '#f44336',
                        '#607d8b'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        // Monthly Activity Chart
        const monthlyActivityCtx = document.getElementById('monthlyActivityChart').getContext('2d');
        const monthlyActivityChart = new Chart(monthlyActivityCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Number of Transactions',
                    data: [15, 22, 18, 25, 20, 28, 32, 24, 30, 26, 29, 24],
                    backgroundColor: '#0066ff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Time filter functionality
        const timeButtons = document.querySelectorAll('.time-btn');
        timeButtons.forEach(button => {
            button.addEventListener('click', function() {
                timeButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Here you would update the charts based on the selected time period
                // For demonstration purposes, we're just showing the UI interaction
            });
        });
    });
</script>
@endsection
