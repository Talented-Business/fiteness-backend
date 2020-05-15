<?php

use Illuminate\Database\Seeder;
use App\User;
class AssignRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::whereEmail('hola@fitemos.com')->first();
        if($user){
            $user->assignRole('super');
        }
    }
}
