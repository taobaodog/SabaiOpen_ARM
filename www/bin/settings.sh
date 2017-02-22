#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
UCI_PATH=""
act=$1

_hostname(){
	name=$(uci get sabai.general.hostname)
	uci set system.@system[0].hostname="$(uci get sabai.general.hostname)"
	uci commit system
	uci set network.wan.hostname="$(uci get sabai.general.hostname)"
	uci commit network
	echo $(uci get system.@system[0].hostname) > /proc/sys/kernel/hostname
	/etc/init.d/network restart
}

_return(){
	echo "res={ sabai: $1, msg: '$2' };"
	exit 0
}

_reboot(){
	reboot
	_return 1 "Rebooting... Please wait about 60 seconds."
}

_halt(){
	halt
	_return 1 "Shut Down Complete"
}

_updatepass(){
	pass=$(cat /tmp/hold)
	echo "root:$pass" | chpasswd -m
        echo "admin:$pass" | chpasswd -m
	rm /tmp/hold
	_return 1 "Password Changed"
}


case $act in
	hostname) _hostname ;;
	reboot)	_reboot	;;
	halt)	_halt	;;
	updatepass) _updatepass ;;
esac
