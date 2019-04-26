function frmvalidate(){
	
	if(document.getElementById('prod_sel').value==''){
		alert('Please Select Product');
		document.getElementById('prod_sel').focus();
		return false;
	}
	
	if(document.getElementById('month_sel').value==''){
		alert('Please Select Month');
		document.getElementById('month_sel').focus();
		return false;
	}
	
	if(document.getElementById('year_sel').value==''){
		alert('Please Select Year');
		document.getElementById('year_sel').focus();
		return false;
	}
	
}

var mygrid;
function doInitGrid(){
	mygrid = new dhtmlXGridObject('mygrid_container_provincial');
	mygrid.selMultiRows = true;
	mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
	mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>"+$('#hdn_stock_report_province').val()+"</div>,#cspan,#cspan,#cspan,#cspan");
	mygrid.attachHeader("<span title='Provincial Office'>Provincial Warehouse</span>,<span title='Stakeholder Name'>Stakeholder</span>,<span title='Average Monthly Consumption'>AMC</span>,<span title='Total'>Total</span>,<span title='Month of Scale'>MOS</span>");
	mygrid.setInitWidths("*,250,100,100,100");
	mygrid.setColAlign("left,left,right,right,right");
	mygrid.setColSorting("str,str");
	mygrid.setColTypes("ro,ro,ro,ro,ro");
	//mygrid.enableLightMouseNavigation(true);
	mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
	mygrid.setSkin("light");
	mygrid.init();
	mygrid.loadXML(appName+"/xml/stock_availability_report_provincial.xml");
	
	mygrid1 = new dhtmlXGridObject('mygrid_container_district');
	mygrid1.selMultiRows = true;
	mygrid1.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
	mygrid1.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>"+$('#hdn_stock_report_district').val()+"</div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
	mygrid1.attachHeader("<span title='District Name'>Districts</span>,<span title='Province/Region Name'>Province/Region</span>,<span title='Stakeholder Name'>Stakeholder</span>,<span title='Average Monthly Consumption'>AMC</span>,<span title='Store'>Store</span>,<span title='Month of Scale'>MOS</span>,<span title='Field'>Field</span>,<span title='Month of Scale'>MOS</span>,<span title='Total'>Total</span>,<span title='Month of Scale'>MOS</span>");
	mygrid1.setInitWidths("*,150,100,80,80,80,80,80,80,80");
	mygrid1.setColAlign("left,left,left,right,right,right,right,right,right,right");
	mygrid1.setColSorting("str,str,str");
	mygrid1.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
	//mygrid1.enableLightMouseNavigation(true);
	mygrid1.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
	mygrid1.setSkin("light");
	mygrid1.init();
	mygrid1.loadXML(appName+"/xml/stock_availability_report_district.xml");
	
	mygrid2 = new dhtmlXGridObject('mygrid_container_national');
	mygrid2.selMultiRows = true;
	mygrid2.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
	mygrid2.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>"+$('#hdn_stock_report_national').val()+"</div>,#cspan,#cspan,#cspan,#cspan");
	mygrid2.attachHeader("<span title='Central Warehouse Name'>Central Warehouse</span>,<span title='Stakeholder Name'>Stakeholder</span>,<span title='Average Monthly Consumption'>AMC</span>,<span title='Total'>Total</span>,<span title='Month of Scale'>MOS</span>");
	mygrid2.setInitWidths("*,250,100,100,100");
	mygrid2.setColAlign("left,left,right,right,right");
	mygrid2.setColSorting("str,str");
	mygrid2.setColTypes("ro,ro,ro,ro,ro");
	//mygrid2.enableLightMouseNavigation(true);
	mygrid2.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
	mygrid2.setSkin("light");
	mygrid2.init();
	mygrid2.loadXML(appName+"/xml/stock_availability_report_national.xml");
}
