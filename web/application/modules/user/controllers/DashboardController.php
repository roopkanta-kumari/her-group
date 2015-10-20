<?php

require_once 'Zend/Controller/Action.php';

class User_DashboardController extends Zend_Controller_Action {

  public function init() {
      $this->session = Zend_Registry::get('sessionNamespace');
      if(!$this->session->role){
         $this->_redirect('/login');
      }
  }

  public function dashboardAction() {
    $objCore = Engine_Core_Core::getInstance();
    $objSecuity = Engine_Vault_Security::getInstance();
    $this->_appSetting = $objCore->getAppSetting();
    $objCurlHandler = Engine_Utilities_CurlRequestHandler::getInstance();
    $userId = $this->session->userId;
    $this->session->currentNav = 'navDash';

if($userId){
  $data = array('uID' => $userId);
  $url = $this->_appSetting->hostlink . '/user/dashboard';
  // echo $url;die;
  $response = $objCurlHandler->curlUsingPost($url, $data);

  if($response)
  {
    if($response->code=200){
      $this->session->reporterId = $response->data['userId'];
      $this->session->nickname = $response->data['nickname'];
      $this->session->email = $response->data['email'];
      $this->session->joinedOn = $response->data['joinedOn'];

      if($response->data['lat'] && $response->data['lon'])
        {
      $this->session->lat = $response->data['lat'];
      $this->session->lon = $response->data['lon'];
        }
    }
  }else {
    echo "No session";die;
  }

}else {
    $this->_redirect("/login");
}

  }

  public function logoutAction() {
    $this->_helper->layout->disableLayout();
            Zend_Session::destroy(true);
           $this->_redirect('/');
}

  public function myDashboardAction() {
        $this->session->currentNav = 'navDash';
$this->_helper->viewRenderer->setRender('dashboard');


  }

  public function profileAction() {
        $this->session->currentNav = 'navProf';

  }

  public function reportCrimeAction() {
        $this->session->currentNav = 'navRC';

  }

  public function reportedCrimesNearbyAction() {
        $this->session->currentNav = 'navRCNb';
        // echo $this->session->lat." - ".$this->session->lon;
        // die("ok");

        //lets get all the nearby crimes to the user

              $objCore = Engine_Core_Core::getInstance();
              $objSecuity = Engine_Vault_Security::getInstance();
              $this->_appSetting = $objCore->getAppSetting();
              $objCurlHandler = Engine_Utilities_CurlRequestHandler::getInstance();
              $userId = $this->session->userId;
              $this->session->currentNav = 'navDash';


              $response = new stdClass();


            if($this->session->lat && $this->session->lon){
              // die("ok");
              $lat = $this->session->lat;
              $lng = $this->session->lon;
              $nick = $this->session->nickname;

              $convertedLat = floatval($lat);
                $convertedLon = floatval($lng);
                // echo $convertedLon;die;

              // lets round the numbers to LL.l (Eg:21.1 from lat lon)

              $roundedLat = round($convertedLat);
                $roundedLon = round($convertedLon);

                // $roundedLat = round($convertedLat, 1);
                //   $roundedLon = round($convertedLon, 1);

                // echo $roundedLat."-".$roundedLon;die;


              $data = array('lat'  => $roundedLat, 'lng' => $roundedLon);

              $url = $this->_appSetting->hostlink . '/report/get-nearby-crimes-lat-lng';
              // echo $url;die;
              $response = $objCurlHandler->curlUsingPost($url, $data);

              // print_r($response);die;


                  // $url = $this->_appSetting->hostlink . 'user/interests/';

                  // $responseUid = $objCurlHandler->curlUsingPost($url, $dataUid);

              if ($response) {
                  if ($response->code == 200) {
                      $this->view->getLatLon = false;
                      $this->view->noResults = false;
                    // die("ok");
                    $response->code = 200;
                    $this->view->crimesNearby = $response->data;
                    $this->view->userLat = $lat;
                    $this->view->userLon = $lng;
                    $this->view->nickname = $nick;
                    // exit();
                  } else {
                    // die("No results");
                    $this->view->noResults = true;
                    // $this->view->getLatLon = true;
                    $response->code = 198;
                    // exit();
                  }
              }

            }
            else {
              // no lat lon found, check the session or ask user to update his location !!!
              //redirect the user to enter address and update it
              $this->view->getLatLon = true;
              $response->code = 197;
              // exit();
            }




  }

  //report a crime hit
  public function reportACrimeAction(){
    // die("reportACrime");
$this->_helper->layout()->disableLayout();
$this->_helper->viewRenderer->setNoRender(true);

    if ($this->getRequest()->isPost()) {
          // die("ok");
          $objCore = Engine_Core_Core::getInstance();
          $objSecuity = Engine_Vault_Security::getInstance();
          $this->_appSetting = $objCore->getAppSetting();
          $objCurlHandler = Engine_Utilities_CurlRequestHandler::getInstance();

          $response = new stdClass();

        $userId = $this->getRequest()->getPost('userId');
        $myip = $this->getRequest()->getPost('myip');
        $lat = $this->getRequest()->getPost('lat');
        $lng = $this->getRequest()->getPost('lng');
        $formattedAddress = $this->getRequest()->getPost('formattedAddress');
        $crimeDesc = $this->getRequest()->getPost('crimeDesc');

        $data = array('userid' => $userId, 'lat'  => $lat, 'lng' => $lng, 'formattedAddress' => $formattedAddress,'crimeDesc' => $crimeDesc, 'myip' =>$myip );
        $url = $this->_appSetting->hostlink . '/report/crime';
        $response = $objCurlHandler->curlUsingPost($url, $data);

        if ($response) {
            if ($response->code == 200) {
              $response->code = 200;
              echo json_encode($response);
              exit();
            } else {
              $response->code = 198;
              echo json_encode($response);
              exit();
            }
        }
    }



  }

  public function getReportDetailsAction(){
$this->_helper->layout()->disableLayout();
$this->_helper->viewRenderer->setNoRender(true);
    // die("reportACrime");

    if ($this->getRequest()->isPost()) {
          // die("ok");
          $objCore = Engine_Core_Core::getInstance();
          $objSecuity = Engine_Vault_Security::getInstance();
          $this->_appSetting = $objCore->getAppSetting();
          $objCurlHandler = Engine_Utilities_CurlRequestHandler::getInstance();

          $response = new stdClass();

        $reportId = $this->getRequest()->getPost('reportId');

        $data = array('reportId' => $reportId);
        $url = $this->_appSetting->hostlink . '/report/get-reports';
        $response = $objCurlHandler->curlUsingPost($url, $data);

        if ($response) {
            if ($response->code == 200) {
              $response->code = 200;
              echo json_encode($response);
              exit();
            } else {
              $response->code = 198;
              echo json_encode($response);
              exit();
            }
        }
    }



  }


  //share my Location

  public function storeMyLocationAction(){
    // die("reportACrime");
$this->_helper->layout()->disableLayout();
$this->_helper->viewRenderer->setNoRender(true);

    if ($this->getRequest()->isPost()) {
          // die("ok");
          $objCore = Engine_Core_Core::getInstance();
          $objSecuity = Engine_Vault_Security::getInstance();
          $this->_appSetting = $objCore->getAppSetting();
          $objCurlHandler = Engine_Utilities_CurlRequestHandler::getInstance();

          $response = new stdClass();
          $userId = $this->session->userId;
          $lat = $this->getRequest()->getPost('lat');
          $lng = $this->getRequest()->getPost('lng');

        $data = array('uid' => $userId, 'lat'  => $lat, 'lon' => $lng);
        $url = $this->_appSetting->hostlink . '/user/set-location';
        $response = $objCurlHandler->curlUsingPost($url, $data);

        if ($response) {
            if ($response->code == 200) {
              $this->session->lat = $lat;
              $this->session->lon = $lng;
              $response->code = 200;
              echo json_encode($response);
              // exit();
            } else {
              $response->code = 198;
              echo json_encode($response);
              // exit();
            }
        }
    }

  }



}
