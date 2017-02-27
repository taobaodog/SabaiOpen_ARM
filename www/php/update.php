<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology
$act=$_GET['act'];

function upgrade() {
	$file_router = '/tmp/upgrade/sabai-bundle-secured.tar';
	$file_acc = '/tmp/upgrade/sabai-acc-bundle-secured.tar';
	if (file_exists($file_router) || file_exists($file_acc)) {
		$res = exec("/www/bin/upgrade.sh exec");
		if (!strpos($res,"-")) {
			echo "OK";
		} else {
			echo strtok($res,"-");
		}
	} else {
		echo "false";
	}
}

function check() {
	$res = exec("/www/bin/upgrade.sh check");
	//Clear flag
	exec("/www/bin/upgrade.sh");
	echo "$res";
}

switch ($act) {
	case 'upgrade':
		upgrade();
		break;
	case 'check':
		check();
		break;
	default:
		echo "ERROR";
		break;
	}
}
?>

