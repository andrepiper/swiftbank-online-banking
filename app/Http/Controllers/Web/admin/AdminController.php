<?php

namespace App\Http\Controllers\Web\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\ObfuscatesIds;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    use ObfuscatesIds;

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
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Check if user is admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login');
        }

        // Get stats for admin dashboard
        $totalUsers = DB::table('users')->count();
        $newUsers = DB::table('users')->where('created_at', '>=', now()->subDays(7))->count();
        $recentLogs = DB::table('audit_logs')->orderBy('created_at', 'desc')->limit(5)->get();
        $totalTransactions = DB::table('transactions')->count() ?? 0;
        $activeSessions = DB::table('sessions')->where('last_activity', '>=', now()->subMinutes(5)->getTimestamp())->count() ?? 0;

        $user = Auth::user(); // Get the authenticated user
        $profilePicture = $user->profile_picture; // Assuming this is the path to the profile picture

        return view('admin.dashboard', compact('totalUsers', 'newUsers', 'recentLogs', 'totalTransactions', 'activeSessions', 'profilePicture'));
    }

    /**
     * Show the user management page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function users()
    {
        // Get all users except the current user
        $users = DB::table('users')
            ->where('id', '!=', auth()->id())
            ->get();

        return view('admin.users', compact('users'));
    }

    /**
     * Show the form for editing a user.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editUser($id)
    {
        $user = DB::table('users')->find($id);

        // Check if the user exists
        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'User not found.');
        }

        // Check if the current user is trying to edit a super admin
        if ($user->role === User::ROLE_SUPER_ADMIN && auth()->user()->role !== User::ROLE_SUPER_ADMIN) {
            return redirect()->route('admin.users')->with('error', 'You do not have permission to edit a Super Admin.');
        }

        return view('admin.edit-user', compact('user'));
    }

    /**
     * Update the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request, $id)
    {
        $user = DB::table('users')->find($id);

        // Check if the user exists
        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'User not found.');
        }

        // Check if the current user is trying to update a super admin
        if ($user->role === User::ROLE_SUPER_ADMIN && auth()->user()->role !== User::ROLE_SUPER_ADMIN) {
            return redirect()->route('admin.users')->with('error', 'You do not have permission to update a Super Admin.');
        }

        // Validate the request
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'role' => ['required', 'string', 'in:USER,ADMIN,SUPER_ADMIN'],
        ]);

        // Check if the current user is trying to set a user as super admin
        if ($request->role === User::ROLE_SUPER_ADMIN && auth()->user()->role !== User::ROLE_SUPER_ADMIN) {
            return redirect()->route('admin.users')->with('error', 'You do not have permission to set a user as Super Admin.');
        }

        // Update the user
        DB::table('users')
            ->where('id', $id)
            ->update([
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'role' => $request->role,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    /**
     * Delete a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUser($id)
    {
        $user = DB::table('users')->find($id);

        // Check if the user exists
        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'User not found.');
        }

        // Check if the current user is trying to delete a super admin
        if ($user->role === User::ROLE_SUPER_ADMIN && auth()->user()->role !== User::ROLE_SUPER_ADMIN) {
            return redirect()->route('admin.users')->with('error', 'You do not have permission to delete a Super Admin.');
        }

        // Delete the user
        DB::table('users')->where('id', $id)->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeUser(Request $request)
    {
        // Validate the request
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:USER,ADMIN,SUPER_ADMIN'],
        ]);

        // Check if the current user is trying to create a super admin
        if ($request->role === User::ROLE_SUPER_ADMIN && auth()->user()->role !== User::ROLE_SUPER_ADMIN) {
            return redirect()->route('admin.users')->with('error', 'You do not have permission to create a Super Admin.');
        }

        // Create the user
        $userId = DB::table('users')->insertGetId([
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Log the action
        $this->logAuditAction('create', 'Created user: ' . $request->firstname . ' ' . $request->lastname);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
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

    /**
     * Show the transaction groups management page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function transactionGroups()
    {
        // Check if user is admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login');
        }

        $transactionGroups = DB::table('transaction_groups')
            ->select('transaction_groups.*', DB::raw('COUNT(transactions.id) as transactions_count'))
            ->leftJoin('transactions', 'transaction_groups.id', '=', 'transactions.transaction_group_id')
            ->groupBy('transaction_groups.id', 'transaction_groups.name', 'transaction_groups.type', 'transaction_groups.description', 'transaction_groups.metadata', 'transaction_groups.status', 'transaction_groups.created_at', 'transaction_groups.updated_at')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.transaction-groups.index', compact('transactionGroups'));
    }

    /**
     * Show the form for creating a new transaction group.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function createTransactionGroup()
    {
        // Check if user is admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login');
        }

        return view('admin.transaction-groups.create');
    }

    /**
     * Store a newly created transaction group in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTransactionGroup(Request $request)
    {
        // Check if user is admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'type' => 'required|string|max:50',
            'metadata' => 'nullable|json'
        ]);

        $id = (string) Str::uuid();

        DB::table('transaction_groups')->insert([
            'id' => $id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'status' => 'active',
            'metadata' => $validated['metadata'] ?? null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Log the action
        $this->logAuditAction('create', 'Created transaction group: ' . $validated['name']);

        return redirect()->route('admin.transaction-groups')
            ->with('success', 'Transaction group created successfully');
    }

    /**
     * Display the specified transaction group.
     *
     * @param  string  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showTransactionGroup($id)
    {
        // Check if user is admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login');
        }

        $realId = $this->deobfuscateId($id);

        if (!$realId) {
            abort(404);
        }

        $transactionGroup = DB::table('transaction_groups')
            ->where('id', $realId)
            ->first();

        if (!$transactionGroup) {
            abort(404);
        }

        // Store the obfuscated ID for use in views
        $transactionGroup->obfuscated_id = $id;

        // Get all transactions in this group
        $transactions = DB::table('transactions')
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->leftJoin('accounts as contra_accounts', 'transactions.contra_account_id', '=', 'contra_accounts.id')
            ->select(
                'transactions.*',
                'accounts.account_name',
                'accounts.account_number',
                'contra_accounts.account_name as contra_account_name'
            )
            ->where('transactions.transaction_group_id', $realId)
            ->orderBy('transactions.created_at', 'desc')
            ->paginate(15);

        return view('admin.transaction-groups.show', compact('transactionGroup', 'transactions'));
    }

    /**
     * Show the form for editing the specified transaction group.
     *
     * @param  string  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editTransactionGroup(Request $request, $id)
    {
        // Check if user is admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login');
        }

        $realId = $this->deobfuscateId($id);

        if (!$realId) {
            abort(404);
        }

        $transactionGroup = DB::table('transaction_groups')
            ->where('id', $realId)
            ->first();

        if (!$transactionGroup) {
            abort(404);
        }

        // Store the obfuscated ID for use in views
        $transactionGroup->obfuscated_id = $id;

        return view('admin.transaction-groups.edit', compact('transactionGroup'));
    }

    /**
     * Update the specified transaction group in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTransactionGroup(Request $request, $id)
    {
        // Check if user is admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login');
        }

        $realId = $this->deobfuscateId($id);

        if (!$realId) {
            abort(404);
        }

        $transactionGroup = DB::table('transaction_groups')
            ->where('id', $realId)
            ->first();

        if (!$transactionGroup) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'type' => 'required|string|max:50',
            'metadata' => 'nullable|json'
        ]);

        DB::table('transaction_groups')
            ->where('id', $realId)
            ->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'type' => $validated['type'],
                'metadata' => $validated['metadata'] ?? null,
                'updated_at' => now()
            ]);

        // Log the action
        $this->logAuditAction('update', 'Updated transaction group: ' . $validated['name']);

        return redirect()->route('admin.transaction-groups.show', $id)
            ->with('success', 'Transaction group updated successfully');
    }

    /**
     * Remove the specified transaction group from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyTransactionGroup(Request $request, $id)
    {
        // Check if user is admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login');
        }

        $realId = $this->deobfuscateId($id);

        if (!$realId) {
            abort(404);
        }

        // Check if there are any transactions using this group
        $transactionCount = DB::table('transactions')
            ->where('transaction_group_id', $realId)
            ->count();

        if ($transactionCount > 0) {
            return back()->with('error', 'Cannot delete transaction group that has transactions');
        }

        $transactionGroup = DB::table('transaction_groups')
            ->where('id', $realId)
            ->first();

        if (!$transactionGroup) {
            abort(404);
        }

        DB::table('transaction_groups')
            ->where('id', $realId)
            ->update([
                'status' => 'inactive',
                'updated_at' => now()
            ]);

        // Log the action
        $this->logAuditAction('delete', 'Deactivated transaction group: ' . $transactionGroup->name);

        return redirect()->route('admin.transaction-groups')
            ->with('success', 'Transaction group deactivated successfully');
    }
}
