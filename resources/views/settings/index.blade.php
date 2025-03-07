@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="settings-container">
    <div class="page-header">
        <h1>Account Settings</h1>
        <p>Manage your account information and preferences</p>
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

    <div class="settings-tabs">
        <div class="tab-navigation">
            <button class="tab-btn active" data-tab="profile">Profile Information</button>
            <button class="tab-btn" data-tab="security">Security</button>
            <button class="tab-btn" data-tab="notifications">Notifications</button>
            <button class="tab-btn" data-tab="preferences">Preferences</button>
        </div>

        <div class="tab-content">
            <!-- Profile Tab -->
            <div class="tab-pane active" id="profile">
                <form action="{{ route('settings.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="profile-header">
                        <div class="profile-picture">
                            <img src="{{ Auth::user()->profile_picture_url }}" alt="Profile Picture" id="profile-preview">
                            <div class="upload-overlay">
                                <label for="profile_picture">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" style="display: none;">
                            </div>
                        </div>
                        <div class="profile-info">
                            <h2>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h2>
                            <p>{{ Auth::user()->email }}</p>
                            <p class="role-badge">{{ ucfirst(strtolower(Auth::user()->role)) }}</p>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text" id="firstname" name="firstname" value="{{ Auth::user()->firstname }}" required>
                        </div>
                        <div class="form-group">
                            <label for="middlename">Middle Name (Optional)</label>
                            <input type="text" id="middlename" name="middlename" value="{{ Auth::user()->middlename }}">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" id="lastname" name="lastname" value="{{ Auth::user()->lastname }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ Auth::user()->phone ?? '' }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="{{ Auth::user()->address ?? '' }}">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" value="{{ Auth::user()->city ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label for="state">State/Province</label>
                            <input type="text" id="state" name="state" value="{{ Auth::user()->state ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label for="zip">Zip/Postal Code</label>
                            <input type="text" id="zip" name="zip" value="{{ Auth::user()->zip ?? '' }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="country">Country</label>
                        <select id="country" name="country">
                            <option value="">Select Country</option>
                            <option value="US" {{ (Auth::user()->country ?? '') == 'US' ? 'selected' : '' }}>United States</option>
                            <option value="CA" {{ (Auth::user()->country ?? '') == 'CA' ? 'selected' : '' }}>Canada</option>
                            <option value="UK" {{ (Auth::user()->country ?? '') == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                            <option value="AU" {{ (Auth::user()->country ?? '') == 'AU' ? 'selected' : '' }}>Australia</option>
                            <!-- Add more countries as needed -->
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">Save Changes</button>
                    </div>
                </form>
            </div>

            <!-- Security Tab -->
            <div class="tab-pane" id="security">
                <form action="{{ route('settings.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>

                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">Update Password</button>
                    </div>
                </form>

                <div class="security-section">
                    <h3>Two-Factor Authentication</h3>
                    <p>Add an extra layer of security to your account by enabling two-factor authentication.</p>
                    <button class="btn-enable-2fa">Enable Two-Factor Authentication</button>
                </div>

                <div class="security-section">
                    <h3>Login Sessions</h3>
                    <p>Manage your active login sessions and sign out from other devices.</p>
                    <button class="btn-manage-sessions">Manage Sessions</button>
                </div>
            </div>

            <!-- Notifications Tab -->
            <div class="tab-pane" id="notifications">
                <form action="{{ route('settings.notifications.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="notification-option">
                        <div class="notification-info">
                            <h3>Email Notifications</h3>
                            <p>Receive email notifications about account activity and transactions.</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="email_notifications" {{ Auth::user()->email_notifications ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="notification-option">
                        <div class="notification-info">
                            <h3>SMS Notifications</h3>
                            <p>Receive text message alerts for important account updates.</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="sms_notifications" {{ Auth::user()->sms_notifications ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="notification-option">
                        <div class="notification-info">
                            <h3>Push Notifications</h3>
                            <p>Receive push notifications on your mobile device.</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="push_notifications" {{ Auth::user()->push_notifications ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="notification-option">
                        <div class="notification-info">
                            <h3>Transaction Alerts</h3>
                            <p>Get notified about new transactions in your account.</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="transaction_alerts" {{ Auth::user()->transaction_alerts ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">Save Preferences</button>
                    </div>
                </form>
            </div>

            <!-- Preferences Tab -->
            <div class="tab-pane" id="preferences">
                <form action="{{ route('settings.preferences.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="language">Language</label>
                        <select id="language" name="language">
                            <option value="en" {{ (Auth::user()->language ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                            <option value="es" {{ (Auth::user()->language ?? 'en') == 'es' ? 'selected' : '' }}>Spanish</option>
                            <option value="fr" {{ (Auth::user()->language ?? 'en') == 'fr' ? 'selected' : '' }}>French</option>
                            <option value="de" {{ (Auth::user()->language ?? 'en') == 'de' ? 'selected' : '' }}>German</option>
                            <!-- Add more languages as needed -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="timezone">Timezone</label>
                        <select id="timezone" name="timezone">
                            <option value="UTC" {{ (Auth::user()->timezone ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="America/New_York" {{ (Auth::user()->timezone ?? 'UTC') == 'America/New_York' ? 'selected' : '' }}>Eastern Time (US & Canada)</option>
                            <option value="America/Chicago" {{ (Auth::user()->timezone ?? 'UTC') == 'America/Chicago' ? 'selected' : '' }}>Central Time (US & Canada)</option>
                            <option value="America/Denver" {{ (Auth::user()->timezone ?? 'UTC') == 'America/Denver' ? 'selected' : '' }}>Mountain Time (US & Canada)</option>
                            <option value="America/Los_Angeles" {{ (Auth::user()->timezone ?? 'UTC') == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (US & Canada)</option>
                            <!-- Add more timezones as needed -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="currency">Default Currency</label>
                        <select id="currency" name="currency">
                            <option value="USD" {{ (Auth::user()->currency ?? 'USD') == 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                            <option value="EUR" {{ (Auth::user()->currency ?? 'USD') == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                            <option value="GBP" {{ (Auth::user()->currency ?? 'USD') == 'GBP' ? 'selected' : '' }}>British Pound (GBP)</option>
                            <option value="CAD" {{ (Auth::user()->currency ?? 'USD') == 'CAD' ? 'selected' : '' }}>Canadian Dollar (CAD)</option>
                            <!-- Add more currencies as needed -->
                        </select>
                    </div>

                    <div class="notification-option">
                        <div class="notification-info">
                            <h3>Dark Mode</h3>
                            <p>Switch between light and dark theme.</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="dark_mode" {{ Auth::user()->dark_mode ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">Save Preferences</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching functionality
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons and panes
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));

                // Add active class to clicked button and corresponding pane
                this.classList.add('active');
                document.getElementById(this.dataset.tab).classList.add('active');
            });
        });

        // Profile picture preview
        const profileInput = document.getElementById('profile_picture');
        const profilePreview = document.getElementById('profile-preview');

        if (profileInput && profilePreview) {
            profileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        profilePreview.src = e.target.result;
                    }

                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
</script>
@endsection

@section('styles')
<style>
    .settings-container {
        max-width: 100%;
        margin-bottom: 30px;
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

    .settings-tabs {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .tab-navigation {
        display: flex;
        border-bottom: 1px solid #eee;
        background-color: #f9f9f9;
    }

    .tab-btn {
        padding: 15px 20px;
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 500;
        color: #555;
        transition: all 0.2s;
        position: relative;
    }

    .tab-btn:hover {
        color: #0066ff;
    }

    .tab-btn.active {
        color: #0066ff;
    }

    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: #0066ff;
    }

    .tab-content {
        padding: 30px;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    .profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
    }

    .profile-picture {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        position: relative;
        margin-right: 20px;
    }

    .profile-picture img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .upload-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        text-align: center;
        padding: 5px 0;
        cursor: pointer;
        transition: all 0.2s;
    }

    .upload-overlay:hover {
        background-color: rgba(0, 0, 0, 0.7);
    }

    .profile-info h2 {
        font-size: 20px;
        margin-bottom: 5px;
    }

    .profile-info p {
        color: #888;
        margin: 0 0 5px;
    }

    .role-badge {
        display: inline-block;
        background-color: #e3f2fd;
        color: #0066ff;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 500;
    }

    .form-row {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }

    .form-group {
        flex: 1;
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
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

    .form-group input:focus,
    .form-group select:focus {
        border-color: #0066ff;
        outline: none;
    }

    .form-actions {
        margin-top: 20px;
    }

    .btn-save {
        background-color: #0066ff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-save:hover {
        background-color: #0052cc;
    }

    .security-section {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .security-section h3 {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .security-section p {
        color: #888;
        margin-bottom: 15px;
    }

    .btn-enable-2fa,
    .btn-manage-sessions {
        background-color: #f5f5f5;
        color: #333;
        border: 1px solid #ddd;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-enable-2fa:hover,
    .btn-manage-sessions:hover {
        background-color: #e9e9e9;
    }

    .notification-option {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }

    .notification-option:last-child {
        border-bottom: none;
    }

    .notification-info h3 {
        font-size: 16px;
        margin-bottom: 5px;
    }

    .notification-info p {
        color: #888;
        margin: 0;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background-color: #0066ff;
    }

    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    .alert {
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 5px;
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

    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 0;
        }

        .tab-navigation {
            flex-wrap: wrap;
        }

        .tab-btn {
            flex: 1;
            min-width: 120px;
            text-align: center;
        }
    }
</style>
@endsection
