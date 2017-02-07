<?php
header('Content-Type: application/javascript');
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology
$UCI_PATH="";

// Bring over variables from the openVPN
if (isset($_POST['switch'])) {
	$act=$_POST['switch'];
} else {
	$act=$_REQUEST['act'];
	$VPNname=trim($_POST['VPNname']);
	$VPNpassword=trim($_POST['VPNpassword']);
	$conf=trim($_POST['conf']);
}

function fixFile(){
	// Removing extra windows charachters
	$ovpn_file=file_get_contents("/etc/sabai/openvpn/ovpn.current");
	if( strpos($ovpn_file,';explicit-exit-notify') == false ) {
    	$ovpn_file_fixed=str_replace("explicit-exit-notify", ";explicit-exit-notify" ,$ovpn_file);
	}
  if( strpos($ovpn_file,';receive-dns') == false ) {
    $ovpn_file_fixed=str_replace("receive-dns", ";receive-dns" ,$ovpn_file_fixed);
  }
  if( strpos($ovpn_file,';crl-verify crl.pem') == false ) {
    $ovpn_file_fixed=str_replace("crl-verify crl.pem", ";crl-verify crl.pem" ,$ovpn_file_fixed);
    file_put_contents("/etc/sabai/openvpn/ovpn.current",$ovpn_file_fixed);
  }
}

function newfile(){
  $file = ( array_key_exists('browse',$_FILES) && array_key_exists('name',$_FILES['browse']) ? $_FILES['browse']['name'] : "" );
  $file=preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['browse']['name']);
  $type = strrchr($file,".");
  $filelocation='/etc/sabai/openvpn/ovpn.current';
  file_put_contents('/etc/sabai/openvpn/auth-pass', '');
  $contents = ( array_key_exists('browse',$_FILES) && array_key_exists('tmp_name',$_FILES['browse']) ? file_get_contents($_FILES['browse']['tmp_name']) : "" );
  $contents = preg_replace(array("/^script-security.*/m","/^route-up .*/m","/^up .*/m","/^down .*/m"),"",$contents);
  
  switch($type){
    case ".conf":
    case ".ovpn":
      file_put_contents('/etc/sabai/openvpn/ovpn.filename', $file);
      file_put_contents($filelocation,$contents);
      file_put_contents($filelocation, "\nscript-security 2\ndown /www/bin/flush_dns_fix.sh", FILE_APPEND);
      exec("uci set openvpn.sabai.filename=$file");
      exec("uci set openvpn.sabai.filetype=$type");
      exec("uci commit");
      fixFile();
      echo "res={ sabai: false, msg: 'OpenVPN $type file loaded.', file: '$file', reload: 'true' };";
    break;
    default:{
    echo "res={ sabai: false, msg: 'OpenVPN file failed. Incorrect format.' };";
  }
 }
}

function savefile(){
$name=$_REQUEST['VPNname'];
$password=$_REQUEST['VPNpassword'];
 if(array_key_exists('conf',$_REQUEST)){
  file_put_contents("/etc/sabai/openvpn/ovpn.current",$_REQUEST['conf']);
  file_put_contents("/etc/sabai/openvpn/auth-pass",$name ."\n");
  file_put_contents("/etc/sabai/openvpn/auth-pass",$password, FILE_APPEND);
  exec("sed -ir 's/auth-user-pass.*$/auth-user-pass \/etc\/sabai\/openvpn\/auth-pass/g' /etc/sabai/openvpn/ovpn.current");
  echo "res={ sabai: true, msg: 'OpenVPN configuration saved.', reload: true };";
 }else{
  echo "res={ sabai: false, msg: 'Invalid configuration.' };";
 }
}



switch ($act){
  case "start":
    if(!file_exists("/etc/sabai/openvpn/ovpn.current")){ echo "res={ sabai: false, msg: 'OpenVPN file missing.' };"; break;}
  case "stop":
    $line=exec("sh /www/bin/ovpn.sh $act 2>&1",$out);
    $i=count($out)-1;
    while( substr($line,0,3)!="res" && $i>=0 ){ $line=$out[$i--]; }
    file_put_contents("/etc/sabai/stat/php.ovpn.log", implode("\n",$out) );
    echo $line;
  break;
  case "clear":
    exec("sh /www/bin/ovpn.sh clear 2>&1");
    echo "res={ sabai: true, msg: 'OpenVPN file removed.', reload: true };";
  break;
  case "newfile": newfile(); break;
  case "save": savefile(); break;
  case "log": exec("/www/bin/ovpn.sh log") ;
		echo (file_exists("/var/log/ovpn_web.log") ?  str_replace(array("\"","\r"),array("'","\n"),file_get_contents("/var/log/ovpn_web.log")) : "No log."); 
  		break;  
  case "check": 
  	$line=exec("/www/bin/ovpn.sh $act");
  	if( strpos($line,'started.') == true ) {
  		exec("/www/bin/ovpn.sh dns");
  	}
  	echo $line; break;
}

?>
