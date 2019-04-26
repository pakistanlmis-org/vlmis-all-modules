<?php
$hostnamec = '10.10.10.4';
$usernamec = 'clmisuser';
$passwordc = '21#GizFfc.Jy';
$dbc = 'clmis';

for($i=2018; $i>=2010; $i--){
	for($m=1; $m<=12; $m++){
		$connc = mysqli_connect($hostnamec, $usernamec, $passwordc, $dbc);

		if($m < 10){
			$m = '0'.$m;
		}

		if($m == date("m") && $i == date("Y")){
			continue;
		}
// Get all districts
$qry = "SELECT
	summary_district.pk_id,
	summary_district.item_id,
	summary_district.stakeholder_id,
	summary_district.reporting_date,
	summary_district.province_id,
	summary_district.district_id,
	summary_district.consumption,
	summary_district.avg_consumption,
	summary_district.soh_district_store,
	summary_district.soh_district_lvl,
	summary_district.dist_reporting_rate,
	summary_district.field_reporting_rate,
	summary_district.reporting_rate,
	summary_district.total_health_facilities
FROM
	summary_district
WHERE
	DATE_FORMAT(summary_district.reporting_date,'%Y-%m') = '$i-$m'";
//echo $qry;
$queryA = $connc->query($qry);
$addSummary = '';
while ($row = $queryA->fetch_assoc()) {	
    $prov_id = $row['province_id'];
    $itmId = $row['item_id'];
    list($year, $month, $dd) = explode("-",$row['reporting_date']);
    $stkid = $row['stakeholder_id'];
    $dist_id = $row['district_id'];

    $addSummary .= "CALL REPUpdateSummaryDistrict2($prov_id,$dist_id,$stkid, '$itmId', $month, $year);";
    //$connc->query($addSummary);
}
//echo $addSummary;
//exit;
$connc->multi_query($addSummary);
sleep(1);
$connc->close();
}}