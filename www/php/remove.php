<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology
function remove($arg) {                                              
        if (file_exists("/configs/ovpn_backup/ovpn.filename_$arg")) {
                exec("rm /configs/ovpn_backup/ovpn.filename_$arg");  
                exec("rm /configs/ovpn_backup/ovpn.msg_$arg");       
                exec("rm /configs/ovpn_backup/ovpn.config_$arg");    
        }                                                            
}

if(isset($_POST['removeName'])) {
	$file_name = $_POST['removeName'];
	$check = strpos($file_name, "backup_");
	if ($check === false) {
		remove($file_name);
	} else {
		$name = str_replace("backup_", "", $file_name);
		remove($name);
	}
	exec("rm /configs/$file_name");
       	echo "OK";
} else {
	echo "false";
}
?>
