<?php
error_reporting(0);
session_start();
if (empty($_POST["pass"])) {
	header( "Location: /" );
} else {
	$_SESSION['count'] = 1;

	$pass=$_REQUEST['pass'];
	exec("echo -n $pass > /tmp/hold");
	exec("sh /www/bin/settings.sh updatepass");
	echo "New password is available.";
}
?>
