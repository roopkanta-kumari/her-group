<?php

class Application_Model_Reports extends Zend_Db_Table_Abstract {

    private static $_instance = null;
    protected $_name = 'reports';

    public static function getInstance() {
        if (!is_object(self::$_instance))
            self::$_instance = new Application_Model_Reports();
        return self::$_instance;
    }

    public function addReport() {
      // die("ok");
        if (func_num_args() > 0) {
            $userid = func_get_arg(0);
            $lat = func_get_arg(1);
            $lng = func_get_arg(2);
            $formattedAddress = func_get_arg(3);
            $crimeDesc = func_get_arg(4);
            $myip = func_get_arg(5);

            $data = array('userId' => $userid, 'lat' => $lat, 'lon' => $lng, 'address' => $formattedAddress, 'description' => $crimeDesc, 'ipAddress' =>$myip);
            // print_r($data);die;
            try {
              $result = $this->insert($data);
                print_r($result);die;
            } catch (Exception $e) {
                throw new Exception('Unable To Insert Exception Occured :' . $e);
            }

            if ($result) {
                return $result;
            }
            else {
            return 0;
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }


    //get nearby reports

    public function nearByReports() {
      // die("ok");
        if (func_num_args() > 0) {
          $lat = func_get_arg(0);
          $lng = func_get_arg(1);

        
            try {
              $select = $this->select()
              ->from($this)
              ->where("lat LIKE ?", "%{$lat}%")
              ->where("lon LIKE ?", "%{$lng}%");

              // echo $select;die;
               $result = $this->getAdapter()->fetchAll($select);
              //  print_r($result);die;
            } catch (Exception $e) {
                throw new Exception('Unable To Insert Exception Occured :' . $e);
            }

            if ($result) {
                return $result;
            }
            else {
            return 0;
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    public function nearByUsersReports() {
      // die("ok");
        if (func_num_args() > 0) {
          $lat = func_get_arg(0);
          $lng = func_get_arg(1);
            try {

              $select = $this->select()
              ->from(array('u' => 'users'), array("u.nickname","r.*"))
              ->setIntegrityCheck(false)
              ->join(array('r' => 'reports'), 'u.userId = r.userId', array())
              ->where("r.lat LIKE ?", "%{$lat}%")
              ->where("r.lon LIKE ?", "%{$lng}%");

               $result = $this->getAdapter()->fetchAll($select);
              //  print_r($result);die;
            } catch (Exception $e) {
                throw new Exception('Unable To Insert Exception Occured :' . $e);
            }

            if ($result) {
                return $result;
            }
            else {
            return 0;
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    public function getReportDetails() {
      // die("ok");
        if (func_num_args() > 0) {
          $reportId = func_get_arg(0);
// echo $reportId;die;
            try {
              $select = $this->select()
              ->from(array('u' => 'users'), array("u.nickname","r.*"))
              ->setIntegrityCheck(false)
              ->join(array('r' => 'reports'), 'u.userId = r.userId', array())
              ->where("r.reportId = ?", $reportId);
// echo $select;die;
               $result = $this->getAdapter()->fetchRow($select);
              //  print_r($result);die;
            } catch (Exception $e) {
                throw new Exception('Unable To Insert Exception Occured :' . $e);
            }

            if ($result) {
                return $result;
            }
            else {
            return 0;
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

}
