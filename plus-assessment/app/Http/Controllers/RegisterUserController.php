<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\Rules;

class RegisterUserController extends Controller
{
    public function index()
    {
        // Fetch all roles
        $roles = Role::all();

        return view('register-user', compact('roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => ['required', 'array'],
            //'permissions' => ['required', 'array'],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Convert role names to role IDs
        $roleIds = Role::whereIn('name', $request->roles)->pluck('id')->toArray();

        // Assuming a many-to-many relationship between User and Role
        $user->roles()->attach($roleIds);

        event(new Registered($user));

        // Assign all permissions to all roles with the name 'Admin'
        $adminRoleIds = DB::table('roles')->where('name', 'Admin')->pluck('id')->toArray();
        $permissions = DB::table('permissions')->pluck('id')->toArray();

        foreach ($adminRoleIds as $adminRoleId) {
            foreach ($permissions as $permissionId) {
                // Get all users with the 'Admin' role
                $userIds = DB::table('role_user')->where('role_id', $adminRoleId)->pluck('user_id')->toArray();

                // Check if the records already exist before inserting
                foreach ($userIds as $userId) {
                    $existingRecords = DB::table('role_permission')
                        ->where('user_id', $userId)
                        ->where('role_id', $adminRoleId)
                        ->where('permission_id', $permissionId)
                        ->exists();

                    // Insert records only if they do not exist
                    if (!$existingRecords) {

                        DB::table('role_permission')->insert([
                            'user_id' => $userId,
                            'role_id' => $adminRoleId,
                            'permission_id' => $permissionId,
                        ]);
                    }
                }
            }
        }

        // Assign 'View Admin Dashboard' permission to all roles with the name 'Content Manager'
        $contentManagerRoleIds = DB::table('roles')->where('name', 'Content Manager')->pluck('id')->toArray();
        $viewAdminDashboardPermission = DB::table('permissions')->where('name', 'View Admin Dashboard')->first();

        foreach ($contentManagerRoleIds as $contentManagerRoleId) {
            // Get all users with the 'Content Manager' role
            $userIds = DB::table('role_user')->where('role_id', $contentManagerRoleId)->pluck('user_id')->toArray();

            // Check if the records already exist before inserting
            foreach ($userIds as $userId) {
                $existingRecords = DB::table('role_permission')
                    ->where('user_id', $userId)
                    ->where('role_id', $contentManagerRoleId)
                    ->where('permission_id', $viewAdminDashboardPermission->id)
                    ->exists();

                // Insert records only if they do not exist
                if (!$existingRecords) {

                    DB::table('role_permission')->insert([
                        'user_id' => $userId,
                        'role_id' => $contentManagerRoleId,
                        'permission_id' => $viewAdminDashboardPermission->id,
                    ]);
                }
            }
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
