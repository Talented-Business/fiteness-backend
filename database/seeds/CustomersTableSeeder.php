<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Customer;
use App\User;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer = Customer::find(3194);
        if($customer){
            $customer->user->password = Hash::make('1234');
            $customer->user->save();
            print_r($customer->user->email);
        }
    }
}
