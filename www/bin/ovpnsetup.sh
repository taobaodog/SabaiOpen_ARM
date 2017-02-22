	#!/bin/ash
	# Sabai Technology - Apache v2 licence
	# Copyright 2016 Sabai Technology
	UCI_PATH=""

	action=$1
	dhsize=$2
	port=$3
	proto=$4

	# Get the external IP
	extip=$(php-fcgi /www/php/get_remote_ip.php | grep -E -o "(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)")
	
	# Apply the diffie hellman key size
	sed -i "s/KEY_SIZE=[0-9][0-9][0-9][0-9]/KEY_SIZE=$dhsize/g" /etc/easy-rsa/vars

	# Clear any existing OpenVPN configuration
	source /etc/easy-rsa/vars; clean-all
	test=$(ps | grep -v grep | grep -ic sabaivpn)
	if [ $test -eq 1 ]; then
        /etc/init.d/openvpn disable
        /etc/init.d/openvpn stop
	fi

	# Create new OpenVPN server setup based on variables recieved
	build-ca --batch
	build-dh 
	cd /etc/easy-rsa; . ./vars; build-key-server --batch sabai-server
	cp /etc/easy-rsa/keys/ca.crt /etc/easy-rsa/keys/sabai-server.* /etc/easy-rsa/keys/dh*.* /etc/openvpn
	mkdir -p /etc/sabai/openvpn/clients
	touch /etc/sabai/openvpn/clients/dummy
	rm -r /etc/sabai/openvpn/clients/*

	#setup VPN interface
	uci set network.vpn0=interface
	uci set network.vpn0.ifname=tun0 #If you wish to use a server-bridge config, replace the tun0 with tap0
	uci set network.vpn0.proto=none
	uci set network.vpn0.auto=1
	uci commit network
	/etc/init.d/network reload
	sleep 3

	# Setup Firewall rule if it hasn't yet been setup
	test=$(cat /etc/config/firewall | grep -ic OpenVPN)
	if [ $test -eq 0 ]; then
	uci add firewall rule
	uci set firewall.@rule[-1].name=Allow-OpenVPN-Inbound
	uci set firewall.@rule[-1].target=ACCEPT
	uci set firewall.@rule[-1].src=*
	uci set firewall.@rule[-1].proto="$proto"
	uci set firewall.@rule[-1].dest_port="$port"
	# Allow VPN Routing
	uci add firewall zone
	uci set firewall.@zone[-1].name=vpn
	uci set firewall.@zone[-1].input=ACCEPT
	uci set firewall.@zone[-1].forward=REJECT
	uci set firewall.@zone[-1].output=ACCEPT
	uci set firewall.@zone[-1].network=vpn0
	uci add firewall forwarding
	uci set firewall.@forwarding[-1].src='vpn'
	uci set firewall.@forwarding[-1].dest='wan'
	uci commit firewall
	/etc/init.d/firewall restart
	sleep 3
	fi

	# Setup the OpenVPN configuration file
	cp /etc/sabai/openvpn/server.conf /etc/openvpn/server.conf
	echo > /etc/config/openvpn # clear the openvpn uci config
	uci set openvpn.sabaivpn=openvpn
	uci set openvpn.sabaivpn.enabled=1
	uci set openvpn.sabaivpn.config='/etc/openvpn/server.conf'
	sed -i "s/dh.....pem/dh$dhsize.pem/" /etc/openvpn/server.conf
	sed -i "s/port\ ..../port\ $port/" /etc/openvpn/server.conf
	sed -i "s/proto\ .../proto\ $proto/" /etc/openvpn/server.conf
	uci add sabai ovpnserver
	uci commit
	uci set sabai.ovpnserver.proto=$proto
	uci set sabai.ovpnserver.port=$port
	uci commit
	/etc/init.d/openvpn enable
	/etc/init.d/openvpn start

	# Prepare return messages and return
	sleep 15
	test=$(ps | grep -v grep | grep -ic sabaivpn)
	if [ $test -eq 1 ]; then
		success="true"
		message="OpenVPN server is running with $dhsize encryption at $extip : $port with protocol $proto"
		data="none"
		rm -f /tmp/setup
	else
		success="false"
		message="OpenVPN server could not be configured properly."
		data="none"
		rm -f /tmp/setup
	fi
		echo "res={ \"sabai\": $success, \"msg\": \"$message\" , \"data\": $data };"
