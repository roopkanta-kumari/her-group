<?php

class Engine_Plugins_RequestHandler extends Zend_Controller_Plugin_Abstract {


    
    
        public function preDispatch(Zend_Controller_Request_Abstract $request){
               $requestParams = $request->getParams();
               
                if ($requestParams) {
                    if (array_key_exists('showmodal', $requestParams)) {
                            $view = Zend_Layout::startMvc()->getView();
                            
                            $view->assign(array(
                                            'showmodal' => true
                                        ));
                            Zend_Layout::getMvcInstance()->disableLayout();
                    } else if (array_key_exists('requestType', $requestParams)) {
                        switch ($requestParams['requestType']) {
                            case 'json':
                               $this->getResponse()
                                    ->setHeader('Content-type', 'text/json; charset=utf-8');
                                break;

                            case 'xml':
                                $this->getResponse()
                                    ->setHeader('Content-type', 'text/xml; charset=utf-8');

                                break;

                            default:
                                break;
                        }
                    }
                }
            
            
            
            
        }
    
 
    

}

?>