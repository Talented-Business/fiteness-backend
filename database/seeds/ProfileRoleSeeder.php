<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ProfileRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'profileManager']);
        // $role =  Role::whereName('profileManager')->first();
        $permission = Permission::create(['name' => 'manage posts on frontend']);
        // $permission = Permission::whereName('manage posts on frontend')->first();
        $role->givePermissionTo($permission);
    }
}
