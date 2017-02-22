<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology
$UCI_PATH="";
// Bring over variables from the UPNP page
$enable=trim($_POST['enableToggle']);
$natpmp=trim($_POST['natpmpToggle']);
$clean=trim($_POST['cleanToggle']);
$secure=trim($_POST['secureToggle']);
$intmin=trim($_POST['intmin']);
$intmax=trim($_POST['intmax']);
$extmin=trim($_POST['extmin']);
$extmax=trim($_POST['extmax']);
$command="sh /www/bin/upnp.sh";

// Set the Sabai config to reflect latest settings
exec("uci $UCI_PATH set sabai.upnp.enable=\"" . $enable . "\"");
exec("uci $UCI_PATH set sabai.upnp.natpmp=\"" . $natpmp . "\"");
exec("uci $UCI_PATH set sabai.upnp.clean=\"" . $clean . "\"");
exec("uci $UCI_PATH set sabai.upnp.secure=\"" . $secure . "\"");
exec("uci $UCI_PATH set sabai.upnp.intmin=\"" . $intmin . "\"");
exec("uci $UCI_PATH set sabai.upnp.intmax=\"" . $intmax . "\"");
exec("uci $UCI_PATH set sabai.upnp.extmin=\"" . $extmin . "\"");
exec("uci $UCI_PATH set sabai.upnp.extmax=\"" . $extmax . "\"");
exec("uci $UCI_PATH commit sabai");
exec("cp -r /etc/config/sabai /configs/");
exec($command);

// Send completion message back to UI
echo "res={ sabai: true, msg: 'UPNP settings applied' }";

?>
