<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology, LLC
if(isset($_POST['newName'])) {
	$file_name = str_replace(" " , "_" , $_POST['newName']);
	if (trim($file_name) == null) {
		echo "false";
	} else {
		exec("cp /configs/sabai /configs/backup_$file_name");
		if ( ! file_exists("/etc/sabai/openvpn/ovpn.current")) {
			exec("logger BACKUP_msg: No ovpn configuration was backuped.");
		} else {
			exec("mkdir /configs/ovpn_backup/");
			exec("cp /etc/sabai/openvpn/ovpn.filename /configs/ovpn_backup/ovpn.filename_$file_name");
			exec("cp /etc/sabai/openvpn/ovpn.current /configs/ovpn_backup/ovpn.config_$file_name");
			exec("cp /etc/sabai/openvpn/ovpn /configs/ovpn_backup/ovpn.msg_$file_name");
		}
		echo "New backup was saved as backup_$file_name";
	}
} else {
    echo "false";
}
?>
