#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
# Creates a json file of wan info and dhcp leases

#turn on tor in specific mode
mode=$1

#path to config files
UCI_PATH=""
config_file=sabai
proto=$(uci get sabai.vpn.proto)
device=$(uci get system.@system[0].hostname)
mode_curr=$(uci get sabai.tor.mode)
tor_stat="$(netstat -lpn | grep '/tor$')"

_return(){
	echo "res={ sabai: $1, msg: '$2' };"
	exit 0;
}

_off(){
	if [ ! "$tor_stat" ]; then
		logger "NO TOR is running."
		_return 0 "NO TOR is running."
	fi

	/etc/init.d/tor stop

	uci delete privoxy.privoxy.forward_socks5t
	uci delete privoxy.privoxy.forward
	uci commit privoxy
	/etc/init.d/privoxy restart

	if [ "$mode_curr" = "tun" ]; then
		uci $UCI_PATH set sabai.vpn.proto="none"
		uci $UCI_PATH set sabai.vpn.status="none"
		/www/bin/gw.sh tortun_off
	fi

	uci $UCI_PATH set sabai.tor.mode="off"
	uci $UCI_PATH commit sabai
	cp -r /etc/config/sabai /configs/
	# must be after sabai changing
	/etc/init.d/firewall restart > /dev/null 2> /dev/null

	logger "TOR turned OFF."
	_return 0 "TOR turned OFF."
}

_common_settings(){
	if [ "$device" = "vpna" ]; then
		ipaddr=$(uci get network.wan.ipaddr)
	else
		ipaddr=$(uci get network.lan.ipaddr)
	fi

	# Privoxy port
	_privox_port="8080"

	# Tor's ProxyPort
	_tor_proxy_port="9050"

	uci set privoxy.privoxy.listen_address=":$_privox_port"
	uci set privoxy.privoxy.forward_socks5t="/	127.0.0.1:$_tor_proxy_port	."
	uci add_list privoxy.privoxy.forward="192.168.*.*/	."
	uci add_list privoxy.privoxy.forward="10.*.*.*/	."
	uci add_list privoxy.privoxy.forward="127.*.*.*/	."
	uci commit privoxy
	#	/etc/init.d/privoxy restart
	/www/bin/proxy.sh proxystop
	/www/bin/proxy.sh proxystart
}

_tun() {
	_check_vpn
	if [ "$tor_stat" ] && [ "$mode_curr" == "tun" ]; then
		logger "TOR tunnel is already running."
		_return 0 "TOR tunnel is already running."
	fi

	uci $UCI_PATH set sabai.tor.mode=$mode
	uci $UCI_PATH set sabai.vpn.proto="tor"
	uci $UCI_PATH set sabai.vpn.status="Anonymity"
	uci $UCI_PATH commit sabai
	cp -r /etc/config/sabai /configs/

	if [ "$mode_curr" == "off" ]; then
		_common_settings
	fi

	/www/bin/gw.sh tortun_on

	/etc/init.d/tor start

	logger "TOR tunnel started."
	logger "ALL traffic will be anonymized; HTTP proxy is also available on port 8080."
	_return 0 "Tor tunnel started."
}

_proxy(){
	if [ "$tor_stat" ] && [ "$mode_curr" == "proxy" ]; then
		logger "TOR proxy is already running."
		_return 0 "TOR proxy is already running."
	fi

	if [ "$mode_curr" == "off" ]; then
		_common_settings
	elif [ "$mode_curr" == "tun" ]; then
		uci $UCI_PATH set sabai.vpn.proto="none"
		uci $UCI_PATH set sabai.vpn.status="none"
		# old implementation
		# sed -ni "/iptables -t nat -A PREROUTING ! -d .*\/.* -p .* -j REDIRECT --to-ports/!p" /etc/firewall.user
		/www/bin/gw.sh tortun_off
	fi

	uci $UCI_PATH set sabai.tor.mode=$mode
	uci $UCI_PATH commit sabai
	cp -r /etc/config/sabai /configs/
	/etc/init.d/firewall restart > /dev/null 2> /dev/null

	/etc/init.d/tor start
	logger "TOR proxy started."
	logger "Anonymizing HTTP proxy is available on port 8080."
	_return 0 "Tor proxy started."
}

_check_vpn() {
	ifconfig > /tmp/check
	if [ "$(cat /tmp/check | grep pptp)" ]; then
		/www/bin/pptp.sh stop
	elif [ "$(cat /tmp/check | grep tun)" ]; then
		/www/bin/ovpn.sh stop
	fi
}

_check() {
	if [ "$tor_stat" ]; then
		_return 0 "TOR is running."
	else
		logger "TOR is not running."
	fi
}

case $mode in
	off)	_off	;;
	proxy)	_proxy	;;
	tun)	_tun	;;
	stat)	_check ;;
esac
