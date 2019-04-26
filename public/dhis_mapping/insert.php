<?php
//echo '<pre>';
//print_r($_REQUEST);
//exit;

require_once 'classes/database.php';

$dist_code = '';
$pk_id = '';
$map='';

     $vlmis_code = $_POST['vlmis_code'];
     
     $map = $_POST['map'];

foreach ($_POST['dist_code'] as $key => $value) { 
    
   
    
    if ($value > 0) {        
        $res = $database->create($value, $vlmis_code[$key],$map);
        header("location:index.php");
    }
}

    
//        
//if (isset($_POST["btn"])) 
//{
//     
//if(isset($_POST) & !empty($_POST))
//    {
//    
//	$dist_code = $database->sanitize($_POST['dist_code']);
//        $pk_id = $database->sanitize($_POST['pk_id']);
//}
//
//$res = $database->create($dist_code,$pk_id );
//if($res)
//{
//	
//       header("location:index.php");
//}
//else
//    {
//	echo "failed to insert data";
//    }
//
//}
//
?>

