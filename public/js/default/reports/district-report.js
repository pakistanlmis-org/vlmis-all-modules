
var mygrid;
function doInitGrid() {

    mygrid = new dhtmlXGridObject('mygrid_container');

    mygrid.selMultiRows = true;
    mygrid.setImagePath("reports/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");

   mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>District Report For Province/Region = " + $('#location_name').val() + " And Product = " + $('#item_name').val() + "(" + $('#sel_month').val() + " " + $('#sel_year').val() + ")</div>,#cspan,#cspan,#cspan,#cspan,#cspan");

    mygrid.attachHeader("<span title='District Name'>Districts</span>,<span title='January'>Jan</span>,<span title='Febrary'>Feb</span>,<span title='March'>Mar</span>,<span title='April'>Apr</span>,<span title='May'>May</span>,<span title='June'>Jun</span>,<span title='July'>Jul</span>,<span title='August'>Aug</span>,<span title='September'>Sep</span>,<span title='October'>Oct</span>,<span title='November'>Nov</span>,<span title='December'>Dec</span>");
    mygrid.setInitWidths("*,160,160,160,40,40");
    mygrid.setColAlign("left,right,right,right,center,center");
    //mygrid.setColSorting("str,int");
    mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
    //mygrid.enableLightMouseNavigation(true);
    mygrid.enableRowsHover(true, 'onMouseOver');   // `onMouseOver` is the css class name.
    mygrid.setSkin("light");
    mygrid.init();
    mygrid.loadXML(appName + "/xml/district_report.xml");
}


    function popUp(str1) {
        str = "index.php?name=" + str1;
        day = new Date();
        id = day.getTime();
        eval("page" + id + " = window.open(str, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=375px,height=950px,left = 190,top = 162');");
    }

    function functionCall(month, year, prod, province, district) {
        window.location = "tehsil-report?month_sel=" + month + "&year_sel=" + year + "&prov_sel=" + province + "&prod_sel=1&item_sel=" + prod + "&dist_id=" + district;
    }
