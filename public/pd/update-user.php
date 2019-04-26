<!DOCTYPE HTML>



<script src="js/jquery.js"></script>
<script src="js/jquery.validate.js"></script>



<script>
    $( document ).ready(function() {
        
   $("#update-user").validate({
    rules: {
        phone: {
            
             number : true   
        },
        email: {
          
            email: true
        }
    }
    
});
});	
</script>

<?php
require_once("inc/db.php");
$user_Id = $_GET['user'];
$strSql = "SELECT
	email,
	address,
	phone_number AS phoneNo,
	user_name AS realName,
	designation
FROM
	users
WHERE
	pk_id =$user_Id";
$rsSql = mysql_query($strSql);
$result = mysql_fetch_object($rsSql);
?>
<?php if (isset($_GET['success']) && $_GET['success'] == 1) { ?>

    <div id="msg" style="color: red">
        <strong>Update</strong> successfully!
    </div>            
<?php } ?>
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
        <h3 align="center" >Update User Detail</h3>
        <form action="action.php?action=update" method="post" name="update-user" id="update-user">
            <table>
                <tr>
                    <td>Full Name</td>
                    <td>
                        <input type="text" name="realname" value="<?= $result->realName; ?>" >
                    </td>
                </tr> 

                <tr>
                    <td>Designation</td>
                    <td>
                        <input type="text" name="designation" value="<?= $result->designation; ?>">
                    </td>
                </tr>
                <input type="hidden" value="<?= $user_Id ?>" name="userId">
                <tr>
                    <td>Address</td>
                    <td>
                        <input type="text" name="address" value="<?= $result->address; ?>" >
                    </td>
                </tr>   
                <tr>
                    <td>Phone No.</td>
                    <td>
                        <input type="text" name="phone" id="phone" value="<?= $result->phoneNo; ?>">
                    </td>
                </tr>   
                <tr>
                    <td>Email</td>
                    <td>
                        <input type="text" name="email"  id="email" value="<?= $result->email; ?>">
                    </td>
                </tr>   
                <tr>
                    <td>
                        <input type="Submit" name="Update" value="Update">
                    </td>    
                </tr>
            </table>

        </form>

    </body>
</html>
