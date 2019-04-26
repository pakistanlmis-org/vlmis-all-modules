<!DOCTYPE HTML>
<?php
require_once("inc/db.php");
$wh_id = $_GET['wh_id'];
$user_id = $_GET['user_id'];
 $strSql = "Select ccem_id from warehouses where pk_id=$wh_id";
$rsSql = mysql_query($strSql);
$result = mysql_fetch_object($rsSql);
?>
<style>
    table {
        border-right: 1px solid #C1DAD7;
        border-left: 1px solid #C1DAD7;
        border-bottom: 1px solid #C1DAD7;
        border-top: 1px solid #C1DAD7;        
    }
    th {
        font: bold 11px "Trebuchet MS", Verdana, Arial, Helvetica,
            sans-serif;
        color: #000;
        border-right: 1px solid #C1DAD7;
        border-bottom: 1px solid #C1DAD7;
        border-top: 1px solid #C1DAD7;
        letter-spacing: 2px;
        text-transform: uppercase;
        text-align: left;
        padding: 6px 6px 6px 12px;
        background: #CAE8EA no-repeat;
    }
    td {
        font: bold 11px "Trebuchet MS", Verdana, Arial, Helvetica,
            sans-serif;
        border-right: 1px solid #C1DAD7;
        border-bottom: 1px solid #C1DAD7;
        background: #fff;
        padding: 6px 6px 6px 12px;
        color: #000;
    }
</style>
<html> 
    <body>
        <h3 align="center" >Update CCM WH ID</h3>
        <form action="action.php?action=add" id="form1" method="post">
            <table>
                <tr>
                    <td>ccm wh Id</td>
                    <td>
                        <input type="text" name="ccm_wh_id" value="<?= $result->ccem_id;?>">
                    </td>
                </tr>
                <input type="hidden" value="<?= $wh_id ?>" name="wh_id" >
                <input type="hidden" value="<?= $user_id ?>" name="user_id" >


                <tr >
                    <td align="right">
                        <input type="button" name="Update" value="Update"  onclick="addClose()"  >
                    </td>      </tr>
            </table>

        </form>

    </body>
</html>
<script type="text/javascript">
    function addClose() {
        document.getElementById("form1").submit();
        window.close();
        window.opener.location.reload();
}

</script>