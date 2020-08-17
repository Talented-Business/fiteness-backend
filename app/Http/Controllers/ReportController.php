<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Customer;

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
            if(isset($customer->subscriptions[0])){
                $reports['users'][$registeredDate] = $reports['users'][$registeredDate] + 1;
                if($customer->subscriptions[0]->plan->type=="Paid"){
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
        return response()->json($reports);
    }
}