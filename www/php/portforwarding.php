<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology 
  
//$json = json_decode($_POST['pftable'], true);
//$file = '/tmp/table1';  
//unset ($json[0]);
//$aaData=json_encode($json);
//file_put_contents($file, $aaData);
#create the table and write to sabai uci
//$command="/www/bin/pftable.sh";
//exec($command);
#implement the table
//
	$json = file_get_contents("/www/libs/data/port_forwarding.json");
	$json_decoded = json_decode($json,true);
	$json_jshn = json_encode($json_decoded["aaData"], JSON_FORCE_OBJECT);
	file_put_contents("/tmp/tablejs", $json_jshn);
	exec("/www/bin/pftable.sh");
	$res=exec("/www/bin/portforwarding.sh 2>&1",$out);
	// Send completion message back to UI
	echo $res;
?>  
