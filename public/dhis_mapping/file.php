
<?php
require_once 'classes/database.php';

$res = $database->read();

if ($res) {
    ?>

    <table class="table table-condensed">
        <td>


            <form  method="POST" action="insert.php">          

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
                        <th>District</th>
                        <th>District Code</th>

                    </tr>
                    </thead>

                    <td>


                        <?php
                        $array = $database->Selectdistrict();

                        while ($row = $res->fetch_array()) {
                            ?>

                        <tr class="selectable">

                            <td name="pk_id"><?php echo $row['pk_id']; ?></td>

                            <td class="important" ><?php echo $row['location_name']; ?></td>

                            <td><input type="text"   id="dist_code" name="dist_code[]" value=" <?php echo (!empty($array[$row['pk_id']]) ? $array[$row['pk_id']] : '') ?> "  placeholder="District Code" /></td> 

                        <input type="hidden" name="vlmis_code[]" value="<?php echo $row['pk_id']; ?>" />

                        </tr>     

                        <?php
                    }
                    ?>
                    <tr><td>    <input type="submit" name="btn" value="Save" /></td></tr>

                    </tbody>

                    <input type="hidden" name="map" value="district"   />
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
        $res = $database->read1();

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


                        <th>District Code</th>
                        <th>District</th>


                    </tr>
            </td>

            <tbody>

                <?php
                while ($row = $res->fetch_array()) {
                    ?>

                    <tr class="selectable">

                        <td name="cname"><?php echo $row['distcode']; ?></td>
                        <td class="important" ><?php echo $row['district']; ?></td>

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








