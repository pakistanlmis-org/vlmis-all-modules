

<?php

require_once 'classes/database.php';

$uc_code = '';
$pk_id = '';
$map='';

     $vlmis_code = $_POST['vlmis_code'];

     $map = $_POST['map'];

foreach ($_POST['uc_code'] as $key => $value) {    
    if ($value > 0) {        
        $res = $database->create($value, $vlmis_code[$key],$map);
        header("location:index.php");
    }
}


?>


