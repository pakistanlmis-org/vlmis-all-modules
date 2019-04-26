
<?php

require_once 'classes/database.php';


//For tehsil
if(isset($_POST["pk_id"]) && !empty($_POST["pk_id"]))
    {
   
      $dist = new Database();
      $res = $dist->tehsildropdown();
    
  
     $rowCount = $res->num_rows;
     
    if($rowCount > 0)
     {
        echo '<option value="">Select tehsil</option>';
       while ($row = $res->fetch_array())
        { 
            echo '<option value="'.$row['pk_id'].'">'.$row['location_name'].'</option>';
        }
      }
    else
      {
        echo '<option value="">tehsil not available</option>';
      }
    }
    
    //For U.C
//if(isset($_POST["pk_id"]) && !empty($_POST["pk_id"])){
//   
//      $dist = new Database();
//      $res = $dist->ucdropdown();
//  
//      $rowCount = $res->num_rows;
//    
//    
//    if($rowCount > 0)
//        {
//        echo '<option value="">Select U.C</option>';
//        while ($row = $res->fetch_array())
//        { 
//            echo '<option value="'.$row['pk_id'].'">'.$row['location_name'].'</option>';
//        }
//    }else{
//        echo '<option value="">U.C not available</option>';
//    }
//}
    
    ?>
