<?php

/**
 * Zend_View_Helper_UserDelete
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper User Delete
 */
class Zend_View_Helper_UserStats extends Zend_View_Helper_Abstract {

    protected $_em_read;

    public function __construct() {
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Reports
     */
    public function userStats() {
        return $this;
    }
    /**
     * User Delete
     * @param type $warehouse_id
     * @return type
     */
    public function userStats1($role_id, $date) {
        if ($role_id == "Store Keeper" || $role_id == "Data manager EPI" || $role_id == "Guest") {
            if ($role_id=="Guest"){
                $role_id = "EPI Guest";
            }
            $str_sql1 = "SELECT
        users.pk_id
        
        FROM
        users
        WHERE
        users.user_name LIKE '%$role_id%'";
        } else {
            $str_sql1 = "SELECT
        users.pk_id
        
        FROM
        users
        WHERE
        users.login_id LIKE '%$role_id%'";
        }


        $rec1 = $this->_em_read->getConnection()->prepare($str_sql1);

        $rec1->execute();
        $result1 = $rec1->fetchAll();

        foreach ($result1 as $res) {
            //  if (is_array($result1)) {
            $res_d[] = $res['pk_id'];
            // }
        }
        if (empty($res_d)) {
            $res_d[] = 1;
        }

        if (is_array($res_d)) {
            $where_s = implode(" , ", $res_d);
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
            AND users.pk_id  IN ($where_s)
            AND DATE_FORMAT(
                    user_click_paths.created_date,
                    '%Y-%m'
            ) = '$date'
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
     * User Delete
     * @param type $warehouse_id
     * @return type
     */
    public function userStatsDistricts($role_id, $date) {


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
            AND users.role_id = '$role_id'
            AND DATE_FORMAT(
                    user_click_paths.created_date,
                    '%Y-%m'
            ) = '$date'
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
     * User Delete
     * @param type $warehouse_id
     * @return type
     */
    public function userStatsProvince($role_id, $date) {


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
            AND users.pk_id = '$role_id'
            AND DATE_FORMAT(
                    user_click_paths.created_date,
                    '%Y-%m'
            ) = '$date'
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

}

?>