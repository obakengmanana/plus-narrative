<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Assign all permissions to the Admin role
        $adminRole = Role::where('name', 'Admin')->first();
        $permissions = Permission::pluck('id')->toArray();
        $adminRole->permissions()->sync($permissions);

        // Create admin user
        $adminUser = [
            'first_name' => 'Obakeng',
            'last_name' => 'Manana',
            'email' => 'obaman@gmail.com',
            'password' => Hash::make('1234'),
        ];

        $this->createUser($adminUser, 'Admin');

        // Create 99 additional users
        for ($i = 0; $i < 99; $i++) {
            $user = [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('5678'),
            ];

            $this->createUser($user, 'User');
        }

        // Assign all permissions to all roles with the name 'Admin'
        $adminRoleIds = DB::table('roles')->where('name', 'Admin')->pluck('id')->toArray();
        $permissions = DB::table('permissions')->pluck('id')->toArray();

        foreach ($adminRoleIds as $adminRoleId) {
            foreach ($permissions as $permissionId) {
                // Get all users with the 'Admin' role
                $userIds = DB::table('role_user')->where('role_id', $adminRoleId)->pluck('user_id')->toArray();

                // Check if the records already exist before inserting
                $existingRecords = DB::table('role_permission')
                    ->whereIn('user_id', $userIds)
                    ->where('role_id', $adminRoleId)
                    ->where('permission_id', $permissionId)
                    ->exists();

                // Insert records only if they do not exist
                if (!$existingRecords) {
                    foreach ($userIds as $userId) {
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
            $existingRecords = DB::table('role_permission')
                ->whereIn('user_id', $userIds)
                ->where('role_id', $contentManagerRoleId)
                ->where('permission_id', $viewAdminDashboardPermission->id)
                ->exists();

            // Insert records only if they do not exist
            if (!$existingRecords) {
                foreach ($userIds as $userId) {
                    DB::table('role_permission')->insert([
                        'user_id' => $userId,
                        'role_id' => $contentManagerRoleId,
                        'permission_id' => $viewAdminDashboardPermission->id,
                    ]);
                }
            }
        }
    }

    private function createUser(array $userData, string $roleName)
    {
        // Find or create the role
        $role = Role::firstOrCreate(['name' => $roleName]);

        // Create the user
        $user = User::create($userData);

        // Assign the role to the user
        $user->roles()->attach($role);

        return $user->id;
    }
}
