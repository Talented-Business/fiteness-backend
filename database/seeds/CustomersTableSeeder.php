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
        for($i = 0;$i<10;$i++){
            $user = new User;
            $user->name = 'customer'.$i;
            $user->email = 'customer'.$i.'@gmail.com'; 
            $user->password = Hash::make('demo'); 
            $user->type='customer';
            $user->save();
            $customer = new Customer;
            $customer->first_name = 'customer'.$i;
            $customer->last_name = 'quick'.$i;
            $genders = ['Male','Female'];
            $customer->gender=$genders[rand(0,1)];
            $year = rand(1986,1990);
            $month = rand(1,9);
            $day = rand(10,28);
            $customer->birthday="$year-0$month-$day";
            $ip2 = rand(1,254);
            $ip3 = rand(1,254);
            $ip4 = rand(1,254);
            $customer->register_ip="134.$ip2.$ip3.$ip4";
            //$customer->country=Customer::ip_info($customer->register_ip, "Country");
            $places = ['Casa o Aire libre','GYM','Ambos'];
            $customer->training_place=$places[rand(0,2)];
            $height = rand(160,175)/100;
            $customer->initial_height=$height;
            $customer->current_height=$height;
            $weight = rand(5600,8000)/100;
            $customer->initial_weight=$weight;
            $customer->initial_condition=1;
            $different_weight = rand(100,800)/100;
            $customer->current_weight=$weight-$different_weight;
            $customer->current_condition=rand(2,5);
            $customer->user_id = $user->id;
            $customer->email = 'customer'.$i.'@gmail.com'; 
            $customer->save();
        }
    }
}
