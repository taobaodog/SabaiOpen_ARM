<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology, LLC

$UCI_PATH = "";

$uploaddir = "/configs/";
$ovpn_dir = $uploaddir . "ovpn_backup/";

function upload_config(){
if (!empty($_FILES['_browse1']['tmp_name'])) {
	$uploadfile = preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['_browse1']['name']);
	$tmp="/tmp/".$uploadfile;
	copy($_FILES['_browse1']['tmp_name'], $tmp) or die( "Could not copy file!");
	$file_type = pathinfo($tmp, PATHINFO_EXTENSION);
	if ($file_type == "tar") {
		$name = str_replace(".tar", "", $uploadfile);
		$check = strpos($name, "backup_");
		if ($check === false) {
			$file = $name;
		} else {
			$file = str_replace("backup_", "", $name);
		}
		exec("mkdir /tmp/$name");
		exec("tar -xvf $tmp -C /tmp/$name");
		if (copy("/tmp/" . $name . "/configs/" . $name, "/configs/".$name)) {
			if (file_exists("/configs/".$name)) {
				$get_version = exec("uci -c /configs get $name.general.version");
				if ($get_version != "") {
					$ovpn_tmp = "/tmp/" . $name . "/configs/ovpn_backup/";
					$copy_config = copy($ovpn_tmp . "ovpn.config_" . $file, $ovpn_dir."ovpn.config_".$file);
					$copy_msg = copy($ovpn_tmp . "ovpn.msg_" . $file, $ovpn_dir."ovpn.msg_".$file) ;
					$copy_filename = copy($ovpn_tmp . "ovpn.filename_" . $file, $ovpn_dir."ovpn.filename_".$file);
					if ($copy_config == 1 && $copy_msg == 1 && $copy_filename ==1) {
						echo "true";
					} else {
						echo "OVPN configuration was not uploaded.";
					}
				} else {
					exec("rm /configs/$name");
					echo "Failed.You tried to upload some wrong configuration.";
				}
			} else {
				echo "Something went wrong. No file was uploaded.";
			}
                } else {
                        echo "Something went wrong. Could not upload your file.";
                }
		exec("rm -r /tmp/$name");
		exec("rm -r $tmp");
        } else {
		if (copy($tmp, "/configs/".$uploadfile)) {
			$get_version = exec("uci -c /configs get $uploadfile.general.version");
			if ($get_version != "") {
				echo "true";
			} else {
				exec("rm /configs/$uploadfile");
				echo "Failed.You tried to upload some wrong configuration.";
			}
		} else {
			echo "Something went wrong. No file was uploaded.";
		}
		exec("rm /tmp/$uploadfile");
		exec("rm /etc/config/$uploadfile");
	}
} else {
        echo "false";
}
}

if (file_exists($ovpn_dir)) {
	upload_config();
} else {
	exec("mkdir $ovpn_dir");
	upload_config();
}

?>

