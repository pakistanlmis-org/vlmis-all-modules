<?php
/**
 * clsLogin
 * @package includes/class
 * 
 * @author     Ajmal Hussain
 * @email <ahussain@ghsc-psm.org>
 * 
 * @version    2.2
 * 
 */
class clsLogin {
    //strPass
    var $m_strPass = "";
    //login
    var $m_login = "";
/**
 * Update
 * 
 * @return boolean
 */
    function Update() {
        $hash = md5(strtolower($_SESSION['user_name']) . '' . $this->m_strPass);
        //update query
        $strSql = "UPDATE sysuser_tab SET sysusr_pwd='" . $this->m_strPass . "', auth='" . $hash . "' WHERE UserID='" . $this->m_login . "'";
        //query result
        $rsSql = mysqli_query($strSql) or die("Error " . $strSql);
        if (mysql_affected_rows()) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }
/**
 * Login
 * 
 * @return type
 */
    function Login() {
        $this->m_strPass = base64_encode($this->m_strPass);
        //login query
        $qry = "SELECT
					COUNT(sysuser_tab.UserID) AS numOfRec,
					sysuser_tab.UserID,
					sysuser_tab.sysusr_type,
					sysuser_tab.sysusr_name,
					tbl_warehouse.wh_id,
					tbl_warehouse.stkid,
					tbl_warehouse.stkofficeid,
					sysuser_tab.user_level AS lvl,
					tbl_warehouse.prov_id,
					tbl_warehouse.dist_id,
					tbl_warehouse.is_allowed_im,
					stakeholder.stk_type_id,
					sysuser_tab.province AS user_province,
					sysuser_tab.stkid AS user_stk,
					resources.resource_name AS landing_page
				FROM
					sysuser_tab
				INNER JOIN roles ON sysuser_tab.sysusr_type = roles.pk_id
				INNER JOIN resources ON roles.landing_resource_id = resources.pk_id
				LEFT JOIN wh_user ON sysuser_tab.UserID = wh_user.sysusrrec_id
				LEFT JOIN tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
				LEFT JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				WHERE
					usrlogin_id = '" . $this->m_login . "'
				AND sysusr_pwd = '" . $this->m_strPass . "'
				AND sysusr_status = 'Active'";
        //echo $qry;exit;
        //query result
        $qryRes = mysqli_query($qry);
        $result = mysql_fetch_array($qryRes);
        return $result;
    }
/**
 * getOldPass
 * 
 * @return string
 */
    function getOldPass() {
        //old pass query
        $strSql = "select sysusr_pwd from sysuser_tab 
		where UserID='" . $this->m_login . "' and sysusr_status='Active'";
        $rsSql = mysqli_query($strSql) or die("Error");
        //query result
        $r = mysql_fetch_row($rsSql);
        if (mysql_num_rows($rsSql) > 0) {
            return $r[0];
        } else {
            return "";
        }
    }

}

?>