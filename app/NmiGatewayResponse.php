<?php
namespace App;
/**
 * Parses an Network Merchants (NMI) Response.
 *
 * @package    Network Merchants (NMI)
 * @subpackage NMI
 */
class NmiGatewayResponse
{
    const APPROVED = 1;
    const DECLINED = 2;
    const ERROR = 3;

    public $approved;
    public $declined;
    public $error;

    public $response;
    public $responsetext;
    public $authcode;
    public $transactionid;
    public $avsresponse;
    public $cvvresponse;
    public $orderid;
    public $type;
    public $response_code;
    public $customer_vault_id;

    private $_response_array = array(); // An array with the split response.

    /**
     * Constructor. Parses the Network Merchants (NMI) response string.
     *
     * @param string $response      The response from the NMI server.
     */
    public function __construct($response)
    {

        if ($response) {

            // Split Array
            parse_str($response, $response_arr);

            /**
             * If Network Merchants (NMI) doesn't return a delimited response.
             */
            if (count($response_arr) < 8) {
                $this->approved = false;
                $this->error = true;
                $this->error_message = sprintf('Unrecognized response from the gateway: %s', $response);
                return;
            }

            // Set all fields
            foreach ($response_arr as $key => $value) {
                $this->{$key} = $response_arr[$key];
            }

            $this->approved = ($this->response == 1);
            $this->declined = ($this->response == 2);
            $this->error = ($this->response == 3);

            if ($this->declined) {
                $this->error_message = 'Your card has been declined.';
            }

            if ($this->error) {
                $this->error_message = $this->responsetext;
            }

        } else {
            $this->approved = false;
            $this->error = true;
            $this->error_message = 'Error connecting to the gateway';
        }
    }
}
