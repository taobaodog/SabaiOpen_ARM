#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
UCI_PATH=""
FACTORY_DIR="/configs/factory"

_return(){
	echo "res={ sabai: $1, msg: '$2' };"
	exit 0;
}

#CURRENT_KERNEL=$(grub-editenv /mnt/grubenv list | grep boot_entry | awk -F "=" '{print $2}')
revert_enabled="$(uci get sabai.general.revert)"

if [ -e $FACTORY_DIR ]; then
	# Copy current custom config
	custom_confs="/configs/custom"	

	[ -e $custom_confs ] || mkdir $custom_confs
	cp -r /etc/config $custom_confs
	[ -e $custom_confs/sabai ] || mkdir $custom_confs/sabai
	cp -r /etc/sabai/openvpn $custom_confs/sabai
	cp /etc/passwd $custom_confs
	cp /etc/shadow $custom_confs

	# Restore system config
	rm -r /etc/config /etc/sabai/openvpn
	cp -fR $FACTORY_DIR/* /etc/
	uci $UCI_PATH set sabai.general.revert=$revert_enabled
	uci $UCI_PATH commit sabai
	logger "SABAI:> Factory reset in process. Rebooting ..."
	echo "SABAI:> Sucessfully booted after factory reset." > /www/resUpgrade.txt
	reboot
	_return 0 "SABAI:> Factory reset in process. Rebooting ..."
else
	_return 0 "Factory Reset is NOT available."
fi
