<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Customer;
use Mail;

class ExportCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command exports customers and sends email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //generate excel file for customers
        $customers = Customer::all();
        $export = Customer::export($customers);
        $result = Excel::store($export, 'customers.xlsx', 'local');
        $file = \Storage::disk('local')->path('customers.xlsx');
        $exists = \Storage::disk('local')->exists('customers.xlsx');
        //send mail
        Mail::to(env("MAIL_FROM_ADDRESS"))->send(new \App\Mail\CustomerExport());
    }
}
