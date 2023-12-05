<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Customer;
use Mail;
function customersGenerator() {
    foreach (Customer::cursor() as $customer) {
        yield $customer;
    }
}
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
        $files = [];
        $filePath = \Storage::disk('local')->path("customers.xlsx");
        $tenPercentCoupons = \App\Coupon::whereType('Private')->whereDiscount('10')->whereForm('%')->get();
        $tenPercentCouponsIds = [];
        foreach($tenPercentCoupons as $tenPercentCoupon){
            $tenPercentCouponsIds[] = $tenPercentCoupon->id;
        }
        (new FastExcel(customersGenerator()))->export($filePath, function ($customer) use ($tenPercentCouponsIds) {
            return $customer->customerExport($tenPercentCouponsIds);
        });
        $files[] = \Storage::disk('local')->path("customers.xlsx");
        
        if(count($files)>0)Mail::to(config('mail.from.address'))->cc(['degracia.jf@gmail.com','sui201837@gmail.com'])->send(new \App\Mail\CustomerExport($files));
    }
}
