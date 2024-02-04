<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class UpdateUserController extends Controller
{
    public function index($id)
    {
        // Fetch user roles
        $roles = Role::all();
        // Fetch the specific user based on the provided ID
        $user = User::find($id);

        if (!$user) {
            abort(404); // Handle the case where the user is not found
        }

        return view('update-user', compact('user', 'roles'));
    }

    /**
     * Handle an incoming update request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class . ',email,' . $id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => ['required', 'array'],
        ]);

        // Fetch the specific user based on the provided ID
        $user = User::find($id);

        if (!$user) {
            abort(404); // Handle the case where the user is not found
        }

        // Update user details
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);


        // Convert role names to role IDs
        $roleIds = Role::whereIn('name', $request->roles)->pluck('id')->toArray();

        // Assuming a many-to-many relationship between User and Role
        $user->roles()->sync($roleIds, true); // The 'true' parameter means to detach missing roles

        $newRolesArray = [];
        foreach ($user->roles as $value) {
            array_push($newRolesArray, $value->getAttributes()['id']);
        }

        // delete all permissions before re-population
        RolePermission::where('user_id', $user->id)->delete();
        foreach ($newRolesArray as $roleId) {

            // Create an associative array to link roles to permissions
            $rolesData = [
                1 => [
                    'id' => 1,
                    'name' => 'Admin',
                    'permissions' => [
                        1 => 'View Admin Dashboard',
                        2 => 'Administer Users',
                    ],
                ],
                2 => [
                    'id' => 2,
                    'name' => 'Content Manager',
                    'permissions' => [
                        1 => 'View Admin Dashboard',
                    ],
                ],
                3 => [
                    'id' => 3,
                    'name' => 'User',
                    'permissions' => [],
                ],
            ];

            $rolePermissions = $rolesData[$roleId]['permissions'];

            // Update or create role permissions for each permission ID
            foreach ($rolePermissions as $permissionId => $permissionName) {
                RolePermission::Create(
                    [
                        'user_id' => $user->id,
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                    ],
                    []
                );
            }
        }

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Allows the admin delete the user's account.
     */
    public function deleteUser($user): RedirectResponse
    {
        // Convert the user parameter to a number (assuming it's the user ID)
        $userId = (int)$user;

        // Admin will be required to provide his password as confirmation to delete the user
        $userToDelete = User::find($userId);

        if (!$userToDelete) {
            abort(404); // Handle the case where the user is not found
        }

        // Check if the authenticated user is an admin
        if ($this->isAdminUser()) {


            // Check if the user is an admin
            if ($userToDelete->id === Auth::id()) {
                // Display a popup message for admins
                Session::flash('admin-delete-error', 'Admins cannot delete themselves.');
                return Redirect::back();
            }

            // Delete the user from the users table
            $userToDelete->delete();

            // Optionally, you might want to delete associated records in other tables if necessary

            return redirect(RouteServiceProvider::HOME)->with('success', 'User deleted successfully.');
        }

        return redirect()->back()->with('error', 'You do not have the necessary permissions to delete a user.');
    }

    /**
     * Check if the authenticated user is an admin.
     */
    private function isAdminUser(): bool
    {
        $adminRole = Role::where('name', 'Admin')->first();

        if (!$adminRole) {
            return false; // Handle the case where the 'Admin' role is not found
        }

        $adminRoleId = $adminRole->id;

        return DB::table('role_permission')
            ->where('user_id', Auth::id())
            ->where('role_id', $adminRoleId)
            ->exists();
    }
}
