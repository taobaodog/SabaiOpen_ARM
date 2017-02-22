#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
UCI_PATH=""

action=$1
status=$(uci get sabai.vpn.status)
proto=$(uci get sabai.vpn.proto)
device=$(uci get system.@system[0].hostname)
start_time="$(date '+%H:%M')"
_return(){
	echo "res={ sabai: $1, msg: '$2' };";
	exit 0;
}

_stop(){
	ifconfig > /tmp/check
	if [ ! "$(cat /tmp/check | grep tun0)" ] && [ "$(uci get sabai.vpn.proto)" != "ovpn" ]; then
		logger "No OpenVPN is running."
		_return 0 "No OpenVPN is running."
	else
		_clear
		#prevent ovpn start during the boot
		uci set openvpn.sabai.enabled='0'
		uci commit openvpn
		logger "Openvpn stopped"
		_return 1 "OpenVPN stopped."
	fi
}

_start(){
	ifconfig > /tmp/check
	if [ "$(cat /tmp/check | grep tun0)" ] && [ "$(uci get sabai.vpn.proto)" = "ovpn" ]; then
		logger "OpenVPN is already running."
		_return 0 "OpenVPN is already running."
	fi

	if [ ! -e /etc/sabai/openvpn/ovpn.current ]; then
		_return 0 "No file is loaded."
	fi

	_config
	/etc/init.d/openvpn start
	/etc/init.d/openvpn enable

	sleep 10

	_stat
}

_save(){
	_return 1 "OpenVPN settings saved.";
}

_config(){
	local port=$1
	if [ -z "$port" ]; then
		port="1194"
	fi

	ifconfig > /tmp/check

	# stop other vpn's if running
	if [ "$(uci get sabai.vpn.proto)" = "pptp" ]; then
		/www/bin/pptp.sh stop
		uci $UCI_PATH set sabai.vpn.status=Starting
		uci $UCI_PATH set sabai.vpn.proto=ovpn
		uci $UCI_PATH commit sabai
		cp -r /etc/config/sabai /configs/sabai
		logger "Vpn stopped and network restarted"
		sleep 5
	elif [ "$(uci get sabai.vpn.proto)" = "ovpn" ]; then
		#Removing old configuration.
		_clear
	else
		logger "No VPN is running."
	fi

	#Configuring openvpn profile.
	uci set openvpn.sabai.log='/var/log/ovpn.log'
	uci set openvpn.sabai.enabled=1
	uci set openvpn.sabai.filename="$(cat /etc/sabai/openvpn/ovpn.filename)"
	uci commit openvpn

	#Configuring network interface
	uci set network.sabai=interface
	uci set network.sabai.ifname='tun0'
	uci set network.sabai.proto='none'
	uci commit network
	/etc/init.d/network reload

	#Firewall settings
	uci set firewall.ovpn=zone
	uci set firewall.ovpn.name=sabai
	uci set firewall.ovpn.input=REJECT
	uci set firewall.ovpn.output=ACCEPT
	uci set firewall.ovpn.forward=REJECT
	uci set firewall.ovpn.network=sabai
	uci set firewall.ovpn.masq=1
	uci add firewall forwarding
	uci set firewall.@forwarding[-1].dest=sabai
	if [ "$device" = "vpna" ]; then
		uci set firewall.@forwarding[-1].src=wan
		uci add firewall redirect > /dev/null
		uci set firewall.@redirect[-1].src='wan'
		uci set firewall.@redirect[-1].src_dport='53'
		uci set firewall.@redirect[-1].proto='tcpudp'
		uci set firewall.@redirect[-1].dest_ip="$(uci get network.wan.ipaddr)"
		uci set firewall.@redirect[-1].dest_port='5353'
		uci set firewall.@redirect[-1].target='DNAT'
		uci set firewall.@redirect[-1].reflection='0'
	else
		uci set firewall.@forwarding[-1].src=lan
		uci add firewall rule
		uci set firewall.@rule[-1].name='Allow OpenVPN via WAN'
		uci set firewall.@rule[-1].src='wan'
		uci set firewall.@rule[-1].proto='udp'
		uci set firewall.@rule[-1].dest_port="$port"
		uci set firewall.@rule[-1].src_port="$port"
		uci set firewall.@rule[-1].target="ACCEPT"
	fi
	# [ "$device" = "SabaiOpen" ] && uci set firewall.@forwarding[-1].src=lan || uci set firewall.@forwarding[-1].src=wan
	uci commit firewall
	/etc/init.d/firewall restart 2>/dev/null > /dev/null

	uci $UCI_PATH set sabai.vpn.status=Started
	uci $UCI_PATH set sabai.vpn.proto=ovpn
	uci $UCI_PATH commit sabai
	cp -r /etc/config/sabai /configs/sabai

	# check if log file is set
	[ -e /var/log/ovpn.log ] || touch /var/log/ovpn.log
	if [ ! "$(cat /etc/sabai/openvpn/ovpn.current | grep log-)" ]; then
		echo -e "\n log-append '$(uci get openvpn.sabai.log)'" >> /etc/sabai/openvpn/ovpn.current
		(cat /etc/sabai/openvpn/ovpn.current | grep verb) || echo "verb 3" >> /etc/sabai/openvpn/ovpn.current
	fi
}


_clear(){
	uci delete network.sabai
	uci commit network
	/etc/init.d/network reload

	#Removing configuration of firewall.
	forward=$(uci show firewall | grep forwarding | grep dest=\'sabai\' | cut -d "[" -f2 | cut -d "]" -f1 | tail -n 1)
	if [ "$forward" != "" ]; then
		uci delete firewall.@forwarding["$forward"]
	fi
	uci delete firewall.ovpn
	for i in $(uci show firewall | grep -e "name='Allow OpenVPN via WAN" | cut -d "[" -f2 | cut -d "]" -f1 | sort -r); do
		uci delete firewall.@rule[$i]
	done
	uci commit firewall
	/etc/init.d/firewall restart 2>/dev/null > /dev/null

	uci $UCI_PATH set sabai.vpn.proto=none
	uci $UCI_PATH set sabai.vpn.status=none
	uci $UCI_PATH set sabai.vpn.dns='0'
	uci $UCI_PATH commit sabai
	cp -r /etc/config/sabai /configs/sabai
	uci delete dhcp.@dnsmasq[0].server
	uci commit dhcp
	/etc/init.d/openvpn stop
	/etc/init.d/openvpn disable
	check=$(uci show firewall | grep forwarding | grep dest=\'sabai\' | cut -d "[" -f2 | cut -d "]" -f1 | wc -l)
	echo "$check"
	if [ "$check" != "0" ]; then
		i=1
		while [ $i -le $check ]; do
			num=$(uci show firewall | grep forwarding | grep dest=\'sabai\' | cut -d "[" -f2 | cut -d "]" -f1 | awk -v i=$i 'NR==$i')
			uci delete firewall.@forwarding["$num"]
			uci commit firewall
			echo "$num"
			i=$(( $i + 1 ))
		done
	fi
}

_clear_all(){
	uci set openvpn.sabai.enabled=0
	uci set openvpn.sabai.filename=none
	uci commit openvpn
	_clear
	rm /etc/sabai/openvpn/ovpn.current
	rm /etc/sabai/openvpn/ovpn
	rm /etc/sabai/openvpn/auth-pass
	sleep 5
	logger "OpenVPN settings cleared."
	_return 1 "OpenVPN settings cleared.";
}

_stat(){
	ifconfig > /tmp/check
	if [ ! "$(cat /tmp/check | grep tun0)" ]; then
		uci $UCI_PATH set sabai.vpn.status=Disconnected
		uci $UCI_PATH commit sabai
		cp -r /etc/config/sabai /configs/sabai
		logger "OpenVPN did not start. Please check your configuration."
		_return 1 "OpenVPN did not start. Please check your configuration."
	else
		uci $UCI_PATH set sabai.vpn.status=Connected
		uci $UCI_PATH commit sabai
		cp -r /etc/config/sabai /configs/sabai

		[ "$device" = "SabaiOpen" ] && /www/bin/gw.sh vpn_gw

		logger "Openvpn started."
		_return 1 "OpenVPN started."
	fi
}

_dns_fix(){
	log_line_1="$(awk '/OpenVPN 2.3.6/{ print NR; }' /var/log/ovpn.log | tail -1)"
	log_line_2="$(awk '/Sequence Completed/{ print NR; }' /var/log/ovpn.log | tail -1)"

	check="$(cat /var/log/ovpn.log | awk '{if((NR>'$log_line_1')&&(NR<'$log_line_2')) print}' | grep DNS)"
	if [ "$check" ]; then
		count="$(echo $check | grep -o 'DNS' | wc -l)"
		i="1"
		while [ "$i" -le "$count" ]
		do
			tun_dns="$tun_dns $(echo $check | grep -o 'dhcp.*' | awk -F',' -v i=$i '{print $i}' | awk -F' ' '{print $3}')"
			i=$(( $i + 1 ))
		done

		cp /dev/null /tmp/resolv.conf.vpn
		for i in $tun_dns; do
			echo "nameserver $i" >> /tmp/resolv.conf.vpn
		done
		uci $UCI_PATH set sabai.vpn.dns='1'
		logger "DNS for VPN was set."
	else
		uci $UCI_PATH set sabai.vpn.dns='0'
		logger "DNS is default."
	fi
	uci $UCI_PATH commit sabai
	cp -r /etc/config/sabai /configs/sabai
}

_log() {
	sed -n '1{h;T;};G;h;$p;' /var/log/ovpn.log > /var/log/ovpn_web.log
}

ls >/dev/null 2>/dev/null

case $action in
	start)	_start	;;
	stop)	_stop	;;
	update) _start  ;;
	save)	_save	;;
	clear)  _clear_all  ;;
	config) _config	;;
	check)	_stat	;;
	dns)	_dns_fix;;
	log)	_log	;;
esac
