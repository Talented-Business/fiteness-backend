<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Customer;

class CompleteWorkout implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $customer;
    private $publishDate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Customer $customer,$publishDate)
    {
        $this->customer = $customer;
        $this->publishDate = $publishDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \App\ActivityWorkout::firstOrCreate(['customer_id' => $this->customer->id,'publish_date'=>$this->publishDate,'done_date'=>$this->customer->currentDate(),'type'=>'complete']);
    }
}
