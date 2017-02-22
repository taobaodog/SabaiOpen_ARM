#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
act=$1

_return(){
	echo "res={ sabai: $1, msg: '$2' };";
	exit 0;
}

_reboot(){
	reboot
	_return 1 "Rebooted"
}

_shutdown(){
	poweroff
	_return 1 "Shut Down Complete"
}

_dhcp(){
	dhclient eth0
	_return 1 "Lease Reset"
}

case $act in
	reboot)	_reboot	;;
	shutdown)	_shutdown	;;
	dhcp) _dhcp ;;
esac
