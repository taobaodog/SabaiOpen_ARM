<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology, LLC

header('Content-Type: application/javascript');
$filter = array("<", ">","="," (",")",";","/","|");
$_REQUEST['act']=str_replace ($filter, "#", $_REQUEST['act']);
$act=$_REQUEST['act'];

switch ($act) {
	case "proxystart":
        exec("sh /www/bin/proxy.sh $act");
		echo "res={ sabai: true, msg: 'Proxy starting' }";
			break;
	case "proxystop":
        exec("sh /www/bin/proxy.sh $act");
		echo "res={ sabai: true, msg: 'Proxy stopped' }";
		    break;
}

?>
