<?php
namespace App;

use Illuminate\Support\Facades\Log;

class NmiClient
{
    private $securityKey;
    private $currency = 'USD';
    private $username;
    private $password;
    private $addCustomerMethod = 'validate';
    private $customerReceipt = false;
    public function __construct()
    {
        $this->username = env('NMI_USERNAME');
        $this->password = env('NMI_PASSWORD');
        $this->securityKey = env('NMI_SECURITY_KEY');
    }
    public function deleteCustomerVault($token,$customerId){
        $args = [
            'customer_vault'=>'delete_customer',
            'customer_vault_id'=>$token,
            'type'=>'deleteCustomer'
        ];
        Log::channel('nmiPayments')->info("Info: Beginning deleting customer vault $token for customer $customerId");
        $response =$this->nmiRequest($args);
        if ($response->approved) {
            Log::channel('nmiPayments')->info("Success: Deleting customer vault");

        } else {
            Log::channel('nmiPayments')->error(sprintf('Gateway Error: %s', $response->error_message));
        }
    }
    public function addCustomerVault($customer,$request){
        Log::channel('nmiPayments')->info("Info: Beginning renewal processing payment for customer vault");
        try {
            $nmiCustomerId = $this->addCustomer($customer, $request);
            if(is_array($nmiCustomerId)){
                return $nmiCustomerId;
            }
            if($request->exists('nmi-payment-token')){
                $token = $request->input('nmi-payment-token');
            }
            if(isset($token)===false){
                $tocken = new PaymentTocken;
                $card = $request->input('nmi');
                $user = $request->user('api');
                $expiry = explode( ' / ', $card['exp'] );
                if(isset($expiry[1])===false){
                    throw new \Exception( 'Card expiration should be in the format MMYY, MM/YY or MM-YY. Months may be one digit with a '/' or '-', and years may be four digits' );
                }
                $expiry[1] = substr( $expiry[1], -2 );
                $tocken->gateway = 'nmi';
                $tocken->holder = $card['holder'];
                $tocken->tocken = $nmiCustomerId;
                $tocken->customer_id = $user->customer->id;
                $tocken->last4 = substr( $card['number'], -4 );
                $tocken->expiry_month = $expiry[0];
                $tocken->expiry_year = $expiry[1];
                $tocken->type = $tocken->findCardType($card['number']);
                $tocken->save();
            }
            return array(
                'result' => 'success',
            );

        } catch (\Exception $e) {
            //wc_add_notice( sprintf( __( 'Gateway Error: %s', 'wc-nmi' ), $e->getMessage() ), 'error' );
            Log::channel('nmiPayments')->error(sprintf('Gateway Error: %s', $e->getMessage()));
            return [
                'result' => 'failed',
                'error_message' => $e->getMessage(),
            ];
        }
    }
    /**
     * Process the subscription
     *
     * @param  Transaction $transaction = order
     * @return array
     */
    public function processSubscription($transaction, $request)
    {
        $tokenId = $request->exists('nmi-payment-token') ? $request->input('nmi-payment-token') : '';
        $nmiCustomerId = $transaction->nmi_vault_id;
        if (!$nmiCustomerId || !is_string($nmiCustomerId)) {
            $nmiCustomerId = 0;
        }
        Log::channel('nmiPayments')->info("Info: Beginning processing payment for transaction $transaction->id for the amount of {$transaction->total}");
        
        // Use NMI CURL API for payment
        try {

            // Pay using a saved card!
            if ($tokenId !== 'new' && $tokenId) {
                $token = PaymentTocken::find($tokenId);

                if ( ! $token ) {
                    throw new \Exception( 'Invalid payment method. Please input a new card number.');
                }

                $nmiCustomerId = $token->tocken;
            }

            // Save token
            if (!$nmiCustomerId || !$tokenId || $tokenId === 'new') {

                // Check for CC details filled or not
                /*if( empty( $_POST['nmi-card-number'] ) || empty( $_POST['nmi-card-expiry'] ) || empty( $_POST['nmi-card-cvc'] ) ) {
                throw new Exception( __( 'Credit card details cannot be left incomplete.', 'wc-nmi' ) );
                }*/

                // Check for card type supported or not
                /*if( ! in_array( $this->get_card_type( $_POST['nmi-card-number'], 'pattern', 'name' ), $this->allowed_card_types ) ) {
                $this->log( sprintf( __( 'Card type being used is not one of supported types in plugin settings: %s', 'wc-nmi' ), $this->get_card_type( $_POST['nmi-card-number'] ) ) );
                throw new Exception( __( 'Card Type Not Accepted', 'wc-nmi' ) );
                }*/

                //$maybe_saved_card = isset( $_POST['wc-nmi-new-payment-method'] ) && ! empty( $_POST['wc-nmi-new-payment-method'] );
                $nmiCustomerId = $this->addCustomer($transaction->customer, $request);

                if ( is_array( $nmiCustomerId ) ) {
                    throw new \Exception( $nmiCustomerId['error_message'] );
                }/* else {
                    $skip = !( $this->saved_cards && $maybe_saved_card );
                    $card = $this->add_card( $nmiCustomerId, $skip );
                }
                $card_id = $nmiCustomerId;*/
                $tocken = new PaymentTocken;
                $card = $request->input('nmi');
                $user = $request->user('api');
                $expiry = explode( ' / ', $card['exp'] );
                if(isset($expiry[1])===false){
                    throw new \Exception( 'Card expiration should be in the format MMYY, MM/YY or MM-YY. Months may be one digit with a '/' or '-', and years may be four digits' );
                }
                $expiry[1] = substr( $expiry[1], -2 );
                $tocken->gateway = 'nmi';
                $tocken->holder = $card['holder'];
                $tocken->tocken = $nmiCustomerId;
                $tocken->customer_id = $user->customer->id;
                $tocken->last4 = substr( $card['number'], -4 );
                $tocken->expiry_month = $expiry[0];
                $tocken->expiry_year = $expiry[1];
                $tocken->type = $tocken->findCardType($card['number']);
                $tocken->save();
            }

            // Store the ID in the order
            //$this->save_meta( $order_id, $nmiCustomerId, $card_id, $card );

            if ($transaction->total > 0 ) {
                $paymentResponse = $this->processSubscriptionPayment($transaction);

                if (is_array($paymentResponse)) {
                    throw new \Exception($paymentResponse[1]);
                }

            } else {
                if($transaction->coupon_id!=null){
                    $coupon = Coupon::find($transaction->coupon_id);
                    if($coupon->discount == 100 && $coupon->form == '%' || $transaction->total == 0){
                        $transaction->status = 'Completed';
                        $transaction->save();        
                    }
                }
                //$transaction->payment_complete();
                //$transaction->save();
            }

            // Return thank you page redirect
            return array(
                'result' => 'success',
            );

        } catch (\Exception $e) {
            //wc_add_notice( sprintf( __( 'Gateway Error: %s', 'wc-nmi' ), $e->getMessage() ), 'error' );
            Log::channel('nmiPayments')->error(sprintf('Gateway Error: %s, code:%s', $e->getMessage(),$e->getCode()));
            return [
                'result' => 'failed',
                'error_message' => $e->getMessage(),
            ];
        }
    }
    /**
     * processSubscriptionPayment function.
     *
     * @access public
     * @param mixed $transaction
     */
    public function processSubscriptionPayment($transaction)
    {

        $amount = $transaction->total;
        $nmiCustomer = $transaction->customer->nmi_vault_id;
        $paymentSubscription = PaymentSubscription::whereSubscriptionId($transaction->payment_subscription_id)->first();
        list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paymentSubscription->analyzeSlug();
        $cycles = 1;
        $transactions = Transaction::wherePaymentSubscriptionId($transaction->payment_subscription_id)->get();
        if ($transactions) {
            $cycles = count($transactions);
        }
        $description = 'Fitemos - Order ' . $transaction->id . ' ';
        $description .= $transaction->plan->service->title . ' ';
        if ($frequency > 1) {
            $description .= $frequency . ' months ';
        } else {
            $description .= $frequency . ' month ';
        }

        $description .= $cycles . ' cycles';
        $args = array(
            'security_key' => $this->securityKey,
            'orderid' => $transaction->id,
            'order_description' => $description,
            'amount' => $amount,
            //'transactionid'        => $order->get_transaction_id(),
            'type' => 'sale',
            'ipaddress' => '',
            'first_name' => $transaction->customer->first_name,
            'last_name' => $transaction->customer->last_name,
            'country' => $transaction->customer->country,
            'email' => $transaction->customer->email,
            'currency' => $this->currency,
        );
        if ($nmiCustomer) {
            $args['customer_vault_id'] = $nmiCustomer;
        } else {
            /*$args['ccnumber']            = '4111111111111111';
        $args['ccexp']                = '0420';
        $args['cvv']                = '999';*/
        }

        // Charge the customer
        $response = $this->nmiRequest($args);

        if ($response->approved) {
            $transaction->payment_transaction_id = $response->transactionid;

            if ($args['type'] == 'sale') {

                $transaction->status = 'Completed';
                $transaction->createInvoice();
                $completeMessage = sprintf('NMI charge complete (Charge ID: %s)', $response->transactionid);
                Log::channel('nmiPayments')->info("Success: $completeMessage");

            } else {
                $transaction->status = 'Pending';
            }
            $transaction->content = $description;
            $transaction->save();

        } else {
            Log::channel('nmiPayments')->error(sprintf('Gateway Error: %s', $response->error_message));
            if($response->declined || $response->error){
                $transaction->status = 'Declined';
                $transaction->save();
            }
            return ['nmi_error', $response->error_message];
        }

        return $response;
    }
    /**
     * Add a customer to NMI via the API.
     *
     * @param Customer $customer
     * @param string $nmi_token
     * @return int|WP_ERROR
     */
    public function addCustomer($customer, $request)
    {
        if($request->exists('nmi-payment-token')){
            $token = $request->input('nmi-payment-token');
        }
        if(isset($token) && $token!="" && $token!="new"){
            $card = PaymentTocken::find($token);
            if($card){
                $customer->nmi_vault_id = $card->tocken;
                $customer->save();    
                return $card->tocken;
            }
        }
        $expiry = explode(' / ', $request['nmi']['exp']);
        if(isset($expiry[1])===false){
            return ['error'=>'nmi', 'error_message'=>"Card expiration should be in the format MMYY, MM/YY or MM-YY. Months may be one digit with a '/' or '-', and years may be four digits"];
        }
        $expiry[1] = substr($expiry[1], -2);
        $customerName = sprintf('Customer: %s %s', $customer->first_name, $customer->last_name);
        $args = array(
            'order_description' => isset($request['nmi']['holder']) ? 'Customer: ' . $request['nmi']['holder'] : $customerName,
            'ccnumber' => $request['nmi']['number'],
            'ccexp' => $expiry[0] . $expiry[1],
            'cvv' => $request['nmi']['cvc'],
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'address1' => '',
            'address2' => '',
            'city' => '',
            'state' => '',
            'country' => $customer->country,
            'email' => $customer->email,
            'customer_vault' => 'add_customer',
            'customer_vault_id' => '',
            'currency' => $this->currency,
        );

        if ($this->addCustomerMethod == 'validate') {
            $customerMethod = array('type' => 'validate');
        } else {
            $customerMethod = array('type' => 'auth', 'amount' => 1.00);
        }

        $args = array_merge($args, $customerMethod);

        $response = $this->nmiRequest($args);

        if ($response->error || $response->declined) {
            return ['error'=>'nmi', 'error_message'=>$response->error_message];
        } elseif (!empty($response->customer_vault_id)) {

            // Store the ID on the user account if logged in
            $customer->nmi_vault_id = $response->customer_vault_id;
            $customer->save();

            // Voiding add_customer auth transaction
            if ($this->addCustomerMethod == 'auth') {
                $args = array(
                    'amount' => 1.00,
                    'transactionid' => $response->transactionid,
                    'type' => 'cancel',
                );
                $this->nmiRequest($args);
            }
    
            return $response->customer_vault_id;
        }

        $error_message = 'Unable to add customer';
        Log::channel('nmiPayments')->error(sprintf('Gateway Error: %s', $error_message));
        return ['error'=>'error', 'error_message'=>$error_message];
    }
    /**
     * scheduledSubscriptionPayment function.
     *
     * @param $renewalTransaction Transaction object created to record the renewal payment.
     * @access public
     * @return void
     */
    public function scheduledSubscriptionPayment($renewalTransaction)
    {

        while (1) {
            $response = $this->processSubscriptionPayment($renewalTransaction);

            if (is_array($response)) {
                throw new \Exception($response[1]);
            } else {
                // Successful
                break;
            }
        }
    }

    private function nmiRequest($args)
    {
        $gatewayDebug = env('PAYMENT_TEST_MODE');
        $nmiRequest = new NmiRequest($this->username, $this->password, $gatewayDebug);

        if (isset($args['customer_vault'])) {
            $nmiRequest->customer_vault = $args['customer_vault'];
        }
        if (isset($args['customer_vault_id']) && !empty($args['customer_vault_id'])) {
            $nmiRequest->customer_vault_id = $args['customer_vault_id'];
        }
        if (isset($args['amount'])) {
            $nmiRequest->amount = $args['amount'];
        }
        if (isset($args['transactionid']) && !empty($args['transactionid'])) {
            $nmiRequest->transactionid = $args['transactionid'];
        }
        if (isset($args['ccnumber'])) {
            $nmiRequest->ccnumber = $args['ccnumber'];
        }
        if (isset($args['ccexp'])) {
            $nmiRequest->ccexp = $args['ccexp'];
        }
        if (isset($args['cvv'])) {
            $nmiRequest->cvv = $args['cvv'];
        }
        if (isset($args['first_name'])) {
            $nmiRequest->first_name = $args['first_name'];
        }
        if (isset($args['last_name'])) {
            $nmiRequest->last_name = $args['last_name'];
        }
        if (isset($args['address1'])) {
            $nmiRequest->address1 = $args['address1'];
        }
        if (isset($args['address2'])) {
            $nmiRequest->address2 = $args['address2'];
        }
        if (isset($args['city'])) {
            $nmiRequest->city = $args['city'];
        }
        if (!in_array($args['type'], array('capture', 'cancel', 'refund'))) {
            if (isset($args['state']) && !empty($args['state'])) {
                $nmiRequest->state = $args['state'];
            } else {
                $nmiRequest->state = 'NA';
            }
        }
        if (isset($args['country'])) {
            $nmiRequest->country = $args['country'];
        }
        if (isset($args['zip'])) {
            $nmiRequest->zip = $args['zip'];
        }
        if (isset($args['email'])) {
            $nmiRequest->email = $args['email'];
        }
        if (isset($args['phone'])) {
            $nmiRequest->phone = $args['phone'];
        }
        if (isset($args['company'])) {
            $nmiRequest->company = $args['company'];
        }
        if (isset($args['orderid'])) {
            $nmiRequest->orderid = $args['orderid'];
        }
        if (isset($args['order_description'])) {
            $nmiRequest->order_description = substr($args['order_description'], 0, 99);
        }

        $nmiRequest->currency = isset($args['currency']) ? $args['currency'] : 'USD';
        $nmiRequest->customer_receipt = isset($args['customer_receipt']) ? $args['customer_receipt'] : $this->customerReceipt;
        $nmiRequest->ipaddress = isset($args['ipaddress']) ? $args['ipaddress'] : User::getUserIP();

        $response = $nmiRequest->{$args['type']}();

        return $response;
    }
}
