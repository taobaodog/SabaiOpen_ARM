<?php
error_reporting(0);
session_start();
if (empty($_POST["pass"]) && empty($_POST["user"]) ) {
	header( "Location: /" );
} else {
	if (!isset($_SESSION['count'])) {
		$_SESSION['count'] = 1;
	}

	$user=$_REQUEST['name'];
	$pass=$_REQUEST['pass'];

	if ($_SESSION['count'] == 10) {
		echo "reset";	
	} else {
		if ($_SESSION['login'] == 'true'){
			die();
		} elseif ($user == "admin"){
			$hash=exec("cat /etc/shadow | grep admin | awk -F: '{print $2}'");
			if (password_verify($pass, $hash)) {
				$_SESSION['login'] = 'true';
				$_SESSION['username'] = $user;
				die();
			} else {
				$_SESSION['count']++;
				echo 'Password is incorrect.';
			}
		} else {
			$_SESSION['count']++;
			echo "User Name is incorrect.";
		}
	}
}
?>
