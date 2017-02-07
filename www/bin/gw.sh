#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
UCI_PATH=""

#Include JSON parser for OpenWrt
. /usr/share/libubox/jshn.sh

# send messages to log file but clear log file on each new setup of gw.sh
#rm /var/log/sabaigw.log; exec 2>&1; exec 1>/var/log/sabaigw.log;

#find our local network, minus last octet.  For example 192.168.199.1 becomes 192.168.199
lan_prefix="$(uci get network.lan.ipaddr | cut -d '.' -f1,2,3)";
device=$(uci get system.@system[0].hostname)

#TODO: Unused functionality for now.
#get the current server address for sabaitechnology.biz for address services
#sabaibiz="$(nslookup sabaitechnology.biz | grep "Address 1:" | cut -d':' -f2 | awk '{print $1}' | awk '{print $1}' | tail -n 1)";

_check_static(){
	[ -n "$(uci show sabai | grep $1)" ] && sed -i "8i\/usr/sbin/ip rule add from "$1" prio "$2" table $3" /etc/rc.local
}

#return macs of all clients whose route is not local (need special vpn dns)
_get_vpn_mac(){
	data=$(uci get sabai.dhcp.tablejs)
	json_load "$data"
	json_get_keys keys
	num_items=$(echo $keys | sed 's/.*\(.\)/\1/')
	i=1
	for i in $(seq 1 $num_items)
	do
		json_select $i
		json_get_var mac mac
		json_get_var route route
		[ "$route" == "default" -o "$route" == "vpn_only" -o "$route" == "vpn_fallback" ] && echo $mac
		json_select ..
	done
}

_get_tor_ip(){
	data=$(uci get sabai.dhcp.tablejs)
	json_load "$data"
	json_get_keys keys
	num_items=$(echo $keys | sed 's/.*\(.\)/\1/')
	i=1
	for i in $(seq 1 $num_items)
	do
		json_select $i
		json_get_var ip ip
		json_get_var route route
		[ "$route" == "default" ] && echo $ip
		json_select ..
	done
}

#configure vpn route table
_vpn_config(){
	ip route add $2 dev $1
	ip route | grep $1 | while read vpn_rt; do ip route add $vpn_rt table vpn; done
	ip route del $2 dev $1
	#ensure all vpn users get proper dns
	for i in $(uci show firewall | grep -e "dest_port='5353'" | cut -d "[" -f2 | cut -d "]" -f1 | sort -r)
	do
		uci delete firewall.@redirect[$i]
	done
	uci commit firewall

	for mac in $(_get_vpn_mac);	do
		uci add firewall redirect > /dev/null
		uci set firewall.@redirect[-1].src='lan'
		uci set firewall.@redirect[-1].dest='wan'
		uci set firewall.@redirect[-1].src_mac=$mac
		uci set firewall.@redirect[-1].src_dport='53'
		uci set firewall.@redirect[-1].proto='tcpudp'
		uci set firewall.@redirect[-1].dest_ip="$(uci get network.lan.ipaddr)"
		# uci set firewall.@redirect[-1].dest_ip='98.158.112.14'
		uci set firewall.@redirect[-1].dest_port='5353'
		uci set firewall.@redirect[-1].target='DNAT'
		uci set firewall.@redirect[-1].reflection='0'
	done
	uci commit firewall
	logger "Restarting firewall"
	/etc/init.d/firewall restart 2>/dev/null > /dev/null
}

_vpn_start(){
	if [ "$(ifconfig | grep tun0)" != "" ]; then
		vpn_device="tun0"
		vpn_gateway="$(ifconfig tun0 | grep P-t-P: | awk '{print $3}' | sed 's/P-t-P://g')"
		_vpn_config $vpn_device $vpn_gateway
	elif [ "$(ifconfig | grep pptp-vpn)" != "" ]; then
		vpn_device="pptp-vpn";
		vpn_gateway="$(ifconfig pptp-vpn | grep P-t-P: | awk '{print $3}' | sed 's/P-t-P://g')";
		_vpn_config $vpn_device $vpn_gateway
	else
		logger "NO VPN route table was added."
	fi
}

_depopulate_route(){
	for i in wan acc vpn; do
		ip route flush table $i;
	done
}

_populate_route(){
	#add routing tables
	for i in wan acc vpn; do ip route add "$lan_prefix.0/24" dev br-lan table $i; done
	wan_gateway="$(uci get network.wan.gateway)"; wan_iface="$(uci get network.wan.ifname)";
	#adding wan route to 1 table
	[ -n "$wan_iface" ] && ([ -n "$wan_gateway" ] && [ "$wan_gateway" != "0.0.0.0" ]) && ip route add default via $wan_gateway dev $wan_iface table wan
	#ensure that accelerator IP is set
	if [ "$(uci get sabai.general.ac_ip)" = "" ]; then
		uci $UCI_PATH set sabai.general.ac_ip=2
		uci $UCI_PATH commit sabai
		cp -r /etc/config/sabai /configs/
	fi
	# adding route to the accelerator to 2 table

	ip route add default via "$lan_prefix.$(uci get sabai.general.ac_ip)" dev br-lan table acc
	# adding VPN route to table 3
	_vpn_start
}

#clear the old ip routes
_fin(){ ip route flush cache; }

#flush the tables on stopping gateways
_stop(){
	start_line=8
	_depopulate_route
	ip rule | grep "$lan_prefix" | cut -d':' -f2 | while read old_rule; do ip rule del $old_rule; done
	ip_rules="$(grep -n -m 1 "exit 0" /etc/rc.local |sed  's/\([0-9]*\).*/\1/')"
	echo $ip_rules
	[ -n "$ip_rules" ] && [ "$ip_rules" -gt "$start_line" ] && sed -i ""$start_line","$(( ip_rules - 1 ))"d" /etc/rc.local
	_fin
}

_start(){
	#clear old settings
	[ -z "$1" ] && _stop
	_populate_route
}

_tor_route(){
	local net=$(ip addr show | grep br-lan: -A 3 | grep inet | awk '{print $2}')
	if [ "$1" = "setup" ]; then
		iptables -t nat -A PREROUTING -s "$2" ! -d "$net" -p udp --dport 53 -j REDIRECT --to-ports 9053
		iptables -t nat -A PREROUTING -s "$2" ! -d "$net" -p tcp --dport 53 -j REDIRECT --to-ports 9053
		iptables -t nat -A PREROUTING -s "$2" ! -d "$net" -p tcp --syn -j REDIRECT --to-ports 9040
		echo "iptables -t nat -A PREROUTING -s "$2" ! -d "$net" -p udp --dport 53 -j REDIRECT --to-ports 9053" >> /etc/firewall.user
		echo "iptables -t nat -A PREROUTING -s "$2" ! -d "$net" -p tcp --dport 53 -j REDIRECT --to-ports 9053" >> /etc/firewall.user
		echo "iptables -t nat -A PREROUTING -s "$2" ! -d "$net" -p tcp --syn -j REDIRECT --to-ports 9040" >> /etc/firewall.user
	elif [ "$1" = "teardown" ]; then
		iptables -t nat -D PREROUTING -s "$2" ! -d "$net" -p udp --dport 53 -j REDIRECT --to-ports 9053
		iptables -t nat -D PREROUTING -s "$2" ! -d "$net" -p tcp --dport 53 -j REDIRECT --to-ports 9053
		iptables -t nat -D PREROUTING -s "$2" ! -d "$net" -p tcp --syn -j REDIRECT --to-ports 9040
		sed -ni "/iptables -t nat -A PREROUTING -s $2 ! -d .*\/.* -p .* -j REDIRECT --to-ports/!p" /etc/firewall.user
	fi
}

_tor_tun_on(){
	if [ "$device" = "vpna" ]; then
		local net=$(ip addr show | grep eth0: -A 3 | grep inet | awk '{print $2}')

		iptables -t nat -I PREROUTING -i "eth0" ! -d "$net" -p udp --dport 53 -j REDIRECT --to-ports 9053 -m comment --comment 'Serve TOR to the default route'
		iptables -t nat -I PREROUTING -i "eth0" ! -d "$net" -p tcp --dport 53 -j REDIRECT --to-ports 9053 -m comment --comment 'Serve TOR to the default route'
		iptables -t nat -I PREROUTING -i "eth0" ! -d "$net" -p tcp --syn -j REDIRECT --to-ports 9040 -m comment --comment 'Serve TOR to the default route'
		echo "iptables -t nat -I PREROUTING -i "eth0" ! -d "$net" -p udp --dport 53 -j REDIRECT --to-ports 9053 -m comment --comment 'Serve TOR to the default route'" >> /etc/firewall.user
		echo "iptables -t nat -I PREROUTING -i "eth0" ! -d "$net" -p tcp --dport 53 -j REDIRECT --to-ports 9053 -m comment --comment 'Serve TOR to the default route'" >> /etc/firewall.user
		echo "iptables -t nat -I PREROUTING -i "eth0" ! -d "$net" -p tcp --syn -j REDIRECT --to-ports 9040 -m comment --comment 'Serve TOR to the default route'" >> /etc/firewall.user
	else
		local net=$(ip addr show | grep br-lan: -A 3 | grep inet | awk '{print $2}')
		for ip in $(_get_tor_ip);	do
			iptables -t nat -A PREROUTING -s "$ip" ! -d "$net" -p udp --dport 53 -j REDIRECT --to-ports 9053 -m comment --comment 'Serve TOR to the default route'
			iptables -t nat -A PREROUTING -s "$ip" ! -d "$net" -p tcp --dport 53 -j REDIRECT --to-ports 9053 -m comment --comment 'Serve TOR to the default route'
			iptables -t nat -A PREROUTING -s "$ip" ! -d "$net" -p tcp --syn -j REDIRECT --to-ports 9040 -m comment --comment 'Serve TOR to the default route'
			echo "iptables -t nat -A PREROUTING -s "$ip" ! -d "$net" -p udp --dport 53 -j REDIRECT --to-ports 9053 -m comment --comment 'Serve TOR to the default route'" >> /etc/firewall.user
			echo "iptables -t nat -A PREROUTING -s "$ip" ! -d "$net" -p tcp --dport 53 -j REDIRECT --to-ports 9053 -m comment --comment 'Serve TOR to the default route'" >> /etc/firewall.user
			echo "iptables -t nat -A PREROUTING -s "$ip" ! -d "$net" -p tcp --syn -j REDIRECT --to-ports 9040 -m comment --comment 'Serve TOR to the default route'" >> /etc/firewall.user
		done
	fi
}

_tor_tun_off(){
	iptables-save | grep -v -- '-m comment --comment "Serve TOR to the default route"' | iptables-restore
	sed -ni "/-m comment --comment 'Serve TOR to the default route'/!p" /etc/firewall.user
}

_ip_rules(){
	#counter via tmp file so priorities are unique
	if [ -f "/tmp/prioctr" -a -n "$(ip rule show | grep ^256:)" ]; then
		val_prio="$(cat /tmp/prioctr)"
	else
		val_prio=256
	fi

	#assign statics to ip rules
	case $1 in
		local)
			ip rule add prio $val_prio from "$2" table wan
			_check_static $2 $val_prio wan
		;;
		vpn_fallback)
			ip rule add prio $val_prio from "$2" table vpn
			_check_static $2 $val_prio vpn
			logger "$2 is connected to vpn_fallback option."
		;;
		vpn_only)
			ip rule add prio $val_prio from "$2" table vpn
			_check_static $2 $val_prio vpn
		;;
		accelerator)
			ip rule add prio $val_prio from "$2" table acc
			_check_static $2 $val_prio acc
		;;
	esac

	echo "$(( $val_prio+1 ))" > /tmp/prioctr

	_fin
}

_ds(){ /etc/init.d/dnsmasq restart; _start; }

case $1 in
	stop)	_stop				;;
	start)	_start $2			;;
	ds)	_ds				;;
	vpn_gw)	_vpn_start			;;
	iprules) _ip_rules $2 $3		;;
	depopulate_route) _depopulate_route	;;
	populate_route) _populate_route		;;
	torroute) _tor_route $2 $3		;;
	tortun_on) _tor_tun_on ;;
	tortun_off) _tor_tun_off ;;
esac
