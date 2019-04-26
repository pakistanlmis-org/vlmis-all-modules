<?php
require_once("config_vlmis.php");
$user_id = $_REQUEST['id'];
$email = $_REQUEST['user_email'];
$phone = $_REQUEST['phone_num'];
//
//
//$userSql = "SELECT
//            tbl_warehouse.wh_id,
//            tbl_warehouse.wh_name
//            FROM
//            tbl_warehouse
//            WHERE
//            tbl_warehouse.wh_id = $wh_id";
//$userResult = mysql_query($userSql) or die("Error " . $userSql);
//$row_warehouse = mysql_fetch_assoc($userResult);


$userSql = "SELECT
	alerts_log.`to`,
	alerts_log.`subject`,
	alerts_log.body,
	alerts_log.created_date,
	alerts_log.response,
	alerts_log.type
FROM
	alerts_log 
WHERE
	alerts_log.`to` IN (
		'$email',
		'$phone'
	)
ORDER BY
	alerts_log.created_date DESC
		 
            ";
$result = mysqli_query($conn, $userSql);
?>    
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
</head>
<!-- END HEAD -->

<!-- BEGIN body -->
<body class="page-header-fixed page-quick-sidebar-over-content1" >
    <!-- BEGIN HEADER -->
    <form action="" method="post" id="survey" name="survey">
        <div id="msg_row" class="">
        </div>
        <div id="btn_div" class="">
        </div>
        <div class="portlet box yellow-gold">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-history"></i>  <h5>Message / Email History </h5>  
                </div>
            </div>
            <div class="portlet-body">
                <div class="margin-top-10 margin-bottom-10 clearfix">



                    <?php
                    $c = 1;
                    $num = mysqli_num_rows($result);
                    if ($num > 0) {
                        ?>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td>#</td> 
                                    <td>Type</td>
                                    <td>Email</td>
                                    <td>Sent On</td> 
                                    <td>Message</td> 
                                    <td>Status</td> 
                                </tr><?php
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $c++ . '</td>';
                                    if ($row['type'] == 'SMS')
                                        echo '<td><i class="fa fa-comment" style="color:green !important;"></i> ' . $row['type'] . '</td>';
                                    else
                                        echo '<td><i class="fa fa-envelope" style="color:purple !important;"></i> ' . $row['type'] . '</td>';

                                    echo '<td>' . $email . '</td>';
                                    echo '<td>' . date('d-M-Y h:m A', strtotime($row['created_date'])) . '</td>';
                                    echo '<td>' . nl2br($row['body']) . '</td>';
                                    if ($row['response'] == 'Invalid Destination Number.') {
                                        echo '<td style="color:red">' . $row['status'] . '</td>';
                                    } else{
                                        echo '<td style="color:green">' . $row['response'] . '</td>';
                                    } 
                                    echo '</tr>';
                                }
                            } else {
                                echo 'NO AlERT HISTORY FOR THIS USER ' . $email;
                            }
                            ?>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="last_row">
            <button type ="submit" id="submit" name="submit" value="submit"  class=" btn btn-primary <?= ((empty($sysusr_email) || isset($_REQUEST['submit'])) ? ' hide ' : '') ?>">Send</button>
        </div>
    </form>


    <script src="../../public/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>

    <script>
        $(document).ready(function () {
            $("#subject").on('input', function () {
                var a = $(this).val().length;
                var b = 150 - a;
                $('#max_sub').html(b);
            });
            $("#message").on('input', function () {
                var a = $(this).val().length;
                var b = 1000 - a;
                $('#max_msg').html(b);
            });
        });
    </script>
</body>
<!-- END BODY -->
</html>
