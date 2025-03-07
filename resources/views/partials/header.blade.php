<div class="header">
    <div class="welcome-section">
        <h1>Hey {{ Auth::user()->firstname }} 👋</h1>
        <p>Welcome to your SwiftBank transaction partner</p>
    </div>

    <div class="search-bar">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search...">
    </div>

    <div class="user-actions">
        <a href="#" class="action-icon">
            <i class="far fa-envelope"></i>
        </a>
        <a href="#" class="action-icon">
            <i class="far fa-bell"></i>
        </a>
        <div class="user-profile">
            <div class="user-info">
                <h4>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h4>
                <p>{{ Auth::user()->role ? ucfirst(strtolower(Auth::user()->role)) : 'User' }}</p>
            </div>
            <img src="{{ Auth::user()->profile_picture_url }}" alt="User Profile" class="user-avatar">
        </div>
    </div>
</div>
