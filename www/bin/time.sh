#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
UCI_PATH=""
action=$1
if [ $action = "update" ]; then
        config_file=sabai-new
else
        config_file=sabai
fi

location=$(uci get $config_file.time.location)

# Set time on system
uci $UCI_PATH set sabai.time.timezone="$(cat /www/libs/timezones.data | grep -w "$location" | awk '{print $2}')"
uci $UCI_PATH commit sabai
cp -r /etc/config/sabai /configs/
uci set system.@system[0].timezone="$(uci get $config_file.time.timezone)"
uci set system.ntp.server="$(uci get $config_file.time.servers)"
uci commit system
echo $(uci get $config_file.time.timezone) > /etc/TZ
if [ $action = "update" ]; then
	echo "time" >> /tmp/.restart_services
else
	/etc/init.d/ntpd restart
	logger "time script run and time client restarted"

	# Send completion message back to UI
	echo "res={ sabai: true, msg: 'Time settings applied' }"
fi

ls >/dev/null 2>/dev/null 
