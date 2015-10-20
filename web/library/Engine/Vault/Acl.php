<?php

class Engine_Vault_Acl extends Zend_Acl {

    public function __construct() {
        // Add a new role called "guest"
        $this->addRole(new Zend_Acl_Role('guest'));
        // Add a role called user, which inherits from guest
        $this->addRole(new Zend_Acl_Role('user'), 'guest');
        // Add a role called admin, which inherits from user
        $this->addRole(new Zend_Acl_Role('admin'), 'user');

        $this->add(new Zend_Acl_Resource('user'))
                //ADD MODULE FIRST -> (module::controller),controller
                ->add(new Zend_Acl_Resource('user::user'), 'user')
                  ->add(new Zend_Acl_Resource('user::authentication'), 'user')
                    ->add(new Zend_Acl_Resource('user::dashboard'), 'user')
                  //THEN ADD ACTIONS -> (module::controller::action),module::controller

                  //user module
                      ->add(new Zend_Acl_Resource('user::user::home'), 'user::user')
                        ->add(new Zend_Acl_Resource('user::user::login'), 'user::user')
                          ->add(new Zend_Acl_Resource('user::user::register'), 'user::user')
                            ->add(new Zend_Acl_Resource('user::user::recover'), 'user::user')

                          // ->add(new Zend_Acl_Resource('user::user::request-recover'), 'user::user')

                            //authentication module
                              ->add(new Zend_Acl_Resource('user::authentication::check'), 'user::authentication')
                              ->add(new Zend_Acl_Resource('user::authentication::signup'), 'user::authentication')
                              ->add(new Zend_Acl_Resource('user::authentication::checknick'), 'user::authentication')
                              ->add(new Zend_Acl_Resource('user::authentication::setnick'), 'user::authentication')

                              //dashboard module
                              ->add(new Zend_Acl_Resource('user::dashboard::dashboard'), 'user::dashboard')
                              ->add(new Zend_Acl_Resource('user::dashboard::logout'), 'user::dashboard')
                              ->add(new Zend_Acl_Resource('user::dashboard::my-dashboard'), 'user::dashboard')
                              ->add(new Zend_Acl_Resource('user::dashboard::profile'), 'user::dashboard')
                              ->add(new Zend_Acl_Resource('user::dashboard::report-crime'), 'user::dashboard')
                              ->add(new Zend_Acl_Resource('user::dashboard::reported-crimes-nearby'), 'user::dashboard')
                              ->add(new Zend_Acl_Resource('user::dashboard::report-a-crime'), 'user::dashboard')
                              ->add(new Zend_Acl_Resource('user::dashboard::get-report-details'), 'user::dashboard')
                              ->add(new Zend_Acl_Resource('user::dashboard::store-my-location'), 'user::dashboard');
                              //store-my-location
                              //getReportDetails


        // ALLOW ACCESS TO GUEST
        // ROLE, MODULE::CONTROLLER::ACTION
                  $this->allow('guest', 'user::user::home')
                        ->allow('guest', 'user::user::login')
                        ->allow('guest', 'user::user::register')
                        ->allow('guest', 'user::user::recover')
                    // ->allow('guest', 'user::user::request-recover')

                        ->allow('guest', 'user::authentication::check')
                        ->allow('guest', 'user::authentication::signup')
                        ->allow('guest', 'user::authentication::checknick')
                        ->allow('guest', 'user::authentication::setnick')

                        ->allow('guest', 'user::dashboard::dashboard')
                        ->allow('guest', 'user::dashboard::logout')
                        ->allow('guest', 'user::dashboard::my-dashboard')
                        ->allow('guest', 'user::dashboard::profile')
                        ->allow('guest', 'user::dashboard::report-crime')
                        ->allow('guest', 'user::dashboard::reported-crimes-nearby')
                        ->allow('guest', 'user::dashboard::report-a-crime')
                        ->allow('guest', 'user::dashboard::get-report-details')
                        ->allow('guest', 'user::dashboard::store-my-location');

    }

}

?>
