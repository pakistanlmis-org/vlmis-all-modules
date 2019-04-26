<?php

class App_Validate_Username extends Zend_Validate_Abstract {

    const INVALID = 'Value is invalid';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID => "Username is already in use,try another"
    );

    /**
     * Check if login already exist in database
     *
     * @param $value string
     *
     * @return Boolean
     */
    public function isValid($login) {
        $em = Zend_Registry::get('doctrine');
        $usersList = $em->getRepository("Users")->findBy(array("loginId" => $login));
        if (count($usersList) >= 1) {
            $this->_error(self::INVALID);
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
