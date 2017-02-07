<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology, LLC
	$UCI_PATH = "";

	# allow HTTPS comunication
	$arrContextOptions=array(
		"ssl"=>array(
		"cafile" => "/etc/php5/ca-bundle.crt",
		"verify_peer"=>true,
		"verify_peer_name"=>true,
		),
	);
	
	# get the location update url
	$URIfile=exec("uci get sabai.general.version_uri");
	# if it doesn't exist, create it
	$URI=file_exists($URIfile)?file_get_contents($URIfile):'https://raw.githubusercontent.com/sabaitechnology/SabaiOpen/master/version';
	$get_data=file_get_contents($URI, false, stream_context_create($arrContextOptions));
	
	$data=str_replace("Soft:", "", $get_data);
	$obj=json_decode($data);
	$version=$obj->version;
	$link=$obj->link;

	$version_list=file_get_contents("/etc/sabaiopen_version_old");
	if( strpos($version_list, $version) == false ) {
		exec("uci $UCI_PATH set sabai.general.new_version=\"" . $version . "\"");
		exec("uci $UCI_PATH set sabai.general.download_uri=\"" . $link . "\"");
		exec("uci $UCI_PATH commit sabai");
		exec("cp -r /etc/config/sabai /configs/");
		echo $version;
	} else {
		echo "false";
	}
	
?>
