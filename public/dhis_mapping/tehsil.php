

<?php
  
 
  require_once 'classes/database.php';
 
  $res = $database->read2();

     if ($res) 
         {
         
            ?>
        
            
            <table class="table table-condensed table-bordered table-hover">
                <td>
                    
                       
                    
                <form  method="POST" action="insert1.php">      
    <table class="table table-condensed table-bordered table-hover">
         <thead class="bdr text-center bg-primary ">

         <td  colspan="3">
                        <b>
                            VLMIS
                        </b>
                    </td>




                    </thead>
        <td>
            <tr>
                      
                <th>Pk Id</th>
                <th>Tehsil</th>
                <th>Tehsil Code</th>
               
            </tr>
        </td>
                
        <tbody>

            
                
 <?php
 
    $array = $database->Selecttehsil();
 
    while ($row = $res->fetch_array()) {

  ?>
            
    <tr class="selectable">
                                  
                <td name="pk_id"><?php echo $row['pk_id']; ?></td>
                
                <td class="important" ><?php echo $row['location_name']; ?></td>
                
                <td><input type="text"   id="tehsil_code" name="tehsil_code[]" value="<?php   echo (!empty($array[$row['pk_id']]) ? $array[$row['pk_id']] : '') ?>"  placeholder="tehsil Code" /></td> 
                
                <input type="hidden" name="vlmis_code[]" value="<?php echo $row['pk_id']; ?>" />
               
    </tr>     
 
<?php
    
     }
?>
    <tr><td>    <input type="submit" name="btn" value="Save" /></td></tr>
            
                      
        </tbody>
        
  
    </table>
                      <input type="hidden" name="map" value="tehsil"   />
          </form>             
             
           </td>
        <?php
            
        } else
            
        {
          echo "<h4><b>No Records Found!</b></h4>";
        }
        
        ?>
            
            <div style=" padding-left: 500px;">
<?php

  $res = $database->read3();

     if ($res) 
         {
         
            ?>
        
          
            <td>
                
                
      
    <table class="table table-condensed table-bordered table-hover">
         <thead class="bdr text-center bg-primary ">

         <td  colspan="2">
                        <b>
                            DHIS
                        </b>
                    </td>




                    </thead>
        <td>
            <tr>
                      
               
                <th>Tehsil Code</th>
                <th>Tehsil</th>
               
                    
            </tr>
        </td>
                
        <tbody>
                    
 <?php
 
    while ($row = $res->fetch_array()) {

  ?>
            
    <tr class="selectable">
                          
                <td name="cname"><?php echo $row['tcode']; ?></td>
                <td class="important" ><?php echo $row['tehsil']; ?></td>
             
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
        </div>