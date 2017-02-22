<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology
if(isset($_POST['loadFile'])) {
        $file_name = $_POST['loadFile'];

	$check = strpos($name, "backup_");
	if ($check === false) {
		$name = $file_name;
	} else {
		$name = str_replace("backup_", "", $file_name);
	}
	
	switch ($name) {
		case "sabai":
			$date = exec("date '+%B %d' | tr -d ' ' ");
			$File = '/configs/'.$file_name.$date;
			exec("cp /configs/$name /configs/$name$date");
			if (file_exists("/etc/sabai/openvpn/ovpn.current")) {
				exec("cp /etc/sabai/openvpn/ovpn.current /configs/ovpn_backup/ovpn.config_$name$date");
				exec("cp /etc/sabai/openvpn/ovpn /configs/ovpn_backup/ovpn.msg_$name$date");
				exec("cp /etc/sabai/openvpn/ovpn /configs/ovpn_backup/ovpn.filename_$name$date");
				$config="/configs/ovpn_backup/ovpn.config_$name$date";                          
				$msg="/configs/ovpn_backup/ovpn.msg_$name$date";                                
				$ovpn_filename="/configs/ovpn_backup/ovpn.filename_$name$date";
				exec("tar -cvf /configs/$name$date.tar $File $config $msg $ovpn_filename");
				$pathToFile = "/configs/" . $name . $date . ".tar" ;
			} else {
				$pathToFile = $File;
			}
			break;
		default:
			$File = '/configs/'.$file_name;
			if (file_exists("/configs/ovpn_backup/ovpn.filename_$name")) {
				$config="/configs/ovpn_backup/ovpn.config_$name";
				$msg="/configs/ovpn_backup/ovpn.msg_$name";
				$ovpn_filename="/configs/ovpn_backup/ovpn.filename_$name";
				exec("tar -cvf /configs/$file_name.tar $File $config $msg $ovpn_filename");
				$pathToFile = "/configs/" . $file_name . ".tar" ;
			} else {
				$pathToFile = $File;
			}
	}	
        echo $pathToFile;
}
?>
