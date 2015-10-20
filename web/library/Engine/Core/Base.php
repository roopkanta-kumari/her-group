<?php
class Engine_Core_Base{

	private static $_instance = null;

	
	//Prevent any oustide instantiation of this class
	private function  __construct() { 
		

	} 
	
	private function  __clone() { } //Prevent any copy of this object
	
	public static function getInstance(){
		if( !is_object(self::$_instance) )  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
		self::$_instance = new Engine_Core_Base();
		return self::$_instance;
	}
        
	

}