<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology, LLC
$filter = array("<", ">","="," (",")",";","/","|");
$_REQUEST['primaryDNS']=str_replace ($filter, "#", $_REQUEST['primaryDNS']);
$_REQUEST['secDNS']=str_replace ($filter, "#", $_REQUEST['secDNS']);
$prim=$_REQUEST['primaryDNS'];
$sec=$_REQUEST['secDNS'];


$toShell= exec("sh dns.sh $prim $sec",$out);

echo $toShell;
?>
