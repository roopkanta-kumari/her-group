<?php

require_once 'Zend/Controller/Action.php';

class UserController extends Zend_Controller_Action {

    public function init() {
      $this->_helper->layout()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);
    }

    public function authenticationAction() {
    //  die("ok");
        $objUserModel = Application_Model_Users::getInstance();
      // die("api");
      if ($this->getRequest()->isPost()) {
          //$objUserModel = Application_Model_Users::getInstance();
          $email = $this->getRequest()->getPost('email');
          $password = $this->getRequest()->getPost('password');
          // die("done");
          // echo $email;die;
          $result = $objUserModel->checkuser($email,$password);
          if ($result) {
              echo json_encode($result);
          }else {
            return 0;
          }
      }
      else {
      die("not a post");
      }
    }

    public function registrationAction() {
        $objUserModel = Application_Model_Users::getInstance();
      // die("api");
      if ($this->getRequest()->isPost()) {
          //$objUserModel = Application_Model_Users::getInstance();
          $email = $this->getRequest()->getPost('email');
          $password = $this->getRequest()->getPost('password');
          $ipaddress = $this->getRequest()->getPost('ipaddress');
          // die("done");
          // echo $email;die;
          $result = $objUserModel->adduser($email,$password,$ipaddress);
          if ($result) {
              echo json_encode($result);
          }else {
            return 0;
          }
      }
      else {
      die("not a post");
      }
    }


    public function dashboardAction() {
    //  die("ok");
        $objUserModel = Application_Model_Users::getInstance();
      if ($this->getRequest()->isPost()) {
          $userId = $this->getRequest()->getPost('uID');
          $result = $objUserModel->getUserInfo($userId);
          if ($result) {
              echo json_encode($result);
          }else {
            return 0;
          }
      }
      else {
      die("not a post");
      }
    }


    public function checkNickAction() {
    //  die("ok");
        $objUserModel = Application_Model_Users::getInstance();
      // die("api");
      if ($this->getRequest()->isPost()) {
          //$objUserModel = Application_Model_Users::getInstance();
          $nick = $this->getRequest()->getPost('nick');
          // die("done");
          // echo $email;die;
          $result = $objUserModel->checknickname($nick);
          if ($result) {
              echo json_encode($result);
          }else {
            return 0;
          }
      }
      else {
      die("not a post");
      }
    }



        public function setNickAction() {
          // die("ok");
            $objUserModel = Application_Model_Users::getInstance();
          if ($this->getRequest()->isPost()) {
              $userId = $this->getRequest()->getPost('uid');
              $nick = $this->getRequest()->getPost('nick');

              // echo $userId.$nick;die;

              $result = $objUserModel->setnickname($userId,$nick);
              if ($result) {
                  echo json_encode($result);
              }else {
                return 0;
              }
          }
          else {
          die("not a post");
          }
        }

        public function setLocationAction() {
          // die("ok");
            $objUserModel = Application_Model_Users::getInstance();
          if ($this->getRequest()->isPost()) {
              $userId = $this->getRequest()->getPost('uid');
              $lat = $this->getRequest()->getPost('lat');
              $lng = $this->getRequest()->getPost('lon');

              $result = $objUserModel->setUserLocation($userId,$lat,$lng);
              if ($result) {
                  echo json_encode($result);
              }else {
                return 0;
              }
          }
          else {
          die("not a post");
          }
        }    
}
