<?php

namespace App\Http\Controllers\Web\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Instead of using middleware here, let's check in each method
    }

    /**
     * Show the system settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function systemSettings()
    {
        // Check if user is super admin
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect()->route('login');
        }

        // Get system settings from database or config
        $settings = [
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'app_timezone' => config('app.timezone'),
            'app_locale' => config('app.locale'),
        ];

        return view('admin.system-settings', compact('settings'));
    }

    /**
     * Show the audit logs page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function auditLogs()
    {
        // Check if user is super admin
        if (!auth()->user()->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
        }

        // Get audit logs from database
        $logs = DB::table('audit_logs')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.audit-logs', compact('logs'));
    }

    /**
     * Update system settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSystemSettings(Request $request)
    {
        // Check if user is super admin
        if (!auth()->user()->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
        }

        // Validate the request
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'app_timezone' => 'required|string|in:' . implode(',', timezone_identifiers_list()),
            'app_locale' => 'required|string|in:en,es,fr,de',
            'session_lifetime' => 'required|integer|min:1',
            'session_encrypt' => 'nullable|boolean',
            'force_https' => 'nullable|boolean',
        ]);

        // Update the .env file
        $this->updateEnvironmentFile([
            'APP_NAME' => $request->app_name,
            'APP_URL' => $request->app_url,
            'APP_TIMEZONE' => $request->app_timezone,
            'APP_LOCALE' => $request->app_locale,
            'SESSION_LIFETIME' => $request->session_lifetime,
            'SESSION_ENCRYPT' => $request->has('session_encrypt') ? 'true' : 'false',
        ]);

        // Log the action
        $this->logAuditAction('update', 'Updated system settings');

        return redirect()->route('admin.system-settings')->with('success', 'System settings updated successfully.');
    }

    /**
     * Update the environment file.
     *
     * @param  array  $data
     * @return void
     */
    private function updateEnvironmentFile($data)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $content = file_get_contents($path);

            foreach ($data as $key => $value) {
                // If the value contains spaces, wrap it in quotes
                if (strpos($value, ' ') !== false) {
                    $value = '"' . $value . '"';
                }

                // Replace the value in the .env file
                $content = preg_replace('/^' . $key . '=.*/m', $key . '=' . $value, $content);
            }

            file_put_contents($path, $content);
        }
    }

    /**
     * Log an audit action.
     *
     * @param  string  $action
     * @param  string  $description
     * @return void
     */
    private function logAuditAction($action, $description)
    {
        DB::table('audit_logs')->insert([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->firstname . ' ' . auth()->user()->lastname,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }
}
