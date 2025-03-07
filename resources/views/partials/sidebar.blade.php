<aside class="sidebar">
    <div class="logo-container">
        <div class="logo">
            <i class="fas fa-dove"></i>
        </div>
        <h2>SwiftBank</h2>
    </div>

    <div class="menu-section">
        <h3>Main Menu</h3>
        <ul class="menu-items">
            @if(Auth::user()->isAdmin())
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                </a>
            </li>
            @else
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            @endif

            @if(!Auth::user()->isAdmin())
            <li class="{{ request()->routeIs('accounts') ? 'active' : '' }}">
                <a href="{{ route('accounts') }}">
                    <i class="fas fa-university"></i> Accounts
                </a>
            </li>
            <li class="{{ request()->routeIs('transactions') ? 'active' : '' }}">
                <a href="{{ route('transaction.index') }}">
                    <i class="fas fa-exchange-alt"></i> Transactions
                </a>
            </li>
            <li class="{{ request()->routeIs('transfers') ? 'active' : '' }}">
                <a href="{{ route('transfers') }}">
                    <i class="fas fa-paper-plane"></i> Transfers
                </a>
            </li>
            <li class="{{ request()->routeIs('kyc') ? 'active' : '' }}">
                <a href="{{ route('kyc') }}">
                    <i class="fas fa-id-card"></i> KYC
                </a>
            </li>
            @endif

            @if(Auth::user()->isAdmin())
            <li class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <a href="{{ route('admin.users') }}">
                    <i class="fas fa-users"></i> User Management
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.transaction-groups*') ? 'active' : '' }}">
                <a href="{{ route('admin.transaction-groups') }}">
                    <i class="fas fa-folder"></i> Transaction Groups
                </a>
            </li>
            @endif

            @if(Auth::user()->isSuperAdmin())
            <li class="{{ request()->routeIs('admin.system-settings') ? 'active' : '' }}">
                <a href="{{ route('admin.system-settings') }}">
                    <i class="fas fa-cog"></i> System Settings
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.audit-logs') ? 'active' : '' }}">
                <a href="{{ route('admin.audit-logs') }}">
                    <i class="fas fa-history"></i> Audit Logs
                </a>
            </li>
            @endif

            <li class="{{ request()->routeIs('settings') ? 'active' : '' }}">
                <a href="{{ route('settings') }}">
                    <i class="fas fa-user-cog"></i> Account Settings
                </a>
            </li>
            <li class="logout">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </a>
            </li>
        </ul>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</aside>
