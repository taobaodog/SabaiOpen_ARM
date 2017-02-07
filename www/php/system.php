<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology
 header("Content-type: text/plain");
 
if(isset($_REQUEST['act']) && $_REQUEST['act']!="")
{
$filter = array("<", ">","="," (",")",";","/","|");
$_REQUEST['act']=str_replace ($filter, "#", $_REQUEST['act']);
$act=$_REQUEST['act'];


$toShell= exec("sudo bin/system.sh $act",$out);

echo $toShell;


}
?>