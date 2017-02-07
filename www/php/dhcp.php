<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology, LLC
$UCI_PATH = "";
//receive action requested from GUI
$act = $_POST['act'];
//$json_new = array();

if ($act == "save") {
	//receive datatables information from GUI
	$json = file_get_contents("/www/libs/data/dhcp.json");
	$json_decoded = json_decode($json,true);
	$json_jshn = json_encode($json_decoded["aaData"], JSON_FORCE_OBJECT);
	$default = json_encode($json_decoded["defSetting"]); 
	file_put_contents("/tmp/defSetting", $default);
	file_put_contents("/tmp/tablejs", $json_jshn);
	exec("/www/bin/dhcp.sh json");
	$res=exec("sh /www/bin/dhcp.sh save 2>&1", $out);
} elseif ($act == "get") {
	//sabai.dhcp.table is constructed and assigned
	$res = exec("/www/bin/dhcp.sh get");
}
	// Send completion message back to UI
	echo "$res";
?>
