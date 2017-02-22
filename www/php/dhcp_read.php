#!/usr/bin/php-cli
<?php
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology, LLC
    foreach (file("/tmp/dhcp.leases") as $line) {
        $fields = explode(" ", $line);
        $data[] = Array($fields[0], $fields[1], $fields[2], $fields[3]);
    }

    function cmp($a, $b) {
        $a_long = sprintf('%u', ip2long($a[2]));
        $b_long = sprintf('%u', ip2long($b[2]));

        return ($a_long > $b_long);
    }

    usort($data, 'cmp');

    foreach ($data as $var=>$value) {
        foreach ($value as $subvar=>$subvalue) {
            echo "\t\t" . $subvalue;
        }
        echo "\n";
    }
?>