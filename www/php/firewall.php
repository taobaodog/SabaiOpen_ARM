<?php 
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology, LLC
$UCI_PATH="";
$filter = array("<", ">","="," (",")",";","/","|");
    
$icmp=str_replace ($filter, "#", $_REQUEST['respondToggle']);
$multicast=str_replace ($filter, "#", $_REQUEST['multicastToggle']);
$cookies=str_replace ($filter, "#", $_REQUEST['synToggle']);
$wanroute=str_replace ($filter, "#", $_REQUEST['wanToggle']);


// Set the Sabai config to reflect latest settings
exec("uci $UCI_PATH set sabai.firewall.icmp=\"" . $icmp . "\"");
exec("uci $UCI_PATH set sabai.firewall.multicast=\"" . $multicast . "\"");
exec("uci $UCI_PATH set sabai.firewall.cookies=\"" . $cookies . "\"");
exec("uci $UCI_PATH set sabai.firewall.wanroute=\"" . $wanroute . "\"");
exec("uci $UCI_PATH commit sabai");
exec("cp -r /etc/config/sabai /configs/");

exec("sh /www/bin/firewall.sh");
echo "res={ sabai: true, msg: 'Firewall settings applied' }";

?>  
