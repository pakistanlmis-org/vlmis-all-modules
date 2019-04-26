<?php

/**
 * Model_UserClickPaths
 *
 * Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for User Click Paths
 */
class Model_UserClickPaths extends Model_Base {

    /**
     * $_table
     * @var type
     */
    protected $_table;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('UserClickPaths');
    }

    /**
     * Update user click paths with current datetime
     */
    public function addUserClickPaths($resource_name) {
        $auth = App_Auth::getInstance();
        $userId = $auth->getIdentity();

        $user_click_login = new UserClickPaths();
        $resource_id = $this->_em->getRepository('Resources')->findBy(array("resourceName" => $resource_name));

        if (count($resource_id) > 0) {
            $user = $this->_em->getRepository('Users')->find($userId);
            $user_click_login->setUser($user);
            $resource = $this->_em->getRepository('Resources')->find($resource_id[0]->getPkId());
            $user_click_login->setResource($resource);
            $roles = $this->_em->getRepository('Roles')->find($this->_identity->getRoleId());
            $user_click_login->setRole($roles);
            if ($this->_identity->getProvinceId()) {
                $province = $this->_em->getRepository('Locations')->find($this->_identity->getProvinceId());
                $user_click_login->setProvince($province);
            } else {
                $province = $this->_em->getRepository('Locations')->find(10);
                $user_click_login->setProvince($province);
            }
            $user_click_login->setSessionId(Zend_Session::getId());
            $user_click_login->setCreatedDate(App_Tools_Time::now());
            $this->_em->persist($user_click_login);
            $this->_em->flush();
        }
    }

    /**
     * Get Daily Active Users
     * @return boolean
     */
    public function getDailyActiveUsers() {
        $str_sql = " SELECT
                        ROUND( COUNT( DISTINCT user_click_paths.user_id ) / 30 ) AS avg_user_count
                    FROM
                        user_click_paths
                    WHERE
                        DATE_FORMAT(created_date, '%Y-%m-%d') BETWEEN DATE_ADD(CURDATE(), INTERVAL - 30 DAY) AND CURDATE()";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Weekly Active Users
     * @return boolean
     */
    public function getWeeklyActiveUsers() {
        $str_sql = " SELECT
                        COUNT( DISTINCT user_click_paths.user_id ) AS user_count
                    FROM
                        user_click_paths
                    WHERE
                        DATE_FORMAT(created_date, '%Y-%m-%d') BETWEEN DATE_ADD(CURDATE(), INTERVAL -21 DAY) AND CURDATE()
                    ";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Monthly Active Users
     * @return boolean
     */
    public function getMonthlyActiveUsers() {
        $str_sql = " SELECT
                        COUNT( DISTINCT user_click_paths.user_id ) AS user_count
                    FROM
                        user_click_paths
                    WHERE
                        DATE_FORMAT(created_date, '%Y-%m-%d') BETWEEN DATE_ADD(CURDATE(), INTERVAL -90 DAY) AND CURDATE()
                    ";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Page Count For Active Roles
     * @return boolean
     */
    public function getPageCountForActiveRoles() {

        $date_from = App_Controller_Functions::dateToDbFormat($this->form_values['date_from']);
        $date_to = App_Controller_Functions::dateToDbFormat($this->form_values['date_to']);
        $province_id = $this->form_values['province_id'];
//        $str_sql1 = " SELECT
//                        Count(*) AS page_count,
//                        roles.role_name,
//                        roles.description,
//                        roles.pk_id AS role_id
//                    FROM
//                        user_click_paths
//                        INNER JOIN users ON user_click_paths.user_id = users.pk_id
//                        INNER JOIN roles ON users.role_id = roles.pk_id
//                    WHERE
//                        DATE_FORMAT( user_click_paths.created_date,'%Y-%m-%d') BETWEEN '$date_from' AND '$date_to'
//                    GROUP BY
//                    roles.pk_id
//                    LIMIT 1
//                    ";
        if ($province_id == "all") {
            $str_sql = "SELECT
	Count(*) AS page_count,
	roles.role_name,
	roles.description,
	roles.pk_id AS role_id
            FROM
                    user_click_paths
                    INNER JOIN resources ON user_click_paths.resource_id = resources.pk_id
            INNER JOIN users ON user_click_paths.user_id = users.pk_id
            INNER JOIN roles ON users.role_id = roles.pk_id
            WHERE

                    DATE_FORMAT(
                            user_click_paths.created_date,
                            '%Y-%m-%d'
                    ) BETWEEN '$date_from'
            AND '$date_to'
                AND resources.resource_type_id = 1
            GROUP BY
                    roles.pk_id";
        } else {
            $str_sql = "SELECT
	Count(*) AS page_count,
	roles.role_name,
	roles.description,
	roles.pk_id AS role_id
            FROM
                    user_click_paths
            INNER JOIN resources ON user_click_paths.resource_id = resources.pk_id
            INNER JOIN users ON user_click_paths.user_id = users.pk_id
            INNER JOIN roles ON users.role_id = roles.pk_id
            INNER JOIN warehouse_users ON users.pk_id = warehouse_users.user_id
            INNER JOIN warehouses ON warehouse_users.warehouse_id = warehouses.pk_id
            WHERE
            warehouses.province_id = '$province_id'
                AND resources.resource_type_id = 1
            AND users.pk_id <> 762
         AND
           DATE_FORMAT(
                            user_click_paths.created_date,
                            '%Y-%m-%d'
                    ) BETWEEN '$date_from'
            AND '$date_to'
            GROUP BY
                    roles.pk_id";
        }


        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Pages For Role
     * @return boolean
     */
    public function getPagesForRole() {
        $where = array();
        if (!empty($this->form_values['role_id'])) {
            $where[] = "roles.pk_id = " . $this->form_values['role_id'];
        }

        $where[] = "DATE_FORMAT(user_click_paths.created_date,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'";

        if (count($where) > 0) {
            $wr = " AND " . implode(" AND ", $where);
        }
        if ($this->form_values['province_id'] == 'all' || $this->form_values['role_id'] == 1) {
            $str_sql = " SELECT
                        Count(*) AS page_count,
                        resources.description AS page_description,
                        roles.role_name,
                        roles.description
                    FROM
                        user_click_paths
                        INNER JOIN resources ON user_click_paths.resource_id = resources.pk_id
                        INNER JOIN users ON user_click_paths.user_id = users.pk_id
                        INNER JOIN roles ON roles.pk_id = users.role_id
                    WHERE
                        resources.resource_type_id = 1
                         $wr
                    GROUP BY
                        resources.pk_id
                    ";
        } else {
            $province_id = $this->form_values['province_id'];
            $str_sql = " SELECT
                        Count(*) AS page_count,
                        resources.description AS page_description,
                        roles.role_name,
                        roles.description
                    FROM
                        user_click_paths
                        INNER JOIN resources ON user_click_paths.resource_id = resources.pk_id
                        INNER JOIN users ON user_click_paths.user_id = users.pk_id
                        INNER JOIN roles ON roles.pk_id = users.role_id
                        INNER JOIN warehouse_users ON users.pk_id = warehouse_users.user_id
		INNER JOIN warehouses ON warehouse_users.warehouse_id = warehouses.pk_id
                    WHERE
                    warehouses.province_id = '$province_id'
                    AND
                        resources.resource_type_id = 1
                         $wr
                    GROUP BY
                        resources.pk_id
                    ";
        }

        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Pages For Role
     * @return boolean
     */
    public function getPagesCountForRole() {
        $where = array();
        if (!empty($this->form_values['role_id'])) {
            $where[] = "roles.pk_id = " . $this->form_values['role_id'];
        }

        $where[] = "DATE_FORMAT(user_click_paths.created_date,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'";

        if (count($where) > 0) {
            $wr = " AND " . implode(" AND ", $where);
        }

        $str_sql = " SELECT
	DATE_FORMAT(
                            user_click_paths.created_date,
                            '%Y-%m'
                    ) AS `Date`,
                    users.login_id `Login ID`,
                    users.user_name `User `,
                    roles.role_name `Role`,
                    roles.description `Role Description`,
                    Count(
                            DISTINCT user_click_paths.pk_id
                    ) AS page_count
            FROM
                    user_click_paths
            INNER JOIN resources ON user_click_paths.resource_id = resources.pk_id
            INNER JOIN users ON user_click_paths.user_id = users.pk_id
            INNER JOIN roles ON roles.pk_id = users.role_id
            WHERE
                    resources.resource_type_id = 1
            AND roles.category_id = 278
            AND DATE_FORMAT(
                    user_click_paths.created_date,
                    '%Y-%m'
            ) BETWEEN '2016-04'
            AND '2016-08'
            GROUP BY
                    Date,
                    roles.pk_id
            ORDER BY
                    Date
                                ";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Pages For Role
     * @return boolean
     */
    public function getRoles() {


//        $str_sql = "SELECT
//                    roles.pk_id as role_id,
//                    roles.role_name
//                    FROM
//                    roles
//                    WHERE
//                    roles.category_id = 278 ";
//
//        $rec = $this->_em_read->getConnection()->prepare($str_sql);
//
//        $rec->execute();
//        $result = $rec->fetchAll();
//        if (count($result) > 0) {
//            return $result;
//        } else {
//            return false;
//        }
        $roles = Array(
            0 => Array('role_id' => 1, 'role_name' => "WHO"),
            1 => Array('role_id' => 2, 'role_name' => "GATES"),
            2 => Array('role_id' => 3, 'role_name' => "UNICEF")
        );
        return $roles;
    }

    /**
     * Get Pages For Role
     * @return boolean
     */
    public function getNationalRoles() {


//        $str_sql = "SELECT
//                    roles.pk_id as role_id,
//                    roles.role_name
//                    FROM
//                    roles
//                    WHERE
//                    roles.category_id = 278 ";
//
//        $rec = $this->_em_read->getConnection()->prepare($str_sql);
//
//        $rec->execute();
//        $result = $rec->fetchAll();
//        if (count($result) > 0) {
//            return $result;
//        } else {
//            return false;
//        }
        $roles = Array(
            0 => Array('role_id' => 1, 'role_name' => "nAsgharEPI"),
            1 => Array('role_id' => 2, 'role_name' => "Store Keeper"),
            2 => Array('role_id' => 3, 'role_name' => "Data manager EPI"),
            3 => Array('role_id' => 4, 'role_name' => "Guest"),
        );
        return $roles;
    }

    /**
     * Get Pages For Role
     * @return boolean
     */
    public function getProvincialRoles() {


//        $str_sql = "SELECT
//                    roles.pk_id as role_id,
//                    roles.role_name
//                    FROM
//                    roles
//                    WHERE
//                    roles.category_id = 278 ";
//
//        $rec = $this->_em_read->getConnection()->prepare($str_sql);
//
//        $rec->execute();
//        $result = $rec->fetchAll();
//        if (count($result) > 0) {
//            return $result;
//        } else {
//            return false;
//        }
        $roles = Array(
            0 => Array('role_id' => 162, 'role_name' => "Punjab Store"),
            1 => Array('role_id' => 163, 'role_name' => "Sindh Store"),
            2 => Array('role_id' => 161, 'role_name' => "Balochistan Store"),
            3 => Array('role_id' => 615, 'role_name' => "Fata Store")
        );
        return $roles;
    }

    /**
     * Get Pages For Role
     * @return boolean
     */
    public function getDistrictRoles() {


//        $str_sql = "SELECT
//                    roles.pk_id as role_id,
//                    roles.role_name
//                    FROM
//                    roles
//                    WHERE
//                    roles.category_id = 278 ";
//
//        $rec = $this->_em_read->getConnection()->prepare($str_sql);
//
//        $rec->execute();
//        $result = $rec->fetchAll();
//        if (count($result) > 0) {
//            return $result;
//        } else {
//            return false;
//        }
        $roles = Array(
            0 => Array('role_id' => 5, 'role_name' => "Division"),
            1 => Array('role_id' => 6, 'role_name' => "District"),
        //    2 => Array('role_id' => 7, 'role_name' => "Tehsil"),
         //   3 => Array('role_id' => 8, 'role_name' => "Union Council")
        );
        return $roles;
    }

}
