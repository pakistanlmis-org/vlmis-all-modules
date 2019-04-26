<?php
 
include("config_vlmis.php");
if (isset($_POST['divi_id'])) {
    $prov_id = $_POST['provinceId'];
    $div_id=$_POST['divi_id'];
    $dist_id=$_POST['dist_id'];
    $tehsil_id=$_POST['teh_id'];
    $uc_id=$_POST['uc_id'];

    $qry_for_div = "SELECT 
	divi.pk_id,
	divi.location_name
FROM
	locations AS divi
WHERE
	divi.geo_level_id = 6
AND divi.province_id=$prov_id
ORDER BY
	divi.location_name ASC";
    $result = mysqli_query($conn, $qry_for_div);
    if (mysqli_num_rows($result)) {
        ?><option value="0" >Select</option> <?php
          ?><option value="-1" >All</option> <?php
        while ($row = $result->fetch_assoc()) {
            ?>
            <option value="<?php echo $row['pk_id']; ?>" ><?php echo $row['location_name']; ?></option>
            <?php
        }
    }
    else {
    ?>
        <option>NO UC</option>
      <?php     
    }

}
else{
    
    $prov_id = $_POST['provinceId'];
    $dist_id=$_POST['dist_id'];
    $tehsil_id=$_POST['teh_id'];
    $uc_id=$_POST['uc_id'];
    $qry_for_teh = "SELECT 
	teh.pk_id,
	teh.location_name
FROM
	locations AS teh
WHERE
	teh.geo_level_id = 6
AND teh.province_id=$prov_id
    AND teh.district_id=$dist_id
ORDER BY
	teh.location_name ASC";
    
    $result = mysqli_query($conn, $qry_for_teh);
    if (mysqli_num_rows($result) > 0) {
        
        ?><option value="0" >Select</option> <?php
  ?><option value="-1" >All</option> <?php
        while ($row = $result->fetch_assoc()) {
            ?>
            <option value="<?php echo $row['pk_id']; ?>" ><?php echo $row['location_name']; ?></option>
            <?php
        }
    }
    else {
     ?>
            <option value><?php echo"No record"?></option>
            <?php
    }
}
?>