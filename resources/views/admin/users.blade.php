@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="admin-users-container">
    <div class="page-header">
        <h1>User Management</h1>
        <p>Manage system users and permissions</p>
    </div>

    <div class="users-actions">
        <div class="search-filter">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" id="searchUsers" placeholder="Search users...">
            </div>

            <div class="filter-dropdown">
                <label for="roleFilter">Filter by Role:</label>
                <select id="roleFilter" class="form-select">
                    <option value="all">All Roles</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                    <option value="super_admin">Super Admin</option>
                </select>
            </div>
        </div>

        <button class="btn-add-user" id="showAddUserForm">
            <i class="fas fa-plus"></i> Add User
        </button>
    </div>

    <div class="users-table-container">
        <table class="users-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Andre Piper</td>
                    <td>andre@user.com</td>
                    <td><span class="role-badge user">USER</span></td>
                    <td>Feb 25, 2025</td>
                    <td class="actions">
                        <button class="btn-icon edit-user" title="Edit User">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon delete-user" title="Delete User">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                <!-- Add more user rows as needed -->
            </tbody>
        </table>
    </div>

    <!-- Add User Form Modal -->
    <div class="modal" id="addUserModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New User</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstname" required>
                    </div>

                    <div class="form-group">
                        <label for="middleName">Middle Name (Optional)</label>
                        <input type="text" id="middleName" name="middlename">
                    </div>

                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastname" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-cancel">Cancel</button>
                        <button type="submit" class="btn-submit">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .admin-users-container {
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

    .users-actions {
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

    .btn-add-user {
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
    }

    .btn-add-user:hover {
        background-color: #0052cc;
    }

    .users-table-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table th,
    .users-table td {
        padding: 15px 20px;
        text-align: left;
        border-bottom: 1px solid #f0f0f0;
    }

    .users-table th {
        background-color: #f9f9f9;
        font-weight: 600;
        color: #555;
    }

    .users-table tr:last-child td {
        border-bottom: none;
    }

    .role-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
    }

    .role-badge.user {
        background-color: #e3f2fd;
        color: #0066ff;
    }

    .role-badge.admin {
        background-color: #fff8e1;
        color: #ff9800;
    }

    .role-badge.super_admin {
        background-color: #fce4ec;
        color: #e91e63;
    }

    .actions {
        display: flex;
        gap: 10px;
    }

    .btn-icon {
        background: none;
        border: none;
        color: #555;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.2s;
    }

    .btn-icon:hover {
        color: #0066ff;
    }

    .btn-icon.delete-user:hover {
        color: #ff3d00;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: white;
        border-radius: 10px;
        width: 500px;
        max-width: 90%;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .modal-header h2 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #888;
    }

    .modal-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
        color: #555;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-cancel {
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        color: #555;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-submit {
        background-color: #0066ff;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-submit:hover {
        background-color: #0052cc;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal functionality
        const modal = document.getElementById('addUserModal');
        const showModalBtn = document.getElementById('showAddUserForm');
        const closeModalBtn = document.querySelector('.close-modal');
        const cancelBtn = document.querySelector('.btn-cancel');

        showModalBtn.addEventListener('click', function() {
            modal.style.display = 'flex';
        });

        closeModalBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        cancelBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Search functionality
        const searchInput = document.getElementById('searchUsers');
        const userRows = document.querySelectorAll('.users-table tbody tr');

        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();

            userRows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();

                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Role filter functionality
        const roleFilter = document.getElementById('roleFilter');

        roleFilter.addEventListener('change', function() {
            const selectedRole = this.value.toLowerCase();

            userRows.forEach(row => {
                const role = row.cells[2].textContent.toLowerCase();

                if (selectedRole === 'all' || role.includes(selectedRole)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
