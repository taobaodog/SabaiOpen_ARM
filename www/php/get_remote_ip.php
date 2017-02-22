<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology, LLC
// allow HTTPS comunication
$arrContextOptions=array(
	"ssl"=>array(
	"cafile" => "/etc/php5/ca-bundle.crt",
	"verify_peer"=>true,
	"verify_peer_name"=>true,
	),
);

// get the location update url
$URIfile=exec("uci get sabai.general.updateuri");
// if it doesn't exist, create it
$URI=file_exists($URIfile)?file_get_contents($URIfile):'http://ip-api.com/json';
//get current location
$get_ip=@file_get_contents($URI, false, stream_context_create($arrContextOptions));
if($get_ip === FALSE) {
	$ip=file_get_contents("/etc/sabai/stat/ip_device", true);
} else {
	$ip=str_replace(
		array("'", " ="),
		array("", ":"),
		$get_ip
	);
	// store it in a stat file
	file_put_contents("/etc/sabai/stat/ip",$ip);
}

// give it back to the calling program
echo $ip;
?>