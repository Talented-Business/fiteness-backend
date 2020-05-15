<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UsersTableSeeder::class);
         //$this->call(ConditionsTableSeeder::class);
         //$this->call(CustomersTableSeeder::class);
         //$this->call(TransactionsTableSeeder::class);
         //$this->call(CouponsTableSeeder::class);
         //$this->call(SubscriptionsTableSeeder::class);
         //$this->call(ShortCodesTableSeeder::class);
         //$this->call(InvoicesTableSeeder::class);
         //$this->call(EventsTableSeeder::class);
         //$this->call(CartTableSeeder::class);
         //$this->call(RolesSeeder::class);
         //$this->call(AssignRoleSeeder::class);
         //$this->call(CreateSubscription::class);
    }
}
