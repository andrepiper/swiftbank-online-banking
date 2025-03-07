@extends('layouts.app')

@section('title', 'Transaction Groups Management')

@section('content')
<div class="admin-transaction-groups-container">
    <div class="page-header">
        <h1>Transaction Groups Management</h1>
        <p>Manage system transaction groups and categories</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="groups-actions">
        <div class="search-filter">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" id="searchGroups" placeholder="Search groups...">
            </div>

            <div class="filter-dropdown">
                <label for="typeFilter">Filter by Type:</label>
                <select id="typeFilter" class="form-select">
                    <option value="all">All Types</option>
                    <option value="deposit">Deposit</option>
                    <option value="withdrawal">Withdrawal</option>
                    <option value="transfer">Transfer</option>
                    <option value="payment">Payment</option>
                </select>
            </div>

            <div class="filter-dropdown">
                <label for="statusFilter">Status:</label>
                <select id="statusFilter" class="form-select">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <a href="{{ route('admin.transaction-groups.create') }}" class="btn-add-group">
            <i class="fas fa-plus"></i> Add Group
        </a>
    </div>

    <div class="groups-table-container">
            @if($transactionGroups->count() > 0)
        <table class="groups-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Transactions</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
            <tbody>
                        @foreach($transactionGroups as $group)
                <tr data-type="{{ strtolower($group->type) }}" data-status="{{ $group->status }}">
                            <td>
                                <strong>{{ $group->name }}</strong>
                            </td>
                            <td>
                        <span class="type-badge {{ strtolower($group->type) }}">
                                    {{ $group->type }}
                                </span>
                            </td>
                    <td>
                        <span class="transaction-count">{{ $group->transactions_count ?? 0 }}</span>
                    </td>
                            <td>{{ Str::limit($group->description, 50) }}</td>
                            <td>
                        <span class="status-badge {{ $group->status }}">
                                    {{ ucfirst($group->status) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($group->created_at)->format('M d, Y') }}</td>
                            <td class="actions">
                              <a href="{{ route('admin.transaction-groups.show', \App\Helpers\IdObfuscator::encode($group->id)) }}"
                                class="action-btn view" title="View Details">
                                  <i class="fas fa-eye"></i>
                              </a>

                              <a href="{{ route('admin.transaction-groups.edit', \App\Helpers\IdObfuscator::encode($group->id)) }}"
                                class="action-btn edit" title="Edit Group">
                                  <i class="fas fa-edit"></i>
                              </a>

                              <form action="{{ route('admin.transaction-groups.destroy', \App\Helpers\IdObfuscator::encode($group->id)) }}"
                                    method="POST" class="d-inline">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="action-btn delete" title="Deactivate Group"
                                          onclick="return confirm('Are you sure you want to deactivate this group?')">
                                      <i class="fas fa-power-off"></i>
                                  </button>
                              </form>
                          </td>
                </tr>
                        @endforeach
                    </tbody>
                </table>
        <div class="pagination-container">
                {{ $transactionGroups->links() }}
            </div>
            @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-folder-open"></i>
                </div>
                <h5>No transaction groups found</h5>
            <p>Create a new transaction group to get started.</p>
            <a href="{{ route('admin.transaction-groups.create') }}" class="btn-add-group">
                <i class="fas fa-plus"></i> Add Group
                </a>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('styles')
<style>
    .admin-transaction-groups-container {
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

    .groups-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .search-filter {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .search-bar {
        position: relative;
        width: 300px;
    }

    .search-bar input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 1px solid #eee;
        border-radius: 8px;
        background-color: #f5f7fb;
        font-size: 14px;
    }

    .search-bar i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
    }

    .filter-dropdown {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-dropdown label {
        font-size: 14px;
        color: #555;
    }

    .form-select {
        padding: 10px;
        border: 1px solid #eee;
        border-radius: 8px;
        background-color: #f5f7fb;
        font-size: 14px;
        min-width: 150px;
    }

    .btn-add-group {
        background-color: #0066ff;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 15px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-add-group:hover {
        background-color: #0052cc;
        color: white;
    }

    .groups-table-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .groups-table {
        width: 100%;
        border-collapse: collapse;
    }

    .groups-table th,
    .groups-table td {
        padding: 15px 20px;
        text-align: left;
        border-bottom: 1px solid #f0f0f0;
    }

    .groups-table th {
        background-color: #f9f9f9;
        font-weight: 600;
        color: #555;
    }

    .groups-table tr:last-child td {
        border-bottom: none;
    }

    .type-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
    }

    .type-badge.deposit {
        background-color: #e3fcef;
        color: #28c76f;
    }

    .type-badge.withdrawal {
        background-color: #fceaea;
        color: #ea5455;
    }

    .type-badge.transfer {
        background-color: #e3f2fd;
        color: #0066ff;
    }

    .type-badge.payment {
        background-color: #fff8e1;
        color: #ff9800;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.active {
        background-color: #e3fcef;
        color: #28c76f;
    }

    .status-badge.inactive {
        background-color: #fceaea;
        color: #ea5455;
    }

    .transaction-count {
        display: inline-block;
        padding: 3px 8px;
        background-color: #f5f7fb;
        border-radius: 15px;
        font-size: 12px;
        color: #555;
    }

    .actions {
        display: flex;
        gap: 10px;
        min-width: 80px;
        justify-content: center;
    }

    .btn-icon {
        background: none;
        border: none;
        color: #555;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-icon:hover {
        color: #0066ff;
    }

    .btn-icon.delete-group:hover {
        color: #ff3d00;
    }

    .pagination-container {
        padding: 15px 20px;
        display: flex;
        justify-content: center;
    }

    .empty-state {
        padding: 50px 20px;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 60px;
        color: #0066ff;
        margin-bottom: 20px;
    }

    .empty-state h5 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #888;
        margin-bottom: 20px;
    }

    .action-btn {
        background-color: #f5f7fb;
        border: 1px solid #e0e0e0;
        color: #555;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 4px;
        margin: 0 2px;
    }

    .action-btn:hover {
        background-color: #e0e0e0;
    }

    .action-btn.view:hover {
        background-color: #e3f2fd;
        color: #0066ff;
        border-color: #0066ff;
    }

    .action-btn.edit:hover {
        background-color: #e3fcef;
        color: #28c76f;
        border-color: #28c76f;
    }

    .action-btn.delete {
        color: #ea5455;
    }

    .action-btn.delete:hover {
        background-color: #fceaea;
        border-color: #ea5455;
    }

    .text-center {
        text-align: center;
    }

    .d-inline {
        display: inline;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchGroups');
        const groupRows = document.querySelectorAll('.groups-table tbody tr');

        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();

            groupRows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const description = row.cells[3].textContent.toLowerCase();

                if (name.includes(searchTerm) || description.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Type filter functionality
        const typeFilter = document.getElementById('typeFilter');

        typeFilter.addEventListener('change', function() {
            const selectedType = this.value.toLowerCase();

            groupRows.forEach(row => {
                if (selectedType === 'all' || row.getAttribute('data-type') === selectedType) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Status filter functionality
        const statusFilter = document.getElementById('statusFilter');

        statusFilter.addEventListener('change', function() {
            const selectedStatus = this.value.toLowerCase();

            groupRows.forEach(row => {
                if (selectedStatus === 'all' || row.getAttribute('data-status') === selectedStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
