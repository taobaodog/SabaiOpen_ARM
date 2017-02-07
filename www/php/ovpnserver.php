<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology

if (isset($_REQUEST['action'])) {
	$act= $_REQUEST['action'];
	switch ($act) {
		case 'setup':
			if (isset($_REQUEST['parm1'])) {
				$keyLen= $_REQUEST['parm1'];
				if (is_numeric($keyLen)) echo exec("sudo sh /www/bin/ovpnserver.sh setup $keyLen");
				else echo "res={ sabai: false, msg: 'invalid key length ($keyLen)' }";
			} else {
				echo "res={ sabai: false, msg: 'key length missing' }";
			}
			break;
		case 'client':
		case 'client_off':
			if (isset($_POST['parm1'])) {
				$clientname= $_POST['parm1'];
				if (preg_match('/^[A-Za-z0-9]+$/', $clientname)) echo exec("sudo sh /www/bin/ovpnserver.sh $act $clientname");
				else echo "res={ sabai: false, msg: 'invalid clientname ($clientname)' }";
			} else {
				echo "res={ sabai: false, msg: 'clientname missing' }";
			}
			break;
		case 'clear':
		case 'stop':
		case 'start':
		case 'check':
			echo exec("sudo sh /www/bin/ovpnserver.sh $act");
			break;
		default:
			echo "res={ sabai: false, msg: 'invalid action ($act)' }";
	}
} else {
	echo "res={ sabai: false, msg: 'action missing' }";
}
?>
