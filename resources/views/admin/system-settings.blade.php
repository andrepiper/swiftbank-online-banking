@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="system-settings">
    <div class="page-header">
        <h1>System Settings</h1>
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

    <div class="settings-container">
        <form action="{{ route('admin.system-settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="settings-section">
                <h2>Application Settings</h2>

                <div class="form-group">
                    <label for="app_name">Application Name</label>
                    <input type="text" id="app_name" name="app_name" value="{{ $settings['app_name'] }}">
                </div>

                <div class="form-group">
                    <label for="app_url">Application URL</label>
                    <input type="text" id="app_url" name="app_url" value="{{ $settings['app_url'] }}">
                </div>

                <div class="form-group">
                    <label for="app_timezone">Timezone</label>
                    <select id="app_timezone" name="app_timezone">
                        @foreach(timezone_identifiers_list() as $timezone)
                        <option value="{{ $timezone }}" {{ $settings['app_timezone'] === $timezone ? 'selected' : '' }}>
                            {{ $timezone }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="app_locale">Default Locale</label>
                    <select id="app_locale" name="app_locale">
                        <option value="en" {{ $settings['app_locale'] === 'en' ? 'selected' : '' }}>English</option>
                        <option value="es" {{ $settings['app_locale'] === 'es' ? 'selected' : '' }}>Spanish</option>
                        <option value="fr" {{ $settings['app_locale'] === 'fr' ? 'selected' : '' }}>French</option>
                        <option value="de" {{ $settings['app_locale'] === 'de' ? 'selected' : '' }}>German</option>
                    </select>
                </div>
            </div>

            <div class="settings-section">
                <h2>Security Settings</h2>

                <div class="form-group">
                    <label for="session_lifetime">Session Lifetime (minutes)</label>
                    <input type="number" id="session_lifetime" name="session_lifetime" value="120">
                </div>

                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="session_encrypt" name="session_encrypt" value="1" checked>
                        <label for="session_encrypt">Encrypt Session</label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="force_https" name="force_https" value="1" checked>
                        <label for="force_https">Force HTTPS</label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">Save Settings</button>
                <button type="reset" class="btn-reset">Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
    .system-settings {
        padding: 20px;
    }

    .page-header {
        margin-bottom: 20px;
    }

    .settings-container {
        background-color: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .settings-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .settings-section h2 {
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 20px;
        color: #333;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
    }

    .checkbox-group input[type="checkbox"] {
        margin-right: 10px;
    }

    .checkbox-group label {
        margin-bottom: 0;
    }

    .form-actions {
        display: flex;
        gap: 15px;
    }

    .btn-save {
        background-color: #0077cc;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-reset {
        background-color: #f5f5f5;
        color: #333;
        border: 1px solid #ddd;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
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
</style>
@endsection
