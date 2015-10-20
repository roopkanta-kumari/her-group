<?php
require_once 'Mandrill.php';
class Engine_Mailer_MandrillApp_Mailer {
    
    private $_mandillKey;
    protected $_appSetting;
    protected $objMandrill;
    //   public $connection;
    ///Condition 1 - Presence of a static member variable
    private static $_instance = null;

    ///Condition 2 - Locked down the constructor
    private function __construct() {
        
        
            $objCore = Engine_Core_Core::getInstance();
            $this->_appSetting = $objCore->getAppSetting();
            $this->mandillkey = $this->_appSetting->mandrillKey;
            $this->objMandrill = new Mandrill($this->mandillkey);

    }

//Prevent any oustide instantiation of this class
    ///Condition 3 - Prevent any object or instance of that class to be cloned
    private function __clone() {
        
    }

//Prevent any copy of this object
    ///Condition 4 - Have a single globally accessible static method
    public static function getInstance() {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Engine_Mailer_MandrillApp_Mailer();
        return self::$_instance;
    }
    
    
    
    public function sendMail(){
        
       if(func_num_args() >= 4){
           
           $to = func_get_arg(0);
           $from = func_get_arg(1);
           $subject = func_get_arg(2);
           $messageRecieved = func_get_arg(3);
           try {
                   
                  $message = array(
                      'html' => $messageRecieved,
                      'subject' => $subject,
                      'from_email' => $from,
                      'to' => array(
                                    array(
                                        'email' => $to,
                                        'type' => 'to'
                                    )
                              ),
                   );    
               
               
               
               
                   $async = false;
                   $ip_pool = 'Main Pool';
                   $result = $this->objMandrill->messages->send($message, $async, $ip_pool);
                   return $result;
           }catch(Mandrill_Error $e) {
                // Mandrill errors are thrown as exceptions
                echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
                // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
                throw $e;
          }
           
           
           
       }else{
           throw new Zend_Controller_Action_Exception('Paramter Not passed', 500);
       }
        
        
    }
    
    
    
    
    /*
     * Name: Abhinish Kumar Singh
     * Date: 22/07/2014
     * Description: This function accepts five mail related parameters and uses 
     *              them to send mail to provided email address using specified 
     *              template.
     */
    public function sendtemplate(){
       
       // die('test');
       if(func_num_args() >= 5){  
        
           $template_name = func_get_arg(0);
           $email = func_get_arg(1);
           $username = func_get_arg(2);
           $subject = func_get_arg(3);
           $mergevars = func_get_arg(4);
           
        try {
        $message = array(
            'html' => null,
            'text' => null,
            'subject' => $subject,
            'from_email' =>'support@takadating.com',
            'from_name' =>'TaKa Support',
            'to' => array(
                array(
                    'email' => $email,
                    'name' => $username
                )
            ),
            'headers' => array('Reply-To' => 'support@takadating.com'),
            'important' => false,
            'track_opens' => null,
            'track_clicks' => null,
            'auto_text' => null,
            'auto_html' => null,
            'inline_css' => null,
            'url_strip_qs' => null,
            'preserve_recipients' => null,
            'bcc_address' => 'message.bcc_address@example.com',
            'tracking_domain' => null,
            'signing_domain' => null,
            'global_merge_vars' => $mergevars,
            'merge' => true

        );
        $async = false;
      $result = $this->objMandrill->messages->sendTemplate($template_name, null, $message, $async);
        return $result;            
         } catch (Mandrill_Error $e) {
            
            echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
           
            throw $e;
        }

       }else{
           throw new Zend_Controller_Action_Exception('Paramter Not passed', 500);
       }
        
        
    }

    


    
    
} 



?>