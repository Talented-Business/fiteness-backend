<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:transactions']);
    }
    public function store(Request $request)
    {
        $transaction = new Transaction;
        $transaction->type =$request->input('paymentProvider');
        $transaction->save();
        return response()->json(array('status'=>'ok','transaction'=>$transaction));
    }
    public function show($id){
        $transaction = Transaction::find($id);
        return response()->json($transaction);
    }
    public function index(Request $request){
        $transaction = new Transaction;
        $transaction->assignSearch($request);
        return response()->json($transaction->search());
    }
    public function export(Request $request)
    {
        $transaction = new Transaction;
        $transaction->assignSearch($request);
        $transactions = $transaction->searchAll();
        $itemsArray = [];
        $itemsArray[] = ['ID','Transaction Type','Customer','Transaction Date','Total','Status'];
        $total = 0;
        foreach($transactions as $transaction){
            $itemsArray[] = [$transaction->id,
                $transaction->content,
                $transaction->customer->first_name.' '.$transaction->customer->last_name,
                $transaction->created_at,
                $transaction->total,
                $transaction->status,
            ];
        }
        $export = new TransactionsExport([
            $itemsArray
        ]);
        return Excel::download($export,'transactions.xlsx');   
    }
    public function log($id){
        $transaction = Transaction::find($id);
        $doneDate = date("Y-m-d",strtotime($transaction->done_date));
        $content = "";
        try{
            switch($transaction->type){
                case 0:
                    $fileName = __DIR__ .'/../../../storage/logs/nmi/payments-'.$doneDate.'.log';
                break;
                case 1:
                    $fileName = __DIR__ .'/../../../storage/logs/paypal/payments-'.$doneDate.'.log';
                break;
            }
            $content = file_get_contents($fileName, false);
        }catch(\Exception $e){
            print_r( $e->getMessage());
        }
        return response()->json(['content'=>$content,'fileName'=>$fileName]);
    }
}
