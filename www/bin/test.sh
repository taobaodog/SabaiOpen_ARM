#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
location="Pacific/Wake"
zone=$(cat /www/libs/timezones.data | grep -w "$location" | awk '{print $2}')
echo $location
echo $zone

