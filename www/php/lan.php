<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology

$UCI_PATH="";

// Bring over variables from the LAN page
$act=trim($_POST['act']);
$ip=trim($_POST['lan_ip']);
$mask=trim($_POST['lan_mask']);
$lease=trim($_POST['dhcp_lease']);
$start=trim($_POST['dhcp_start']);
$limit=trim($_POST['dhcp_limit']);
$command="sh /www/bin/lan.sh " . $act;

// Set the Sabai config to reflect latest settings
exec("uci $UCI_PATH set sabai.lan.ipaddr=\"" . $ip . "\"");
exec("uci $UCI_PATH set sabai.lan.netmask=\"" . $mask . "\"");
exec("uci $UCI_PATH set sabai.dhcp.leasetime=\"" . $lease . "\"");
exec("uci $UCI_PATH set sabai.dhcp.start=\"" . $start . "\"");
exec("uci $UCI_PATH set sabai.dhcp.limit=\"" . $limit . "\"");
exec("uci $UCI_PATH  commit sabai");
exec("cp -r /etc/config/sabai /configs/");
$res=exec($command);
 
// Send completion message back to UI
echo $res;
?>
