<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology, LLC
		 
	if(isset($_REQUEST['act']) && $_REQUEST['act']!="")
	{
	$filter = array("<", ">","="," (",")",";","/","|");
        $_REQUEST['act']=str_replace ($filter, "#", $_REQUEST['act']);
		$act=$_REQUEST['act'];

		$toDo= exec("sudo /var/www/bin/ssh.sh $act",$out);

		echo $toDo;
	}
?>
