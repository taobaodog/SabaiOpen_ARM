<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology
$UCI_PATH="";
$filter = array("<", ">","="," (",")",";","/","|");

if (isset($_POST['check'])) {
	$act=$_POST['check'];
	$res=exec("sh /www/bin/pptp.sh $act");
//  DNS leaks fixed in pptp.sh
//	if( strpos($res,'connected.') == true ) {
//  		exec("sh /www/bin/pptp.sh dns");
//  	}
	echo $res;
} else if (isset($_POST['switch']))	{
	$act=$_POST['switch'];
	$res=exec("sh /www/bin/pptp.sh $act");
	echo $res;
} else {
	$_REQUEST['act']=str_replace ($filter, "#", $_REQUEST['act']);
	$act=$_REQUEST['act'];


$user=trim($_REQUEST['user']);
$pass=trim($_REQUEST['pass']);
$server=trim($_REQUEST['server']);
$serverip=trim(gethostbyname($server));
$mppe_mode=trim($_REQUEST['mppe']);

if ($user && $pass && $server) {
	// Set the Sabai config to reflect latest settings
	exec("uci set sabai.vpn.username=\"" . $user . "\"");
	exec("uci set sabai.vpn.password=\"" . $pass . "\"");
	exec("uci set sabai.vpn.server=\"" . $server . "\"");
	exec("uci set sabai.vpn.mppe_mode=\"" . $mppe_mode . "\"");
	if ($mppe_mode == 'stateless') {
		exec("uci set sabai.vpn.req_mppe_128=\"required,no40,no56\"");
	} else {
		exec("uci set sabai.vpn.req_mppe_128=\"\"");
	}
	exec("uci commit sabai");
	exec("cp -r /etc/config/sabai /configs/");

	//execute the action and give response to calling page
	switch ($act) {
		case "start":
			$res=exec("sh /www/bin/pptp.sh $act");
			echo $res;
			break;
		case "stop":
			$res=exec("sh /www/bin/pptp.sh $act");
			echo $res;
			break;
		case "save":
			echo "res={ sabai: true, msg: 'PPTP settings saved.' }";
		    break;
		case "clear":
			exec("sh /www/bin/pptp.sh $act");
			echo "res={ sabai: true, msg: 'PPTP settings cleared.' }";
		    break;
	}
} else {
	echo "res={ sabai: true, msg: 'Incorrect PPTP settings. Please check.' }";
}
}
?>
