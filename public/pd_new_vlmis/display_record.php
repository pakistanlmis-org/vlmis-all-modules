<?php
include("config_vlmis.php");
if (isset($_POST['provinceId'])) {
    $prov_id = $_POST['provinceId'];
    $level_id = $_POST['lev_id'];
    //print_r($level_id);
    if ($level_id == -1) {

//    if ($level == 1) {
//        $and = '';
//    } else if ($level != 1) {
//        $and = " sysuser_tab.province = $province AND ";
//    }
//    if ($stakeholder == null) {
//        $check = '';
//    } else if ($stakeholder != null) {
//        $check = " sysuser_tab.stkid = $stakeholder AND ";
//    }

        $qry = "SELECT
users.pk_id as u_id,
users.cell_number AS contact,
users.email AS email,
users.user_name AS `name`,
users.login_id AS id, 
users.`status` AS `status`, 
warehouses.warehouse_name,
uc.location_name AS uc,
teh.location_name AS tehsil,
dis.location_name AS district,
province.location_name as prov, 
roles.role_name as r_name,
stakeholders.stakeholder_name as s_name, 
users.logged_at AS login_time
 
FROM
warehouse_users
INNER JOIN users ON users.pk_id = warehouse_users.user_id
INNER JOIN warehouses ON warehouse_users.warehouse_id = warehouses.pk_id
INNER JOIN locations AS uc ON warehouses.location_id = uc.pk_id
INNER JOIN locations AS teh ON uc.parent_id = teh.pk_id
INNER JOIN locations AS dis ON teh.district_id = dis.pk_id

INNER JOIN locations AS province ON province.pk_id = dis.province_id
INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
INNER JOIN roles ON users.role_id = roles.pk_id
WHERE
warehouses.stakeholder_id = 1 AND
warehouses.province_id = $prov_id 

order by stakeholders.geo_level_id ASC

";
        $result = mysqli_query($conn, $qry);
        $num = mysqli_num_rows($result);
        if ($num > 0) {
            ?>
            <button name="create_excel" id="create_excel" class="btn btn-success" style="float: right;margin-right:15px;height:35px;" onClick="tableToExcel('export', 'sheet 1', '<?php echo 'Data'; ?>')">Export To Excel</button>  
            <div id="export"> 
                
                <table class="table table-bordered table-condensed " style="table-layout:fixed;font-size: 12px" id="example">

                    <thead>

                        <tr>
                            <th class="text-center" style='width:5%;'>Sr. No.</th>
                            <th style='width:7%;'>Level</th>

                            <th style='width:10%;'>Warehouse</th>
                            <th style='width:18%;'>Login User</th>
                            <th style='width:7%;'>User Role</th>
                            <th style='width:5%;'>Status</th>
                            <th style='width:18%'>Operator Name</th>
                            <th style='width:15%'>Contact No.</th>
                            <th style='width:25%;'>Email</th>
                            <th style='width:10%;'>Last Login</th>
                            <th style='width:5%;'>Alert History</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        $stakeholder = '';
                        while ($row = $result->fetch_assoc()) {
//                    if ($row['stkid'] != $stakeholder) {
//                        $counter = 1;
//                        echo "<tr bgcolor=\"#D8E6FD\">";
//                        echo "<th colspan=\"3\">" . $row['stkname'] . "</th>";
//                        echo "</tr>";
//                    }
//                    else{
                            echo "<tr>";
                            echo "<td class=\"text-center\" width=\"60\">" . $counter++ . "</td>";
                            echo '<td>';
                            if($row['s_name']=="District EPI Office")
                                                        echo "District";
                            else if ($row['s_name']=="Divisional  EPI Office")
                                                        echo "Division";
                            else if ($row['s_name']=="Tehsil EPI Office")
                                                        echo "Tehsil";
                            else if ($row['s_name']=="Provincial EPI Office")
                                                        echo "Province";
                            else if ($row['s_name']=="EPI Center")
                            echo "UC";
                        echo '</td>';
                            echo "<td>" . $row['warehouse_name'] . "</td>";
                            echo "<td >" . $row['id'] . "</td>";
                            echo "<td >" . $row['r_name'] . "</td>";
                            if ($row['status'] == 1)
                                echo "<td>Active</td>";
                            else {
                                echo "<td>In-Active</td>";
                            }
                            echo "<td >" . $row['name'] . "</td>";
                            echo "<td  >" . $row['contact'] . "</td>";
                            echo "<td  >" . $row['email'] . "</td>";
                            echo "<td >" . $row['login_time'] . "</td>";
                            ?>
                        <td style="cursor:pointer;"> <?php echo ' <a   class="pull-left " onclick="window.open(\'contact_history.php?id=' . $row['u_id'] . '&user_email=' . $row['email'] . '&phone_num=' . $row['contact'] . '\', \'_blank\', \'scrollbars=1,width=800,height=500\');"><i class="fa fa-history" style="color:black !important;padding-top:5px;font-size:25px;"></i> </a>';
                            ?></td>
                            <?php
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
        } else {
            echo "No record found";
        }
    } else if ($level_id == 2) {
        $qry = "SELECT
users.pk_id as u_id,
users.cell_number AS contact,
users.email AS email,
users.user_name AS `name`,
users.login_id AS id, 
users.`status` AS `status`, 
warehouses.warehouse_name,
uc.location_name AS uc,
teh.location_name AS tehsil,
dis.location_name AS district,
province.location_name as prov, 
roles.role_name as r_name,
stakeholders.stakeholder_name as s_name
,
	users.logged_at AS login_time
FROM
warehouse_users
INNER JOIN users ON users.pk_id = warehouse_users.user_id
INNER JOIN warehouses ON warehouse_users.warehouse_id = warehouses.pk_id
INNER JOIN locations AS uc ON warehouses.location_id = uc.pk_id
INNER JOIN locations AS teh ON uc.parent_id = teh.pk_id
INNER JOIN locations AS dis ON teh.district_id = dis.pk_id

INNER JOIN locations AS province ON province.pk_id = dis.province_id
INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
INNER JOIN roles ON users.role_id = roles.pk_id
WHERE
warehouses.stakeholder_id = 1 AND
warehouses.province_id = $prov_id 
and stakeholders.pk_id= 2
order by stakeholders.geo_level_id ASC


";
        //print_r($qry);
        $result = mysqli_query($conn, $qry);
        $num = mysqli_num_rows($result);
        if ($num > 0) {
            ?>
            <button name="create_excel" id="create_excel" class="btn btn-success" style="float: right;margin-right:15px;height:35px;" onClick="tableToExcel('export', 'sheet 1', '<?php echo 'Data'; ?>')">Export To Excel</button>  
            <div id="export">   
                <table class="table table-bordered table-condensed " style="table-layout:fixed;font-size: 12px"  id="example">
                    <thead>
                        <tr>
                            <th class="text-center" style='width:5%;'>Sr. No.</th>
                            <th style='width:7%;'>Level</th>

                           <th style='width:10%;'>Warehouse</th>
                            <th style='width:18%;'>Login User</th>
                            <th style='width:7%;'>User Role</th>
                            <th style='width:5%;'>Status</th>
                            <th style='width:18%'>Operator Name</th>
                            <th style='width:15%'>Contact No.</th>
                            <th style='width:25%;'>Email</th>
                            <th style='width:10%;'>Last Login</th>
                            <th style='width:5%;'>Alert History</th>


                        </tr>
                    </thead>
                    <tbody>
            <?php
            $counter = 1;
            $stakeholder = '';
            while ($row = $result->fetch_assoc()) {
//                    if ($row['stkid'] != $stakeholder) {
//                        $counter = 1;
//                        echo "<tr bgcolor=\"#D8E6FD\">";
//                        echo "<th colspan=\"3\">" . $row['stkname'] . "</th>";
//                        echo "</tr>";
//                    }
//                    else{
                echo "<tr>";
                echo "<td class=\"text-center\" width=\"60\">" . $counter++ . "</td>";

                echo "<td width='20%'> Province </td>";
                echo "<td>" . $row['warehouse_name'] . "</td>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td >" . $row['r_name'] . "</td>";
                if ($row['status'] == 1)
                    echo "<td>Active</td>";
                else {
                    echo "<td>In-Active</td>";
                }
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['contact'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['login_time'] . "</td>";
                ?>
                        <td style="cursor:pointer;"> <?php echo ' <a  class="pull-left " onclick="window.open(\'contact_history.php?id=' . $row['u_id'] . '&user_email=' . $row['email'] . '&phone_num=' . $row['contact'] . '\', \'_blank\', \'scrollbars=1,width=800,height=500\');"><i class="fa fa-history" style="color:black !important;padding-top:5px;font-size:25px;"></i> </a>';
                ?></td>
                            <?php
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
                    <?php
                } else {
                    echo "No record found";
                }
            } else if ($level_id == 3) {
                $qry = "SELECT
users.pk_id as u_id,
users.cell_number AS contact,
users.email AS email,
users.user_name AS `name`,
users.login_id AS id, 
users.`status` AS `status`, 
warehouses.warehouse_name,
uc.location_name AS uc,
teh.location_name AS tehsil,
dis.location_name AS district,
province.location_name as prov, 
roles.role_name as r_name,
stakeholders.stakeholder_name as s_name 
,users.logged_at AS login_time
FROM
warehouse_users
INNER JOIN users ON users.pk_id = warehouse_users.user_id
INNER JOIN warehouses ON warehouse_users.warehouse_id = warehouses.pk_id
INNER JOIN locations AS uc ON warehouses.location_id = uc.pk_id
INNER JOIN locations AS teh ON uc.parent_id = teh.pk_id
INNER JOIN locations AS dis ON teh.district_id = dis.pk_id

INNER JOIN locations AS province ON province.pk_id = dis.province_id
INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
INNER JOIN roles ON users.role_id = roles.pk_id
WHERE
warehouses.stakeholder_id = 1 AND
warehouses.province_id = $prov_id 
and stakeholders.pk_id= 3
order by stakeholders.geo_level_id ASC

";
                //print_r($qry);
                $result = mysqli_query($conn, $qry);
                $num = mysqli_num_rows($result);
                if ($num > 0) {
                    ?>
            <button name="create_excel" id="create_excel" class="btn btn-success" style="float: right;margin-right:15px;height:35px;" onClick="tableToExcel('export', 'sheet 1', '<?php echo 'Data'; ?>')">Export To Excel</button>  
            <div id="export">   
                <table class="table table-bordered table-condensed " style="table-layout:fixed;font-size: 12px"  id="example">
                    <thead>
                        <tr>
                            <th class="text-center" style='width:5%;'>Sr. No.</th>
                            <th style='width:7%;'>Level</th>
                             
                            <th style='width:10%;'>Warehouse</th>
                            <th style='width:18%;'>Login User</th>
                            <th style='width:7%;'>User Role</th>
                            <th style='width:5%;'>Status</th>
                            <th style='width:18%'>Operator Name</th>
                            <th style='width:15%'>Contact No.</th>
                            <th style='width:25%;'>Email</th>
                            <th style='width:10%;'>Last Login</th>
                            <th style='width:5%;'>Alert History</th>


                        </tr>
                    </thead>
                    <tbody>
            <?php
            $counter = 1;
            $stakeholder = '';
            while ($row = $result->fetch_assoc()) {
//                    if ($row['stkid'] != $stakeholder) {
//                        $counter = 1;
//                        echo "<tr bgcolor=\"#D8E6FD\">";
//                        echo "<th colspan=\"3\">" . $row['stkname'] . "</th>";
//                        echo "</tr>";
//                    }
//                    else{
                echo "<tr>";
                echo "<td class=\"text-center\" width=\"60\">" . $counter++ . "</td>";

                echo "<td >Division</td>"; 
                 
                echo "<td>" . $row['warehouse_name'] . "</td>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td >" . $row['r_name'] . "</td>";
                if ($row['status'] == 1)
                    echo "<td>Active</td>";
                else {
                    echo "<td>In-Active</td>";
                }
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['contact'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['login_time'] . "</td>";
                ?>
                        <td style="cursor:pointer;"> <?php echo ' <a  class="pull-left " onclick="window.open(\'contact_history.php?id=' . $row['u_id'] . '&user_email=' . $row['email'] . '&phone_num=' . $row['contact'] . '\', \'_blank\', \'scrollbars=1,width=800,height=500\');"><i class="fa fa-history" style="color:black !important;padding-top:5px;font-size:25px;"></i> </a>';
                ?></td>
                            <?php
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
                    <?php
                } else {
                    echo "No record found";
                }
            } else if ($level_id == 4) {
                $qry = "SELECT
users.pk_id as u_id,
users.cell_number AS contact,
users.email AS email,
users.user_name AS `name`,
users.login_id AS id, 
users.`status` AS `status`, 
warehouses.warehouse_name,
uc.location_name AS uc,
teh.location_name AS tehsil,
dis.location_name AS district,
province.location_name as prov, 
roles.role_name as r_name,
stakeholders.stakeholder_name as s_name
,users.logged_at AS login_time
FROM
warehouse_users
INNER JOIN users ON users.pk_id = warehouse_users.user_id
INNER JOIN warehouses ON warehouse_users.warehouse_id = warehouses.pk_id
INNER JOIN locations AS uc ON warehouses.location_id = uc.pk_id
INNER JOIN locations AS teh ON uc.parent_id = teh.pk_id
INNER JOIN locations AS dis ON teh.district_id = dis.pk_id

INNER JOIN locations AS province ON province.pk_id = dis.province_id
INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
INNER JOIN roles ON users.role_id = roles.pk_id
WHERE
warehouses.stakeholder_id = 1 AND
warehouses.province_id = $prov_id 
and stakeholders.pk_id= 4
order by stakeholders.geo_level_id ASC

";
                //print_r($qry);
                $result = mysqli_query($conn, $qry);
                $num = mysqli_num_rows($result);
                if ($num > 0) {
                    ?>
            <button name="create_excel" id="create_excel" class="btn btn-success" style="float: right;margin-right:15px;height:35px;" onClick="tableToExcel('export', 'sheet 1', '<?php echo 'Data'; ?>')">Export To Excel</button>  
            <div id="export">   
                <table class="table table-bordered table-condensed " style="table-layout: fixed;font-size: 12px"  id="example">
                    <thead>
                        <tr>
                            <th class="text-center" style='width:5%;'>Sr. No.</th>
                            <th style='width:7%;'>Level</th>  
                             
                            <th style='width:10%;'>Warehouse</th>
                            <th style='width:18%;'>Login User</th>
                            <th style='width:7%;'>User Role</th>
                            <th style='width:5%;'>Status</th>
                            <th style='width:18%'>Operator Name</th>
                            <th style='width:15%'>Contact No.</th>
                            <th style='width:25%;'>Email</th>
                            <th style='width:10%;'>Last Login</th>
                            <th style='width:5%;'>Alert History</th>


                        </tr>
                    </thead>
                    <tbody>
            <?php
            $counter = 1;
            $stakeholder = '';
            while ($row = $result->fetch_assoc()) {
//                    if ($row['stkid'] != $stakeholder) {
//                        $counter = 1;
//                        echo "<tr bgcolor=\"#D8E6FD\">";
//                        echo "<th colspan=\"3\">" . $row['stkname'] . "</th>";
//                        echo "</tr>";
//                    }
//                    else{
                echo "<tr>";
                echo "<td class=\"text-center\" width=\"60\">" . $counter++ . "</td>";

                echo "<td width='20%'>District</td>";  
                
                echo "<td>" . $row['warehouse_name'] . "</td>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td >" . $row['r_name'] . "</td>";
                if ($row['status'] == 1)
                    echo "<td>Active</td>";
                else {
                    echo "<td>In-Active</td>";
                }
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['contact'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['login_time'] . "</td>";
                ?>
                        <td style="cursor:pointer;"> <?php echo ' <a  class="pull-left " onclick="window.open(\'contact_history.php?id=' . $row['u_id'] . '&user_email=' . $row['email'] . '&phone_num=' . $row['contact'] . '\', \'_blank\', \'scrollbars=1,width=800,height=500\');"><i class="fa fa-history" style="color:black !important;padding-top:5px;font-size:25px;"></i> </a>';
                ?></td>
                            <?php
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
                        <?php
                    } else {
                        echo "No record found";
                    }
                } else if ($level_id == 5) {
                    $qry = "SELECT
users.pk_id as u_id,
users.cell_number AS contact,
users.email AS email,
users.user_name AS `name`,
users.login_id AS id, 
users.`status` AS `status`, 
warehouses.warehouse_name,
uc.location_name AS uc,
teh.location_name AS tehsil,
dis.location_name AS district,
province.location_name as prov, 
roles.role_name as r_name,
stakeholders.stakeholder_name as s_name 
,users.logged_at AS login_time
FROM
warehouse_users
INNER JOIN users ON users.pk_id = warehouse_users.user_id
INNER JOIN warehouses ON warehouse_users.warehouse_id = warehouses.pk_id
INNER JOIN locations AS uc ON warehouses.location_id = uc.pk_id
INNER JOIN locations AS teh ON uc.parent_id = teh.pk_id
INNER JOIN locations AS dis ON teh.district_id = dis.pk_id

INNER JOIN locations AS province ON province.pk_id = dis.province_id
INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
INNER JOIN roles ON users.role_id = roles.pk_id
WHERE
warehouses.stakeholder_id = 1 AND
warehouses.province_id = $prov_id 
and stakeholders.pk_id= 5
order by stakeholders.geo_level_id ASC

";
                    //print_r($qry);
                    $result = mysqli_query($conn, $qry);
                    $num = mysqli_num_rows($result);
                    if ($num > 0) {
                        ?>
            <button name="create_excel" id="create_excel" class="btn btn-success" style="float: right;margin-right:15px;height:35px;" onClick="tableToExcel('export', 'sheet 1', '<?php echo 'Data'; ?>')">Export To Excel</button>  
            <div id="export">   
                <table class="table table-bordered table-condensed " style=" table-layout: fixed;font-size: 12px"  id="example">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:5%;">Sr. No.</th>
                            <th style="width:7%;">Level</th>  
                              <th style='width:8%;'>District</th>
                            <th style='width:10%;'>Warehouse</th>
                            <th style='width:18%;'>Login User</th>
                            <th style='width:7%;'>User Role</th>
                            <th style='width:5%;'>Status</th>
                            <th style='width:18%'>Operator Name</th>
                            <th style='width:15%'>Contact No.</th>
                            <th style='width:25%;'>Email</th>
                            <th style='width:10%;'>Last Login</th>
                            <th style='width:5%;'>Alert History</th>


                        </tr>
                    </thead>
                    <tbody>
            <?php
            $counter = 1;
            $stakeholder = '';
            while ($row = $result->fetch_assoc()) {
//                    if ($row['stkid'] != $stakeholder) {
//                        $counter = 1;
//                        echo "<tr bgcolor=\"#D8E6FD\">";
//                        echo "<th colspan=\"3\">" . $row['stkname'] . "</th>";
//                        echo "</tr>";
//                    }
//                    else{
                echo "<tr>";
                echo "<td class=\"text-center\" width=\"60\">" . $counter++ . "</td>";

                echo "<td width='20%'>Tehsil</td>"; 
                
                echo "<td>" . $row['district'] . "</td>";
                echo "<td>" . $row['warehouse_name'] . "</td>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td >" . $row['r_name'] . "</td>";
                if ($row['status'] == 1)
                    echo "<td>Active</td>";
                else {
                    echo "<td>In-Active</td>";
                }
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['contact'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['login_time'] . "</td>";
                ?>
                        <td style="cursor:pointer;"> <?php echo ' <a  class="pull-left " onclick="window.open(\'contact_history.php?id=' . $row['u_id'] . '&user_email=' . $row['email'] . '&phone_num=' . $row['contact'] . '\', \'_blank\', \'scrollbars=1,width=800,height=500\');"><i class="fa fa-history" style="color:black !important;padding-top:5px;font-size:25px;"></i> </a>';
                ?></td>
                            <?php
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
                        <?php
                    } else {
                        echo "No record found";
                    }
                } else if ($level_id == 6) {
                    $qry = "SELECT
users.pk_id as u_id,
users.cell_number AS contact,
users.email AS email,
users.user_name AS `name`,
users.login_id AS id, 
users.`status` AS `status`, 
warehouses.warehouse_name,
uc.location_name AS uc,
teh.location_name AS tehsil,
dis.location_name AS district,
province.location_name as prov, 
roles.role_name as r_name,
stakeholders.stakeholder_name as s_name 
,users.logged_at AS login_time
FROM
warehouse_users
INNER JOIN users ON users.pk_id = warehouse_users.user_id
INNER JOIN warehouses ON warehouse_users.warehouse_id = warehouses.pk_id
INNER JOIN locations AS uc ON warehouses.location_id = uc.pk_id
INNER JOIN locations AS teh ON uc.parent_id = teh.pk_id
INNER JOIN locations AS dis ON teh.district_id = dis.pk_id

INNER JOIN locations AS province ON province.pk_id = dis.province_id
INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
INNER JOIN roles ON users.role_id = roles.pk_id
WHERE
warehouses.stakeholder_id = 1 AND
warehouses.province_id = $prov_id 
and stakeholders.pk_id= 6
order by stakeholders.geo_level_id ASC

"; 
                    $result = mysqli_query($conn, $qry);
                    $num = mysqli_num_rows($result);
                    if ($num > 0) {
                        ?>
            <button name="create_excel" id="create_excel" class="btn btn-success" style="float: right;margin-right:15px;height:35px;" onClick="tableToExcel('export', 'sheet 1', '<?php echo 'Data'; ?>')">Export To Excel</button>  
            <div id="export">   
                <table class="table table-bordered table-condensed " style="table-layout: fixed;font-size: 12px"  id="example">
                    <thead>
                        <tr>
                            <th class="text-center" style='width:5%;'>Sr. No.</th>
                            <th style='width:7%;'>Level</th> 
                            <th style='width:7%;'>District</th>
                             <th style='width:7%;'>Tehsil</th>
                           <th style='width:10%;'>Warehouse</th>
                            <th style='width:18%;'>Login User</th>
                            <th style='width:7%;'>User Role</th>
                            <th style='width:5%;'>Status</th>
                            <th style='width:18%'>Operator Name</th>
                            <th style='width:15%'>Contact No.</th>
                            <th style='width:25%;'>Email</th>
                            <th style='width:10%;'>Last Login</th>
                            <th style='width:5%;'>Alert History</th>


                        </tr>
                    </thead>
                    <tbody>
            <?php
            $counter = 1;
            $stakeholder = '';
            while ($row = $result->fetch_assoc()) {
//                    if ($row['stkid'] != $stakeholder) {
//                        $counter = 1;
//                        echo "<tr bgcolor=\"#D8E6FD\">";
//                        echo "<th colspan=\"3\">" . $row['stkname'] . "</th>";
//                        echo "</tr>";
//                    }
//                    else{
                echo "<tr>";
                echo "<td class=\"text-center\" width=\"60\">" . $counter++ . "</td>";

                echo "<td width='20%'>UC</td>";
                echo "<td>" . $row['district'] . "</td>";
                echo "<td>" . $row['tehsil'] . "</td>";
                echo "<td>" . $row['warehouse_name'] . "</td>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td >" . $row['r_name'] . "</td>";
                if ($row['status'] == 1)
                    echo "<td>Active</td>";
                else {
                    echo "<td>In-Active</td>";
                }
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['contact'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['login_time'] . "</td>";
                ?>
                        <td style="cursor:pointer;"> <?php echo ' <a  class="pull-left " onclick="window.open(\'contact_history.php?id=' . $row['u_id'] . '&user_email=' . $row['email'] . '&phone_num=' . $row['contact'] . '\', \'_blank\', \'scrollbars=1,width=800,height=500\');"><i class="fa fa-history" style="color:black !important;padding-top:5px;font-size:25px;"></i> </a>';
                ?></td>
                            <?php
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
                        <?php
                    } else {
                        echo "No record found";
                    }
                }
            }