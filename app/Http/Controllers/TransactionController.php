<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
/**
 * @group Transaction   
 *
 * APIs for managing transactions
 */

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:transactions']);
    }
    /**
     * create a transaction.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function store(Request $request)
    {
        $transaction = new Transaction;
        $transaction->type =$request->input('paymentProvider');
        $transaction->save();
        return response()->json(array('status'=>'ok','transaction'=>$transaction));
    }
    /**
     * show a transaction.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function show($id){
        $transaction = Transaction::find($id);
        return response()->json($transaction);
    }
    /**
     * search transactions.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function index(Request $request){
        $transaction = new Transaction;
        $transaction->assignSearch($request);
        return response()->json($transaction->search());
    }
    /**
     * export transactions as excel file.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
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
    /**
     * show transaction log.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
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
