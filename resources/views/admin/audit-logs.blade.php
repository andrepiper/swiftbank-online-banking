@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="audit-logs">
    <div class="page-header">
        <h1>Audit Logs</h1>
    </div>

    <div class="filter-section">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="logSearch" placeholder="Search logs...">
        </div>

        <div class="filter-dropdown">
            <label for="actionFilter">Filter by Action:</label>
            <select id="actionFilter">
                <option value="">All Actions</option>
                <option value="login">Login</option>
                <option value="logout">Logout</option>
                <option value="create">Create</option>
                <option value="update">Update</option>
                <option value="delete">Delete</option>
            </select>
        </div>

        <div class="date-filter">
            <label for="dateFilter">Date Range:</label>
            <input type="date" id="startDate" name="startDate">
            <span>to</span>
            <input type="date" id="endDate" name="endDate">
            <button id="applyDateFilter" class="btn-apply">Apply</button>
        </div>
    </div>

    <div class="logs-container">
        <table class="table" id="logsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($logs) && count($logs) > 0)
                    @foreach($logs as $log)
                    <tr data-action="{{ $log->action }}">
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->user_name ?? 'System' }}</td>
                        <td><span class="action-badge action-{{ $log->action }}">{{ ucfirst($log->action) }}</span></td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->ip_address }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i:s') }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">No audit logs found</td>
                    </tr>
                @endif
            </tbody>
        </table>

        @if(isset($logs) && $logs->hasPages())
        <div class="pagination-container">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Search functionality
    document.getElementById('logSearch').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const table = document.getElementById('logsTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const user = rows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
            const action = rows[i].getElementsByTagName('td')[2].textContent.toLowerCase();
            const description = rows[i].getElementsByTagName('td')[3].textContent.toLowerCase();

            if (user.includes(searchValue) || action.includes(searchValue) || description.includes(searchValue)) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    });

    // Action filter functionality
    document.getElementById('actionFilter').addEventListener('change', function() {
        const filterValue = this.value.toLowerCase();
        const table = document.getElementById('logsTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const action = rows[i].getAttribute('data-action');

            if (!filterValue || action === filterValue) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    });

    // Date filter functionality
    document.getElementById('applyDateFilter').addEventListener('click', function() {
        const startDate = new Date(document.getElementById('startDate').value);
        const endDate = new Date(document.getElementById('endDate').value);

        if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) {
            alert('Please select valid start and end dates');
            return;
        }

        const table = document.getElementById('logsTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const dateStr = rows[i].getElementsByTagName('td')[5].textContent;
            const rowDate = new Date(dateStr);

            if (rowDate >= startDate && rowDate <= endDate) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    });
</script>
@endsection

@section('styles')
<style>
    .audit-logs {
        padding: 20px;
    }

    .page-header {
        margin-bottom: 20px;
    }

    .filter-section {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 20px;
        align-items: center;
    }

    .search-box {
        position: relative;
        flex: 1;
        max-width: 300px;
    }

    .search-box i {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
    }

    .search-box input {
        width: 100%;
        padding: 8px 8px 8px 35px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .filter-dropdown {
        display: flex;
        align-items: center;
    }

    .filter-dropdown label {
        margin-right: 10px;
        color: #666;
    }

    .filter-dropdown select {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .date-filter {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .date-filter input[type="date"] {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .btn-apply {
        background-color: #0077cc;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
    }

    .logs-container {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .table th {
        font-weight: 600;
        color: #666;
    }

    .action-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .action-login {
        background-color: #e3f2fd;
        color: #0077cc;
    }

    .action-logout {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .action-create {
        background-color: #f3e5f5;
        color: #7b1fa2;
    }

    .action-update {
        background-color: #fff8e1;
        color: #ffa000;
    }

    .action-delete {
        background-color: #ffebee;
        color: #c62828;
    }

    .pagination-container {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    .text-center {
        text-align: center;
    }
</style>
@endsection
