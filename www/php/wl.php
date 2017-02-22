<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology
$UCI_PATH="";

function setVar($prefix, $option){
	// Bring over variables from the Wireless page
	$mode=trim($_POST[$prefix.'_mode']);
	$ssid=trim($_POST[$prefix.'_ssid']);
	$encryption=trim($_POST[$prefix.'_encryption']);
	$wpa_encryption=trim($_POST[$prefix.'_wpa_encryption']);
	$wpa_psk=trim($_POST[$prefix.'_wpa_psk']);
	if ($prefix == 'wl') {
		$wpa_rekey=trim($_POST[$prefix.'_wpa_rekey']);
		$wepkeys=implode(" ", $_POST[$prefix.'_wep_keys']);
	};
	$auto=trim($_POST['channel_mode']);
	$freq=trim($_POST['channel_freq']);
	$width=trim($_POST['channel_width']);
	$channel=trim($_POST[$prefix.'_channel']);
	if ($channel >= 1 && $channel <= 14) {
                // all is well
        } else {
                $channel=1;
        }

	//if ($freq == '2') {
	//$width=trim($_POST['channel_width_2']);
	//$channel=trim($_POST[$prefix.'_channel']);
	//} else {
	//	$width=trim($_POST['channel_width_5']);
	//	$channel=trim($_POST[$prefix.'_channel_5']);
	//}
	
	$command="sh /www/bin/wl.sh save $option";

	// Set the Sabai config to reflect latest settings
	exec("uci $UCI_PATH set sabai.wlradio$option.mode=\"" . $mode . "\"");
	exec("uci $UCI_PATH set sabai.wlradio$option.ssid=\"" . $ssid . "\"");
	exec("uci $UCI_PATH set sabai.wlradio$option.encryption=\"" . $encryption . "\"");
	exec("uci $UCI_PATH set sabai.wlradio$option.wpa_encryption=\"" . $wpa_encryption . "\"");
	exec("uci $UCI_PATH set sabai.wlradio$option.wpa_psk=\"" . $wpa_psk . "\"");
	if ($option == '0') {
		exec("uci $UCI_PATH set sabai.wlradio$option.auto=\"" . $auto . "\"");
		exec("uci $UCI_PATH set sabai.wlradio$option.channel_freq=\"" . $channel . "\"");
		exec("uci $UCI_PATH set sabai.wlradio$option.wpa_group_rekey=\"" . $wpa_rekey . "\"");
		exec("uci $UCI_PATH set sabai.wlradio$option.wepkeys=\"" . $wepkeys . "\"");
		exec("uci $UCI_PATH set sabai.wlradio$option.freq=\"" . $freq . "\"");
		exec("uci $UCI_PATH set sabai.wlradio$option.width=\"" . $width . "\"");
	};
	exec("uci $UCI_PATH commit sabai");
	exec("cp -r /etc/config/sabai /configs/");
	exec($command);

// Send completion message back to UI
	echo "res={ sabai: true, msg: 'Wireless settings applied' }";
}

//Check what wl device must be configured
if (isset($_POST['form_wl0'])) {
	setVar("wl","0");
} else {
	setVar("wl1","1");
}

?>
