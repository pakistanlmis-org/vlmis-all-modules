<?php

class App_Controller_Plugin_Survey extends Zend_Controller_Plugin_Abstract {

    /**
     * @var Zend_Auth
     */
    private $_auth = null;
    private $_em = null;

    public function __construct() {
        $this->_auth = App_Auth::getInstance();
        $this->_em_read = Zend_Registry::get("doctrine_read");
    }

    /**
     * Called before an action is dispatched by Zend_Controller_Dispatcher.
     *
     * This callback allows for proxy or filter behavior.  By altering the
     * request and resetting its dispatched flag (via
     * {@link Zend_Controller_Request_Abstract::setDispatched() setDispatched(false)}),
     * the current action may be skipped.
     *
     * @param  Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        if ($this->_auth->hasIdentity()) {
            $userId = $this->_auth->getIdentity();
            $str_sql = "SELECT
	users.user_name,
	users.email,
	users.cell_number,
	users.designation,
	users.department
FROM
	users
WHERE
	(
		email NOT REGEXP '^[a-zA-Z0-9][a-zA-Z0-9._-]*@[a-zA-Z0-9][a-zA-Z0-9._-]*\\.[a-zA-Z]{2,}$'
		OR cell_number NOT REGEXP '^[0-9().-]{12,}$'
	)
AND pk_id = $userId";
            $rec = $this->_em_read->getConnection()->prepare($str_sql);

            $rec->execute();
            $result = $rec->fetchAll();
            if (count($result) > 0) {
                Zend_Registry::set('isupdatable', 'Yes');

                Zend_Registry::set('username', $result[0]['user_name']);
                Zend_Registry::set('cellno', $result[0]['cell_number']);
                Zend_Registry::set('designation', $result[0]['designation']);
                Zend_Registry::set('department', $result[0]['department']);
                Zend_Registry::set('email', $result[0]['email']);
                Zend_Registry::set('userID', $userId);

                $str_sql2 = "SELECT
                            email_verification.email_address
                    FROM
                            email_verification
                    WHERE
                            email_verification.user_id = $userId
                    AND email_verification.is_verified = 0";
                $rec2 = $this->_em_read->getConnection()->prepare($str_sql2);

                $rec2->execute();
                $result2 = $rec2->fetchAll();

                if (count($result2) > 0) {
                    Zend_Registry::set('isupdatable', 'Verify');
                    Zend_Registry::set('email', $result2[0]['email_address']);
                }
            } else {
                Zend_Registry::set('isupdatable', 'No');
            }
        }
    }

}
