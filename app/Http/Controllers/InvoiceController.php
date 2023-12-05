<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\PaymentSubscription;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesExport;
/**
 * @group Invoice   
 *
 * APIs for managing  invoice
 */

class InvoiceController extends Controller
{
    /**
     * show a invoice.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function show($id,Request $request){
        $user = $request->user('api');
        $invoice = Invoice::find($id);
        $invoice->transaction;
        if($user->can('invoices') || $user->customer && $invoice->transaction->customer_id == $user->customer->id){
            $invoice['doneDate'] = date('M d,Y',strtotime($invoice->transaction->done_date));
            $invoice['to'] = $invoice->transaction->customer->first_name.' '.$invoice->transaction->customer->last_name;
            $paymentSubscription = PaymentSubscription::whereSubscriptionId($invoice->transaction->payment_subscription_id)->first();
            $invoice['serviceName'] = $invoice->transaction->plan->service->title;
            $invoice['dueDate'] = date('M d,Y',strtotime($paymentSubscription->start_date));
            setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
            $invoice['customer_name'] = $invoice->transaction->customer->first_name.' '.$invoice->transaction->customer->last_name;
            $invoice['doneDate'] = iconv('ISO-8859-2', 'UTF-8', strftime("%b %d, %Y", strtotime($invoice->transaction->done_date)));
            $invoice['to'] = $invoice->transaction->customer->first_name.' '.$invoice->transaction->customer->last_name;
            $paymentSubscription = PaymentSubscription::whereSubscriptionId($invoice->transaction->payment_subscription_id)->first();
            $invoice['serviceName'] = $invoice->transaction->plan->service->title;
            $invoice['dueDate'] = iconv('ISO-8859-2', 'UTF-8', strftime("%b %d, %Y", strtotime($paymentSubscription->start_date)));
            $invoice['startDate'] = iconv('ISO-8859-2', 'UTF-8', strftime("%b %d, %Y", strtotime($invoice->transaction->done_date)));
            $invoice['expiredDate'] = iconv('ISO-8859-2', 'UTF-8', strftime("%b %d, %Y", strtotime($paymentSubscription->getEndDate())));
            $invoice['nextPaymentDate'] = iconv('ISO-8859-2', 'UTF-8', strftime("%b %d, %Y", strtotime($paymentSubscription->getEndDate())));
            return response()->json($invoice);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * search invoices.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function index(Request $request){
        $user = $request->user('api');
        if($user->can('invoices') || $user->customer){
            $invoice = new Invoice;
            $invoice->assignSearch($request);
            return response()->json($invoice->search());
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * export invoices.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function export(Request $request)
    {
        $user = $request->user('api');
        if($user->can('invoices')){
            $invoice = new Invoice;
            $invoice->assignSearch($request);
            $invoices = $invoice->searchAll();
            $itemsArray = [];
            $itemsArray[] = ['ID','Invoice Type','Customer','Invoice Date','Total','Status'];
            $total = 0;
            foreach($invoices as $invoice){
                $itemsArray[] = [$invoice->id,
                    $invoice->transaction->content,
                    $invoice->transaction->customer->first_name.' '.$invoice->transaction->customer->last_name,
                    $invoice->created_at,
                    $invoice->transaction->total,
                    $invoice->transaction->status,
                ];
            }
            $export = new InvoicesExport([
                $itemsArray
            ]);
            return Excel::download($export,'invoices.xlsx');   
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
}
