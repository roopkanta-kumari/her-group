<?php
class Engine_Api_ApiHandler {
    

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
            if(func_num_args() > 0){
                $selector = func_get_arg(0);
            }
            switch ($selector) {
                case 'curl' :
                    
                default:
                    throw new Zend_Controller_Action_Exception('Invalid Api selector Passed', 500);
                    break;
            }
            
        }
            
        return self::$_instance;
    }
    
  
} 



?>