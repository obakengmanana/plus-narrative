<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert permissions only if they don't exist
        $permissions = [
            ['name' => 'View Admin Dashboard'],
            ['name' => 'Administer Users'],
        ];

        foreach ($permissions as $permission) {
            $existingPermission = DB::table('permissions')->where('name', $permission['name'])->first();

            if (!$existingPermission) {
                DB::table('permissions')->insert($permission);
            }
        }
    }
}
