<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology, LLC

// Check type of device
$dev=exec("uci get system.@system[0].hostname");

//set system variables
 exec("/sbin/ifconfig eth0 | egrep -o \"HWaddr [A-Fa-f0-9:]*|inet addr:[0-9:.]*|UP BROADCAST RUNNING MULTICAST\"",$out);
$sys = " \"sys\": {
  \"name\": \"". exec("uci get system.@system[0].hostname") ."\" ,
  \"model\": \"". exec("awk '/model name/' /proc/cpuinfo | awk -F: '{print $2}'") ."\",
  \"version\": \"". exec("cat /etc/sabaiopen_version") ."\",
  \"time\": \"". exec("date") ."\",
  \"uptime\": \"". exec("uptime | awk '{print $3,$4}' | sed 's/\,//g'") ."\",
  \"cpuload\": \"". exec("uptime | awk -F: '{print $5,$6,$7}'") ."\",
  \"mem\": \"". exec("cat /proc/meminfo |grep MemAvailable| awk '{print $2,$3}'") ."\",
  \"gateway\": \"". exec("ip route show | grep default | awk '{print $3}'") ."\"
},\n";

unset($out);

//set wan variables
 exec("/sbin/ifconfig eth0 | egrep -o \"HWaddr [A-Fa-f0-9:]*|inet addr:[0-9:.]*|UP BROADCAST RUNNING MULTICAST\"",$out);
$wan = " \"wan\": {
  \"mac\": \"". strtoupper(str_replace("HWaddr ","", ( array_key_exists(0,$out)? "$out[0]" : "-" ) )) ."\",
  \"connection\": \"". exec("uci get sabai.wan.proto") ."\",
  \"ip\": \"". str_replace("inet addr:","", ( array_key_exists(1,$out)? "$out[1]" : "-" ) ) ."\",
  \"subnet\": \"". exec("ifconfig eth0 | grep Mask | awk '{print $4}' |sed 's/Mask://g'") ."\",
  \"gateway\": \"". exec("route -n | grep eth0 | grep UGH | awk '{print $2}'") ."\"
},\n";

unset($out);
if ($dev != 'vpna')	{
//set lan variables
 exec("/sbin/ifconfig br-lan | egrep -o \"HWaddr [A-Fa-f0-9:]*|inet addr:[0-9:.]*|UP BROADCAST RUNNING MULTICAST\"",$out);
$lan = " \"lan\": {
  \"mac\": \"". strtoupper(str_replace("HWaddr ","", ( array_key_exists(0,$out)? "$out[0]" : "-" ) )) ."\",
  \"ip\": \"". str_replace("inet addr:","", ( array_key_exists(1,$out)? "$out[1]" : "-" ) ) ."\",
  \"subnet\": \"". exec("ifconfig br-lan | grep Mask | awk '{print $4}' |sed 's/Mask://g'") ."\",
  \"dhcp\": \"". exec("if [ $(uci -p /var/state get sabai.dhcp.lan) = 'yes' ]; then echo 'server'; else echo 'off'; fi") ."\" 
},\n";

unset($out);

//set wl0 (wireless) variables
 exec("/sbin/ifconfig wlan0 | egrep -o \"HWaddr [A-Fa-f0-9:]*|inet addr:[0-9:.]*|UP BROADCAST RUNNING MULTICAST\"",$out);
$wl0 = " \"wl0\": {
  \"mac\": \"". strtoupper(str_replace("HWaddr ","", ( array_key_exists(0,$out)? "$out[0]" : "-" ) )) ."\",
  \"mode\": \"". exec("iw wlan0 info | grep type | awk '{print $2}'") ."\",
  \"ssid\": \"". exec("uci get sabai.wlradio0.ssid") ."\",
  \"security\": \"". exec("uci get sabai.wlradio0.encryption") ."\",
  \"channel\": \"". exec("iw wlan0 info | grep channel | awk '{print $2}'") ."\",
  \"width\": \"". exec("iw wlan0 info | grep channel | awk '{print $6,$7}' | sed 's/,//g'") ."\"
},\n";
unset($out);


$wl1 = " \"wl1\": {
  \"mode\": \"". exec("iw wlan1 info | grep type | awk '{print $2}'") ."\",
  \"ssid\": \"". exec("uci get sabai.wlradio1.ssid") ."\",
  \"security\": \"". exec("uci get sabai.wlradio1.encryption") ."\",
  \"channel\": \"". exec("iw wlan1 info | grep channel | awk '{print $2}'") ."\",
  \"width\": \"". exec("iw wlan1 info | grep channel | awk '{print $6,$7}' | sed 's/,//g'") ."\"
},\n";
unset($out);

} else {
	$lan = " \"lan\": {
		\"mac\": \"none\",
		\"ip\": \"none\",
		\"subnet\": \"none\",
		\"dhcp\": \"none \"
	},\n";
	unset($out);
	
	$wl0 = " \"wl0\": {
		\"mac\": \"none\",
		\"mode\": \"none\",
 		\"ssid\": \"none\",
		\"security\": \"none\",
 		\"channel\": \"none\",
 		\"width\": \"none\"
	},\n";
	
	unset($out);
}
//set vpn variables
$vpn = " \"vpn\": {
  \"proto\": \"". exec("uci get sabai.vpn.proto") ."\",
  \"status\": \"". exec("uci get sabai.vpn.status") ."\"
},\n";


//set proxy variables
$proxy = " \"proxy\": {
  \"status\": \"". exec("uci get sabai.proxy.status") ."\",
  \"port\": \"". exec("cat /etc/privoxy/config | grep listen-address | awk -F: '{print $2}'") ."\" 
}\n";

$fullinfo = "{\n"
.$sys
.$wan
.$lan
.$wl0
.$wl1
.$vpn
.$proxy
."\n}";

echo $fullinfo;

?>
