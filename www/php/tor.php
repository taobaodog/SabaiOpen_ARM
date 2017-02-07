<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology
	$UCI_PATH="";
	$prefix="tor";

	// must be removed if WL is not needed
	if (isset($_POST['switch'])) {
		$mode=$_POST['switch'];
		$command="sh /www/bin/tor.sh $mode";
		// turn off TOR tunnel
		exec("/www/bin/tor.sh off");
		// turn on proxy 
		$res=exec($command);
	} else {
		$mode=trim($_POST[$prefix.'_mode']);
		$ssid=trim($_POST[$prefix.'_ssid']);
		$ip=trim($_POST[$prefix.'_nw_ip']);
		$mask=trim($_POST[$prefix.'_nw_mask']);
		$server=trim($_POST[$prefix.'_server']);
		$command="/www/bin/tor.sh $mode";

		exec("uci $UCI_PATH set sabai.wlradio0.ssid=\"" . $ssid . "\"");
		exec("uci $UCI_PATH set sabai.tor.ipaddr=\"" . $ip . "\"");
		exec("uci $UCI_PATH set sabai.tor.netmask=\"" . $mask . "\"");
		exec("uci $UCI_PATH set sabai.tor.network=\"" . $server . "\"");
		exec("uci commit sabai");
		exec("cp -r /etc/config/sabai /configs/");
		$res=exec($command);
	}
	echo $res;

?>
