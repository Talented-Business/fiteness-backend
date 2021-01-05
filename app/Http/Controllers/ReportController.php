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
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:settings']);
    }
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
        $customers = Customer::where('created_at','>',$fromDate." 00:00:00")->where('created_at','<=',$toDate." 00:00:00")->get();
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
    public function customerWorkouts(Request $request){
        $fromDate = $request->input("from");
        $toDate = $request->input("to");
        $number = $request->input("number");
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
        $results = [];
        if(count($workoutPublishDates)>0 && count($customers)>0){
            uasort($customers, function ($a, $b) { 
                if ( $a['workouts'] == $b['workouts']  ) return 0;
                return ( $a['workouts'] > $b['workouts'] ? -1 : 1 ); 
            });
            $workoutCount = count($workoutPublishDates);
            $customers = array_slice(array_values($customers),0,$number);
            $workoutComplete = 0;
            foreach($customers as $index=>$customer){
                $item = Customer::find($customer['id']);
                $workouts = ActivityWorkout::whereCustomerId($customer['id'])->whereType('complete')->get();
                if($workoutComplete == $customer['workouts']){
                }else{
                    $pos = $index + 1;
                    $workoutComplete = $customer['workouts'];
                }
                if($item->user && $item->user->avatar){
                    $avatarUrls = [
                        'max'=>url("storage/".$item->user->avatar),
                        'large'=>url("storage/".$item->user->avatar),
                        'medium'=>url("storage/".$item->user->avatar),
                        'small'=>url("storage/".$item->user->avatar),
                    ];
                }else{
                    if($item->gender=="Male"){
                        $avatarUrls = [
                            'max'=>url("storage/media/avatar/X-man-large.jpg"),
                            'large'=>url("storage/media/avatar/X-man-large.jpg"),
                            'medium'=>url("storage/media/avatar/X-man-medium.jpg"),
                            'small'=>url("storage/media/avatar/X-man-small.jpg"),
                        ];
                    }else{
                        $avatarUrls = [
                            'max'=>url("storage/media/avatar/X-woman-large.jpg"),
                            'large'=>url("storage/media/avatar/X-woman-large.jpg"),
                            'medium'=>url("storage/media/avatar/X-woman-medium.jpg"),
                            'small'=>url("storage/media/avatar/X-woman-small.jpg"),
                        ];
                    }
                }    
                $workouts = Done::where('customer_id','=',$customer['id'])->get();
                $results[] = ['id'=>$item->id,'pos'=>$pos,'avatar_url'=>$avatarUrls,'name'=>$item->first_name.' '.$item->last_name,'workout_completeness'=>round($customer['workouts']/$workoutCount*100),'workout_complete_count'=>$customer['workouts'].'/'.$workoutCount,'total'=>count($workouts)];
            }
        }
        return response()->json($results);
    }
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