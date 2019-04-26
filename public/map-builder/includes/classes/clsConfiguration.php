<?php
/**
 * clsConfiguration
 * @package includes/class
 * 
 * @author     Ajmal Hussain
 * @email <ahussain@ghsc-psm.org>
 * 
 * @version    2.2
 * 
 */
 //Class clsConfiguration
class clsConfiguration
{
        /**
        * 
        * Check file
        * 
        */
        
	function Checkfile($strFileName)
	{
		if(file_exists($strFileName))
		{ return true; }
		else 
		{ return false; }
	}
        /**
        * 
        * Get DB 
        * 
        */
	function GetDB($strHost, $strDatabase, $strUser, $strPass)
	{
		$strLink=mysqli_connect($strHost, $strUser, $strPass);
		if(!$strLink)
			{ return "Connection could not be made"; }
		$strDB=mysqli_select_db($strLink,$strDatabase);
		if(!$strDB)
			{ return "Database not found."; }
		return true;
	}
        /**
        * 
        * Get DB Tables 
        * 
        */
	function GetDBTables()
	{
		$strSql="show tables";
		$rsSql=mysqli_query($strSql);
		return $rsSql;
	}
}
?>