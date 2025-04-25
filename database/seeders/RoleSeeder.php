<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds for roles.
     */
    public function run()
    {
        // Define roles
        $roles = [
            ['name' => 'Owner', 'guard_name' => 'web'],
            ['name' => 'Admin', 'guard_name' => 'web'],
            ['name' => 'Manager', 'guard_name' => 'web'],
            ['name' => 'Employee', 'guard_name' => 'web'],
        ];

        DB::transaction(function () use ($roles) {
            foreach ($roles as $roleData) {
                Role::updateOrCreate(
                    ['name' => $roleData['name'], 'guard_name' => $roleData['guard_name']]
                );
            }
        });
    }
}