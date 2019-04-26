<?php
include 'config.php';
$province_id = $_REQUEST['province_id'];

 $query = $conn->query("SELECT
locations.location_name,
locations.geo_level_id,
locations.pk_id
FROM
locations
WHERE
locations.geo_level_id = 4
and locations.province_id = '$province_id'
order by location_name");
 ?>
<option>Select</option>
<?php
                                        while ($row = $query->fetch_assoc()) {
                                            // $id=$row["id_expense"];  
                                            $pk_id = $row["pk_id"];
                                            $product_name = $row["location_name"];

        ?>

        <option value="<?php echo $pk_id; ?>"><?php echo $product_name; ?></option>
                <?php
            }
        
?>