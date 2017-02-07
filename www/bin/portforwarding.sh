#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
# iptables rules are stored in /etc/sabai/firewall.settings

#Include JSON parser for OpenWrt
. /usr/share/libubox/jshn.sh

_return(){
	echo "res={ sabai: $1, msg: '$2' }"
	exit 0
}

action=$1

if [ $action = "update" ]; then
	config_file=sabai-new
else
	config_file=sabai
fi

uci get $config_file.pf.tablejs > /tmp/tmppftable
data=$(cat /tmp/tmppftable)
json_load "$data"
json_select 1
json_select ..
json_get_keys keys
num_items=$(echo $keys | sed 's/.*\(.\)/\1/')
uci show firewall >> /tmp/test_fw3
i=0
j=0
while [ $i -le $num_items ]; do
	echo "processing rule  #$i:"
	json_select $i
	json_get_var pfenable status
	json_get_var protocol protocol
	json_get_var gateway gateway
	json_get_var src src
	json_get_var ext ext
	json_get_var int int
	json_get_var address address
	json_get_var description description

	#if ! (echo $ext | grep -xqE '[0-9]{1,5}(:[0-9]{1,5})?') ||
	#	 ! (echo $int | grep -xqE '[0-9]{1,5}(:[0-9]{1,5})?'); then
	#		logger "INVALID PORT RANGE FORMAT: you're not supposed to get here"
	#fi

	case $protocol in
		Both) protocol="tcpudp" ;;
		UDP) protocol="udp"  ;;
		TCP) protocol="tcp"  ;;

		*) echo "INVALID PROTOCOL: you're not supposed to get here." ;;
	esac

	case $gateway in
		WAN) gateway="wan" ;;
		LAN) gateway="lan"  ;;
		VPN) logger "portforwarding.sh: BAD GATEWAY (VPN gateway should be refactored into separate OpenVPN and PPTP)"
		     exit 1 ;;
		PPTP) gateway="pptp" ;;
		OVPN) gateway="ovpn" ;;

		*) echo "INVALID GATEWAY: you're not supposed to get here." ;;
	esac

	num=$(uci show firewall | grep 'redirect' | grep "='portforwarding$j'" | cut -d "[" -f2 | cut -d "]" -f1 | tail -n 1 )
	if [ "$num" != "" ]; then
		uci delete firewall.@redirect[$num]
	fi
	if [ $pfenable = "on" ]; then
		uci add firewall redirect
		uci set firewall.@redirect[-1].name='portforwarding'$j
		uci set firewall.@redirect[-1].proto=$protocol

		if [ $gateway == "wan" ]; then
			uci set firewall.@redirect[-1].src='wan'
			uci set firewall.@redirect[-1].src_ip=$src
			uci set firewall.@redirect[-1].src_dport=$int
			uci set firewall.@redirect[-1].dest_ip=$address
			uci set firewall.@redirect[-1].dest_port=$ext
			uci set firewall.@redirect[-1].dest='lan'
			uci set firewall.@redirect[-1].target='DNAT'
		elif [ $gateway == "lan" ]; then
			# No idea how this should work, but this implementation is most likely WRONG
			# As soon as it is worked out this code can be optimized for size and readability
			uci set firewall.@redirect[-1].src='lan'
			uci set firewall.@redirect[-1].src_ip=$src
			uci set firewall.@redirect[-1].src_dip=$(uci get network.wan.ipaddr)
			uci set firewall.@redirect[-1].src_dport=$int
			uci set firewall.@redirect[-1].dest_ip=$address
			uci set firewall.@redirect[-1].dest_port=$ext
			uci set firewall.@redirect[-1].dest='wan'
			uci set firewall.@redirect[-1].target='SNAT'
		elif [ $gateway == "ovpn" ]; then
			uci set firewall.@redirect[-1].src='sabai'
			uci set firewall.@redirect[-1].src_ip=$src
			uci set firewall.@redirect[-1].src_dport=$int
			uci set firewall.@redirect[-1].dest_ip=$address
			uci set firewall.@redirect[-1].dest_port=$ext
			uci set firewall.@redirect[-1].dest='lan'
			uci set firewall.@redirect[-1].target='DNAT'
		elif [ $gateway == "pptp" ]; then
			uci set firewall.@redirect[-1].src='vpn'
			uci set firewall.@redirect[-1].src_ip=$src
			uci set firewall.@redirect[-1].src_dport=$int
			uci set firewall.@redirect[-1].dest_ip=$address
			uci set firewall.@redirect[-1].dest_port=$ext
			uci set firewall.@redirect[-1].dest='lan'
			uci set firewall.@redirect[-1].target='DNAT'
		else
			echo -e "\n"
		fi
	fi

	json_select ..
	i=$(( $i + 1 ))
	j=$(( $j + 1 ))
	uci commit firewall
done

#cleanup
rm /tmp/tmppftable

/www/bin/dmz.sh restart

uci commit
if [ $action = "update" ]; then
	echo "firewall" >> /tmp/.restart_services
else
	/etc/init.d/firewall restart
	logger "Port forwarding configs were aplied."

	# Send completion message back to UI
	_return 1 "Port forwarding settings applied. $msg"
fi

ls >/dev/null 2>/dev/null
exit 0
