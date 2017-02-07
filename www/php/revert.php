<?php
#restore to previous partition
$res = exec("sh /www/bin/revert.sh");
echo $res;
?>