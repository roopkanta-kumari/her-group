<?php

require_once 'Zend/Controller/Action.php';

class User_UserController extends Zend_Controller_Action {

    public function init() {
        $this->session = Zend_Registry::get('sessionNamespace');
        if($this->session->role == 1){
           $this->_redirect('/dashboard');
        }
    }

    public function homeAction() {

    }

    public function loginAction() {



    }

    public function registerAction() {

    }

    public function recoverAction() {
      //let this action load view (recover.phtml) with some specific data required.
    }


      //u can create a new action for recovery process
        // public function requestRecoverAction() {
        //   if ($this->getRequest()->isPost()) {
        //     $objCore = Engine_Core_Core::getInstance();
        //     $objSecuity = Engine_Vault_Security::getInstance();
        //     $this->_appSetting = $objCore->getAppSetting();
        //     $objCurlHandler = Engine_Utilities_CurlRequestHandler::getInstance();
        //     $response = new stdClass();
        //       $email = $this->getRequest()->getPost('email');
        //       $data = array('email' => $email, 'password'  => $password);
        //       $url = $this->_appSetting->hostlink . '/user/recover-password';
        //       $response = $objCurlHandler->curlUsingPost($url, $data);
        //       if ($response) {
        //           if ($response->code == 200) {
        //             $response->code = 200;
        //             echo json_encode($response);
        //             exit();
        //           } else {
        //             $response->data = 'Invalid Email ID';
        //             $response->code = 198;
        //             echo json_encode($response);
        //             exit();
        //           }
        //         }
        //       }
        //     }


}
