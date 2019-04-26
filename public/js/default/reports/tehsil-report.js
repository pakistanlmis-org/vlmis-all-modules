
var mygrid;
function doInitGrid() {

    mygrid = new dhtmlXGridObject('mygrid_container');

    mygrid.selMultiRows = true;
    mygrid.setImagePath("reports/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");

    mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Tehsil Report For District = " + $('#location_name').val() + " And Product = " + $('#item_name').val() + "(" + $('#sel_month').val() + " " + $('#sel_year').val() + ")</div>,#cspan,#cspan,#cspan,#cspan,#cspan");
   
    mygrid.attachHeader("<span title='Tehsil Name'>Tehsil</span>,<span title='Product Consumption'>Consumption (Doses)</span>,<span title='Average Monthly Consumption'>AMC (Doses)</span>,<span title='Product On Hand'>On Hand (Doses)</span>,<span title='Month of Scale'>MOS</span>,#cspan");
    mygrid.setInitWidths("*,160,160,160,40,40");
    mygrid.setColAlign("left,right,right,right,center,center");
    //mygrid.setColSorting("str,int");
    mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
    //mygrid.enableLightMouseNavigation(true);
    mygrid.enableRowsHover(true, 'onMouseOver');   // `onMouseOver` is the css class name.
    mygrid.setSkin("light");
    mygrid.init();
    mygrid.loadXML(appName + "/xml/tehsil_report.xml");
}

        
        
$(function() {
    $('#wh_type').change(function() {
        //alert("test");
        $('#loader').show();
        $.ajax({
            type: "POST",
            url: "provWH.php",
            data: {SkOfcLvl: $(this).val(), combo: '2'},
            dataType: 'html',
            success: function(data)
            {
                $('#loader').hide();
                $('#warehouse_id').html(data);
            }
        });
    });

    $('#prov_sel').change(function() {
        $('#loader').show();
        $.ajax({
            type: "POST",
            url: appName + "/reports/prov-2dist",
            data: {prov_sel: $(this).val(), combo: '2'},
            dataType: 'html',
            success: function(data)
            {

                $('#loader').hide();
                $('#dist_id').html(data);
                $('#teh_id').empty();
                $('#uc_id').empty();
            }
        });
    });

    $('#dist_id').change(function() {
        var dist_id = $(this).val();
        $('#loader').show();
        $.ajax({
            type: "POST",
            url: appName + "/reports/prov-2dist",
            data: {dist_sel: dist_id, combo: '4'},
            dataType: 'html',
            success: function(data)
            {
                /*$('#hidden_dist').val(dist_id);
                 $('#hidden_teh').val($('#teh_id').val());*/
                $('#loader').hide();
                $('#teh_id').html(data);
            }
        });
        $.ajax({
            type: "POST",
            url: appName + "/reports/prov-2dist",
            data: {dist_sel: dist_id, combo: '5'},
            dataType: 'html',
            success: function(data)
            {
                $('#loader').hide();
                $('#uc_id').html(data);
            }
        });
    });

    $('#teh_id').change(function() {
        var teh_id = $(this).val();
        $('#loader').show();
        $.ajax({
            type: "POST",
            url: appName + "/reports/prov-2dist",
            data: {teh_sel: teh_id, combo: '5'},
            dataType: 'html',
            success: function(data)
            {
                /*$('#hidden_dist').val(dist_id);
                 $('#hidden_teh').val($('#teh_id').val());*/
                $('#loader').hide();
                $('#uc_id').html(data);
            }
        });
    });

});

 function functionCall(month, year, prod, province, district, tehsil) {
     
     window.location = "uc-report?month_sel=" + month
                + "&year_sel=" + year
                + "&prov_sel=" + province
                + "&prod_sel=1&item_sel=" + prod
                + "&dist_id=" + district
                + "&teh_id=" + tehsil;
        
        }