<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology
	$filter = array("<", ">","="," (",")",";","/","|");
	$_REQUEST['ns_domain']=str_replace ($filter, "#", $_REQUEST['ns_domain']);
	$lookupAddress=$_REQUEST['ns_domain'];
	echo $lookupAddress;
	$ip = gethostbynamel($lookupAddress);

	exec("nslookup $lookupAddress", $ip);
	$ip=str_replace($lookupAddress, "", $ip);
	$addrs = count($ip);

	for ($i = 0 ; $i < $addrs ; $i++)
        echo($ip[$i] . "\n");
?>
