<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert roles
        // Create roles only if they don't exist
        $roles = ['Admin', 'Content Manager', 'User'];

        foreach ($roles as $role) {
            $existingRole = DB::table('roles')->where('name', $role)->first();

            if (!$existingRole) {
                DB::table('roles')->insert(['name' => $role]);
            }
        }

    }
}
