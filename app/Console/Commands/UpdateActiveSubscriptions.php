<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Subscription;
use App\PaymentSubscription;
use App\PaymentPlan;
use App\Transaction;

class UpdateActiveSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $items = [[1,1293,''],
            [2,2,''],
            [4,1529,''],
            [5,1870,''],
            [6,1531,''],
            [9,2281,''],
            [10,1888,''],
            [13,1889,''],
            [17,1907,''],
            [20,1898,''],
            [21,1558,''],
            [24,31,''],
            [29,39,''],
            [32,42,''],
            [33,1559,''],
            [34,1893,''],
            [38,1892,''],
            [44,1894,''],
            [51,1896,''],
            [60,1236,''],
            [64,91,''],
            [65,1582,''],
            [68,97,''],
            [70,789,''],
            [72,102,''],
            [73,1899,''],
            [74,1900,''],
            [81,1901,''],
            [96,1591,''],
            [99,1905,''],
            [103,1906,''],
            [105,1759,''],
            [107,1909,''],
            [114,171,'2020-10-18 14:57:33'],
            [115,1926,''],
            [117,1331,''],
            [119,1908,''],
            [122,1928,''],
            [127,1924,''],
            [128,1381,''],
            [138,1930,''],
            [142,211,'2020-10-18 21:31:23'],
            [145,1619,'2020-10-18 22:40:04'],
            [151,857,''],
            [153,227,''],
            [160,2119,''],
            [165,1932,''],
            [166,1624,''],
            [167,1311,''],
            [169,1140,''],
            [173,256,''],
            [181,2172,''],
            [185,1981,''],
            [192,1972,''],
            [194,1939,''],
            [195,1940,''],
            [202,1941,''],
            [219,313,''],
            [224,1967,''],
            [232,1948,''],
            [236,1943,'2020-09-20 13:10:06'],
            [242,2005,''],
            [243,2000,''],
            [246,1951,''],
            [249,1952,''],
            [255,1890,'2020-09-17 09:55:48'],
            [260,1659,''],
            [263,1661,''],
            [268,1965,''],
            [271,1968,''],
            [272,1971,''],
            [275,1973,''],
            [277,1964,''],
            [297,2099,''],
            [300,1955,''],
            [308,1978,''],
            [313,1980,''],
            [344,1695,''],
            [350,1710,''],
            [352,1711,''],
            [355,1712,'2020-10-27 11:20:07'],
            [366,2053,''],
            [376,2056,''],
            [385,2090,''],
            [386,2091,''],
            [387,2092,''],
            [389,2112,''],
            [394,1648,'2020-10-20 22:25:43'],
            [399,1253,''],
            [404,2143,''],
            [411,1781,''],
            [413,2159,''],
            [419,2189,''],
            [421,637,''],
            [422,638,''],
            [428,1884,''],
            [440,2235,''],
            [444,2240,'2020-12-09 19:20:08'],
            [448,1830,''],
            [451,2268,''],
            [452,679,''],
            [453,2254,''],
            [457,2253,''],
            [458,1947,''],
            [466,715,''],
            [477,2275,''],
            [479,2279,''],
            [487,1966,''],
            [489,1248,''],
            [496,848,''],
            [497,1933,''],
            [503,1949,''],
            [506,1953,''],
            [507,1970,''],
            [511,1979,''],
            [512,974,''],
            [517,2006,''],
            [519,992,''],
            [520,2019,''],
            [525,2033,''],
            [528,1528,''],
            [534,1787,''],
            [542,1061,''],
            [555,2142,''],
            [557,1087,''],
            [561,1431,''],
            [563,2166,'2020-12-03 20:50:10'],
            [571,2193,''],
            [572,1114,''],
            [580,2213,''],
            [582,1131,'2020-12-08 11:09:36'],
            [583,1132,''],
            [586,2216,'2020-12-08 15:50:10'],
            [587,2224,''],
            [603,2278,''],
            [605,1876,'2020-09-15 22:40:11'],
            [610,1895,''],
            [611,1910,''],
            [612,1911,''],
            [613,1912,''],
            [615,1913,''],
            [616,1915,''],
            [617,1916,''],
            [618,1917,''],
            [619,1918,''],
            [620,1919,''],
            [621,1920,''],
            [622,1921,''],
            [623,1897,'2020-09-17 18:30:12'],
            [624,1914,''],
            [625,1922,''],
            [633,1305,''],
            [645,2015,''],
            [648,2018,''],
            [649,2020,''],
            [650,2021,'2020-09-26 21:10:11'],
            [652,1363,''],
            [653,1936,'2020-09-19 19:50:11'],
            [656,2047,'2020-06-30 08:42:17'],
            [660,2054,''],
            [663,2057,'2020-09-29 08:00:15'],
            [664,2058,''],
            [667,2070,'2020-09-29 18:20:12'],
            [671,2191,''],
            [681,2194,''],
            [682,2195,''],
            [683,1451,''],
            [688,2204,''],
            [689,2205,''],
            [692,2225,''],
            [697,2231,''],
            [698,2236,''],
            [699,2238,''],
            [703,2245,'2020-10-10 22:30:13'],
            [709,1502,'2020-10-12 13:48:04'],
            [713,2259,''],
            [722,2269,''],
            [725,2271,''],
            [726,2273,''],
            [727,2274,''],
            [729,2280,''],
            [730,1875,''],
            [731,1550,'2020-10-16 14:14:29'],
            [734,1557,''],
            [735,1560,''],
            [740,1931,'2020-09-19 01:20:09'],
            [742,1628,''],
            [743,1938,'2020-09-19 21:00:18'],
            [745,1641,'2020-10-20 18:00:12'],
            [748,1976,''],
            [749,1977,''],
            [750,2003,''],
            [752,2013,''],
            [754,2014,''],
            [758,1697,'2020-10-26 14:49:39'],
            [759,1698,'2020-10-26 15:05:42'],
            [760,1703,'2020-10-26 18:45:06'],
            [762,2022,''],
            [765,2052,''],
            [768,2059,''],
            [770,2071,''],
            [771,2073,''],
            [772,2165,''],
            [774,1748,''],
            [784,2133,''],
            [791,1796,''],
            [793,2209,''],
            [795,2214,''],
            [798,2226,''],
            [800,2233,''],
            [802,2244,''],
            [803,1838,'2021-02-10 23:19:57'],
            [805,1846,''],
            [811,1873,'2020-09-15 20:43:01'],
            [813,1887,''],
            [815,1903,''],
            [816,1904,''],
            [817,1927,''],
            [818,1929,''],
            [819,1934,''],
            [820,1937,''],
            [821,1942,'2020-09-20 12:39:12'],
            [822,1945,''],
            [823,1950,''],
            [824,1956,''],
            [825,1957,''],
            [826,1961,''],
            [827,1963,'2020-09-22 11:19:11'],
            [830,2262,''],
            [837,2023,''],
            [838,2255,'2020-12-12 14:00:20'],
            [839,2029,''],
            [845,2088,''],
            [847,2098,''],
            [850,2120,''],
            [852,2131,''],
            [853,2137,''],
            [858,2164,'2021-03-03 16:28:18'],
            [859,2277,''],
            [860,2178,''],
            [861,2192,'2020-10-05 16:51:05'],
            [862,2196,''],
            [863,2197,''],
            [864,2198,''],
            [867,2206,''],
            [868,2208,''],
            [870,2223,''],
            [872,2232,''],
            [874,2248,'2020-10-11 15:51:35'],
            [878,2258,'2020-10-12 20:20:33'],
            [879,2260,''],
            [880,2261,''],
            [881,2263,''],
            [882,2264,''],
            [883,2265,''],
            [885,2267,''],
            [886,2272,''],
            [887,2276,''],
            [888,2282,''],
        ];
        if(config('app.interval_unit') != "MONTH") return;
        print_r(config('app.interval_unit'));print_r("\n");
        print_r(config('mail.driver'));print_r("\n");
        foreach($items as $item){
            $subscription = Subscription::find($item[0]);
            $time = $this->nextPaymentTime($subscription,$item[1]);
            if(strtotime($time)>time()){
                if($subscription->transaction_id != $item[1]){
                    if($subscription->status == "Active"){
                        //print_r($subscription->id);    
                    }else{
                        $transaction1 = Transaction::find($subscription->transaction_id);
                        $subscription->status = "Active";
                        $subscription->transaction_id = $item[1];
                        if($item[2] == '' ){
                            $subscription->end_date = null;
                        }else{
                            if( $subscription->end_date != $item[2]){
                                //print_r($subscription->id);    print_r("\n");
                            }
                        }
                        //$subscription->save();
                        $transaction = Transaction::find($item[1]);
                        $paymentSubscription = PaymentSubscription::whereSubscriptionId($transaction->payment_subscription_id)->first();
                        if($paymentSubscription){
                            $paymentSubscription->status = "Active";
                            //$paymentSubscription->save();
                        }
                        //$transaction1->delete();
                    }
                }
            }
            else {
                print_r($time);
                print_r("****");
                print_r($subscription->id);
                print_r("****");
                print_r($subscription->transaction_id);
                print_r("****");
                print_r($subscription->customer_id);
                print_r("\n");
            }
        }
    }
    private function nextPaymentTime($subscription,$transactionId)
    {
        $transaction = Transaction::find($transactionId);
        if($subscription->plan->type=="Free"){
            $cycles = $subscription->plan->free_duration;
            $nextdatetime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($subscription->start_date)) . " +$cycles day"));
            return $nextdatetime;
        }else{
            if ($transaction && $transaction->status=='Completed') {
                $paymentSubscription = PaymentSubscription::whereSubscriptionId($transaction->payment_subscription_id)->first();
                if ($paymentSubscription) {
                    return $this->getEndDate($transaction);
                }
            }else{
                $paypalPlan = PaymentPlan::wherePlanId($subscription->payment_plan_id)->first();
                if ($paypalPlan) {
                    list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paypalPlan->analyzeSlug();
                    $intervalUnit = strtolower(config('app.interval_unit'));
                    $cycles = $frequency;
                    $nextdatetime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($subscription->start_date)) . " +$cycles $intervalUnit"));
                    return $nextdatetime;
                }            
            }
        }
        return null;
    }
    private function getEndDate($transaction=null)//with transaction
    {
        $paymentSubscription = PaymentSubscription::whereSubscriptionId($transaction->payment_subscription_id)->first();
        if($paymentSubscription){
            list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paymentSubscription->analyzeSlug();
            $intervalUnit = strtolower(config('app.interval_unit'));
            switch($provider){
                case 'nmi':
                    $cycles = $frequency;
                    $nextdatetime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($transaction->done_date)) . " +$cycles $intervalUnit"));
                break;
                case 'paypal':
                    $paymentCycles = $paymentSubscription->getCycles();
                    $cycles = $frequency * $paymentCycles;
                    $nextdatetime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($paymentSubscription->start_date)) . " +$cycles $intervalUnit"));
                break;
            }
        }else{
            $nextdatetime = null;
        }
        //print_r($nextdatetime);
        return $nextdatetime;
    }
}
