<?php
class Engine_Mailer_Mailer {
    

    private static $_instance = null;

    ///Condition 2 - Locked down the constructor
    private function __construct() {
        


    }

//Prevent any oustide instantiation of this class
    ///Condition 3 - Prevent any object or instance of that class to be cloned
    private function __clone() {
        
    }

//Prevent any copy of this object
    ///Condition 4 - Have a single globally accessible static method
    public static function getInstance() {
        if (!is_object(self::$_instance)){          //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            $objCore = Engine_Core_Core::getInstance();
            $appSetting = $objCore->getAppSetting();
            switch ($appSetting->mailSelector) {
                case 'Mandrill':
                    self::$_instance = Engine_Mailer_MandrillApp_Mailer::getInstance();

                    break;
                
                case 'SendGrid':
                    self::$_instance = Engine_Mailer_SendGrid_Mailer::getInstance();
                    
                    break;
                default:
                    throw new Zend_Controller_Action_Exception('Invalid Mail selector Passed', 500);
                    break;
            }
            
        }
            
        return self::$_instance;
    }
    
  
} 



?>