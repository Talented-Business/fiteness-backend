<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Customer;
use App\Transaction;
use App\Exports\CustomersExport;
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
}