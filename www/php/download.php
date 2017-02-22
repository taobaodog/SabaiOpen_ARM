<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology, LLC

$uploaddir = '/tmp/upgrade/';

if (is_dir($uploaddir))	{
	exec("rm /tmp/upgrade/*");
} else {
	exec("mkdir /tmp/upgrade/");
}

if (!empty($_FILES['_browse']['tmp_name'])) {
	$uploadfile = $uploaddir.basename($_FILES['_browse']['name']);
	$copy_res = copy($_FILES['_browse']['tmp_name'], $uploadfile) or die( "Could not copy file!");
	$exist_res = file_exists("$uploadfile");	
	if ($copy_res != 1 && $exist_res != 1) {
		echo "false";
	} else {
		echo "true";
	}
} else {
	echo "false";
}
?>
