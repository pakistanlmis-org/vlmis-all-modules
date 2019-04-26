<!DOCTYPE HTML>
<script src="js/jquery.js"></script>
<script src="js/jquery.validate.js"></script>



<script>
    $( document ).ready(function() {
        
   $("#form2").validate({
    rules: {
        ccm_loc_id: {
            
             number : true   
        }
    }
    
});
});	
</script>
<?php
 $loc_id = $_GET['loc_id'];



?>
<style>
    table {
        border-right: 1px solid #C1DAD7;
        border-left: 1px solid #C1DAD7;
        border-bottom: 1px solid #C1DAD7;
        border-top: 1px solid #C1DAD7;        
    }
    th {
        font: bold 11px "Trebuchet MS", Verdana, Arial, Helvetica,
            sans-serif;
        color: #000;
        border-right: 1px solid #C1DAD7;
        border-bottom: 1px solid #C1DAD7;
        border-top: 1px solid #C1DAD7;
        letter-spacing: 2px;
        text-transform: uppercase;
        text-align: left;
        padding: 6px 6px 6px 12px;
        background: #CAE8EA no-repeat;
    }
    td {
        font: bold 11px "Trebuchet MS", Verdana, Arial, Helvetica,
            sans-serif;
        border-right: 1px solid #C1DAD7;
        border-bottom: 1px solid #C1DAD7;
        background: #fff;
        padding: 6px 6px 6px 12px;
        color: #000;
    }
</style>
<html> 
    <body>
        <h3 align="center" >Add CCM LOC ID</h3>
        <form action="action.php?action=add2" id="form2" method="post">
            <table>
                <tr>
                    <td>ccm loc ID</td>
                    <td>
                        <input type="text" name="ccm_loc_id" value="">
                    </td>
                </tr>
                <input type="hidden" value="<?=$loc_id?>" name="loc_id" >
               
                

                <tr >
                    <td align="right">
                        <input type="button" name="Update" value="Update"  onclick="addClose()"  >
                    </td>      </tr>
            </table>

        </form>

    </body>
</html>
<script type="text/javascript">
    function addClose () {
        document.getElementById("form2").submit();
     window.close();
       window.opener.location.reload();
        
    }
    
    </script>