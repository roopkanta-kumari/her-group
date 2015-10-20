<?php
class Engine_Mailer_SendGrid_Mailer {
    
  
    protected $_appSetting;
    protected $_host;
    protected $_username;
    protected $_password;
    protected $_port;

    private static $_instance = null;

    ///Condition 2 - Locked down the constructor
    private function __construct() {
        
        
            $objCore = Engine_Core_Core::getInstance();
            $this->_appSetting = $objCore->getAppSetting();
            $this->_host = $this->_appSetting->sendgrid->host;
            $this->_username = $this->_appSetting->sendgrid->username;
            $this->_password = $this->_appSetting->sendgrid->password;
            $this->_port = $this->_appSetting->sendgrid->port;

    }

//Prevent any oustide instantiation of this class
    ///Condition 3 - Prevent any object or instance of that class to be cloned
    private function __clone() {
        
    }

//Prevent any copy of this object
    ///Condition 4 - Have a single globally accessible static method
    public static function getInstance() {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Engine_Mailer_SendGrid_Mailer();
        return self::$_instance;
    }
    
    
    
    public function sendMail(){
        
       if(func_num_args() >= 4){
           
           $to = func_get_arg(0);
           $from = func_get_arg(1);
           $subject = func_get_arg(2);
           $messageRecieved = func_get_arg(3);
           
           $tr = new Zend_Mail_Transport_Smtp($this->_host,
                array('auth' => 'login',
                      'username' => $this->_username,
                      'password' => $this->_password,
                      'port' => $this->_port));

            Zend_Mail::setDefaultTransport($tr);
            $mail = new Zend_Mail('utf-8');
            $mail->setFrom($from);
            $mail->setBodyHtml($messageRecieved);
            $mail->addTo($to);
            $mail->setSubject($subject);
            $mail->addHeader('MIME-Version', '1.0');
            $mail->addHeader('Content-Transfer-Encoding', '8bit');
            $mail->addHeader('X-Mailer:', 'PHP/'.phpversion());
            try {
                    $mail->send();
                    $response = "Mail sent";
                    return $response;
            } catch (Exception $e){
                 throw new Zend_Controller_Action_Exception('SendGrid Mail sending error', 500);
            }
           
           
           
       }else{
           throw new Zend_Controller_Action_Exception('Paramter Not passed', 500);
       }
        
        
    }

    


    
    
} 



?>