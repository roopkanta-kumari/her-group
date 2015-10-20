<?php
class Engine_Payment_Payment{
    
    private static $_instance = null;
    
    private function __construct() { }
    
    //Prevent any copy of this object
    private function  __clone() { } 
    
    public static function getInstance() {
        if (!is_object(self::$_instance)){
            $objCore = Engine_Core_Core::getInstance();
            $appSetting = $objCore->getAppSetting();
            
            switch ($appSetting->payment){
                case "Paypal":
                    self::$_instance = Engine_Payment_Paypal_Paypal::getInstance();
                    break;
                case "Authorize":
                    self::$_instance = Engine_Payment_Authorize_Authorize::getInstance();
                    break;
                default :
                    throw new Zend_Controller_Action_Exception('Payment method not selected', 500);
                    break;
            }
        }
        
        return self::$_instance;
    }
}

?>
