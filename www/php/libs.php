<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology

# Currently this file just accumulates all the files included in it
# Later, we may want to cache them or do some other wizardry

function dumpJavaScript($lib,$found){
	header("Content-Type: text/javascript; charset=utf-8");
	if($found){
		echo "/* BEGIN $v */\n";
		readfile("libs/".$v);
		echo "/* END $v */\n\n";
	}else{
		echo "/* ". $lib ." not found. */\n";
	}
}

function insertJavascriptTag($lib,$found){
	echo ( $found ? ("<script src=\"". $lib ."\"></script>\n") : ("<!-- ". $lib ." not found. -->\n") );
}

foreach(array(
	"jquery.js",
	"jqueryui.js",
	"jquery.mousewheel.js",
	"jquery.terminal-0.8.8.js",
	"jeditable.js",
	"noty.js",
	"jquery.noty.jai.js",
	"math.js",
	"jai.js",
	"current/jquery.validate.js",
	"main.js",
	"jai-widgets.js",
	"migrate.js",
	"bootstrap.min.js"
) as $libFile){
	// dumpJavaScript("/libs/". $libFile,file_exists("libs/".$libFile));
	insertJavascriptTag("/libs/". $libFile,file_exists($_SERVER['DOCUMENT_ROOT'] ."/libs/". $libFile));
}

// header("Content-Type: text/javascript; charset=utf-8");

// echo $_SERVER['DOCUMENT_ROOT'] ."\n";

// var_dump(scandir($_SERVER['DOCUMENT_ROOT'] ."/libs/"));

?>