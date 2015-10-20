<?php

require_once 'Zend/Controller/Action.php';

class User_AuthenticationController extends Zend_Controller_Action {

    public function init() {
      $this->session = Zend_Registry::get('sessionNamespace');
      $this->_helper->layout()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);
    }

    public function checkAction() {
if ($this->getRequest()->isPost()) {
  $objCore = Engine_Core_Core::getInstance();
  $objSecuity = Engine_Vault_Security::getInstance();
  $this->_appSetting = $objCore->getAppSetting();
  $objCurlHandler = Engine_Utilities_CurlRequestHandler::getInstance();
  $response = new stdClass();
    $email = $this->getRequest()->getPost('email');
    $password = $this->getRequest()->getPost('password');
    $data = array('email' => $email, 'password'  => $password);
    $url = $this->_appSetting->hostlink . '/user/authentication';
    $response = $objCurlHandler->curlUsingPost($url, $data);
    if ($response) {
        if ($response->code == 200) {
          $this->session->role = 1;
          $this->session->userId = $response->data['userId'];
          $response->data = 'Valid User';
          $response->code = 200;
          echo json_encode($response);
          exit();
        } else {
          $response->data = 'Invalid User';
          $response->code = 198;
          echo json_encode($response);
          exit();
        }
    }
}

    }


    public function signupAction() {

if ($this->getRequest()->isPost()) {
  $objCore = Engine_Core_Core::getInstance();
  $objSecuity = Engine_Vault_Security::getInstance();
  $this->_appSetting = $objCore->getAppSetting();
  $objCurlHandler = Engine_Utilities_CurlRequestHandler::getInstance();
  $response = new stdClass();
    $email = $this->getRequest()->getPost('email');
    $password = $this->getRequest()->getPost('password');
    $ipaddress = $this->getRequest()->getPost('ipaddress');
    $data = array('email' => $email, 'password'  => $password, 'ipaddress' => $ipaddress);
    // print_r($data);die;
    $url = $this->_appSetting->hostlink . '/user/registration';
    // echo $url;die;
    $response = $objCurlHandler->curlUsingPost($url, $data);
    //print_r($response);die("signup");
    if ($response) {
        if ($response->code == 200) {

          if ($response->data == 197) {
            $response->code = 197;
            echo json_encode($response);
            exit();
            //already registered

          }else if($response->data == 200) {
            //successful registration
            $response->code = 200;
            echo json_encode($response);
            exit();
          }
          else {
           $response->code = 198;
           echo json_encode($response);
           exit();
         }

        } else {
          $response->code = 198;
          echo json_encode($response);
          exit();
        }
    }
}

    }


    public function checknickAction() {

if ($this->getRequest()->isPost()) {
  $objCore = Engine_Core_Core::getInstance();
  $objSecuity = Engine_Vault_Security::getInstance();
  $this->_appSetting = $objCore->getAppSetting();
  $objCurlHandler = Engine_Utilities_CurlRequestHandler::getInstance();
  $response = new stdClass();
    $nick = $this->getRequest()->getPost('nick');
    $data = array('nick' => $nick);
    $url = $this->_appSetting->hostlink . '/user/check-nick';
    $response = $objCurlHandler->curlUsingPost($url, $data);
    // print_r($response);die("nick");
    if ($response) {
        if ($response->code == 200) {
          if ($response->data == 200) {
            $response->code = 200;
            echo json_encode($response);
            exit();
          //confirm
        }else if($response->data == 198) {
            $response->code = 198;
            echo json_encode($response);
            exit();
          }
        } else {
          $response->code = 198;
          echo json_encode($response);
          exit();
        }
    }
}

    }

    public function setnickAction() {

if ($this->getRequest()->isPost()) {
  $objCore = Engine_Core_Core::getInstance();
  $objSecuity = Engine_Vault_Security::getInstance();
  $this->_appSetting = $objCore->getAppSetting();
  $objCurlHandler = Engine_Utilities_CurlRequestHandler::getInstance();
  $userId = $this->session->userId;
  $response = new stdClass();
    $nick = $this->getRequest()->getPost('nick');
    $data = array('uid' =>$userId, 'nick' => $nick);
    // print_r($data);die;
    $url = $this->_appSetting->hostlink . '/user/set-nick';
    $response = $objCurlHandler->curlUsingPost($url, $data);
    // print_r($response);die("setnick");
    if ($response) {
        if ($response->code == 200) {
          if ($response->data == 200) {
            $response->code = 200;
            echo json_encode($response);
            exit();
          //confirm
        }else if($response->data == 198) {
            $response->code = 198;
            echo json_encode($response);
            exit();
          }
        } else {
          $response->code = 198;
          echo json_encode($response);
          exit();
        }
    }
}

    }

    public function loginAction() {

    }

    public function registerAction() {

    }

    public function recoverAction() {

    }


}
