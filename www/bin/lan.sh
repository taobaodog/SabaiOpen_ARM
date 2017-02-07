#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology

action=$1

if [ $action = "update" ]; then
        config_file=sabai-new
else
        config_file=sabai
fi

uci set network.lan.ipaddr=$(uci get $config_file.lan.ipaddr)
uci set network.lan.netmask=$(uci get $config_file.lan.netmask)
uci commit network
uci set dhcp.lan.leasetime=$(uci get $config_file.dhcp.leasetime)
uci set dhcp.lan.start=$(uci get $config_file.dhcp.start)
uci set dhcp.lan.limit=$(uci get $config_file.dhcp.limit)
uci commit dhcp

if [ $action = "update" ]; then
	echo "network" >> /tmp/.restart_services
else
	/etc/init.d/network restart
	logger "LAN war reconfigured."
	# Send completion message back to UI
	echo "res={ sabai: true, msg: 'LAN settings applied' }"
fi

ls >/dev/null 2>/dev/null 
