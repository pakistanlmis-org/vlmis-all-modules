<?php
include 'config.php';
$hf = $_REQUEST['hf_id'];

$m_hf = explode('|',$hf);

 $ccem_id = $m_hf[0];
 $lmis_id = $m_hf[1];
if ($lmis_id == 0){
    $lmis_id = '';
}


    $str_qry1_12 = "UPDATE import_ccem SET hf_id='$lmis_id', status='1' Where import_ccem.service_point = '$ccem_id'";
   
   $conn->query($str_qry1_12);
 ?>
