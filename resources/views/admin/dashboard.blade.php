@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="admin-dashboard">
    <div class="page-header">
        <h1>Admin Dashboard</h1>
        <p>Welcome to the administration panel, {{ Auth::user()->firstname }}.</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- Admin Stats Cards -->
    <div class="stats-cards">
        <div class="stat-card users">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $totalUsers }}</h3>
                <p>Total Users</p>
            </div>
        </div>

        <div class="stat-card new-users">
            <div class="stat-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $newUsers }}</h3>
                <p>New Users (Last 7 Days)</p>
            </div>
        </div>

        <div class="stat-card transactions">
            <div class="stat-icon">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $totalTransactions ?? 0 }}</h3>
                <p>Total Transactions</p>
            </div>
        </div>

        <div class="stat-card active-sessions">
            <div class="stat-icon">
                <i class="fas fa-desktop"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $activeSessions ?? 0 }}</h3>
                <p>Active Sessions</p>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="recent-activity">
        <div class="section-header">
            <h2>Recent Activity</h2>
            <a href="{{ route('admin.audit-logs') }}" class="view-all">View All</a>
        </div>

        <div class="activity-list">
            @if(isset($recentLogs) && count($recentLogs) > 0)
                @foreach($recentLogs as $log)
                <div class="activity-item">
                    <div class="activity-icon {{ $log->action }}">
                        @if($log->action == 'login')
                            <i class="fas fa-sign-in-alt"></i>
                        @elseif($log->action == 'logout')
                            <i class="fas fa-sign-out-alt"></i>
                        @elseif($log->action == 'create')
                            <i class="fas fa-plus"></i>
                        @elseif($log->action == 'update')
                            <i class="fas fa-edit"></i>
                        @elseif($log->action == 'delete')
                            <i class="fas fa-trash"></i>
                        @else
                            <i class="fas fa-info-circle"></i>
                        @endif
                    </div>
                    <div class="activity-details">
                        <p class="activity-description">{{ $log->description }}</p>
                        <div class="activity-meta">
                            <span class="activity-user">{{ $log->user_name ?? 'System' }}</span>
                            <span class="activity-time">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="no-activity">
                    <p>No recent activity found.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <div class="section-header">
            <h2>Quick Actions</h2>
        </div>

        <div class="action-buttons">
            <a href="{{ route('admin.users') }}" class="action-button">
                <i class="fas fa-user-cog"></i>
                <span>Manage Users</span>
            </a>

            @if(Auth::user()->isSuperAdmin())
            <a href="{{ route('admin.system-settings') }}" class="action-button">
                <i class="fas fa-cogs"></i>
                <span>System Settings</span>
            </a>

            <a href="{{ route('admin.audit-logs') }}" class="action-button">
                <i class="fas fa-history"></i>
                <span>View Audit Logs</span>
            </a>
            @endif

            <a href="#" class="action-button" id="backupSystemBtn">
                <i class="fas fa-database"></i>
                <span>Backup System</span>
            </a>
        </div>
    </div>
</div>

<!-- Backup Modal -->
<div class="modal" id="backupModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Backup System</h2>
            <button class="close-modal">×</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to create a system backup? This process may take a few minutes.</p>
            <div class="form-actions">
                <button type="button" class="btn-cancel">Cancel</button>
                <button type="button" class="btn-submit">Create Backup</button>
            </div>
        </div>
    </div>
</div>

<style>
    .admin-dashboard {
        padding: 20px;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-header h1 {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 5px;
        color: #333;
    }

    .page-header p {
        color: #666;
        font-size: 16px;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 20px;
        color: white;
    }

    .stat-card.users .stat-icon {
        background-color: #4a7fff;
    }

    .stat-card.new-users .stat-icon {
        background-color: #00c853;
    }

    .stat-card.transactions .stat-icon {
        background-color: #ff9800;
    }

    .stat-card.active-sessions .stat-icon {
        background-color: #9c27b0;
    }

    .stat-details h3 {
        font-size: 24px;
        font-weight: 600;
        margin: 0 0 5px;
        color: #333;
    }

    .stat-details p {
        margin: 0;
        color: #666;
        font-size: 14px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .section-header h2 {
        font-size: 20px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .view-all {
        color: #4a7fff;
        text-decoration: none;
        font-size: 14px;
    }

    .recent-activity, .quick-actions {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .activity-list {
        margin-top: 15px;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 14px;
        color: white;
    }

    .activity-icon.login {
        background-color: #4a7fff;
    }

    .activity-icon.logout {
        background-color: #00c853;
    }

    .activity-icon.create {
        background-color: #9c27b0;
    }

    .activity-icon.update {
        background-color: #ff9800;
    }

    .activity-icon.delete {
        background-color: #f44336;
    }

    .activity-details {
        flex: 1;
    }

    .activity-description {
        margin: 0 0 5px;
        color: #333;
    }

    .activity-meta {
        display: flex;
        font-size: 12px;
        color: #666;
    }

    .activity-user {
        margin-right: 10px;
    }

    .no-activity {
        padding: 20px 0;
        text-align: center;
        color: #666;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .action-button {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
        text-decoration: none;
        color: #333;
        transition: all 0.2s;
    }

    .action-button:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
    }

    .action-button i {
        font-size: 24px;
        margin-bottom: 10px;
        color: #4a7fff;
    }

    .alert {
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }

    .alert-success {
        background-color: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }

    .alert-danger {
        background-color: #ffebee;
        color: #c62828;
        border: 1px solid #ffcdd2;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .modal.show {
        display: flex;
    }

    .modal-content {
        background-color: white;
        border-radius: 8px;
        width: 500px;
        max-width: 90%;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #666;
    }

    .modal-body {
        padding: 20px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        gap: 10px;
    }

    .btn-cancel {
        padding: 8px 16px;
        background-color: #f1f1f1;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-submit {
        padding: 8px 16px;
        background-color: #4a7fff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const backupBtn = document.getElementById('backupSystemBtn');
        const backupModal = document.getElementById('backupModal');
        const closeModalBtn = backupModal.querySelector('.close-modal');
        const cancelBtn = backupModal.querySelector('.btn-cancel');
        const submitBtn = backupModal.querySelector('.btn-submit');

        // Open modal
        backupBtn.addEventListener('click', function(e) {
            e.preventDefault();
            backupModal.classList.add('show');
        });

        // Close modal functions
        function closeModal() {
            backupModal.classList.remove('show');
        }

        closeModalBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        // Handle backup creation
        submitBtn.addEventListener('click', function() {
            // Add your backup logic here
            alert('Backup process started!');
            closeModal();
        });
    });
</script>
@endsection
