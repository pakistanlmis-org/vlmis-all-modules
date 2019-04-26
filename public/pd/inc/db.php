<?php
require_once("clsConfiguration.php");
$objConfiguration=new clsConfiguration();

$nStat=$objConfiguration->GetDB($strHost, $strDatabase, $strUserName, $strPassword);
?>