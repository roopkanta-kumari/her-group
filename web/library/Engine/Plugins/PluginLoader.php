<?php
class Engine_Plugins_PluginLoader{

	private static $_instance = null;

	
	//Prevent any oustide instantiation of this class
	private function  __construct() { 
		

	} 
	
	private function  __clone() { } //Prevent any copy of this object
	
	public static function getInstance(){
		if( !is_object(self::$_instance) )  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
		self::$_instance = new Engine_Plugins_PluginLoader();
		return self::$_instance;
	}
	
        public function registerPlugin(){
            $frontController = Zend_Controller_Front::getInstance();
            $getPlugins = glob(__DIR__."\*.php");
            if(!empty($getPlugins)){
                foreach ($getPlugins as $pkey => $plugin) {
                    if($plugin != __FILE__){
                       
                       $plugin = basename($plugin, ".php");
                       if(class_exists("Engine_Plugins_".$plugin)){
                           $plugin = "Engine_Plugins_".$plugin;
                           $pluginClass = new $plugin;
                            $frontController->registerPlugin($pluginClass);
                       }
                      
                        
                    }else{
                        
                    }
                }
            }
        }
        
}