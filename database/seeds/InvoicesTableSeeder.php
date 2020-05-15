<?php

use Illuminate\Database\Seeder;
use App\Transaction;
use App\Invoice;

class InvoicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactions = Transaction::all();
        foreach($transactions as $transaction){
            if($transaction->status == "Completed"){
                $invoice = new Invoice;
                $invoice->transaction_id = $transaction->id;
                $invoice->save();
            }
        }
    }
}
