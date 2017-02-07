<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology

$file_router = '/tmp/upgrade/sabai-bundle-secured.tar';
$file_acc = '/tmp/upgrade/sabai-acc-bundle-secured.tar';
if (file_exists($file_router) || file_exists($file_acc)) {
	$res = exec("sh /www/bin/upgrade.sh");
	if (!strpos($res,"-")) {
		echo "OK";
	} else {
		echo strtok($res,"-");
	}
} else {
	echo "false";
}  

?>

