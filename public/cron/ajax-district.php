<?php
include 'config.php';
$province_id = $_REQUEST['province_id'];

 $query = $conn->query("SELECT
                                        DISTINCT
                                        
                                        import_ccem.lowest_distribution
                                        FROM
                                        import_ccem
                                        where subnational_1 = '$province_id'");
 ?>
<option>Select</option>
<?php
                                        while ($row = $query->fetch_assoc()) {
                                            // $id=$row["id_expense"];  
                                            $pk_id = $row["lowest_distribution"];
                                            $product_name = $row["lowest_distribution"];

        ?>

        <option value="<?php echo $pk_id; ?>"><?php echo $product_name; ?></option>
                <?php
            }
        
?>