<?php
class Engine_Core_Core{

	private static $_instance = null;
	protected $_session;
	protected $_auth;
	protected $_db;
	protected $_appSetting;
	protected $_logger;
	protected $_env;
        protected $_isAjax;
	
	//Prevent any oustide instantiation of this class
	private function  __construct() { 
		
		$this->_session = Zend_Registry::get('sessionNamespace');
		$this->_auth 	= Zend_Auth::getInstance();
		$this->_db 		=  Zend_Db_Table::getDefaultAdapter();
		$this->_appSetting 	= Zend_Registry::get('appconfig')->appSettings;
		$this->_logger	= Zend_Registry::get('logger');
		$this->_env	= getenv('APPLICATION_ENV');
              if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
                                 if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){ 
                                    $this->_isAjax	= 1;
                                  }else{
                                    $this->_isAjax	= 0;  
                                  } 
              }else{
                    $this->_isAjax	= 0;  
              }

	} 
	
	private function  __clone() { } //Prevent any copy of this object
	
	public static function getInstance(){
		if( !is_object(self::$_instance) )  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
		self::$_instance = new Engine_Core_Core();
		return self::$_instance;
	}
	
	public function getSession(){
		return $this->_session;
	}
	
	public function getAuth(){
		$storage = new Zend_Auth_Storage_Session($this->_appSetting->appName);
		// Use 'someNamespace' instead of 'Zend_Auth'
		$this->_auth->setStorage($storage);
		return $this->_auth;
	}
	
	public function getDb(){
		return $this->_db;
	}
	
	public function getAppSetting(){
		return $this->_appSetting;
	}
	
	public function getEnv(){
		return $this->_env;
	}
	
	public function getLogger(){
		return $this->_logger;
	}
	public function getAjax(){
		return $this->_isAjax;
	}        
        
}