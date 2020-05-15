<?php
namespace App;

class PayPalProduct
{
    public static function create($service){
        if($service->paypal_product_id===null){
            $client = new \GuzzleHttp\Client();
            list($baseUrl, $clientId,$clientSecret) = PayPalClient::findKeys();
            $res = $client->request('POST', $baseUrl.'/v1/catalogs/products', [
                'auth' => [$clientId, $clientSecret, 'basic'],
                'headers' => [
                    'Accept-Language' => 'en_US',
                    'Accept' => 'application/json' 
                ],
                'json' => [
                    'name' => $service->title,
                    'description' => $service->description,
                    'type' =>'SERVICE',
                ]
            ]);
            $data = json_decode($res->getBody(), true);
            $service->paypal_product_id = $data['id'];
            $service->save();
        }else{
            $client = new \GuzzleHttp\Client();
            list($baseUrl, $clientId,$clientSecret) = PayPalClient::findKeys();
            $res = $client->get($baseUrl.'/v1/catalogs/products/'.$service->paypal_product_id, [
                'auth' => [$clientId, $clientSecret, 'basic'],
                'headers' => [
                    'Accept-Language' => 'en_US',
                    'Accept' => 'application/json' 
                ],
                'json' => [
                    [
                        "op" => "replace",
                        "path" => "/description",
                        "value" => "Product Premium video streaming service"                        
                    ]
                ]
            ]);
            $data = json_decode($res->getBody(), true);
            var_dump($data);   die;         
        }
    }
}