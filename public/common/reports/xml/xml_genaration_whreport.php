<?php //ob_start();
/**
* writeXML : Converting mysql table into XML
*
* @author : S.M. Saidur Rahman ,
* Moderator, joomla_experts (http://tech.groups.yahoo.com/group/joomla_experts/)
Moderator, cakephpexperts (http://tech.groups.yahoo.com/group/cakephpexperts/)
* URL: http://ranawd.wordpress.com/
* @version : 1.0
* @date 2008-07-09
* Purpose : Write XML file and collect data from mysql table
*/

/*
@Steps:
#Create a table name "sampletable"
#Create a XML file name "sample.xml"
#Correct root path as define value
#Call this function and Enjoy!
*/

//Here is an example of mysql table

//Define XML file root path
//define('ROOT_PATH', "C:\\wamp\\www\\myweb\\paklmis_final\\plmis_src\\operations\\xml\\");


function get($var){			  
	$StakeHolderName = mysql_fetch_row(mysql_query("SELECT stkname FROM stakeholder WHERE stkid = '".$var."' "));
	return $StakeHolderName[0];
}

//XML write function
function writeXML($xmlfile)
{
$xmlfile_path= GRID_XML_PATH."/".$xmlfile;
/*$query_xmlw = "SELECT usrt.sysusrrec_id, usrt.sysusr_type, tbwr.wh_name, usrt.sysusr_name, usrt.stkid, usrt.homepage, usrt.staticmenu  FROM sysuser_tab usrt, tbl_warehouse tbwr 
where usrt.whrec_id = tbwr.wh_id";
*/
/*$query_xmlw = "SELECT usrt.sysusrrec_id, usrt.sysusr_type, tbwr.wh_name, usrt.sysusr_name, stktb.stkname, usrt.homepage, usrt.staticmenu  FROM sysuser_tab usrt, 		 tbl_warehouse tbwr, stakeholder stktb 
where usrt.whrec_id = tbwr.wh_id AND usrt.stkid = stktb.stkid ";*/

$where = "";
if(isset($_POST['report_year']) && $_POST['report_year'] != ""){
	$where .="tbl_wh_data.report_year='".$_POST['report_year']."' ";
	$_SESSION['filterParam']['year'] = $_POST['report_year'];
}if(isset($_POST['report_month']) && $_POST['report_month'] != ""){
	$where .=" AND tbl_wh_data.report_month='".$_POST['report_month']."' ";
	$_SESSION['filterParam']['month'] = $_POST['report_month'];
}
if(isset($_POST['district']) && $_POST['district'] != ""){
	$where .=" AND tbl_wh_data.wh_id='".$_POST['district']."'";
	$_SESSION['filterParam']['wh'] = $_POST['district'];
	$whid=$_POST['district'];
	
}
if(isset($_POST['province']) && $_POST['province'] != ""){
	$_SESSION['filterParam']['province'] = $_POST['province'];
$province=$_POST['province'];
}


if(isset($_POST['stk_sel']) && $_POST['stk_sel'] != ""){
	$sel_stk = $_POST['stk_sel'];
//$where .=" AND tbl_warehouse.stkid='".$sel_stk."'";
}

 $query_xmlw = "SELECT  tbl_wh_data.w_id,
					tbl_wh_data.report_month,
					tbl_wh_data.report_year,
					itminfo_tab.itm_name,
					tbl_warehouse.wh_name,
					tbl_wh_data.wh_obl_a,
					tbl_wh_data.wh_received,
					tbl_wh_data.wh_issue_up,
					tbl_wh_data.wh_adja,
					tbl_wh_data.wh_adjb,
					tbl_wh_data.wh_cbl_a
					FROM  tbl_wh_data LEFT JOIN itminfo_tab ON  tbl_wh_data.item_id=itminfo_tab.itmrec_id LEFT JOIN
					tbl_warehouse ON  tbl_wh_data.wh_id=tbl_warehouse.wh_id where $where order by frmindex";
					
//print $query_xmlw;
$result_xmlw = mysql_query($query_xmlw);
$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$xmlstore .="<rows>\n";
$counter = 0;
$numOfRows = mysql_num_rows($result_xmlw);
$_SESSION['numOfRows'] = $numOfRows;
if($numOfRows>0){
	while($row_xmlw = mysql_fetch_array($result_xmlw)) {
		if ($row_xmlw['report_month'] == '1'){
			$month = 'January';	
		}else if ($row_xmlw['report_month'] == '2'){
			$month = 'February';	
		}else if ($row_xmlw['report_month'] == '3'){
			$month = 'March';	
		}else if ($row_xmlw['report_month'] == '4'){
			$month = 'April';	
		}else if ($row_xmlw['report_month'] == '5'){
			$month = 'May';	
		}else if ($row_xmlw['report_month'] == '6'){
			$month = 'June';	
		}else if ($row_xmlw['report_month'] == '7'){
			$month = 'July';	
		}else if ($row_xmlw['report_month'] == '8'){
			$month = 'August';	
		}else if ($row_xmlw['report_month'] == '9'){
			$month = 'September';	
		}else if ($row_xmlw['report_month'] == '10'){
			$month = 'October';	
		}else if ($row_xmlw['report_month'] == '11'){
			$month = 'November';	
		}else if ($row_xmlw['report_month'] == '12'){
			$month = 'December';	
		}
		
		$temp = "\"$row_xmlw[w_id]\"";
		$xmlstore .="\t<row id=\"$counter\">\n";
		$xmlstore .="\t\t<cell>".$month."</cell>\n";
		$xmlstore .="\t\t<cell>".$row_xmlw['report_year']."</cell>\n";
		$xmlstore .="\t\t<cell>".$row_xmlw['itm_name']."</cell>\n";
		$xmlstore .="\t\t<cell>".$row_xmlw['wh_name']."</cell>\n";
		$xmlstore .="\t\t<cell>".number_format($row_xmlw['wh_obl_a'])."</cell>\n";
		$xmlstore .="\t\t<cell>".number_format($row_xmlw['wh_received'])."</cell>\n";
		$xmlstore .="\t\t<cell>".number_format($row_xmlw['wh_issue_up'])."</cell>\n";
		$xmlstore .="\t\t<cell>".number_format($row_xmlw['wh_adja'])."</cell>\n";
		$xmlstore .="\t\t<cell>".number_format($row_xmlw['wh_adjb'])."</cell>\n";
		$xmlstore .="\t\t<cell>".number_format($row_xmlw['wh_cbl_a'])."</cell>\n";
		
		$xmlstore .="\t</row>\n";
		$counter++;
	}
}
$xmlstore .="</rows>\n";

$handle = fopen($xmlfile_path, 'w');

fwrite($handle, $xmlstore);
}

//Put XML file name and mysql table name simultaniously
writeXML('whreport.xml');


?>