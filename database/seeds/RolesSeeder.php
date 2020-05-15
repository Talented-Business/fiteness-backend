<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super = Role::create(['name' => 'super']);
        $admin = Role::create(['name' => 'admin']);
        $permission = Permission::create(['name' => 'customers']);
        $permission = Permission::create(['name' => 'subscriptions']);
        $permission = Permission::create(['name' => 'transactions']);
        $permission = Permission::create(['name' => 'invoices']);
        $permission = Permission::create(['name' => 'coupons']);
        $permission = Permission::create(['name' => 'subscription workout content']);
        $permission = Permission::create(['name' => 'subscription pricing']);
        $permission = Permission::create(['name' => 'events']);
        $permission = Permission::create(['name' => 'shortcodes']);
        $permission = Permission::create(['name' => 'benchmarks']);
        $permission = Permission::create(['name' => 'adminUsers']);
        $permission = Permission::create(['name' => 'settings']);
    }
}
