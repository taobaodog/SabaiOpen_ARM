#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
# iptables rules are stored in /etc/sabai/firewall.settings

val=$1
state=$2

_return(){
	echo "res={ sabai: $1, msg: '$2' };"
	exit 0;
}

_ping(){
	local state=$1

	local field=$(uci show firewall | grep -e "name='Enable ping from WAN'" | cut -d "[" -f2 | cut -d "]" -f1)

	if [ -n "$field" ]; then
		uci delete firewall.@rule[$field]
	fi

	if [ "$state" == "enabled" ]; then
		uci add firewall rule > /dev/null
		uci set firewall.@rule[-1].name='Enable ping from WAN'
		uci set firewall.@rule[-1].src='wan'
		uci set firewall.@rule[-1].proto='icmp'
		uci set firewall.@rule[-1].icmp_type='8'
		uci set firewall.@rule[-1].target='ACCEPT'
	fi

	uci commit firewall
	logger "Ping from WAN is $state."
}

_multicast(){
	local state=$1

	for field in $(uci show firewall | grep -e "name='Enable UDP multicast (IGMP)'" | cut -d "[" -f2 | cut -d "]" -f1 | sort -r)
	do
		uci delete firewall.@rule[$field]
	done
	uci commit firewall

	if [ "$state" == "enabled" ]; then
		uci set network.lan.igmp_snooping=1
		uci commit network

		uci add firewall rule > /dev/null
		uci set firewall.@rule[-1].name='Enable UDP multicast (IGMP)'
		uci set firewall.@rule[-1].src='wan'
		uci set firewall.@rule[-1].proto='igmp'
		uci set firewall.@rule[-1].target='ACCEPT'
		uci commit firewall

		uci add firewall rule > /dev/null
		uci set firewall.@rule[-1].name='Enable UDP multicast (IGMP)'
		uci set firewall.@rule[-1].src='wan'
		uci set firewall.@rule[-1].proto='udp'
		uci set firewall.@rule[-1].dest='lan'
		uci set firewall.@rule[-1].target=ACCEPT
		uci set firewall.@rule[-1].family=ipv4
		uci commit firewall

	else
		uci set network.lan.igmp_snooping=0
		uci commit network
	fi

	logger "UDP multicast (IGMP) is $state. Reloading network"
	/etc/init.d/network reload
}

_cookies(){
	local state=$1

	if [ "$state" == "enabled" ]; then
		uci set firewall.@defaults[0].tcp_syncookies=1
	else
		uci set firewall.@defaults[0].tcp_syncookies=0
	fi
	uci commit firewall

	logger "SYN cookies are $state."
}

_wanroute(){
	local state=$1

	if [ "$state" == "enabled" ]; then
		uci set firewall.wan.input="ACCEPT"
	else
		uci set firewall.wan.input="REJECT"
	fi
	uci commit firewall

	logger "WAN access is $state."
}

if [ -z "$val" ]; then
	if [ "$(uci get sabai.firewall.icmp)" == "on" ]; then
		_ping enabled
	else
		_ping disabled
	fi
	if [ "$(uci get sabai.firewall.multicast)" == "on" ]; then
		_multicast enabled
	else
		_multicast disabled
	fi
	if [ "$(uci get sabai.firewall.cookies)" == "on" ]; then
		_cookies enabled
	else
		_cookies disabled
	fi
	if [ "$(uci get sabai.firewall.wanroute)" == "on" ]; then
		_wanroute enabled
	else
		_wanroute disabled
	fi

	logger "Restarting firewall"
	/etc/init.d/firewall restart 2>/dev/null > /dev/null
	exit
fi

if [ ! \( "$state" == "enabled" -o "$state" == "disabled" \) ]; then
	logger -p user.err "Wrong state for firewall.sh!"
	exit 1
fi

case $val in
	ping)		_ping $state	   ;;
	multicast)   _multicast $state  ;;
	syn_cookies) _cookies $state	;;
	wanroute)  _wanroute $state ;;
esac

logger "Restarting firewall"
/etc/init.d/firewall restart 2>/dev/null > /dev/null

_return 0 "Firewall settings was applied."
