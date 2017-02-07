#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology

address=$1;
hops=$2;
wait=$3;

rm -rf /www/bin/tmp
mkdir /www/bin/tmp
traceroute $address -m $hops -w $wait> /www/bin/tmp/trace

ls >/dev/null 2>/dev/null 


