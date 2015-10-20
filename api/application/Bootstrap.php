<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    private $_acl = null;

    protected function _initAutoload() {

        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => APPLICATION_PATH.'/application/models'));
        return $autoloader;

    }

     protected function _initSession()
    {

        Zend_Session::start();
        $sessionNamespace = new Zend_Session_Namespace('testing');
       
        Zend_Registry::set('sessionNamespace',$sessionNamespace);


    }

}
