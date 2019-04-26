<?php

class App_Auth extends Zend_Auth {

    /**
     *
     * @var App_Auth
     */
    protected static $_instance;

    /**
     * @return NULL
     */
    public function __construct() {
        
    }

    /**
     * @return App_Auth
     */
    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Create Zend_Auth_Adapter_Doctrine adapter
     *
     * @param $type string
     *
     * @return Zend_Auth_Adapter
     */
    protected function _authAdapterFactory($type) {
        switch ($type) {
            case 'doctrine':
            default:
                $em = Zend_Registry::get('doctrine');
                $authAdapter = new \App\Auth\Adapter\DoctrineORMAdapter($em, 'Users', 'u', 'pkId');
        }

        return $authAdapter;
    }

    /**
     * Login
     *
     * @param $login string
     * @param $password string
     *
     * @return bool
     */
    public function login($login, $password) {
        $auth = Zend_Auth::getInstance();

        //if ($auth->hasIdentity()) {
        //  return true;
        //} else {
        $adapter = $this->_authAdapterFactory('doctrine');
        $adapter->addConditions(array("loginId" => $login, "password" => $password, "status" => 1));
        $result = $auth->authenticate($adapter);

        if (!$result->isValid()) {
            $this->_updateFailedQuantity($login);
            $this->_updateFailedAt($login);
            return false;
        } else {
            $this->_addLoginTime();
            $this->_updateLoggedAt();
            return true;
        }
        //}
    }

    /**
     * Login
     *
     * @param $login string
     * @param $password string
     *
     * @return bool
     */
    public function loginAuth($auth_id) {
        $auth = Zend_Auth::getInstance();

        $adapter = $this->_authAdapterFactory('doctrine');
        $adapter->addConditions(array("auth" => $auth_id));
        $result = $auth->authenticate($adapter);

        if (!$result->isValid()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Increment (+1) number of failed logins
     *
     * @param string $login
     */
    protected function _updateFailedQuantity($login) {
        $em = Zend_Registry::get('doctrine');
        $user = $em->getRepository('Users')->findOneBy(array("loginId" => $login));

        if (count($user) <= 0) {
            return;
        }
        $qty = $user->getFailedQuantity();

        $user->setFailedQuantity( ++$qty);
        $user->setModifiedBy($user);
        $user->setModifiedDate(App_Tools_Time::now());

        $em->persist($user);
        $em->flush();
    }

    /**
     * Update failed_at with current datetime
     *
     * @param string $login
     */
    protected function _updateFailedAt($login) {
        $em = Zend_Registry::get('doctrine');
        $user = $em->getRepository('Users')->findOneBy(array("loginId" => $login));

        if (count($user) <= 0) {
            return;
        }

        $user->setFailedAt(App_Tools_Time::now());
        $user->setModifiedBy($user);
        $user->setModifiedDate(App_Tools_Time::now());
        $em->persist($user);
        $em->flush();
    }

    /**
     * Update logged_at with current datetime
     *
     * @param string $login
     */
    protected function _updateLoggedAt() {
        $userId = $this->getIdentity();
        $em = Zend_Registry::get('doctrine');
        $user = $em->getRepository('Users')->find($userId);

        if (count($user) <= 0) {
            return;
        }

        $user->setLoggedAt(App_Tools_Time::now());
        $user->setModifiedBy($user);
        $user->setModifiedDate(App_Tools_Time::now());
        $em->persist($user);
        $em->flush();
    }

    /**
     * Update logged_at with current datetime
     *
     * @param string $login
     */
    protected function _addLoginTime() {
        $userId = $this->getIdentity();
        $em = Zend_Registry::get('doctrine');
        $user_login = new UserLoginLog();
        $user_login->setIpAddress($_SERVER['HTTP_X_FORWARDED_FOR']);
        $user_login->setLoginTime(App_Tools_Time::now());
        $user = $em->getRepository('Users')->find($userId);
        $user_login->setUser($user);
        $user_login->setModifiedBy($user);
        $user_login->setModifiedDate(App_Tools_Time::now());
        $user_login->setCreatedBy($user);
        $user_login->setCreatedDate(App_Tools_Time::now());
        $em->persist($user_login);
        $em->flush();

        $session = new Zend_Session_Namespace("UserLog");
        $session->login_log_id = $user_login->getPkId();
    }

    /**
     * Salt password
     *
     * @param $password string
     *
     * @return string
     */
    public static function saltPassword($password) {
        if (NULL == $password)
            throw new Exception();

        return '*' . Zend_Registry::get('salt') . $password;
    }

    /**
     * Logout
     *
     * @return NULL
     */
    public function logout() {
        //$unset = true;
        //$this->_updateLoggedAt($unset);
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
    }

    /**
     * Check if user is logged in
     *
     * @return bool
     */
    public function isLoggedIn() {
        $auth = Zend_Auth::getInstance();
        return $auth->hasIdentity();
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin() {
        $auth = Zend_Auth::getInstance();
        return $auth->getIdentity();
    }

    public function getUserName() {
        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity();
        $user = Zend_Registry::get('doctrine')->getRepository('Users')->find($userId);
        if (count($user) > 0) {
            return $user->getUserName();
        } else {
            return false;
        }
    }

    public function getUserDepartment() {
        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity();
        $user = Zend_Registry::get('doctrine')->getRepository('Users')->find($userId);
        if (count($user) > 0) {
            return $user->getDepartment();
        } else {
            return false;
        }
    }

    /**
     * Checks user's role by userId
     *
     * @param int $userId
     * @return int
     */
    public function getRoleId() {
        $auth = Zend_Auth::getInstance();
         $userId = $auth->getIdentity();
     
      $user = Zend_Registry::get('doctrine')->getRepository('Users')->find($userId);
        if (count($user) > 0) {
            return $user->getRole()->getPkId();
        } else {
            return false;
        }
    }

    public function getRoleName($role_id = null) {
        if ($role_id == null) {
            $auth = Zend_Auth::getInstance();
            $userId = $auth->getIdentity();
            $user = Zend_Registry::get('doctrine')->getRepository('Users')->find($userId);
            if (count($user) > 0) {
                return $user->getRole()->getRoleName();
            } else {
                return false;
            }
        } else {
            $roles = Zend_Registry::get('doctrine')->getRepository('Roles')->find($role_id);
            if (count($roles) > 0) {
                return $roles->getDescription();
            } else {
                return false;
            }
        }
    }

    public function getWarehouseId() {

        $session = new Zend_Session_Namespace('alllevel');
        $wh_id = $session->warehouse;
        if (!empty($wh_id)) {
            return $wh_id;
        }

        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity();
        $user = Zend_Registry::get('doctrine')->getRepository('WarehouseUsers')->findOneBy(array("user" => $userId, "isDefault" => 1));
        if (count($user) > 0) {
            return $user->getWarehouse()->getPkId();
        } else {
            return false;
        }
    }

    public function getIsScannerEnable() {
        $wh_id = $this->getWarehouseId();
        $is_enable = Zend_Registry::get('doctrine')->getRepository('BarcodeScannerWarehouses')->findOneBy(array("warehouse" => $wh_id));
        if (count($is_enable) > 0) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    public function getUserLocationId() {
        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity();
        $user = Zend_Registry::get('doctrine')->getRepository('Users')->find($userId);
        if (count($user) > 0) {
            return $user->getLocation()->getPkId();
        } else {
            return false;
        }
    }

    public function getUserProvinceId() {
        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity();
        $user = Zend_Registry::get('doctrine')->getRepository('Users')->find($userId);
        if (count($user) > 0) {
            return $user->getLocation()->getProvince()->getPkId();
        } else {
            return false;
        }
    }

    public function getLocationId() {
        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity();

        $user = Zend_Registry::get('doctrine')->getRepository('WarehouseUsers')->findOneBy(array("user" => $userId, "isDefault" => 1));
        if (count($user) > 0) {
            return $user->getWarehouse()->getLocation()->getPkId();
        } else {
            return false;
        }
    }

    public function getWarehouseName() {
        $session = new Zend_Session_Namespace('alllevel');
        $wh_id = $session->warehouse;
        if (!empty($wh_id)) {
            $wh = Zend_Registry::get('doctrine')->getRepository('Warehouses')->find($wh_id);
            if (count($wh) > 0) {
                return $wh->getWarehouseName();
            }
        }

        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity();
        $user = Zend_Registry::get('doctrine')->getRepository('WarehouseUsers')->findOneBy(array("user" => $userId, "isDefault" => 1));
        if (count($user) > 0) {
            return $user->getWarehouse()->getWarehouseName();
        } else {
            return false;
        }
    }

    public function getProvinceId() {

        $session = new Zend_Session_Namespace('alllevel');
        $province = $session->province;
        if (!empty($province)) {
            return $province;
        }

        $auth = Zend_Auth::getInstance();
        $user_id = $auth->getIdentity();
        $em = Zend_Registry::get('doctrine');
        $db = $em->createQueryBuilder();
        $db->select('p.pkId')
                ->from("WarehouseUsers", "wu")
                ->join("wu.warehouse", "w")
                ->join("w.province", "p")
                ->where("wu.user = $user_id ");
        try {
            $row = $db->getQuery()->getResult();
        } catch (Exception $e) {
            App_FileLogger::info($e->getMessage());
        }

        if (count($row) > 0) {
            return $row[0]['pkId'];
        } else {
            return false;
        }
    }

    public function getStakeholderId() {
        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity();
        $user = Zend_Registry::get('doctrine')->getRepository('Users')->find($userId);
        if (count($user) > 0) {
            return $user->getStakeholder()->getPkId();
        } else {
            return false;
        }
    }

    public function getGeoLevelId($stakeholder_id) {
        $em = Zend_Registry::get('doctrine');
        $db = $em->createQueryBuilder();
        $db->select('gl.pkId')
                ->from("Stakeholders", "s")
                ->join("s.geoLevel", "gl")
                ->where("s.pkId = $stakeholder_id ");
        try {
            $row = $db->getQuery()->getResult();
        } catch (Exception $e) {
            App_FileLogger::info($e->getMessage());
        }

        if (count($row) > 0) {
            return $row[0]['pkId'];
        } else {
            return false;
        }
    }

    function getUserLevel($user_id) {
        $em = Zend_Registry::get('doctrine');
        $db = $em->createQueryBuilder();
        $db->select('wu')
                ->from("WarehouseUsers", "wu")
                ->join("wu.user", "u")
                ->join("wu.warehouse", "wh")
                ->join("wh.stakeholderOffice", "sh")
                ->where("u.pkId = $user_id ")
                ->andWhere("wu.isDefault =  1 ");
        try {
            $row = $db->getQuery()->getResult();
        } catch (Exception $e) {
            App_FileLogger::info($e->getMessage());
        }

        if (count($row) > 0) {
            return $row[0]->getWarehouse()->getStakeholderOffice()->getGeoLevel()->getPkId();
        } else {
            return false;
        }
    }

    /**
     * Confirm user
     *
     * @param string $key
     * @param int $id
     *
     * @return bool
     */
    public static function confirmUser($key, $id) {
        $usersList = Doctrine_Core::getTable('Model_Users')->findBy('pk_id', $id);

        if ($usersList->count() > 0 && $usersList->getFirst()->status == Model_Users::UNCONFIRMED && $usersList->getFirst()->regkey == $key) {
            try {
                $user = $usersList->getFirst();
                $user->status = Model_Users::CONFIRMED;
                $user->regkey = '';
                $user->save();
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }

    function getDistrictId($user_id = null) {
        $auth = Zend_Auth::getInstance();
        $user_id = $auth->getIdentity();

        $em = Zend_Registry::get('doctrine');
        $db = $em->createQueryBuilder();
        $db->select('d.pkId')
                ->from("WarehouseUsers", "wu")
                ->join("wu.warehouse", "w")
                ->join("w.district", "d")
                ->where("wu.user = $user_id");


        try {
            $row = $db->getQuery()->getResult();
        } catch (Exception $e) {
            App_FileLogger::info($e->getMessage());
        }

        if (count($row) > 0) {
            return $row[0]['pkId'];
        } else {
            return false;
        }
    }

    function getTehsilId($user_id = null) {
        $auth = Zend_Auth::getInstance();
        $user_id = $auth->getIdentity();

        $em = Zend_Registry::get('doctrine');
        $db = $em->createQueryBuilder();
        $db->select('l.pkId')
                ->from("WarehouseUsers", "wu")
                ->join("wu.warehouse", "w")
                ->join("w.location", "l")
                ->where("wu.user = $user_id")
                ->andWhere("w.stakeholderOffice = 5");

        try {
            $row = $db->getQuery()->getResult();
        } catch (Exception $e) {
            App_FileLogger::info($e->getMessage());
        }

        if (count($row) > 0) {
            return $row[0]['pkId'];
        } else {
            return false;
        }
    }

    public function getUserRecordId() {
        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity();
        $user = Zend_Registry::get('doctrine')->getRepository('Users')->find($userId);
        if (count($user) > 0) {
            return $user->getRecordId();
        } else {
            return false;
        }
    }

    public function getTotalUsers() {
        $auth = Zend_Auth::getInstance();
        $user_id = $auth->getIdentity();
        $em = Zend_Registry::get('doctrine');
        $db = $em->createQueryBuilder();
        $db->select(' count(DISTINCT u.pkId) as pkId')
                ->from("WarehouseUsers", "wu")
                ->join("wu.user", "u")
                ->join("wu.warehouse", "w")
                ->where("u.role IN (3,4,5,6,7,8)")
                ->andWhere("u.pkId NOT IN (343,751,1706)");

        try {
            $row = $db->getQuery()->getResult();
        } catch (Exception $e) {
            App_FileLogger::info($e->getMessage());
        }

        if (count($row) > 0) {
            return $row[0]['pkId'];
        } else {
            return false;
        }
    }
    
    public function getUserCount() {
        $province = $this->getProvinceId();
        //$date = date("Y-m-d");
        $str_sql = "SELECT COUNT(DISTINCT
	users.pk_id) total
FROM
	user_login_log
INNER JOIN users ON user_login_log.user_id = users.pk_id
INNER JOIN warehouse_users ON warehouse_users.user_id = users.pk_id
INNER JOIN warehouses ON warehouse_users.warehouse_id = warehouses.pk_id
WHERE
	user_login_log.login_time >= NOW() - INTERVAL 2 HOUR
ORDER BY
	user_login_log.pk_id DESC";
        $em = Zend_Registry::get('doctrine_read');
        $rec = $em->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result[0]['total'];
        } else {
            return '0';
        }
    }

    public static function ChangeRole($newrole) {
        $auth = Zend_Auth::getInstance();
        $em = Zend_Registry::get('doctrine');
        $record = self::getUserRecordId();
        if ($auth->hasIdentity() && $record == 'SUPERUSER') {
            $userId = $auth->getIdentity();
            $user = Zend_Registry::get('doctrine')->getRepository('Users')->find($userId);
            $role = Zend_Registry::get('doctrine')->getRepository('Roles')->find($newrole);
            $user->setRole($role);
            $em->persist($user);
            $em->flush();

            return array(
                'login_id' => $user->getLoginId(),
                'password' => $user->getPassword()
            );
        }
        return false;
    }

}
