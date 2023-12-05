<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Customer;
use App\Transaction;
use App\Exports\CustomersExport;
use App\Activity;
use App\ActivityWorkout;
use App\Workout;
use App\Done;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
/**
 * @group Report   
 *
 * APIs for managing  report
 */

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:settings'])->except("customerWorkouts", "customerWorkoutsRange");;
    }
    /**
     * get customer report.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function customers(Request $request){
        $fromDate = $request->input("from");
        $nextDate = $fromDate;
        $toDate = $request->input("to");
        $reports = ['registers'=>[],'users'=>[],'clients'=>[],'users_registers'=>[],'clients_registers'=>[],'clients_users'=>[]];
        while($nextDate<=$toDate){
            $reports['registers'][$nextDate] = 0;
            $reports['users'][$nextDate] = 0;
            $reports['clients'][$nextDate] = 0;
            $reports['users_registers'][$nextDate] = 0;
            $reports['clients_registers'][$nextDate] = 0;
            $reports['clients_users'][$nextDate] = 0;
            $nextDate = date("Y-m-d",strtotime($nextDate) + 3600*24);
        }
        $customers = Customer::where('created_at','>',$fromDate." 00:00:00")->where('created_at','<=',$toDate." 23:59:59")->get();
        foreach($customers as $customer){
            $registeredDate = $customer->created_at->format('Y-m-d');
            $reports['registers'][$registeredDate] = $reports['registers'][$registeredDate] + 1;
            if(isset($customer->subscriptions[0]) && $customer->subscriptions[0]->status!='Pending'){
                $reports['users'][$registeredDate] = $reports['users'][$registeredDate] + 1;
                if($customer->subscriptions[0]->plan->type=="Paid" && $customer->subscriptions[0]->start_date){
                    $reports['clients'][$registeredDate] = $reports['clients'][$registeredDate] + 1;
                }
            }
        }
        foreach($reports['registers'] as $date=>$report){
            if($report>0){
                $reports['users_registers'][$date] = round($reports['users'][$date] / $report * 100);
                $reports['clients_registers'][$date] = round($reports['clients'][$date] / $report * 100);
            }
            if($reports['users'][$date]>0){
                $reports['clients_users'][$date] = round($reports['clients'][$date] / $reports['users'][$date] * 100);
                //print_r($report);
            }
        }
        $labels = array_keys($reports['registers']);
        $reports['labels'] = [];
        foreach($labels as $label){
            $reports['labels'][] = date("d-M",strtotime($label));
        }
        $reports['registers'] = array_values($reports['registers']);
        $reports['users'] = array_values($reports['users']);
        $reports['clients'] = array_values($reports['clients']);
        $reports['users_registers'] = array_values($reports['users_registers']);
        $reports['clients_registers'] = array_values($reports['clients_registers']);
        $reports['clients_users'] = array_values($reports['clients_users']);
        $reports['usage'] = $this->getUsage($fromDate,$toDate, $reports['labels']);
        return response()->json($reports);
    }
    private function getUsage($fromDate,$toDate, $labels){
        $nextDate = $fromDate;
        $reports = ['activity'=>[],'start'=>[],'complete'=>[]];
        $i = 0;
        $activityCount = 0;
        $startCount = 0;
        $completeCount = 0;
        while($nextDate<=$toDate){
            $reports['activity'][$nextDate] = Activity::whereDoneDate($nextDate)->count();
            $activityCount += $reports['activity'][$nextDate];
            $reports['start'][$nextDate] = ActivityWorkout::whereDoneDate($nextDate)->whereType('start')->count();
            $startCount += $reports['start'][$nextDate];
            $reports['complete'][$nextDate] = ActivityWorkout::whereDoneDate($nextDate)->whereType('complete')->count();
            $completeCount += $reports['complete'][$nextDate];
            $nextDate = date("Y-m-d",strtotime($nextDate) + 3600*24);
            $i++;
        }
        $labels[] = "Average";
        $reports['labels'] = $labels;
        $reports['activity'] = array_values($reports['activity']);
        $reports['activity'][] = round($activityCount/$i);
        $reports['start'] = array_values($reports['start']);
        $reports['start'][] = round($startCount/$i);
        $reports['complete'] = array_values($reports['complete']);
        $reports['complete'][] = round($completeCount/$i);
        return $reports;
    }
    /**
     * export customers.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function exportCustomers(Request $request){
        $from = $request->input("from");
        $to = $request->input("to");
        $customer = new Customer;
        $itemsArray = [["Mensual inicial","Trimestral inicial","Semestral inicial","Anual inicial","Mensual actual","Trimestral actual","Semestral actual","Annual actual","Ventas inicial","Ventas acumuladas"]];
        $transactions = Transaction::where("done_date",">=",$from." 00:00:00")->where("done_date","<=",$to." 23:59:59")->whereStatus("Completed")->get();
        $customerIds = [];
        $firstPurchaseMonthly = 0;
        $firstPurchaseQuartly = 0;
        $firstPurchaseSemiannual = 0;
        $firstPurchaseYearly = 0;
        $renewalPurchaseMonthly = 0;
        $renewalPurchaseQuartly = 0;
        $renewalPurchaseSemiannual = 0;
        $renewalPurchaseYearly = 0;
        $firstPaidAmount = 0;
        $totalPaidAmount = 0;
        foreach($transactions as $transaction){
            if($transaction->total == 0) continue;
            $totalPaidAmount += $transaction->total;
            if(!in_array($transaction->customer_id,$customerIds)){
                array_push($customerIds,$transaction->customer_id);
                $firstTransaction = Transaction::whereCustomerId($transaction->customer_id)->whereStatus("Completed")->first();
                if($firstTransaction && $firstTransaction->id == $transaction->id ){
                    $firstPaidAmount += $transaction->total;
                    switch($transaction->frequency){
                        case 'Mensual':
                            $firstPurchaseMonthly++;
                            break;
                        case 'Trimestral':
                            $firstPurchaseQuartly++;
                            break;
                        case 'Semestral':
                            $firstPurchaseSemiannual++;
                            break;
                        case 'Anual':
                            $firstPurchaseYearly++;
                            break;
                    }
                }else{
                    switch($transaction->frequency){
                        case 'Mensual':
                            $renewalPurchaseMonthly++;
                            break;
                        case 'Trimestral':
                            $renewalPurchaseQuartly++;
                            break;
                        case 'Semestral':
                            $renewalPurchaseSemiannual++;
                            break;
                        case 'Anual':
                            $renewalPurchaseYearly++;
                            break;
                    }
                }
            }else{
                //print_r($transaction->customer_id);
            }
        }
        $itemsArray[] = [
            $firstPurchaseMonthly,
            $firstPurchaseQuartly,
            $firstPurchaseSemiannual,
            $firstPurchaseYearly,
            $renewalPurchaseMonthly,
            $renewalPurchaseQuartly,
            $renewalPurchaseSemiannual,
            $renewalPurchaseYearly,
            $firstPaidAmount,
            $totalPaidAmount-$firstPaidAmount,
        ];
        $export = new CustomersExport([
            $itemsArray
        ]);
        return Excel::download($export,'customers.xlsx');   
    }
    /**
     * export usage.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function exportUsage(Request $request){
        $fromDate = $request->input("from");
        $toDate = $request->input("to");
        $nextDate = $fromDate;
        $itemsArray = [["Fecha","Activity","Start","Complete"]];
        $i = 0;
        $activityCount = 0;
        $startCount = 0;
        $completeCount = 0;
        while($nextDate<=$toDate){
            $activity = Activity::whereDoneDate($nextDate)->count();
            $activityCount += $activity;
            $start = ActivityWorkout::whereDoneDate($nextDate)->whereType('start')->count();
            $startCount += $start;
            $complete = ActivityWorkout::whereDoneDate($nextDate)->whereType('complete')->count();
            $completeCount += $complete;
            $itemsArray[] = [$nextDate, $activity, $start, $complete];
            $nextDate = date("Y-m-d",strtotime($nextDate) + 3600*24);
            $i++;
        }
        $labels[] = "Average";
        $itemsArray[] = ["Average", round($activityCount/$i), round($startCount/$i), round($completeCount/$i)];
        $export = new CustomersExport([
            $itemsArray
        ]);
        return Excel::download($export,'usage.xlsx');   
    }
    /**
     * get customer workouts.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function customerWorkouts(Request $request){
        if($request->exists("from")){
            $fromDate = $request->input("from");
            $toDate = $request->input("to");
            $change =  true;
        }else{
            $fromDate = date("Y-m")."-01";
            $toDate = date("Y-m-d");
            $change =  false;
        }
        $number = $request->input("number");
        if($request->exists('gender') && $request->input("gender") != 'all'){
            $gender=$request->input("gender");
        }
        $month = $fromDate;
        do {
            $workoutPublishDates = [];
            $workouts = ActivityWorkout::where('publish_date','>=',$fromDate)->where('publish_date','<=',$toDate)->whereType('complete')->get();
            $records = Workout::where('publish_date','>=',$fromDate)->where('publish_date','<=',$toDate)->whereNotNull('comentario')->get();
            foreach($records as $record){
                if(in_array($record->publish_date,$workoutPublishDates)==false)$workoutPublishDates[] = $record->publish_date;
            }
            $customers = [];
            foreach($workouts as $workout){
                if(in_array($workout->publish_date,$workoutPublishDates)==false)$workoutPublishDates[] = $workout->publish_date;
                if(isset($customers[$workout->customer_id])){
                    $customers[$workout->customer_id]['workouts']++;
                }else{
                    $customers[$workout->customer_id] = ['id'=>$workout->customer_id,'workouts'=>1];
                }
            }
            if($change){
                break;
            }else{
                if($customers>0)$month = $fromDate;
                $fromDate = date("Y-m", strtotime( date( "Y-m-d", strtotime( $fromDate ) ) . "-1 month" ) )."-01";
                $toDate = date("Y-m-t", strtotime($fromDate));
            }
        } while(count($customers)==0);
        $results = [];
        if(count($workoutPublishDates)>0 && count($customers)>0){
            uasort($customers, function ($a, $b) { 
                if ( $a['workouts'] == $b['workouts']  ) return 0;
                return ( $a['workouts'] > $b['workouts'] ? -1 : 1 ); 
            });
            $workoutCount = count($workoutPublishDates);
            // $customers = array_slice(array_values($customers),0,$number);
            $workoutComplete = 0;
            $pos = 0;
            $same = 0;
            foreach($customers as $index=>$customer){
                $item = Customer::find($customer['id']);
                if(isset($gender)&&$gender!=$item->gender)continue;
                $workouts = ActivityWorkout::whereCustomerId($customer['id'])->whereType('complete')->get();
                if($workoutComplete == $customer['workouts']){
                    $same++;
                }else{
                    $pos = $same + $pos + 1;
                    $same = 0;
                    $workoutComplete = $customer['workouts'];
                }
                if($item->user && $item->user->avatar){
                    $data = pathinfo($item->user->avatar);
                    $avatarFile = $data['dirname']."/avatar/".$data['filename'].".".$data['extension'];                                
                    $avatarUrls = [
                        'max'=>secure_url("storage/".$item->user->avatar),
                        'large'=>secure_url("storage/".$item->user->avatar),
                        'medium'=>secure_url("storage/".$item->user->avatar),
                        'small'=>secure_url("storage/".$avatarFile),
                    ];
                }else{
                    if($item->gender=="Male"){
                        $avatarUrls = [
                            'max'=>secure_url("storage/media/avatar/X-man-large.jpg"),
                            'large'=>secure_url("storage/media/avatar/X-man-large.jpg"),
                            'medium'=>secure_url("storage/media/avatar/X-man-medium.jpg"),
                            'small'=>secure_url("storage/media/avatar/X-man-small.jpg"),
                        ];
                    }else{
                        $avatarUrls = [
                            'max'=>secure_url("storage/media/avatar/X-woman-large.jpg"),
                            'large'=>secure_url("storage/media/avatar/X-woman-large.jpg"),
                            'medium'=>secure_url("storage/media/avatar/X-woman-medium.jpg"),
                            'small'=>secure_url("storage/media/avatar/X-woman-small.jpg"),
                        ];
                    }
                }    
                $workouts = Done::where('customer_id','=',$customer['id'])->get();
                $results[] = [
                    'id'=>$item->id,
                    'pos'=>$pos,
                    'avatar_url'=>$avatarUrls,
                    'name'=>$item->first_name.' '.$item->last_name,
                    'username'=>$item->username,
                    'workout_completeness'=>round($customer['workouts']/$workoutCount*100),
                    'workout_complete_count'=>$customer['workouts'].'/'.$workoutCount,
                    'total'=>count($workouts)
                ];
                if(count($results)>=$number) break;
            }
        }
        return response()->json(['data'=>$results,'month'=>$month]);
    }
    /**
     * get customer workouts.
     * 
     * This endpoint.
     * @authenticated
     * @queryParam range string required // all , current, last
     * @queryParam gender string required // all , Male, Female, MaleMaster, FemaleMaster
     * @response {
     * }
     */
    public function customerWorkoutsRange(Request $request){
        if(!$request->exists("range")){
            $range = 'all';
        }else{
            $range = $request->input('range');
        }
        if(!$request->exists("gender")){
            $gender = 'all';
        }else{
            $gender = $request->input('gender');
        }
        $workoutPublishDates = [];
        if( $range == 'all' ){
            $workouts = ActivityWorkout::whereType('complete')->get();
            $records = Workout::whereNotNull('comentario')->get();
        }else{
            $user = $request->user();
            if($user->customer){
                $userTimezone = new \DateTimeZone($user->customer->timezone);
                $objDateTime = new \DateTime('NOW');
                $objDateTime->setTimezone($userTimezone);
                $today = $objDateTime->format('Y-m-d');
            }else{
                $today = date("Y-m-d");
            }
            if($range == 'current'){
                $fromDate = date("Y-m")."-01";
                $toDate = $today;
            }else{
                $fromDate = date("Y-m", strtotime( date( "Y-m-d", strtotime( $today ) ) . "-1 month" ) )."-01";
                $toDate = date("Y-m-t", strtotime($fromDate));
            }
            $workouts = ActivityWorkout::where('publish_date','>=',$fromDate)->where('publish_date','<=',$toDate)->whereType('complete')->get();
            $records = Workout::where('publish_date','>=',$fromDate)->where('publish_date','<=',$toDate)->whereNotNull('comentario')->get();    
        }
        foreach($records as $record){
            if(in_array($record->publish_date,$workoutPublishDates)==false)$workoutPublishDates[] = $record->publish_date;
        }
        $customers = [];
        foreach($workouts as $workout){
            if(in_array($workout->publish_date,$workoutPublishDates)==false)$workoutPublishDates[] = $workout->publish_date;
            if(isset($customers[$workout->customer_id])){
                $customers[$workout->customer_id]['workouts']++;
                if($customers[$workout->customer_id]['done_date']<$workout->done_date)$customers[$workout->customer_id]['done_date'] = $workout->done_date;
            }else{
                $customers[$workout->customer_id] = ['id'=>$workout->customer_id,'workouts'=>1,'done_date'=>$workout->done_date];
            }
        }
        $results = [];
        if(count($workoutPublishDates)>0 && count($customers)>0){
            uasort($customers, function ($a, $b) { 
                if ( $a['workouts'] == $b['workouts']  ) return 0;
                return ( $a['workouts'] > $b['workouts'] ? -1 : 1 ); 
            });
            $workoutCount = count($workoutPublishDates);
            // $customers = array_slice(array_values($customers),0,$number);
            $workoutComplete = 0;
            $pos = 0;
            $same = 0;
            $now = Carbon::now();
            foreach($customers as $index=>$customer){
                $item = Customer::find($customer['id']);
                if(in_array($gender,['Male','Female'])){
                    if($gender!=$item->gender)continue;
                }else if(in_array($gender,['MaleMaster','FemaleMaster'])){
                    $age = \DateTime::createFromFormat('Y-m-d', $item->birthday)->diff(new \DateTime('now'))->y;
                    if($age<40)continue;
                    if($gender == 'MaleMaster' && 'Male'!=$item->gender)continue;
                    if($gender == 'FemaleMaster' && 'Female'!=$item->gender)continue;
                }
                $workouts = ActivityWorkout::whereCustomerId($customer['id'])->whereType('complete')->get();
                if($workoutComplete == $customer['workouts']){
                    $same++;
                }else{
                    $pos = $same + $pos + 1;
                    $same = 0;
                    $workoutComplete = $customer['workouts'];
                }
                if($item->user && $item->user->avatar){
                    $data = pathinfo($item->user->avatar);
                    $avatarFile = $data['dirname']."/avatar/".$data['filename'].".".$data['extension'];                                
                    $avatarUrls = [
                        'max'=>secure_url("storage/".$item->user->avatar),
                        'large'=>secure_url("storage/".$item->user->avatar),
                        'medium'=>secure_url("storage/".$item->user->avatar),
                        'small'=>secure_url("storage/".$avatarFile),
                    ];
                }else{
                    if($item->gender=="Male"){
                        $avatarUrls = [
                            'max'=>secure_url("storage/media/avatar/X-man-large.jpg"),
                            'large'=>secure_url("storage/media/avatar/X-man-large.jpg"),
                            'medium'=>secure_url("storage/media/avatar/X-man-medium.jpg"),
                            'small'=>secure_url("storage/media/avatar/X-man-small.jpg"),
                        ];
                    }else{
                        $avatarUrls = [
                            'max'=>secure_url("storage/media/avatar/X-woman-large.jpg"),
                            'large'=>secure_url("storage/media/avatar/X-woman-large.jpg"),
                            'medium'=>secure_url("storage/media/avatar/X-woman-medium.jpg"),
                            'small'=>secure_url("storage/media/avatar/X-woman-small.jpg"),
                        ];
                    }
                }    
                $doneWorkout = Done::where('customer_id','=',$customer['id'])->where('done_date',$customer['done_date'])->orderByDesc('created_at')->first();
                if($doneWorkout)$diffHours = $now->diffInHours($doneWorkout->created_at);
                else $diffHours=1000;
                $results[] = [
                    'id'=>$item->id,
                    'pos'=>$pos,
                    'avatar_url'=>$avatarUrls,
                    'name'=>$item->first_name.' '.$item->last_name,
                    'username'=>$item->username,
                    'workout_completeness'=>round($customer['workouts']/$workoutCount*100),
                    'workout_complete_count'=>$customer['workouts'],
                    'workout_done_date'=>$customer['done_date'],
                    'recent_workout' => $diffHours
                ];
                // if(count($results)>=$number) break;
            }
        }
        return response()->json(['data'=>$results]);
    }
    /**
     * export subscriptions.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function exportSubscriptions(Request $request){
        $fromDate = $request->input("from");
        $toDate = $request->input("to");
        $records = \DB::table('reports')->where('created_date','>=',$fromDate)->where('created_date','<=',$toDate)->get();
        $nextDate = $fromDate;
        $itemsArray = [];
        $headers = ['Date'];
        $results = [];
        $i = 0;
        while($nextDate<=$toDate){
            foreach($records as $record){
                if($record->created_date == $nextDate){
                    $results[$nextDate] = [$record->registered,$record->inactive,$record->guest,$record->ex_customer,
                    $record->ex_trial,$record->total_users,$record->active_users,$record->active_customers,
                    $record->active_trials,$record->leaving_users,$record->leaving_customers,$record->leaving_trials,
                    $record->total_customers,$record->total_sales,$record->customer_base,$record->customer_churn,
                    $record->trial_churn];
                    $headers[] = date("d-m-Y",strtotime($nextDate));
                }
            }
            $itemsArray = [$headers];
            $nextDate = date("Y-m-d",strtotime($nextDate) + 3600*24);
        }
        for($i=0;$i<17;$i++){
            switch($i){
                case 0:
                    $item = ['Registered'];
                break;
                case 1:
                    $item = ['Inactive'];
                break;
                case 2:
                    $item = ['Guest'];
                break;
                case 3:
                    $item = ['Ex-Customer'];
                break;
                case 4:
                    $item = ['Ex-Trial'];
                break;
                case 5:
                    $item = ['Total Users'];
                break;
                case 6:
                    $item = ['Active Users'];
                break;
                case 7:
                    $item = ['Active Customers'];
                break;
                case 8:
                    $item = ['Active Trials'];
                break;
                case 9:
                    $item = ['Leaving Users'];
                break;
                case 10:
                    $item = ['Leaving Customers'];
                break;
                case 11:
                    $item = ['Leaving Trials'];
                break;
                case 12:
                    $item = ['Total Customers'];
                break;
                case 13:
                    $item = ['Total Sales'];
                break;
                case 14:
                    $item = ['Customer Base'];
                break;
                case 15:
                    $item = ['Customer (Churn)'];
                break;
                case 16:
                    $item = ['Trial (Churn)'];
                break;
            }
            foreach($results as $result){
                $item[] = $result[$i];
            }
            $itemsArray[] = $item;
        }
        $export = new CustomersExport([
            $itemsArray
        ]);
        return Excel::download($export,'subscription-usage.xlsx');
    }
}