#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
UCI_PATH=""

action=$1
vartwo=$2
varthree=$3
varfour=$4

_setup(){
	# Create marker for tmp that setup is in progress
	echo "setup" > /tmp/setup
	# Bring in the variables with default of port=1194 and proto=udp
	dhsize=$vartwo
	echo $dhsize
	if [ ! -z $varthree ]; then
		port=$varthree
	else
		port="1194"
	fi
	proto="udp"
	extip=$(php-fcgi /www/php/get_remote_ip.php | grep -E -o "(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)")
	success="true"
	message="OpenVPN server is being setup with $dhsize encryption at $extip : $port with protocol $proto"
	data="none"
	sh /www/bin/ovpnsetup.sh setup $dhsize $port $proto >/dev/null &
	_return
}

_client(){
	clientname=$vartwo
	port=$(uci get sabai.ovpnserver.port)
	proto=$(uci get sabai.ovpnserver.proto)
	dnsip=$(uci get network.wan.dns|awk '{print $1}')
	if [ $dnsip == "" ]; then
		dnsip='8.8.8.8'
	fi
	# Get the external IP
	extip=$(php-fcgi /www/php/get_remote_ip.php | grep -E -o "(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)")
	echo $extip
	if [ ! -f "/etc/easy-rsa/keys/ca.crt" ]; then 
		success="false"
		message="OpenVPN server must be setup first."
		data="none" 
	else
	build-key --batch $clientname
	cat /etc/sabai/openvpn/clientheader > /etc/sabai/openvpn/clients/$clientname.ovpn
	echo -e "<ca>" >> /etc/sabai/openvpn/clients/$clientname.ovpn
	sed -ne '/-BEGIN CERTIFICATE-/,/-END CERTIFICATE-/p' /etc/easy-rsa/keys/ca.crt >> /etc/sabai/openvpn/clients/$clientname.ovpn
	echo -e "</ca>\n<key>" >> /etc/sabai/openvpn/clients/$clientname.ovpn
	sed -ne '/-BEGIN PRIVATE KEY-/,/-END PRIVATE KEY-/p' /etc/easy-rsa/keys/$clientname.key >> /etc/sabai/openvpn/clients/$clientname.ovpn
	echo -e "</key>\n<cert>" >> /etc/sabai/openvpn/clients/$clientname.ovpn
	sed -ne '/-BEGIN CERTIFICATE-/,/-END CERTIFICATE-/p' /etc/easy-rsa/keys/$clientname.crt >> /etc/sabai/openvpn/clients/$clientname.ovpn
	echo -e "</cert>" >> /etc/sabai/openvpn/clients/$clientname.ovpn
	sed -i "s/EXTIP/$extip/g" /etc/sabai/openvpn/clients/$clientname.ovpn
 	sed -i "s/PORT/$port/g" /etc/sabai/openvpn/clients/$clientname.ovpn
	sed -i "s/PROTO/$proto/g" /etc/sabai/openvpn/clients/$clientname.ovpn
	sed -i "s/DNSIP/$dnsip/g" /etc/sabai/openvpn/clients/$clientname.ovpn
	fi
	logger "OpenVPN Client $clientname setup."
	success="true"
	message="OpenVPN client $clientname has been setup"
	data="/etc/sabai/openvpn/clients/$clientname.ovpn"
	_return
}

_clientoff(){
	clientname=$vartwo
	cd /etc/easy-rsa
	revoke-full $clientname
        success="true"
        message="OpenVPN client $clientname has been revoked"
        data="none"
	_return
}


_clear(){
        test=$(ps | grep -v grep | grep -ic sabaivpn)
        if [ $test -eq 1 ]; then
	        /etc/init.d/openvpn stop
        	/etc/init.d/openvpn disable
        fi
	source /etc/easy-rsa/vars; clean-all
	directory="/etc/sabai/openvpn/clients"
	if [ -d "$directory" ]; then
		rm -r /etc/sabai/openvpn/clients 
	fi
	rm -f /tmp/setup
	rm -f /etc/openvpn/server.conf
	cd /etc/sabai/openvpn
	logger "OpenVPN Server settings cleared."
	success="true"
	message="OpenVPN server and clients have been cleared"
	data="reload"
	_return
}

_start(){
	test=$(ps | grep -v grep | grep -ic sabaivpn)
	if [ $test -eq 1 ]; then
		logger "Attempted to start OpenVPN server when already running"
		success="false"
		message="OpenVPN server already running"
		data="none"
		_return
	fi

	if [ ! -f "/etc/easy-rsa/keys/ca.crt" ]; then 
		success="false"
		message="OpenVPN server not yet setup"
		data="none"
	else 
		/etc/init.d/openvpn start
		/etc/init.d/openvpn enable
		sleep 5
	fi
	
	test=$(ps | grep -v grep | grep -ic sabaivpn)
	if [ $test -eq 0 ]; then
		success="false"
		message="OpenVPN server did not start"
		data="none"
	else
		success="true"
		message="'OpenVPN server started"
		data="none"
	fi
	_return
}

_stop(){
	test=$(ps | grep -v grep | grep -ic sabaivpn)
	if [ $test -eq 0 ]; then
	0	logger "Tried to stop OpenVPN server when already stopped"
		success="false"
		message="OpenVPN server was already stopped"
		data="none"
		_return
	fi

	if [ ! -f "/etc/easy-rsa/keys/ca.crt" ]; then 
		success="false"
		message="OpenVPN server not yet setup"
                echo "{ \"sabai\": $success, \"msg\": \"$message\" , \"data\": $data }"
                exit 0;
		data="none"
	else 
		/etc/init.d/openvpn stop
		/etc/init.d/openvpn disable
		sleep 5
	fi
	
	test=$(ps | grep -v grep | grep -ic sabaivpn)
	if [ $test -eq 0 ]; then
		success="true"
		message="OpenVPN server stopped"
		data="none"
	else
		success="false"
		message="OpenVPN server did not stop"
		data="none"
	fi
	_return
}

_check(){
cat /etc/easy-rsa/keys/index.txt | grep fred | awk '{print $1}'
	if [ -e /tmp/setup ]; then
		success="false"
		message="OpenVPN server is being setup"
		data="setup"
		_return
	fi
	if [ ! -f "/etc/easy-rsa/keys/ca.crt" ]; then
                success="false"
                message="OpenVPN server is not setup"
                data="none"
                _return
        fi
	test=$(ps | grep -v grep | grep -ic sabaivpn)
	if [ $test -eq 1 ]; then
		success="true"
		message="OpenVPN server is running"
	else
		success="false"
		message="OpenVPN server is stopped"
		data="stop"
		_return
	fi

	#Get client names and whether they are active or not
	directory="/etc/sabai/openvpn/clients"
	rm -f /tmp/clientdata
	if [ -d "$directory" ] && [ "$(ls -A $directory)" ]; then
		ls /etc/sabai/openvpn/clients/ | sed 's/\.[^.]*$//' > /tmp/clients 
		rm /tmp/clientdata
		echo -n "{ \"clients\":[" >> /tmp/clientdata
		while read c; do
  		echo -n "{\"name\":\"$c\",\"status\":\"$(sudo cat /etc/easy-rsa/keys/index.txt | grep CN=$c\/ | cut -c 1)\",\"data\":\"none\"}," >> /tmp/clientdata
		done < /tmp/clients
		sed -i '$ s/.$//' /tmp/clientdata
		echo -n "]}" >> /tmp/clientdata
		sed -i 's/\"V\"/true/g' /tmp/clientdata
                sed -i 's/\"R\"/false/g' /tmp/clientdata
		data=$(cat /tmp/clientdata)
		echo "{ \"sabai\": $success, \"msg\": \"$message\" , \"data\": $data }"
                exit 0;
	else
	data="noclient"
	fi
	_return
}

_return(){
	echo "{ \"sabai\": $success, \"msg\": \"$message\" , \"data\": \"$data\" }"
	exit 0;
}

case $action in
	setup)  _setup  ;;
	clear)  _clear  ;;
	client)	_client	;;
	clientoff) _clientoff ;;
	start)	_start	;;
	stop)	_stop	;;
	check)	_check	;;
esac
