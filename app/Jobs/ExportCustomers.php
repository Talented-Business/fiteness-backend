<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Rap2hpoutre\FastExcel\FastExcel;

function customersGenerator($customers) {
    foreach ($customers as $customer) {
        yield $customer;
    }
}

class ExportCustomers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $request;
    public $uid;
    public $status;
    public $search;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uid, $search, $status)
    {
        $this->uid = $uid;
        $this->search = $search;
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Cache::put('export-'.$this->uid,'start', 1000);
        $customer = new \App\Customer;
        $customer->assignExport($this->search, $this->status);
        $customers = $customer->searchAll();
        $tenPercentCoupons = \App\Coupon::whereType('Private')->whereDiscount('10')->whereForm('%')->get();
        $tenPercentCouponsIds = [];
        foreach($tenPercentCoupons as $tenPercentCoupon){
            $tenPercentCouponsIds[] = $tenPercentCoupon->id;
        }
        $filePath = \Storage::disk('local')->path("customers/$this->uid");
        (new FastExcel(customersGenerator($customers)))->export($filePath, function ($customer) use ($tenPercentCouponsIds) {
            return $customer->customerExport($tenPercentCouponsIds);
        });
        Cache::put('export-'.$this->uid,'completed', 100);
    }
}
