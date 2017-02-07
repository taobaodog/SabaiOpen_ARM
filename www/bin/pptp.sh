#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
UCI_PATH=""

act=$1
config_act=$2
proto=$(uci get sabai.vpn.proto)
status=$(uci get sabai.vpn.status)
device=$(uci get system.@system[0].hostname)

if [ "$config_act" = "update" ]; then
	config_file="sabai-new"
else
	config_file="sabai"
fi

_return(){
	echo "res={ sabai: $1, msg: '$2' };"
	exit 0;
}

_rm_nw_fw(){
	proto=$1
	uci delete network.$proto
	uci commit network
	uci delete firewall.$proto
	forward=$(uci show firewall | grep forwarding | grep dest=\'$proto\' | cut -d "[" -f2 | cut -d "]" -f1 | tail -n 1)
	if [ "$forward" != "" ]; then
		uci delete firewall.@forwarding["$forward"]
	fi
	for i in $(uci show firewall | grep -e "name='Allow PPtP via WAN" | cut -d "[" -f2 | cut -d "]" -f1 | sort -r); do
		uci delete firewall.@rule[$i]
	done
	uci commit firewall
}

_stop(){
	ifconfig > /tmp/check
	if [ ! "$(cat /tmp/check | grep pptp)" ] && [ "$(uci get sabai.vpn.proto)" != "pptp" ]; then
		logger "No PPTP is running."
		_return 0 "No PPTP is running."
	else
		_rm_nw_fw vpn
		uci $UCI_PATH set sabai.vpn.status=none
		uci $UCI_PATH commit sabai
		cp -r /etc/config/sabai /configs/sabai
		# uci delete dhcp.@dnsmasq[0].server
		#unset dns for pptp
		# uci set dhcp.@dnsmasq[0].resolvfile='/tmp/resolv.conf.auto'
		# uci commit dhcp
		if [ "$config_act" = "update" ]; then
			echo "network" >> /tmp/.restart_services
			echo "firewall" >> /tmp/.restart_services
		else
			/etc/init.d/firewall restart 2>/dev/null > /dev/null
			/etc/init.d/network reload
			#update routing table
			udhcpc
			/www/bin/gw.sh depopulate_route
			/www/bin/gw.sh populate_route
		fi
		uci $UCI_PATH set sabai.vpn.proto=none
		uci $UCI_PATH set sabai.vpn.ip=none
		uci $UCI_PATH set sabai.vpn.dns='0'
		uci $UCI_PATH commit sabai
		cp -r /etc/config/sabai /configs/sabai
		logger "DNS is default."
		logger "PPTP is stopped."
		_return 0 "PPTP is stopped."
	fi
}

_start(){
	ifconfig > /tmp/check
	if [ "$(cat /tmp/check | grep pptp)" ] && [ "$(uci get sabai.vpn.proto)" = "pptp" ]; then
		logger "PPTP is already running."
		_return 0 "PPTP is already running."
	elif [ "$(cat /tmp/check | grep tun0)" ] || [ "$(uci get sabai.vpn.proto)" = "ovpn" ]; then
		#ensure that openvpn is stopped
		/www/bin/ovpn.sh stop
		/etc/init.d/openvpn stop
		/etc/init.d/openvpn disable
		#ensure that openvpn settings removed
	elif [ "$(uci get sabai.vpn.proto)" = "pptp" ]; then
		_rm_nw_fw vpn
	else
		logger "No VPN is running."
	fi
	#get the pptp settings
	user=$(uci get $config_file.vpn.username)
	pass=$(uci get $config_file.vpn.password)
	server=$(uci get $config_file.vpn.server)
	#set the network vpn settings
	uci set network.vpn=interface
	uci set network.vpn.ifname=pptp-vpn
	uci set network.vpn.proto=pptp
	uci set network.vpn.username="$user"
	uci set network.vpn.password="$pass"
	#ip needed so dnsmasq can be restarted safely
	uci set network.vpn.server="$server"
	uci set network.vpn.buffering=1
	uci commit network

	#set pptp mppe settings
	req_mppe_128=$(uci get $config_file.vpn.req_mppe_128)
	mppe_mode=$(uci get $config_file.vpn.mppe_mode)

	sed -ni '/mppe/!p' /etc/ppp/options.pptp
	if [ "$mppe_mode" = "nomppe" ]; then
		echo "nomppe" >> /etc/ppp/options.pptp
	else
		mppe_config="$req_mppe_128,$mppe_mode"
		echo "mppe $mppe_config" >> /etc/ppp/options.pptp
	fi

	#set the firewall
	uci set firewall.vpn=zone
	uci set firewall.vpn.name=vpn
	uci set firewall.vpn.input=REJECT
	uci set firewall.vpn.output=ACCEPT
	uci set firewall.vpn.forward=REJECT
	uci set firewall.vpn.network=vpn
	uci set firewall.vpn.masq=1
	uci add firewall forwarding
	uci set firewall.@forwarding[-1].dest=vpn
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
		uci set firewall.@rule[-1].name='Allow PPtP via WAN (tunnel)'
		uci set firewall.@rule[-1].src='wan'
		uci set firewall.@rule[-1].proto='gre'
		uci set firewall.@rule[-1].target='ACCEPT'
		uci add firewall rule
		uci set firewall.@rule[-1].name='Allow PPtP via WAN (maintenance)'
		uci set firewall.@rule[-1].src='wan'
		uci set firewall.@rule[-1].proto='tcp'
		uci set firewall.@rule[-1].src_port="1723"
		uci set firewall.@rule[-1].target="ACCEPT"
	fi
	# [ "$device" = "SabaiOpen" ] && uci set firewall.@forwarding[-1].src=lan || uci set firewall.@forwarding[-1].src=wan
	#commit all changed services
	uci commit firewall
	#set dns for pptp
	# uci set dhcp.@dnsmasq[0].resolvfile='/tmp/resolv.conf.ppp'
	# uci commit dhcp
	#set sabai vpn settings
	uci $UCI_PATH set sabai.vpn.proto=pptp
	uci $UCI_PATH set sabai.vpn.status=Starting
	uci $UCI_PATH set sabai.vpn.status=pptp
	uci $UCI_PATH commit
	cp -r /etc/config/sabai /configs/sabai
	#restart services
	if [ "$config_act" = "update" ]; then
		echo "network" >> /tmp/.restart_services
		echo "firewall" >> /tmp/.restart_services
	else
		sleep 2
		# /etc/init.d/dnsmasq restart
		/etc/init.d/network reload
		/etc/init.d/firewall restart 2>/dev/null > /dev/null
	fi

	logger "PPTP starts..."
	_return 0 "PPTP starts..."
}

_clear(){
	_rm_nw_fw vpn
	uci $UCI_PATH delete sabai.vpn.username
	uci $UCI_PATH delete sabai.vpn.password
	uci $UCI_PATH delete sabai.vpn.server
	uci $UCI_PATH set sabai.vpn.status=none
	uci $UCI_PATH set sabai.vpn.proto=none
	uci $UCI_PATH commit sabai
	cp -r /etc/config/sabai /configs/sabai
	check=$(uci show firewall | grep forwarding | grep dest=\'vpn\' | cut -d "[" -f2 | cut -d "]" -f1 | wc -l)
	echo "$check"
	if [ "$check" != "0" ]; then
		i=1
		while [ $i -le $check ]; do
			num=$(uci show firewall | grep forwarding | grep dest=\'vpn\' | cut -d "[" -f2 | cut -d "]" -f1 | awk -v i=$i 'NR==$i')
			uci delete firewall.@forwarding["$num"]
			uci commit firewall
			echo "$num"
			i=$(( $i + 1 ))
		done
	fi
	/etc/init.d/firewall restart 2>/dev/null > /dev/null
	logger "pptp cleared and firewall restarted."
}

_dns_fix() {
	log_line_1="$(awk '/starts/{ print NR; }' /var/log/messages | tail -1)"
	log_line_2="$(awk '/connected/{ print NR; }' /var/log/messages | tail -1)"

	check="$(cat /var/log/messages | awk '{if((NR>'$log_line_1')&&(NR<'$log_line_2')) print}' | grep "primary   DNS")"

	if [ "$check" ]; then
		tun_dns_1="$(cat /var/log/messages | grep 'primary   DNS address' | tail -1 | awk -F]: '{print $2}' | awk '{print $4}')"
		tun_dns_2="$(cat /var/log/messages | grep 'secondary DNS address' | tail -1 | awk -F]: '{print $2}' | awk '{print $4}')"

		if [ "$tun_dns_1" !=  "$tun_dns_2" ]; then
			iptables -t nat -A PREROUTING -i eth0 -p udp --dport 53 -j DNAT --to "$tun_dns_2"
			uci add_list dhcp.@dnsmasq[0].server="$tun_dns_2"
		fi
		iptables -t nat -A PREROUTING -i eth0 -p udp --dport 53 -j DNAT --to "$tun_dns_1"
		uci add_list dhcp.@dnsmasq[0].server="$tun_dns_1"
		uci commit dhcp
		uci $UCI_PATH set sabai.vpn.dns='1'
		logger "DNS for VPN was set."
	else
		uci $UCI_PATH set sabai.vpn.dns='0'
		logger "DNS is default."
	fi
	uci $UCI_PATH commit sabai
	cp -r /etc/config/sabai /configs/sabai
}


_stat(){
	ifconfig > /tmp/check
	if [ ! "$(cat /tmp/check | grep pptp)" ]; then
		uci $UCI_PATH set sabai.vpn.status=Disconnected
		uci $UCI_PATH commit sabai
		cp -r /etc/config/sabai /configs/sabai
		logger "pptp is disconnected."
		_return 1 "PPTP is disconnected."
	else
		uci $UCI_PATH set sabai.vpn.status=Connected
		uci $UCI_PATH commit sabai
		cp -r /etc/config/sabai /configs/sabai

		# [ "$device" = "SabaiOpen" ] && /www/bin/gw.sh vpn_gw

		logger "pptp is connected."
		_return 1 "PPTP is connected."
	fi
}

ls >/dev/null 2>/dev/null

case $act in
	start)  _start   ;;
	stop)   _stop    ;;
	status) _stat    ;;
	dns)    _dns_fix ;;
	clear)  _clear   ;;
esac
