<?php
if (isset($_GET['num'])) {
	$line=$_GET['num'];
	$file = "/etc/wl_channel_58";
	$file_data = file("/etc/wl_channels_58");
	echo "$file_data[$line]";
}

?>
