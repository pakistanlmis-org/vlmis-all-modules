
var mygrid;
function doInitGrid() {

    mygrid = new dhtmlXGridObject('mygrid_container');

    mygrid.selMultiRows = true;
    mygrid.setImagePath("reports/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");

    mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Divisional Report For Province/Region = " + $('#location_name').val() + " And Product = " + $('#item_name').val() + "(" + $('#sel_month').val() + " " + $('#sel_year').val() + ")</div>,#cspan,#cspan,#cspan,#cspan,#cspan");


    mygrid.attachHeader("<span title='Division'>Division</span>,<span title='Product Consumption'>Consumption (Doses)</span>,<span title='Average Monthly Consumption'>AMC (Doses)</span>,<span title='Product On Hand'>On Hand (Doses)</span>,<span title='Month of Scale'>MOS</span>,#cspan");
    mygrid.setInitWidths("*,160,160,160,40,40");
    mygrid.setColAlign("left,right,right,right,center,center");
    //mygrid.setColSorting("str,int");
    mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
    //mygrid.enableLightMouseNavigation(true);
    mygrid.enableRowsHover(true, 'onMouseOver');   // `onMouseOver` is the css class name.
    mygrid.setSkin("light");
    mygrid.init();
    mygrid.loadXML(appName + "/xml/divisional_report.xml");
}

