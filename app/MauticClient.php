<?php
namespace App;

use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;

class MauticClient
{
    private $auth;
    private $apiUrl;
    public function authenticate()
    {
        $settings = array(
            'baseUrl' => env('MAUTIC_API_URL'),
            'userName' => env('MAUTIC_API_USERNAME'),
            'password' => env('MAUTIC_API_PASSWORD'),
            //'version'      => 'OAuth2',
            //'clientKey'=>'1_63mb6k6rkxcsk0w0oo44c884kc0840kcc04g8cw8wo8080w0g0',
            //'clientSecret'=>'2ojzeb8hs0w0ssc00s8okw0sww08g8kkw0088c4kcokcwwgok0',
            //'callback'=>'https://dev.fitemos.com/api/redirect'
        );

        // Initiate the auth object
        $initAuth = new ApiAuth();
        $this->auth = $initAuth->newAuth($settings, 'BasicAuth');
        //$this->auth = $initAuth->newAuth($settings);
        /*if ($this->auth->validateAccessToken()) {
            if ($this->auth->accessTokenUpdated()) {
                $accessTokenData = $this->auth->getAccessTokenData();
        
                //store access token data however you want
            }
        }*/
        $this->apiUrl = env('MAUTIC_API_URL') . '/api';
    }
    private function getFields()
    {
        // Get contact field context:
        $api = new MauticApi();
        $fieldApi = $api->newApi("contactFields", $this->auth, $this->apiUrl);
        $fields = $fieldApi->getList();
        return $fields;
    }
    private function getList()
    {
        // Get contact field context:
        $api = new MauticApi();
        $contactApi = $api->newApi("contacts", $this->auth, $this->apiUrl);
        $contacts = $contactApi->getList();
        return [$contacts, $contactApi];
    }
    private function findEmail($contacts, $email)
    {
        foreach ($contacts['contacts'] as $index => $contact) {
            if (isset($contact['fields']['core']['email'])) {
                if ($contact['fields']['core']['email']['value'] === $email) {
                    return $contact['id'];
                }

            }
        }
        return false;
    }
    public static function subscribe($name,$email,$lastName=null){
        $client = new MauticClient;
        $client->authenticate();
        list($contacts, $contactApi) = $client->getList();
        $id = $client->findEmail($contacts, $email);
        if ($id===false) {
            $data = [
                'firstname' => $name,
                'email' => $email,
            ];
            if($lastName)$data['lastname']=$lastName;
            $contract = $contactApi->create($data);
        }
    }
    public static function scrape()
    {
        $client = new MauticClient;
        $client->authenticate();
        /*$fields = $client->getFields();
        foreach($fields['fields'] as $index=>$field){
        print_r($field['label']);
        print_r("\n");
        }*/
        list($contacts, $contactApi) = $client->getList();
        $customers = Customer::all();
        foreach ($customers as $customer) {
            $id = $client->findEmail($contacts, $customer->email);
            $customer->extends();
            if ($id) {
                $data = [
                    'firstname' => $customer->first_name,
                    'lastname' => $customer->last_name,
                    'birthdate' => $customer->birthday,
                    'whatsapp' => $customer->whatsapp_phone_number,
                    'weight' => $customer->current_weight,
                    'weight_unit' => $customer->current_weight_unit,
                    'height' => $customer->current_height,
                    'height_unit' => $customer->current_height_unit,
                    'country' => $customer->country,
                    'gender' => $customer->gender,
                    'imc' => $customer['imc'],
                    'active_subscription' => $customer['activeSubscription'],
                    'package_name' => $customer['serviceName'],
                    'plan_frequency' => $customer['planFrequency'],
                    'training_place' => $customer->training_place,
                    'total_spend' => $customer['total_paid'],
                    'coupon' => $customer->coupon ? $customer->coupon->name : "",
                    'process_payment' => $customer->first_payment_date ? "Yes" : "No",
                    'registered'=>'yes',
                ];
                $contract = $contactApi->edit($id, $data, false);
            } else {
                $data = [
                    'firstname' => $customer->first_name,
                    'lastname' => $customer->last_name,
                    'birthdate' => $customer->birthday,
                    'email' => $customer->email,
                    'whatsapp' => $customer->whatsapp_phone_number,
                    'weight' => $customer->current_weight,
                    'weight_unit' => $customer->current_weight_unit,
                    'height' => $customer->current_height,
                    'height_unit' => $customer->current_height_unit,
                    'country' => $customer->country,
                    'gender' => $customer->gender,
                    'imc' => $customer['imc'],
                    'active_subscription' => $customer['activeSubscription'],
                    'package_name' => $customer['serviceName'],
                    'plan_frequency' => $customer['planFrequency'],
                    'training_place' => $customer->training_place,
                    'total_spend' => $customer['total_paid'],
                    'registration_date' => $customer['registration_date'],
                    'coupon' => $customer->coupon ? $customer->coupon->name : "",
                    'process_payment' => $customer->first_payment_date ? "Yes" : "No",
                    'registered'=>'yes',
                ];
                $contract = $contactApi->create($data);
            }
        }
    }
}
