<?php

class IndexController extends Zend_Controller_Action {

    protected $session;

    public function init() {


        $this->session = Zend_Registry::get('sessionNamespace');
    }

    public function indexAction() {

    }


}
