<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class EventAttend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $customerId;
    private $eventId;
    private $doneDate;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customerId, $eventId, $doneDate)
    {
        $this->customerId = $customerId;
        $this->eventId = $eventId;
        $this->doneDate = $doneDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $attend = DB::table("eventos_customers")->select("*")->where('evento_id',$this->eventId)->where('customer_id',$this->customerId)->first();        
        if($attend){
            $datatime = iconv('ISO-8859-2', 'UTF-8', strftime("%B %d, %Y ", strtotime($this->doneDate))).date(" h:i a",strtotime($this->doneDate));
            $evento = \App\Models\Evento::find($this->eventId);
            \App\Models\Notification::events($this->customerId,$datatime,$evento->title);
        }
    }
}
