<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Coupon;

class CouponReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupons:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Coupon report weekly';

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
        Coupon::sendReport();
    }
}
