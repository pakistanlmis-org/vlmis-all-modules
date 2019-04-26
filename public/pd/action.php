<?php

require_once("inc/db.php");

$act_page = $_GET['action'];
switch ($act_page) {
    case "update":

        $user_id = $_POST['userId'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $realname = $_POST['realname'];
        $designation = $_POST['designation'];
        $email = $_POST['email'];
        //$strSql = "UPDATE sysuser_tab  set sysusr_email='" . $email . "',sysusr_addr='" . $address . "',sysusr_cell='" . $phone . "',sysusr_name='" . $realname . "',sysusr_deg='" . $designation . "' where UserID='" . $user_id . "'";
$strSql = "UPDATE users
SET email = '" . $email . "',
 address = '" . $address . "',
 phone_number = '" . $phone . "',
 user_name = '" . $realname . "',
 designation = '" . $designation . "'
WHERE
	pk_id = '" . $user_id . "'";
        $rsSql = mysql_query($strSql);

        header("Location:http://lmis.gov.pk/vlmis/plmis_admin/pd/update-user.php?user=$user_id&success=1");
        exit;

        break;
    case "add":

        $wh_id = $_POST['wh_id'];
        $user_id = $_POST['user_id'];
        $ccm_wh_id = $_POST['ccm_wh_id'];

        $strSql = "UPDATE warehouses  set ccem_id='" . $ccm_wh_id . "'  where pk_id='" . $wh_id . "'";

        $rsSql = mysql_query($strSql);

        exit;

        break;

    case "add2":

        $loc_id = $_POST['loc_id'];

        $ccm_loc_id = $_POST['ccm_loc_id'];

        $strSql = "UPDATE locations  set ccm_location_id='" . $ccm_loc_id . "'  where pk_id='" . $loc_id . "'";

        $rsSql = mysql_query($strSql);

        exit;

        break;
}
?>