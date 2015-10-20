<?php

class Application_Model_Users extends Zend_Db_Table_Abstract {

    private static $_instance = null;
    protected $_name = 'users';

//Avoid Cloning

    public static function getInstance() {
        if (!is_object(self::$_instance))
            self::$_instance = new Application_Model_Users();
        return self::$_instance;
    }

    public function checkuser() {
		//die("test");

        if (func_num_args() > 0) {
			//die("test");
            $email = func_get_arg(0);
            $password = func_get_arg(1);
            // $password = md5(func_get_arg(1));

            try {
                $select = $this->select()
                        ->from($this)
                        ->where('email = ?', $email)
                        ->where('password = ?', $password);

                $result = $this->getAdapter()->fetchRow($select);
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

    public function adduser() {
		//die("test");

        if (func_num_args() > 0) {
			//die("test");
            $email = func_get_arg(0);
            $password = func_get_arg(1);
            $ipaddress = func_get_arg(2);

            try {
                $select = $this->select()
                        ->from($this)
                        ->where('email = ?', $email);

                $result = $this->getAdapter()->fetchRow($select);

                if($result){
                  if(!empty($result))
                  {
                    return 197; //already in our db
                  }
                }else {
                  $data = array('email' => $email, 'password' => $password, 'ipaddress' => $ipaddress);
                  try {
                    $result = $this->insert($data);
                  } catch (Exception $e) {
                      throw new Exception('Unable To Insert Exception Occured :' . $e);
                  }
                }
            } catch (Exception $e) {
                throw new Exception('Unable To Insert Exception Occured :' . $e);
            }

            if ($result) {
                return 200; //added Successfully
            }
            else {
            return 0;
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }


    public function getUserInfo() {
        if (func_num_args() > 0) {
            $userId = func_get_arg(0);
            try {
                $select = $this->select()
                        ->from($this)
                        ->where('userId = ?', $userId);
                $result = $this->getAdapter()->fetchRow($select);
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

    public function checknickname() {
		//die("test");

        if (func_num_args() > 0) {
			//die("test");
            $nick= func_get_arg(0);

            try {
                $select = $this->select()
                        ->from($this)
                        ->where('nickname = ?', $nick);

                $result = $this->getAdapter()->fetchRow($select);

                if($result){
                    return 198; //nick not available
                }
                else
                {
                  return 200; //nick available
                }
            } catch (Exception $e) {
                throw new Exception('Unable To Insert Exception Occured :' . $e);
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }



        public function setnickname() {
            if (func_num_args() > 0) {
                $userId= func_get_arg(0);
                $nickname= func_get_arg(1);
                try {
                    $data = array('nickname' => $nickname);

                    $result = $this->update($data, 'userId = "' . $userId . '"');

                    if($result){
                        return 200; //updated nickname
                    }
                    else
                    {
                      return 198; //no change
                    }
                } catch (Exception $e) {
                    throw new Exception('Unable To Insert Exception Occured :' . $e);
                }
            } else {
                throw new Exception('Argument Not Passed');
            }
        }


        public function setUserLocation() {
            if (func_num_args() > 0) {
                $userId= func_get_arg(0);
                $lat= func_get_arg(1);
                $lon= func_get_arg(2);
                try {
                    $data = array('lat' => $lat, 'lon' => $lon);

                    $result = $this->update($data, 'userId = "' . $userId . '"');

                    if($result){
                        return 200; //updated nickname
                    }
                    else
                    {
                      return 198; //no change
                    }
                } catch (Exception $e) {
                    throw new Exception('Unable To Insert Exception Occured :' . $e);
                }
            } else {
                throw new Exception('Argument Not Passed');
            }
        }

}
