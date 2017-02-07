<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology, LLC
$ex=str_replace("\r","\n",$_REQUEST['cmd']);
$rname="/tmp/tmp.". str_pad(mt_rand(1000,9999), 4, "0", STR_PAD_LEFT)  .".sh";
file_put_contents($rname,"#!/bin/ash\nexport PATH='/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin'\n$ex\n");
exec("rm /tmp/console; ash $rname > /tmp/console");
$pass=json_encode(file_get_contents("/tmp/console"));
// Send completion message back to UI
echo $pass;
?>