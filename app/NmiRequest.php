<?php
namespace App;

use Illuminate\Support\Facades\Log;
/**
 * Sends requests to the Network Merchants (NMI) gateways.
 *
 * @package    Network Merchants (NMI)
 * @subpackage NMI_Request
 */
class NmiRequest {

    protected $_username;
    protected $_password;
	protected $_logging;
    protected $_post_string;
    public $VERIFY_PEER = false; // Set to false if getting connection errors.
    protected $_sandbox = true;
    protected $_log_file = false;
    private $type;
	private $url = 'https://secure.networkmerchants.com/api/transact.php';

	public function __construct( $username = false, $password = false, $logging = false, $url = false ) {
		if( $url ) {
			$this->url = $url;
		}
        $this->_username = config('app.nmi_username');
        $this->_password = config('app.nmi_password');
	$this->_logging = true;
        $this->_sandbox = config('app.payment_test_mode');
        $this->_log_file = false;        
	}

    /**
     * Checks to make sure a field is actually in the API before setting.
     * Set to false to skip this check.
     */
    public $verify_fields = true;

    /**
     * A list of all fields in the Network Merchants (NMI) API.
     * Used to warn user if they try to set a field not offered in the API.
     */
	private $_all_nmi_fields = array(
		"type","username","password","ccnumber","ccexp","cvv","amount","transactionid","orderid","ipaddress","tax","shipping","first_name","last_name","company","address1","address2",
		"city","state","zip","country","phone","fax","email","customer_receipt","recurring","shipping","order_description","customer_vault","customer_vault_id","currency","dup_seconds",
		"checkaba","checkaccount","account_type","checkname","payment","account_holder_type",
    );

	/**
	 * Product Sale transaction (Capture On)
	 * Transaction do completed/processing
	 */
    public function sale() {
        $this->type = "sale";
		return $this->_sendRequest();
    }

	/**
	 * Product Sale transaction (Capture Off)
	 * Transaction to put on-hold
	 */
	public function auth() {
        $this->_post_fields['type'] = "auth";
		return $this->_sendRequest();
    }

	/**
	 * Add Customer
	 */
	public function validate() {
        $this->type = "validate";
        $this->_post_fields['type'] = "validate";        
        return $this->_sendRequest();
    }

	/**
	 * Process Product on-hold to complete/processing transaction (Capture Off)
	 */
	public function capture() {
        $this->type = "capture";
        return $this->_sendRequest();
    }

	/**
	 * Process Product on-hold to cancel/refund transaction (Capture Off)
	 */
	public function cancel() {
        $this->type = "void";
        $this->_post_fields['type'] = "void";
		return $this->_sendRequest();
    }

	/**
	 * Product Sale Refund transaction (Capture On)
	 */
    public function refund() {
        $this->type = "refund";
		return $this->_sendRequest();
    }

    public function deleteCustomer(){
		return $this->_sendRequest();
    }
     /**
     * Alternative syntax for setting x_ fields.
     *
     * Usage: $sale->method = "echeck";
     *
     * @param string $name
     * @param string $value
     */
    public function __set( $name, $value ) {
        $this->setField( $name, $value );
    }

    /**
     * Quickly set multiple fields.
     *
     * Note: The prefix x_ will be added to all fields. If you want to set a
     * custom field without the x_ prefix, use setCustomField or setCustomFields.
     *
     * @param array $fields Takes an array or object.
     */
    public function setFields( $fields ) {
        $array = (array) $fields;
        foreach( $array as $key => $value ) {
            $this->setField( $key, $value );
        }
    }

    /**
     * Set an individual name/value pair. This will append x_ to the name
     * before posting.
     *
     * @param string $name
     * @param string $value
     */
    public function setField( $name, $value ) {
        if( $this->verify_fields ) {
            if( in_array( $name, $this->_all_nmi_fields ) ) {
                $this->_post_fields[$name] = $value;
            } else {
                throw new NMI_Exception( "Error: no field $name exists in the NMI API.
                To set a custom field use setCustomField('field','value') instead." );
            }
        } else {
            $this->_post_fields[$name] = $value;
        }
    }

    /**
     * Unset an x_ field.
     *
     * @param string $name Field to unset.
     */
    public function unsetField( $name ) {
        unset( $this->_post_fields[$name] );
    }

    /**
     *
     *
     * @param string $response
     *
     * @return NMI_Response
     */
    protected function _handleResponse( $response ) {
        return new NmiGatewayResponse( $response );
    }

    /**
     * @return string
     */
    protected function _getPostUrl() {
        return $this->url;
    }

    /**
     * Converts the x_post_fields array into a string suitable for posting.
     */
    protected function _setPostString() {
        $this->_post_fields['username'] = $this->_username;
        $this->_post_fields['password'] = $this->_password;
        $this->_post_string = "";
        foreach( $this->_post_fields as $key => $value ) {
            $this->_post_string .= "$key=" . urlencode( $value ) . "&";
        }
        $this->_post_string = rtrim( $this->_post_string, "& " );
    }

    /**
     * Set a log file.
     *
     * @param string $filepath Path to log file.
     */
    public function setLogFile( $filepath ) {
        $this->_log_file = $filepath;
    }

    /**
     * Return the post string.
     *
     * @return string
     */
    public function getPostString() {
        return $this->_post_string;
    }

    /**
     * Posts the request to Network Merchants (NMI) & returns response.
     *
     * @return NMI_Response The response.
     */
    protected function _sendRequest() {
        $this->_setPostString();
        $post_url = $this->_getPostUrl();
        if($this->_sandbox){
            // if($this->type=='sale') $response = "response=1&responsetext=Approved&authcode=00".rand(1000,9999)."&transactionid=".time().rand(10,99)."&avsresponse=&cvvresponse=&orderid=".$this->_post_fields['orderid']."&type=sale&response_code=100&customer_vault_id=".$this->_post_fields['customer_vault_id'];
            // else  $response = "response=1&responsetext=Approved&authcode=00".rand(1000,9999)."&transactionid=".time().rand(10,99)."&avsresponse=&cvvresponse=M&orderid=&type=validate&response_code=100&customer_vault_id=".time();
            if($this->type=='sale') $response = "response=2&responsetext=Insufficient funds&authcode=&transactionid=5780958322&avsresponse=&cvvresponse=&orderid=4251&type=sale&response_code=202&customer_vault_id=1535325496";
            else  $response = "response=1&responsetext=Approved&authcode=00".rand(1000,9999)."&transactionid=".time().rand(10,99)."&avsresponse=&cvvresponse=M&orderid=&type=validate&response_code=100&customer_vault_id=".time();
            return $this->_handleResponse( $response );
        }

        $curl_request = curl_init( $post_url );

        curl_setopt( $curl_request, CURLOPT_POSTFIELDS, $this->_post_string );
        curl_setopt( $curl_request, CURLOPT_HEADER, 0 );
        curl_setopt( $curl_request, CURLOPT_TIMEOUT, 45 );
        curl_setopt( $curl_request, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $curl_request, CURLOPT_SSL_VERIFYHOST, 2 );

		if( $this->VERIFY_PEER ) {
            //curl_setopt( $curl_request, CURLOPT_CAINFO, dirname( dirname( __FILE__ ) ) . '/ssl/cert.pem' );
        } else {
            curl_setopt( $curl_request, CURLOPT_SSL_VERIFYPEER, false );
        }

        if( preg_match( '/xml/', $post_url ) ) {
            curl_setopt( $curl_request, CURLOPT_HTTPHEADER, Array( "Content-Type: text/xml" ) );
        }

        $response = curl_exec( $curl_request );

        // Saving to Log here
        if($this->_logging){
            $message = sprintf( "\nPosting to: \n%s\nRequest: \n%s\nResponse: \n%s", $post_url, $this->_post_string, $response );
            Log::channel('nmiPayments')->info($message);
        }

        if( $curl_error = curl_error( $curl_request ) ) {
            Log::channel('nmiRequests')->error("----CURL ERROR----\n$curl_error\n\n");
        }
        // Do not log requests that could contain CC info.
        Log::channel('nmiRequests')->error("----Response----\n$response\n\n");
        curl_close( $curl_request );

        return $this->_handleResponse( $response );
    }

}