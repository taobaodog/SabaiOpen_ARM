<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology

$act=array_key_exists('act', $_REQUEST) ? $_REQUEST['act'] : null;
$log=array_key_exists('log', $_REQUEST) ? $_REQUEST['log'] : null;
$detail=array_key_exists('detail', $_REQUEST) ? $_REQUEST['detail'] : null;

$logPath = '/var/log/';
$validPath = $logPath.$log;
$isZipped = (pathinfo($validPath, PATHINFO_EXTENSION) == 'gz');
$detail = escapeshellarg($detail);

#TODO: rework file handling
if ("$log" == "kernel.log") {
	if (file_exists($validPath)) {
		exec("dmesg >> \"". $validPath ."\"");
	} else {
		exec("dmesg > \"". $validPath ."\"");
	}
}

if (file_exists($validPath) && filesize($validPath)) {
switch ($act) {
	case 'all':
		if ($isZipped){
			passthru("gunzip -c $validPath");
		}else{
			readfile($validPath);
		}
	break;
	case 'head':
	case 'tail':
		$detail = '-n '. $detail;
	case 'grep':
		passthru( $isZipped ? "gunzip -c $validPath | $act $detail" : "$act $detail $validPath" );
	break;
	case 'download':
			$pathToFile = "/configs/log/" . $log;
			if (file_exists("/configs/log/")) {
				copy($validPath, $pathToFile);
			} else {
				mkdir("/configs/log", 0700);
				copy($validPath, $pathToFile);
			}
			echo $pathToFile;
	break;
	default:
		echo "No log file was found.";
	break;
} } else {
	echo "false";
}

?>
