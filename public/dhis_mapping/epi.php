


<?php
 
  require_once 'classes/database.php';
 
  $res = $database->readepivlmis();

     if ($res) 
         {
         
            ?>
        
            <table class="table table-condensed">
                <td>
                    
                      
                    
    <form  method="POST" action="epiinsert.php">        
        
    <table class="table table-condensed table-bordered table-hover">
         <thead class="bdr text-center bg-primary ">

         <td  colspan="4">
                        <b>
                            VLMIS
                        </b>
                    </td>




                    </thead>
        <td>
            <tr>
                      
                <th>UC</th>
                <th>Pk Id</th>
                <th>Warehouse Name</th>
                <th>Facility Code</th>
               
            </tr>
        </td>
                
        <tbody>

            
              
 <?php
 
    $array = $database->Selectepi();
 
    while ($row = $res->fetch_array()) {

  ?>
            
    <tr class="selectable">
                
                <td class="important" ><?php echo $row['location_name']; ?></td>
                
                <td name="pk_id"><?php echo $row['pk_id']; ?></td>
                
                <td class="important" ><?php echo $row['warehouse_name']; ?></td>
                
                <td><input type="text"   id="epi_code" name="epi_code[]" value="<?php  echo (!empty($array[$row['pk_id']]) ? $array[$row['pk_id']] : '') ?>"  placeholder="facility Code" /></td> 
                
                <input type="hidden" name="vlmis_code[]" value="<?php echo $row['pk_id']; ?>" />
               
    </tr>     
 
<?php
    
     }
?>
    <tr><td>    <input type="submit" name="btn" value="Save" /></td></tr>
            
                     
        </tbody>
        <input type="hidden" name="map" value="epi"   />
  
    </table>
            </form>            
             
           </td>
        <?php
            
        } else
            
        {
          echo "<h4><b>No Records Found!</b></h4>";
        }
        
        ?>
            
         
<?php

  $res = $database->readepidhis();

     if ($res) 
         {
         
            ?>
        
          
            <td>
                
             
      
    <table class="table table-condensed table-bordered table-hover">
         <thead class="bdr text-center bg-primary ">

         <td  colspan="3">
                        <b>
                            DHIS
                        </b>
                    </td>




                    </thead>
        <td>
            <tr>
                      
               
                <th>UC</th>
                <th>facility Code</th>
                <th>facility</th>
                    
            </tr>
        </td>
                
        <tbody>
                    
 <?php
 
    while ($row = $res->fetch_array()) {

  ?>
            
    <tr class="selectable">
                          
                <td class="important" ><?php echo $row['un_name']; ?></td>
                <td name="faname"><?php echo $row['facode']; ?></td>
                <td class="important" ><?php echo $row['fac_name']; ?></td>
    </tr>
    
<?php
    
     }
?>
                   
        </tbody>
    </table>
                </td>
         </table>  
        <?php
            
        } else
            
        {
          echo "<h4><b>No Records Found!</b></h4>";
        }
        
        ?>
        
