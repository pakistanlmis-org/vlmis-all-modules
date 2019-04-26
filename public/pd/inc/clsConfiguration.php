<?php
require_once("Configuration.inc.php");

class clsConfiguration
{

	function Checkfile($strFileName)
	{
		if(file_exists($strFileName))
		return true;
		else 
		return false;
	}

	function GetDB($strHost, $strDatabase, $strUser, $strPass)
	{
		$strLink=mysql_connect($strHost, $strUser, $strPass);
		if(!$strLink)
			return "Connection could not be made";
		$strDB=mysql_select_db($strDatabase,$strLink);
		if(!$strDB)
			return "Database not found.";
		return true;
	}

	function GetDBTables()
	{
		$strSql="show tables";
		$rsSql=mysql_query($strSql);
		return $rsSql;
	}
}
?>