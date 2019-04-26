<?php
require_once("inc/db.php");
?>
<option value="">Select District</option>
<?php
if(!empty($_POST['province_id']))
{
	$district = '';
	if ( !empty($_POST['dist_id']) )
	{
		$district = $_POST['dist_id'];
	}
	$getAllDist = "SELECT
	locations.pk_id as PkLocID,
	locations.location_name as LocName
FROM
	locations
WHERE
	locations.province_id = '".$_POST['province_id']."'
AND locations.geo_level_id = 4
AND pk_id IN (
	SELECT DISTINCT
		warehouses.district_id as dist_id
	FROM
		warehouses
	WHERE
		warehouses.stakeholder_office_id = 6
	AND province_id = '".$_POST['province_id']."'
)";
        $rs1 = mysql_query($getAllDist) or die(mysql_error());
	while($rowProv=mysql_fetch_assoc($rs1))
	{
	?>
    	<option value="<?php echo $rowProv['PkLocID'] ?>" <?php if($district == $rowProv['PkLocID']){echo "selected=selected";}?>><?php echo $rowProv['LocName'] ?></option>
	<?php 
	}
}