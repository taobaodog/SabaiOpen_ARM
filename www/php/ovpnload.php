<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology
$UCI_PATH="";
 $file = ( array_key_exists('file',$_FILES) && array_key_exists('name',$_FILES['file']) ? $_FILES['file']['name'] : "" );
  exec("uci set openvpn.sabai.filename=$file");
  file_put_contents('/etc/sabai/openvpn/ovpn.filename', $file);
 $contents = ( array_key_exists('file',$_FILES) && array_key_exists('tmp_name',$_FILES['file']) ? file_get_contents($_FILES['file']['tmp_name']) : "" );
 $contents = preg_replace(array("/^script-security.*/m","/^route-up .*/m","/^up .*/m","/^down .*/m"),"",$contents);
  file_put_contents('/etc/sabai/openvpn/ovpn.current', $contents);
 $contents = trim( substr( $contents, 0, stripos($contents,"nvram set ovpn") ), "\n'");
 $type = strrchr($file,".");
  exec("uci set openvpn.sabai.filetype=$type");
  exec("uci $UCI_PATH commit sabai"); 
  exec("cp -r /etc/config/sabai /configs/");
echo $_SERVER['REQUEST_URI'];
?>
