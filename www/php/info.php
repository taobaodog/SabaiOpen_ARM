<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology, LLC
error_reporting(0);
session_start();
if (!isset($_SESSION['count_vpn'])) {
		$_SESSION['count_vpn'] = 1;
}

$UCI_PATH="";

exec("/sbin/ifconfig eth0 | egrep -o \"HWaddr [A-Fa-f0-9:]*|inet addr:[0-9:.]*|UP BROADCAST RUNNING MULTICAST\"",$out);
$wan = " wan: {
  mac: '". strtoupper(str_replace("HWaddr ","", ( array_key_exists(0,$out)? "$out[0]" : "-" ) )) ."',
  ip: '". str_replace("inet addr:","", ( array_key_exists(1,$out)? "$out[1]" : "-" ) ) ."',
  status: '". ( array_key_exists(2,$out)? "Connected" : "-" ) ."' 
},\n";

unset($out);

$proxy = " proxy: {
  \"status\": \"". exec("uci get sabai.proxy.status") ."\"
}";

unset($out);

$tor_pr_switch=exec("uci get sabai.tor.mode");
switch($tor_pr_switch){
	case 'off':
		$tor_proxy = ",\n \"tor_proxy\": {
				\"status\": \"-\",
				\"port\": \"-\"
				},\n";
		break;
	case 'tun':
	case 'proxy':
		$tor_proxy = ",\n tor_proxy: {
				\"status\": \"Enabled\",
				\"port\": \"8080\"
			},\n";
		break;
}

$vpn_switch=exec("uci get sabai.vpn.proto");
switch($vpn_switch){
 case 'none': $vpn_type='-'; break;
 case 'pptp': 
 	$vpn_type='PPTP'; 
 	$vpn_ip=exec("ifconfig pptp-vpn | grep inet\ addr | awk '{print $2}' | sed 's/addr://g' ");
 	if($vpn_ip) {
 		exec("uci $UCI_PATH set sabai.vpn.ip=$vpn_ip");
 		exec("uci $UCI_PATH set sabai.vpn.status=Connected");
 		exec("uci $UCI_PATH commit sabai");
 		exec("cp -r /etc/config/sabai /configs/");
 	} else {
 		exec("uci $UCI_PATH set sabai.vpn.status=Disconnected");
 		exec("uci $UCI_PATH commit sabai");
 		exec("cp -r /etc/config/sabai /configs/");
 		$_SESSION['count_vpn']++;
 	}
 	break;
 case 'l2tp': $vpn_type='L2TP'; break;
 case 'ovpn': 
 	$vpn_type='OpenVPN'; 
 	$vpn_ip=exec("ifconfig tun0 | grep inet | awk '{print $2}' | sed 's/addr://g' ");
 	if($vpn_ip) {
 		exec("uci $UCI_PATH set sabai.vpn.ip=$vpn_ip");
 		exec("uci $UCI_PATH set sabai.vpn.status=Connected");
 		exec("uci $UCI_PATH commit sabai");
 		exec("cp -r /etc/config/sabai /configs/");
 	} else {
 		exec("uci $UCI_PATH set sabai.vpn.status=Disconnected");
 		exec("uci $UCI_PATH commit sabai");
 		exec("cp -r /etc/config/sabai /configs/");
 		$_SESSION['count_vpn']++;
 	}
 	break;
 	case 'tor':
 		$tor_stat=exec("/www/bin/tor.sh stat");
 		if($tor_stat) {
 			$tor_mode=exec("uci get sabai.tor.mode");
 			if($tor_mode=='tun') {
 				$vpn_type='TOR';
 			} else {
 				$vpn_type='-';
 			}
 		} else {
 			$vpn_type='-';
 		}  	
 		break;
}
$vpn_status=exec("uci get sabai.vpn.status");
$vpn = ",\n vpn: {\n type: '". (($vpn_type=='-')?'VPN': $vpn_type) ."',
  status: '". (($vpn_type=='-')?'-': $vpn_status) ."',
  ip: '". (($vpn_ip=='')?'-': $vpn_ip) ."' \n }";

if ($_SESSION['count_vpn'] == 10) {
	if ($vpn_type == 'PPTP') {
		exec("/www/bin/pptp.sh stop");
		$_SESSION['count_vpn'] = 0;
	} elseif ($vpn_type='OpenVPN') {
		exec("/www/bin/ovpn.sh stop");
		$_SESSION['count_vpn'] = 0;
	} else {
		$_SESSION['count_vpn'] = 0;
	}

}




echo "info = {\n"
.$wan
.$proxy
.$vpn
.$tor_proxy
."\n}";

?>