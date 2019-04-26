

<?php
require_once 'classes/database.php';

$res = $database->read4();

if ($res) {
    ?>

    <table class="table table-condensed ">
        <td>

            <form  method="POST" action="insert2.php">        
                <table class="table table-condensed table-bordered table-hover">
                    <thead class="bdr text-center bg-primary ">

                    <td  colspan="3">
                        <b>
                            VLMIS
                        </b>
                    </td>

                    </thead>
                    
                    <td>
                    <tr>

                        <th>Pk Id</th>
                        <th>UC</th>
                        <th>UC Code</th>

                    </tr>
                    </td>

                    <tbody>



    <?php
    $array = $database->Selectuc();

    while ($row = $res->fetch_array()) {
        ?>

                            <tr class="selectable">

                                <td name="pk_id"><?php echo $row['pk_id']; ?></td>

                                <td class="important" ><?php echo $row['location_name']; ?></td>

                                <td><input type="text"   id="uc_code" name="uc_code[]" value="<?php echo (!empty($array[$row['pk_id']]) ? $array[$row['pk_id']] : '') ?>"  placeholder="U.C Code" /></td> 

                        <input type="hidden" name="vlmis_code[]" value="<?php echo $row['pk_id']; ?>" />

                        </tr>     

        <?php
    }
    ?>
                    <tr><td>    <input type="submit" name="btn" value="Save" /></td></tr>


                    </tbody>
                    <input type="hidden" name="map" value="uc"   />

                </table>
            </form>            

        </td>
    <?php
} else {
    echo "<h4><b>No Records Found!</b></h4>";
}
?>

    <div style=" padding-left: 500px;">
    <?php
    $res = $database->read5();

    if ($res) {
        ?>


            <td>

                

                <table class="table table-condensed table-bordered table-hover">
                     <thead class="bdr text-center bg-primary ">

         <td  colspan="2">
                        <b>
                            DHIS
                        </b>
                    </td>

                    </thead>
                    <td>
                        <tr>


                            <th>UC Code</th>
                            <th>UC</th>


                        </tr>
                    </td>

                    <tbody>

    <?php
    while ($row = $res->fetch_array()) {
        ?>

                            <tr class="selectable">

                                <td name="cname"><?php echo $row['uncode']; ?></td>
                                <td class="important" ><?php echo $row['un_name']; ?></td>

                            </tr>

        <?php
    }
    ?>

                    </tbody>
                </table>
            </td>
    </table>  
    <?php
} else {
    echo "<h4><b>No Records Found!</b></h4>";
}
?>
</div>
