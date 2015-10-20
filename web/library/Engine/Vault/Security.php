<?php

class Engine_Vault_Security {

    private $_coreObj;
    private $_appSetting;
    private $_env;
    private $_logger;
    private $_session;
    private $_auth;
    private $_db;
    private static $_instance = null;

    private function __clone() {
        
    }

//Prevent any copy of this object

    public static function getInstance() {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Engine_Vault_Security();
        return self::$_instance;
    }

    public function __construct() {

        $this->_coreObj = Engine_Core_Core::getInstance();

        /** get the website defaults * */
        $this->_appSetting = $this->_coreObj->getAppSetting();

        /** get app enviornment * */
        $this->_env = $this->_coreObj->getEnv();

        /** get loggers * */
        $this->_logger = $this->_coreObj->getLogger();

        /** get the session * */
        $this->_session = $this->_coreObj->getSession();

        /** get the auth instance * */
        $this->_auth = $this->_coreObj->getAuth();

        /** get the auth instance * */
        $this->_db = $this->_coreObj->getDb();
    }

    public function authenticate() {
        $data = new stdClass();
        $data->username = func_get_arg(0);
        $data->password = func_get_arg(1);
     //   echo $data;die;

        //	$storage = new Zend_Auth_Storage_Session($this->_appSetting->appName);
        // echo $this->_appSetting->appName; die;
        $auth = Zend_Auth::getInstance();
        $storage = new Zend_Auth_Storage_Session($this->_appSetting->appName);
        $auth->setStorage($storage);

        $authAdapter = new Zend_Auth_Adapter_DbTable($this->_db);

        /** check creds against the this table and column * */
        $authAdapter->setTableName('users')
                ->setIdentityColumn(stripos($data->username, '@') ? 'userEmail' : 'userName')
                ->setCredentialColumn('password');

        /** check creds against this values * */
        $authAdapter->setIdentity($data->username)
                ->setCredential($data->password)
                ->setCredentialTreatment('MD5(?)');
        //->setCredentialTreatment('SHA1(CONCAT(?,salt))');

        $result = $this->_auth->authenticate($authAdapter);
        //echo "<pre>"; print_r($result); echo "</pre>"; die;
        switch ($result->getCode()) {

            case Zend_Auth_Result::SUCCESS:
                $this->_logger->info('success');
                break;

            case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
            case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:

            default:

                foreach ($result->getMessages() as $message) {
                    //throw new Exception($message);
                    $errorMsg = $message;
                }
                break;
        }
        $response = new stdClass();
        if ($result->isValid()) {
            
            $storage->write($authAdapter->getResultRowObject());
            $this->_logger->info($storage);
            $this->_logger->info($auth->getIdentity());
            $response->code = 200;
            $response->data = $result;

            return $response;
        } else {
            $response->code = 198;
            $response->data = $errorMsg;
            return $response;
        }
    }

    public function simpleAuthenticate() {
        $data = new stdClass();
        $data->username = func_get_arg(0);
        $data->password = func_get_arg(1);
        $data->accessToken = func_get_arg(2);
//        $data->tabledetails = func_get_arg(3);

        $auth = Zend_Auth::getInstance();
        $storage = new Zend_Auth_Storage_Session($this->_appSetting->appName);
        $auth->setStorage($storage);
        $storage->write($data);
        $this->_logger->info($storage);
        $this->_logger->info($auth->getIdentity());
        return TRUE;
    }

}