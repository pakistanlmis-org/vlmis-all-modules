<html>
    <head>
        
        <head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css.css">
    <link type="text/css" rel="stylesheet" href="style.css"/>    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open Sans">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script> 
    <script src="jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <meta charset="UTF-8">
        <title></title>
        
        <style>
a.btn:hover {
     -webkit-transform: scale(1.1);
     -moz-transform: scale(1.1);
     -o-transform: scale(1.1);
 }
 a.btn {
     -webkit-transform: scale(0.8);
     -moz-transform: scale(0.8);
     -o-transform: scale(0.8);
     -webkit-transition-duration: 0.5s;
     -moz-transition-duration: 0.5s;
     -o-transition-duration: 0.5s;
 }

table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}

</style>
        
    </head>
    
    
         
    <body>  
        
<?php

  require_once 'classes/database.php';
 
  ?>
        <table>
   
    <form method="POST" action="insert.php">    
           
    <td>   
    <div class="custom-select" >
    <label for="map"> Map By: </label>
    <select name="map" id="map" style="width:150px;"  >
    <option value="">select</option>  
    <option value="district"> District</option>
    <option value="tehsil">Tehsil</option>
    <option value="uc">UC</option>
    <option value="epi">EPI</option>
    </select>
    </div>
    </td>
    </form> 
            
    <td>
    <?php
    
     $list = new Database();
     $res = $list->districtdropdown();
     
     $rowCount = $res->num_rows;
  
     ?>
    
    <div class="custom-select" >
    <label style="display: none;" id="district2" for="district"> District: </label>
    <select name="district" id="district"  style=" width: 150px; display: none;">
    <option value="" >Select district </option>
    
         <?php
     
        if($rowCount > 0)
        {
            
        while ($row = $res->fetch_array())
           { 

        echo '<option value="'.$row['pk_id'].'">'.$row['location_name'].'</option>';
           }
        }
        else
        {
            echo '<option value="">District not available</option>';
        }
        ?>
    
    </select>
    </div>
    </td>
            
    <td>
    <div class="custom-select" >
    <label style="display: none;" id="tehsil2" for="tehsil"> Tehsil: </label>
    <select name="tehsil" id="tehsil"  style=" width: 150px; display: none;">
    <option value="" >Select district first</option>
    </select>
    </div>
    </td>  
    
</table>
  
<div id="map_district_list">
    
    
</div>


    
    </body>
        
</html>


<script type="text/javascript">
     
  $(document).ready(function(){
    
    $('#map').on('change',function(){
           var countryID = $(this).val();
           var val = $(this).val();
           console.log('V:'+val);
        if(countryID){
            $.ajax({
                type:'POST',
                url:'file.php',
                data:'pk_id='+countryID,
                success:function(html){
                    
                    $('#tehsil').html('<option value="">Select district first</option>');
                    
                     if(val == 'district')
                    {
                        
                    $('#map_district_list').html(html);
                   
                    $('#district').hide();
                    $('#district2').hide();
                    
                    $('#tehsil').hide();
                    $('#tehsil2').hide();
                    
                    $('#uc').hide();
                    $('#uc2').hide();
                   
                    }
                    else
                    {
                    if(val == 'epi')
                    {
                    $('#district').show();
                    $('#district2').show();
                    
                    $('#tehsil').show();
                    $('#tehsil2').show();
                    
                    $('#uc').show();
                    $('#uc2').show();
                    }else{
                        if(val == 'uc')
                    { 
                    $('#district').show();
                    $('#district2').show();
                    
                    $('#tehsil').show();
                    $('#tehsil2').show();
                    
                     $('#uc').hide();
                    $('#uc2').hide();
                    
                }
                    }
                        if(val == 'tehsil')
                    {
                    
                    $('#district').show();
                    $('#district2').show();
                    
                    $('#tehsil').hide();
                    $('#tehsil2').hide();
                    
                    $('#uc').hide();
                    $('#uc2').hide();
                    }
                    }
                    }
                
            }); 
        }
   
     });


$('#district').on('change',function(){
        var stateID = $(this).val();
        if(stateID){
            $.ajax({
                type:'POST',
                url:'tehsil.php',
                data:'pk_id='+stateID,
               success:function(html)
               {
                    $('#map_district_list').html(html);
                }
            }); 
                 $.ajax({
                type:'POST',
                url:'dropdown.php',
                data:'pk_id='+stateID,
               success:function(html){
                    $('#tehsil').html(html);                    
                }
            });
        }
   
    });
    
    
    $('#tehsil').on('change',function(){
        var map = $("#map").val();
        var anyID = $(this).val();
        
        if(map == 'epi'){
            $.ajax({
                type:'POST',
                url:'epi.php',
                data:'pk_id='+anyID,
               success:function(html){
                        
                    $('#map_district_list').html(html);
                }
            }); 
        } else {
        
        if(anyID){
            $.ajax({
                type:'POST',
                url:'unc.php',
                data:'pk_id='+anyID,
               success:function(html)
               {
                   
                    $('#map_district_list').html(html);
                }
            }); 
            
               $.ajax({
                type:'POST',
                url:'dropdown.php',
                data:'pk_id='+anyID,
               success:function(html){
                    $('#uc').html(html);     
                }
            });
        }
        }
        
    }); 
    
});


</script>