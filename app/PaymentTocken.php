<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class PaymentTocken extends Model
{
    protected $table = 'payment_tockens';
    protected $fillable = ['gateway','tocken','customer_id','last4','card_holder','mode'];
    private $customer;
    public static function validateRules(){
        return array(
            'holder'=>'required',
            'number'=>'required',
            'exp'=>'required',
            'cvc'=>'required',
        );
    }
    public function assign($request){
        $card = $request->input('nmi');
        $user = $request->user('api');
        $nmiClient = new NmiClient;
        $response = $nmiClient->addCustomer($user->customer, $request);
        if(is_array($response)){
            return $response;
        }
        $expiry = explode( ' / ', $card['exp'] );
		$expiry[1] = substr( $expiry[1], -2 );
        $this->gateway = 'nmi';
        $this->holder = $card['holder'];
        $this->tocken = $response;
        $this->customer_id = $user->customer->id;
        $this->last4 = substr( $card['number'], -4 );
        $this->expiry_month = $expiry[0];
        $this->expiry_year = $expiry[1];
        $this->type = $this->findCardType($card['number']);
        if($user->customer->nmi_vault_id == null){
            $user->customer->nmi_vault_id = $tocken->tocken;
            $user->customer->save();
        }        
        return true;
    }
	public function findCardType( $value, $field = 'pattern', $return = 'name' ) {
		$card_types = array(
			array(
				'label' => 'American Express',
				'name' => 'amex',
				'pattern' => '/^3[47]/',
				'valid_length' => '[15]'
			),
			array(
				'label' => 'JCB',
				'name' => 'jcb',
				'pattern' => '/^35(2[89]|[3-8][0-9])/',
				'valid_length' => '[16]'
			),
			array(
				'label' => 'Discover',
				'name' => 'discover',
				'pattern' => '/^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)/',
				'valid_length' => '[16]'
			),
			array(
				'label' => 'MasterCard',
				'name' => 'mastercard',
				'pattern' => '/^5[1-5]/',
				'valid_length' => '[16]'
			),
			array(
				'label' => 'Visa',
				'name' => 'visa',
				'pattern' => '/^4/',
				'valid_length' => '[16]'
			),
			array(
				'label' => 'Maestro',
				'name' => 'maestro',
				'pattern' => '/^(5018|5020|5038|6304|6759|6799|676[1-3])/',
				'valid_length' => '[12, 13, 14, 15, 16, 17, 18, 19]'
			),
			array(
				'label' => 'Diners Club',
				'name' => 'diners-club',
				'pattern' => '/^3[0689]/',
				'valid_length' => '[14]'
			),
		);

		foreach( $card_types as $type ) {
			$card_type = $type['name'];
			$compare = $type[$field];
			if ( ( $field == 'pattern' && preg_match( $compare, $value, $match ) ) || $compare == $value ) {
				return $type[$return];
			}
		}
    }
    public function search(){
        $where = self::whereCustomerId($this->customer->id);
        $items = $where->orderBy('created_at', 'DESC')->get();
        foreach($items as $index=> $item){
            unset($items[$index]->tocken);
        }
        return $items;
    }
    public function assignSearch($request){
        $user = $request->user('api');
        $this->customer = $user->customer;
    }    
    public static function changeSubscription($customerId,$tocken){
        $customer = Customer::find($customerId);
        $nmiClient = new NmiClient;
        $nmiClient->deleteCustomerVault($tocken,$customerId);
        if($customer->nmi_vault_id == $tocken){
            $paymentTocken = PaymentTocken::whereCustomerId($customerId)->first();
            if($paymentTocken){
                $customer->nmi_vault_id = $paymentTocken->tocken;
                $customer->save();
            }else{
                $customer->nmi_vault_id = null;
                $customer->save();
                $subscriptions = Subscription::whereCustomerId($customerId)->get();
                foreach($subscriptions as $subscription){
                    if($subscription&&$subscription->gateway=='nmi'){
                        $subscription->suspendWithNmi();
                    }
                }
            }
        }
    }
}
