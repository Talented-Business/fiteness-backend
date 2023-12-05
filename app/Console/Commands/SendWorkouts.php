<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Customer;
use Twilio\Rest\Client;
use App\Mail\Workout;
use Mail;

class SendWorkouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workouts:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send workouts to users through twilio sendgrid and whatsapp';

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
        $content = "Hello Fitemos, please make workouts, and make customers. This testing content is temporary.";
        $customers = Customer::all();
        if(count($customers)>0){
            $customerList = [];
            foreach($customers as $customer){
                if($customer->hasActiveSubscription() && $customer->user->active == 1 && $customer->id == 6965){
                    $userTimezone = new \DateTimeZone($customer->timezone);
                    $objDateTime = new \DateTime('NOW');
                    $objDateTime->setTimezone($userTimezone);
                    $hour = $objDateTime->format('H');
                    //if( $hour == 19){
                        $customerList[] = $customer;
                    //}
                    break;
                }
            }
            foreach($customerList as $customer){var_dump($customer->id);
                $workout = $customer->getSendableWorkout(1);
                if($workout){
                    var_dump($workout['content']);
                    try{
                        $customer->send($workout);
                    }catch(\Exception $e){
                        //print_r($e);
                    }
                }
            }
        }else{
            //$this->broadCast($content);
        }
        //return $this->send($content);
    }
    private function send($workout,$customer){
        if($customer->active_email){
            Mail::to($customer->email)->send(new Workout($workout['date'],$workout['content']));
        }
        /*if($customer->active_whatsapp && $customer->whatsapp_phone_number&&getenv("APP_ENV")!="local"){
            $to = $customer->whatsapp_phone_number;
            $from = config('services.twilio.whatsapp_from');
            $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
            $status =  $twilio->messages->create('whatsapp:' . $to, [
                "from" => 'whatsapp:' . $from,
                "body" => $workout['whatsapp']
            ]);
        }*/
    }
    private function broadCast($content){
        $whatsappTos = ["+8617043900977","+50769453583","+61424447732"];
        foreach($whatsappTos as $to){
            if(getenv("APP_ENV")!="local")$this->sendTestWhatapp($content,$to);
        }
        $emails = ["sui201837@gmail.com","rdk@leo.com.pa"];
        foreach($emails as $email){
            $this->sendTestEmail($content,$email);
        }
    }
    private function sendTestWhatapp($content,$to){
        $to = $to;
        $from = config('services.twilio.whatsapp_from');
        $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
        $status =  $twilio->messages->create('whatsapp:' . $to, [
            "from" => 'whatsapp:' . $from,
            "body" => $content
        ]);
    }
    private function sendTestEmail($content,$to){
        Mail::to($to)->send(new Workout("2020-12-12",$content));
    }
}
