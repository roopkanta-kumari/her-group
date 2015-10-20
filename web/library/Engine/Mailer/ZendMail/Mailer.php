<?php
require_once 'Mandrill.php';
class Engine_Mailer_ZendMail_Mailer {
    
  
    protected $_appSetting;
 
    //   public $connection;
    ///Condition 1 - Presence of a static member variable
    private static $_instance = null;

    ///Condition 2 - Locked down the constructor
    private function __construct() {
        
        
            $objCore = Engine_Core_Core::getInstance();
            $this->_appSetting = $objCore->getAppSetting();
          

    }

//Prevent any oustide instantiation of this class
    ///Condition 3 - Prevent any object or instance of that class to be cloned
    private function __clone() {
        
    }

//Prevent any copy of this object
    ///Condition 4 - Have a single globally accessible static method
    public static function getInstance() {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Engine_Mailer_ZendMail_Mailer();
        return self::$_instance;
    }
    
    
    
    public function sendMail(){
        
       if(func_num_args() >= 4){
           
           $to = func_get_arg(0);
           $from = func_get_arg(1);
           $subject = func_get_arg(2);
           $messageRecieved = func_get_arg(3);
           
           
           
           
           
       }else{
           throw new Zend_Controller_Action_Exception('Paramter Not passed', 500);
       }
        
        
    }

    


    
    
} 



?>