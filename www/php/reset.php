<?php
#restore system settings for current partition
$res = exec("sh /www/bin/reset.sh");
echo $res;
?>