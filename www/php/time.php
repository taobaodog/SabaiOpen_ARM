<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology
$UCI_PATH="";
$command="/www/bin/time.sh";

if (isset($_POST['sync'])) {
	$location=$_POST['sync'];
	exec("uci $UCI_PATH set sabai.time.location=\"" . $location . "\"");
	exec("uci $UCI_PATH commit sabai");
	exec("cp -r /etc/config/sabai /configs/");
	exec($command);
} else {
	// Bring over variables from the Time page
	$json_ntp_raw = file_get_contents("/www/libs/data/network.time.json");
	$json_ntp = json_decode($json_ntp_raw, true);
	foreach ($json_ntp["aaData"] as $key => $value) {
		$ntp .= $value["ntp_server"] . " ";
	}
	$location=$_POST['timezone'];
	// Set the Sabai config to reflect latest settings
	exec("uci $UCI_PATH set sabai.time.servers=\"" . $ntp . "\"");
	exec("uci $UCI_PATH set sabai.time.location=\"" . $location . "\"");
	exec("uci $UCI_PATH commit sabai");
	exec("cp -r /etc/config/sabai /configs/");
	exec($command);
}

// Send completion message back to UI
echo "res={ sabai: true, msg: 'Time settings applied' }";
?>
