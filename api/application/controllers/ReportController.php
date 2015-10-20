<?php

require_once 'Zend/Controller/Action.php';

class ReportController extends Zend_Controller_Action {

    public function init() {
      $this->_helper->layout()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);
    }

    public function crimeAction() {
       $objReportModel = Application_Model_Reports::getInstance();
      if ($this->getRequest()->isPost()) {
          $userid =  $this->getRequest()->getPost('userid');
          $lat = $this->getRequest()->getPost('lat');
          $lng = $this->getRequest()->getPost('lng');
          $formattedAddress = $this->getRequest()->getPost('formattedAddress');
          $crimeDesc = $this->getRequest()->getPost('crimeDesc');
          $myip = $this->getRequest()->getPost('myip');
          $result = $objReportModel->addReport($userid,$lat,$lng,$formattedAddress,$crimeDesc,$myip);
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


    public function getNearbyCrimesLatLngAction() {
      // die("ok");
       $objReportModel = Application_Model_Reports::getInstance();
      if ($this->getRequest()->isPost()) {
          // $userid =  $this->getRequest()->getPost('userid');
          $lat = $this->getRequest()->getPost('lat');
          $lng = $this->getRequest()->getPost('lng');

            $result = $objReportModel->nearByUsersReports($lat,$lng);
          // print_r($result);die("After results");
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


    public function getReportsAction() {
      // die("ok");
       $objReportModel = Application_Model_Reports::getInstance();
      if ($this->getRequest()->isPost()) {
          $reportId = $this->getRequest()->getPost('reportId');
            $result = $objReportModel->getReportDetails($reportId);
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
