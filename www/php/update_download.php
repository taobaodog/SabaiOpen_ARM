<?php
	$dir_upgrade="/tmp/upgrade/sabai-bundle-secured.tar";
	$link_upgrade=exec("uci get sabai.general.download_uri");
	
	# allow HTTPS comunication
	$arrContextOptions=array(
		"ssl"=>array(
		"cafile" => "/etc/php5/ca-bundle.crt",
		"verify_peer"=>true,
		"verify_peer_name"=>true,
		),
	);
	
	if (file_exists($dir_upgrade)) { 
		exec("rm -r /tmp/upgrade/*");
	} else {
		exec("mkdir /tmp/upgrade/");
	}

	$content = file_get_contents($link_upgrade, false, stream_context_create($arrContextOptions));
	file_put_contents($dir_upgrade, $content);
?>
