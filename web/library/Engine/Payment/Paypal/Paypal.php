<?php

class Engine_Payment_Paypal_Paypal {

    protected $PROXY_HOST, $PROXY_PORT, $API_UserName, $API_Password, $API_Signature, $API_Endpoint, $PAYPAL_URL;
    protected $SandboxFlag;
    protected $USE_PROXY = false;
    protected $version = "98";
    //   protected $version     = "111";
    protected $session;
    // BN Code 	is only applicable for partners
    protected $sBNCode = "PP-ECWizard";
    private static $_instance = null;
    protected $_appSetting;
    protected $Currency_Code;
    protected $Payment_type;

    public function __construct() {

        $objCore = Engine_Core_Core::getInstance();
        $this->_appSetting = $objCore->getAppSetting();

        $this->PROXY_HOST = $this->_appSetting->paypal->proxyHost;
        $this->PROXY_PORT = $this->_appSetting->paypal->proxyPort;
        $this->API_UserName = $this->_appSetting->paypal->username;
        $this->API_Password = $this->_appSetting->paypal->password;
        $this->API_Signature = $this->_appSetting->paypal->signature;
        $this->SandboxFlag = $this->_appSetting->paypal->sandboxFlag;
        $this->Currency_Code = $this->_appSetting->paypal->currencyCodeType;
        $this->Payment_type = $this->_appSetting->paypal->paymentType;

        if ($this->SandboxFlag == true) {
            $this->API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
            $this->PAYPAL_URL = "https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=";
        } else {
            $this->API_Endpoint = "https://api-3t.paypal.com/nvp";
            $this->PAYPAL_URL = "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=";
        }
    }

    private function __clone() {
        
    }

    public static function getInstance() {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Engine_Payment_Paypal_Paypal();
        return self::$_instance;
    }

    /* An express checkout transaction starts with a token, that
      identifies to PayPal your transaction
      In this example, when the script sees a token, the script
      knows that the buyer has already authorized payment through
      paypal.  If no token was found, the action is to send the buyer
      to PayPal to first authorize payment
     */

    /*
      '-------------------------------------------------------------------------------------------------------------------------------------------
      ' Purpose: 	Prepares the parameters for the SetExpressCheckout API Call.
      ' Inputs:
      '		paymentAmount:  	Total value of the shopping cart
      '		currencyCodeType: 	Currency code value the PayPal API
      '		paymentType: 		paymentType has to be one of the following values: Sale or Order or Authorization
      '		returnURL:			the page where buyers return to after they are done with the payment review on PayPal
      '		cancelURL:			the page where buyers return to when they cancel the payment review on PayPal
      '--------------------------------------------------------------------------------------------------------------------------------------------
     */

    public function CallShortcutExpressCheckout($paymentAmount, $returnURL, $cancelURL, $payment_request_quantity, $description, $payment_request_number, $payment_type, $custom, $subscription_type) {
        //------------------------------------------------------------------------------------------------------------------------------------
        // Construct the parameter string that describes the SetExpressCheckout API call in the shortcut implementation

        $nvpstr = "&RETURNURL=" . $returnURL;
        $nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;
        $nvpstr = $nvpstr . "&ALLOWNOTE=1";
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_PAYMENTACTION=" . $this->Payment_type;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_CURRENCYCODE=" . $this->Currency_Code;
        //$currency_Code
       // $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_CURRENCYCODE=" .$currency_Code;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_AMT=" . $paymentAmount;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_ITEMAMT=" . $paymentAmount;
        $nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_QTY0=" . $payment_request_quantity;
        $nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_AMT0=" . $paymentAmount;
        $nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_NAME0=" . $description;
        $nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_NUMBER0=" . $payment_request_number;
        $nvpstr = $nvpstr . "&L_BILLINGTYPE0=RecurringPayments";
        $nvpstr = $nvpstr . "&L_BILLINGAGREEMENTDESCRIPTION0=" . $subscription_type;
        $nvpstr = $nvpstr . "&L_PAYMENTTYPE0=" . $payment_type;
        $nvpstr = $nvpstr . "&L_CUSTOM0=" . $custom;



        //'--------------------------------------------------------------------------------------------------------------- 
        //' Make the API call to PayPal
        //' If the API call succeded, then redirect the buyer to PayPal to begin to authorize payment.  
        //' If an error occured, show the resulting errors
        //'---------------------------------------------------------------------------------------------------------------
        $resArray = $this->hash_call("SetExpressCheckout", $nvpstr);
        return $resArray;
    }

    /*
      '-------------------------------------------------------------------------------------------------------------------------------------------
      ' Purpose: 	Prepares the parameters for the SetExpressCheckout API Call.
      ' Inputs:
      '		paymentAmount:  	Total value of the shopping cart
      '		currencyCodeType: 	Currency code value the PayPal API
      '		paymentType: 		paymentType has to be one of the following values: Sale or Order or Authorization
      '		returnURL:			the page where buyers return to after they are done with the payment review on PayPal
      '		cancelURL:			the page where buyers return to when they cancel the payment review on PayPal
      '		shipToName:		the Ship to name entered on the merchant's site
      '		shipToStreet:		the Ship to Street entered on the merchant's site
      '		shipToCity:			the Ship to City entered on the merchant's site
      '		shipToState:		the Ship to State entered on the merchant's site
      '		shipToCountryCode:	the Code for Ship to Country entered on the merchant's site
      '		shipToZip:			the Ship to ZipCode entered on the merchant's site
      '		shipToStreet2:		the Ship to Street2 entered on the merchant's site
      '		phoneNum:			the phoneNum  entered on the merchant's site
      '--------------------------------------------------------------------------------------------------------------------------------------------
     */

    function CallMarkExpressCheckout($paymentAmount, $returnURL, $cancelURL
    , $shipToName, $shipToStreet, $shipToCity
    , $shipToState, $shipToCountryCode
    , $shipToZip, $shipToStreet2
    , $phoneNum) {
        //------------------------------------------------------------------------------------------------------------------------------------
        // Construct the parameter string that describes the SetExpressCheckout API call in the shortcut implementation

        $nvpstr = "&PAYMENTREQUEST_0_AMT=" . $paymentAmount;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_PAYMENTACTION=" . $this->Payment_type;
        $nvpstr = $nvpstr . "&RETURNURL=" . $returnURL;
        $nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_CURRENCYCODE=" . $this->Currency_Code;
        $nvpstr = $nvpstr . "&ADDROVERRIDE=1";
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTONAME=" . $shipToName;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOSTREET=" . $shipToStreet;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOSTREET2=" . $shipToStreet2;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOCITY=" . $shipToCity;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOSTATE=" . $shipToState;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE=" . $shipToCountryCode;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOZIP=" . $shipToZip;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOPHONENUM=" . $phoneNum;




        //'--------------------------------------------------------------------------------------------------------------- 
        //' Make the API call to PayPal
        //' If the API call succeded, then redirect the buyer to PayPal to begin to authorize payment.  
        //' If an error occured, show the resulting errors
        //'---------------------------------------------------------------------------------------------------------------
        $resArray = $this->hash_call("SetExpressCheckout", $nvpstr);
        $ack = strtoupper($resArray["ACK"]);
        if ($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {
            $token = urldecode($resArray["TOKEN"]);
            $payerID = $this->GetShippingDetails($token);
            if ($payerID) {
                $confirmPayment = $this->ConfirmPayment($paymentAmount, $token, $payerID);
                return $confirmPayment;
            }
            return $resArray;
        }
        return $resArray;
    }

    /*
      '-------------------------------------------------------------------------------------------
      ' Purpose: 	Prepares the parameters for the GetExpressCheckoutDetails API Call.
      '
      ' Inputs:
      '		None
      ' Returns:
      '		The NVP Collection object of the GetExpressCheckoutDetails Call Response.
      '-------------------------------------------------------------------------------------------
     */

    function GetShippingDetails($token) {
        //'--------------------------------------------------------------
        //' At this point, the buyer has completed authorizing the payment
        //' at PayPal.  The function will call PayPal to obtain the details
        //' of the authorization, incuding any shipping information of the
        //' buyer.  Remember, the authorization is not a completed transaction
        //' at this state - the buyer still needs an additional step to finalize
        //' the transaction
        //'--------------------------------------------------------------
        //'---------------------------------------------------------------------------
        //' Build a second API request to PayPal, using the token as the
        //'  ID to get the details on the payment authorization
        //'---------------------------------------------------------------------------
        $nvpstr = "&TOKEN=" . $token;

        //'---------------------------------------------------------------------------
        //' Make the API call and store the results in an array.  
        //'	If the call was a success, show the authorization details, and provide
        //' 	an action to complete the payment.  
        //'	If failed, show the error
        //'---------------------------------------------------------------------------
        $resArray = $this->hash_call("GetExpressCheckoutDetails", $nvpstr);
        return $resArray;
    }

    /*
      '-------------------------------------------------------------------------------------------------------------------------------------------
      ' Purpose: 	Prepares the parameters for the GetExpressCheckoutDetails API Call.
      '
      ' Inputs:
      '		sBNCode:	The BN code used by PayPal to track the transactions from a given shopping cart.
      ' Returns:
      '		The NVP Collection object of the GetExpressCheckoutDetails Call Response.
      '--------------------------------------------------------------------------------------------------------------------------------------------
     */

    function ConfirmPayment($FinalPaymentAmt, $token, $payerID) {
        /* Gather the information to make the final call to
          finalize the PayPal payment.  The variable nvpstr
          holds the name value pairs
         */


        //Format the other parameters that were stored in the session from the previous calls	
        $token = urlencode($token);
        $paymentType = urlencode($this->Payment_type);
       $currencyCodeType = urlencode($this->Currency_Code);
        $payerID = urlencode($payerID);

        $serverName = urlencode($_SERVER['SERVER_NAME']);

        $nvpstr = '&TOKEN=' . $token . '&PAYERID=' . $payerID . '&PAYMENTREQUEST_0_PAYMENTACTION=' . $paymentType . '&PAYMENTREQUEST_0_AMT=' . $FinalPaymentAmt;
        $nvpstr .= '&PAYMENTREQUEST_0_CURRENCYCODE=' . $currencyCodeType . '&IPADDRESS=' . $serverName;

        /* Make the call to PayPal to finalize payment
          If an error occured, show the resulting errors
         */
        $resArray = $this->hash_call("DoExpressCheckoutPayment", $nvpstr);
        return $resArray;

        /* Display the API response back to the browser.
          If the response from PayPal was a success, display the response parameters'
          If the response was an error, display the errors received using APIError.php.
         */
//        $ack = strtoupper($resArray["ACK"]);
    }

    /*
      '-------------------------------------------------------------------------------------------------------------------------------------------
      ' Purpose: 	This function makes a DoDirectPayment API call
      '
      ' Inputs:
      '		paymentType:		paymentType has to be one of the following values: Sale or Order or Authorization
      '		paymentAmount:  	total value of the shopping cart
      '		currencyCode:	 	currency code value the PayPal API
      '		firstName:			first name as it appears on credit card
      '		lastName:			last name as it appears on credit card
      '		street:				buyer's street address line as it appears on credit card
      '		city:				buyer's city
      '		state:				buyer's state
      '		countryCode:		buyer's country code
      '		zip:				buyer's zip
      '		creditCardType:		buyer's credit card type (i.e. Visa, MasterCard ... )
      '		creditCardNumber:	buyers credit card number without any spaces, dashes or any other characters
      '		expDate:			credit card expiration date
      '		cvv2:				Card Verification Value
      '
      '-------------------------------------------------------------------------------------------
      '
      ' Returns:
      '		The NVP Collection object of the DoDirectPayment Call Response.
      '--------------------------------------------------------------------------------------------------------------------------------------------
     */

    function DirectPayment($paymentAmount, $creditCardType, $creditCardNumber, $expDate, $cvv2, $firstName, $lastName, $street, $city, $state, $zip, $countryCode, $returnURL, $cancelURL) {
        //Construct the parameter string that describes DoDirectPayment
        $nvpstr = "&AMT=" . $paymentAmount;
        $nvpstr = $nvpstr . "&CURRENCYCODE=" . $this->Currency_Code;
        $nvpstr = $nvpstr . "&PAYMENTACTION=" . $this->Payment_type;
        $nvpstr = $nvpstr . "&CREDITCARDTYPE=" . $creditCardType;
        $nvpstr = $nvpstr . "&ACCT=" . $creditCardNumber;
        $nvpstr = $nvpstr . "&EXPDATE=" . $expDate;
        $nvpstr = $nvpstr . "&CVV2=" . $cvv2;
        $nvpstr = $nvpstr . "&FIRSTNAME=" . $firstName;
        $nvpstr = $nvpstr . "&LASTNAME=" . $lastName;
        $nvpstr = $nvpstr . "&STREET=" . $street;
        $nvpstr = $nvpstr . "&CITY=" . $city;
        $nvpstr = $nvpstr . "&STATE=" . $state;
        $nvpstr = $nvpstr . "&COUNTRYCODE=" . $countryCode;
        $nvpstr = $nvpstr . "&IPADDRESS=" . $_SERVER['REMOTE_ADDR'];
        $nvpstr = $nvpstr . "&RETURNURL=" . $returnURL;
        $nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;
//         echo $nvpstr;die;
        $resArray = $this->hash_call("DoDirectPayment", $nvpstr);
//        print_r($resArray);die;

        return $resArray;
    }

    /**
      '-------------------------------------------------------------------------------------------------------------------------------------------
     * hash_call: Function to perform the API call to PayPal using API signature
     * @methodName is name of API  method.
     * @nvpStr is nvp string.
     * returns an associtive array containing the response from the server.
      '-------------------------------------------------------------------------------------------------------------------------------------------
     */
    function hash_call($methodName, $nvpStr) {
        //declaring of global variables
//		global $API_Endpoint, $version, $API_UserName, $API_Password, $API_Signature;
//		global $USE_PROXY, $PROXY_HOST, $PROXY_PORT;
//		global $gv_ApiErrorURL;
//		global $sBNCode;
        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        //turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
        //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
        if ($this->USE_PROXY)
            curl_setopt($ch, CURLOPT_PROXY, $this->PROXY_HOST . ":" . $this->PROXY_PORT);

        //NVPRequest for submitting to server
        $nvpreq = "METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode($this->version) . "&PWD=" . urlencode($this->API_Password) . "&USER=" . urlencode($this->API_UserName) . "&SIGNATURE=" . urlencode($this->API_Signature) . $nvpStr . "&BUTTONSOURCE=" . urlencode($this->sBNCode);

        //setting the nvpreq as POST FIELD to curl
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

        //getting response from server
        $response = curl_exec($ch);

        //convrting NVPResponse to an Associative Array
        $nvpResArray = $this->deformatNVP($response);
        $nvpReqArray = $this->deformatNVP($nvpreq);
        //print_r($nvpResArray);die;
        $_SESSION['nvpReqArray'] = $nvpReqArray;

        if (curl_errno($ch)) {
            // moving to display page to display curl errors
            $_SESSION['curl_error_no'] = curl_errno($ch);
            $_SESSION['curl_error_msg'] = curl_error($ch);

            //Execute the Error handling module to display errors. 
        } else {
            //closing the curl
            curl_close($ch);
        }

        return $nvpResArray;
    }

    /* '----------------------------------------------------------------------------------
      Purpose: Redirects to PayPal.com site.
      Inputs:  NVP string.
      Returns:
      ----------------------------------------------------------------------------------
     */

    function RedirectToPayPal($token) {
        //global $PAYPAL_URL;
        // Redirect to paypal.com here
        $payPalURL = $this->PAYPAL_URL . $token;
        header("Location: " . $payPalURL);
        exit;
    }

    /* '----------------------------------------------------------------------------------
     * This function will take NVPString and convert it to an Associative Array and it will decode the response.
     * It is usefull to search for a particular key and displaying arrays.
     * @nvpstr is NVPString.
     * @nvpArray is Associative Array.
      ----------------------------------------------------------------------------------
     */

    function deformatNVP($nvpstr) {
        $intial = 0;
        $nvpArray = array();

        while (strlen($nvpstr)) {
            //postion of Key
            $keypos = strpos($nvpstr, '=');
            //position of value
            $valuepos = strpos($nvpstr, '&') ? strpos($nvpstr, '&') : strlen($nvpstr);

            /* getting the Key and Value values and storing in a Associative Array */
            $keyval = substr($nvpstr, $intial, $keypos);
            $valval = substr($nvpstr, $keypos + 1, $valuepos - $keypos - 1);
            //decoding the respose
            $nvpArray[urldecode($keyval)] = urldecode($valval);
            $nvpstr = substr($nvpstr, $valuepos + 1, strlen($nvpstr));
        }
        return $nvpArray;
    }

    public function DirectProPayment($paymentAmount, $creditCardType, $creditCardNumber, $expDate, $cvv2, $currencyCode, $desc, $paymentType, $returnURL, $cancelURL) {

        $nvpstr = $nvpstr . "&PAYMENTACTION=" . $this->Payment_type;
        $nvpstr = $nvpstr . "&DESC=" . $desc;
        $nvpstr = $nvpstr . "&AMT=" . $paymentAmount;
        $nvpstr = $nvpstr . "&ACCT=" . $creditCardNumber;
        $nvpstr = $nvpstr . "&CREDITCARDTYPE=" . $creditCardType;
        $nvpstr = $nvpstr . "&CVV2=" . $cvv2;
        $nvpstr = $nvpstr . "&CURRENCYCODE=" . $this->Currency_Code;
        $nvpstr = $nvpstr . "&EXPDATE=" . $expDate;
        $nvpstr = $nvpstr . "&IPADDRESS=" . $_SERVER['REMOTE_ADDR'];
        $nvpstr = $nvpstr . "&RETURNURL=" . $returnURL;
        $nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;

        $resArray = $this->hash_call("DoDirectPayment", $nvpstr);

        return $resArray;
    }

    /**
     * The following is a sample MassPay API call to recipients using 
     * the PayPal Name-Value Pair (NVP) API. 
     * The recipients are specified by email address
     */
    public function MassPay($recepientDetails) {

        $nvpstr = "&RECEIVERTYPE=EmailAddress";
        for ($i = 0; $i < count($recepientDetails); $i++) {
            $nvpstr = $nvpstr . "&L_EMAIL$i=" . $recepientDetails[$i]['email'];
            $nvpstr = $nvpstr . "&L_AMT$i=" . $recepientDetails[$i]['amount'];
        }
        $nvpstr = $nvpstr . "&CURRENCYCODE=" . $this->Currency_Code;

        $resArray = $this->hash_call("MassPay", $nvpstr);

        return $resArray;
    }

    public function RecurringPayments($getExpToken, $getExpressTokenPayerId, $getExpressPayerEmail
    , $payerProfileStartDate, $description, $billingPeriod, $billingFrequency, $paymentAmount
    , $trialBillingPeriod, $trailBillingFrequency, $trialTotalBillingCycles, $trailAmt
    , $maxFailedPayments, $getExpressPayerStreet, $getExpressPayerName
    , $payerProfileReference, $payerAutoBillAmt, $payerShippingAmt
    , $payerTaxAmt, $total_billing_cycle) {


        $getExpToken = urlencode($getExpToken);
        $currencyCodeType = urlencode($this->Currency_Code);
        $getExpressTokenPayerId = urlencode($getExpressTokenPayerId);


        $nvpstr = "&TOKEN=" . $getExpToken;
        $nvpstr = $nvpstr . "&SUBSCRIBERNAME=" . $getExpressPayerName;
        $nvpstr = $nvpstr . "&PAYERID=" . $getExpressTokenPayerId;
        $nvpstr = $nvpstr . "&EMAIL=" . $getExpressPayerEmail;
        $nvpstr = $nvpstr . "&STREET=" . $getExpressPayerStreet;
        $nvpstr = $nvpstr . "&PROFILESTARTDATE=" . $payerProfileStartDate;
        $nvpstr = $nvpstr . "&PROFILEREFERENCE=" . $payerProfileReference;
        $nvpstr = $nvpstr . "&DESC=" . $description;
        $nvpstr = $nvpstr . "&MAXFAILEDPAYMENTS=" . $maxFailedPayments;
        $nvpstr = $nvpstr . "&AUTOBILLAMT=" . $payerAutoBillAmt;
        $nvpstr = $nvpstr . "&TRIALBILLINGPERIOD=" . $trialBillingPeriod;
        $nvpstr = $nvpstr . "&TRIALBILLINGFREQUENCY=" . $trailBillingFrequency;
        $nvpstr = $nvpstr . "&TRIALTOTALBILLINGCYCLES=" . $trialTotalBillingCycles;
        $nvpstr = $nvpstr . "&TRIALAMT=" . $trailAmt;
        $nvpstr = $nvpstr . "&BILLINGPERIOD=" . $billingPeriod;
        $nvpstr = $nvpstr . "&BILLINGFREQUENCY=" . $billingFrequency;
        $nvpstr = $nvpstr . "&TOTALBILLINGCYCLES=" . $total_billing_cycle;
        $nvpstr = $nvpstr . "&AMT=" . $paymentAmount;
        $nvpstr = $nvpstr . "&CURRENCYCODE=" . $currencyCodeType;
        $nvpstr = $nvpstr . "&SHIPPINGAMT=" . $payerShippingAmt;
        $nvpstr = $nvpstr . "&TAXAMT=" . $payerTaxAmt;
        $resArray = $this->hash_call("CreateRecurringPaymentsProfile", $nvpstr);
        return $resArray;
    }

    public function getRecurringProfile($profileId) {

        $nvpstr = "&PROFILEID=" . $profileId;
        $nvpstr = $nvpstr . "&CURRENCYCODE=" . $this->Currency_Code;

        $resArray = $this->hash_call("GetRecurringPaymentsProfileDetails", $nvpstr);
//        echo "<pre>";
//        print_r($resArray);
//        echo "</pre>";
//        die;

        return $resArray;
    }

    public function getTransactionDetails($transactionId) {

        $nvpstr = "&TransactionID=" . $transactionId;
        $resArray = $this->hash_call("GetTransactionDetails", $nvpstr);
        return $resArray;
    }

    public function CallShortExpressCheckout($paymentAmount, $returnURL, $cancelURL, $description, $payment_request_quantity) {
        //------------------------------------------------------------------------------------------------------------------------------------
        // Construct the parameter string that describes the SetExpressCheckout API call in the shortcut implementation

        $nvpstr = "&RETURNURL=" . $returnURL;
        $nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;
        $nvpstr = $nvpstr . "&ALLOWNOTE=1";
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_PAYMENTACTION=" . $this->Payment_type;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_CURRENCYCODE=" . $this->Currency_Code;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_AMT=" . $paymentAmount;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_ITEMAMT=" . $paymentAmount;
        $nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_QTY0=" . $payment_request_quantity;
        $nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_AMT0= " . $paymentAmount;
        $nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_NAME0= " . $description;


        //'--------------------------------------------------------------------------------------------------------------- 
        //' Make the API call to PayPal
        //' If the API call succeded, then redirect the buyer to PayPal to begin to authorize payment.  
        //' If an error occured, show the resulting errors
        //'---------------------------------------------------------------------------------------------------------------
        $resArray = $this->hash_call("SetExpressCheckout", $nvpstr);
        $ack = strtoupper($resArray["ACK"]);
        if ($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {
            return $resArray;
        }
    }

}

?>
