<?php
class Engine_Api_CurlHandler_CurlHandler {
    

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
	public static function getInstance(){
		if( !is_object(self::$_instance) )  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
		self::$_instance = new self();
		return self::$_instance;
	}
    
  
} 



?>