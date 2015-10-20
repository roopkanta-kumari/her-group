<?php
class Engine_Plugins_DataProvider extends Zend_Controller_Plugin_Abstract {
    
        public function preDispatch(Zend_Controller_Request_Abstract $request){
          
            $ObjCore = Engine_Core_Core::getInstance();

            $view = Zend_Layout::startMvc()->getView();
           
            $view->assign(array(
               'session' => $ObjCore->getSession(),
               'appSetting' => $ObjCore->getAppSetting(),
               'logger' => $ObjCore->getLogger(),
               'auth' => $ObjCore->getAuth(),
               'isAjax' => $ObjCore->getAjax() 
            ));

         
        } 
}

?>