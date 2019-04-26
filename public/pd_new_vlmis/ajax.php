<?php
 
include("config_vlmis.php");
if (isset($_POST['provinceId'])) {
    $prov_id = $_POST['provinceId'];
    $div_id=$_POST['divi_id'];
    $dist_id=$_POST['dist_id'];
    $tehsil_id=$_POST['teh_id'];
    $uc_id=$_POST['uc_id'];

   echo $qry_for_div = "SELECT 
	divi.pk_id,
	divi.location_name
FROM
	locations AS divi
WHERE
	divi.geo_level_id = 3
AND divi.parent_id=$prov_id
ORDER BY
	divi.location_name ASC";
     
    $result = mysqli_query($conn, $qry_for_div);
  
    if (mysqli_num_rows($result) > 0) {
        
        ?><option value="0" >Select</option> <?php
       ?><option value="-1" >All</option> <?php
        while ($row = $result->fetch_assoc()) {
            ?>
            <option value="<?php echo $row['pk_id']; ?>" ><?php echo $row['location_name']; ?></option>
            <?php
        }
    }
    else if(mysql_num_rows($result) == 0){
    ?>
        <option>No record</option>
      <?php     
    }

}
?>