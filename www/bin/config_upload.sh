#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology

# Script is used for restoring procedure.
# |
# /etc/config/sabai is a link to /configs/sabai 
# /configs - mounting point for /dev/sda6
# all configurations are located on /dev/sda6
#

#apply settings from new config

#section 'lan'         -> /www/bin/lan.sh             --> network restart
#section 'dhcp'        -> /www/bin/dhcp.sh(aaData)    --> network/firewall restart
#section 'wan'         -> /www/bin/wan.sh             --> network restart
#section 'vpn'         -> /www/bin/ovpn.sh pptp.sh    --> openvpn start/enable; network/firewall restart
#section 'general'     -> none
#section 'dns'         -> /www/bin/wan.sh             --> network restart
#section 'time'        -> /www/bin/time.sh            --> ntpd restart
#section 'firewall'    -> /www/bin/firewall.sh        --> firewall restart
#section 'dmz'         -> /www/bin/dmz.sh             --> firewall restart
#section 'upnp'        -> /www/bin/upnp.sh            --> firewall restart
#section 'pf'          -> /www/bin/portfowarding.sh   --> firewall restart
#section 'wlradio'     -> /www/bin/wl.sh              --> wifi up

SABAI_CONFIG=/etc/config/sabai
RESTORED_CONFIG=$1
cp $RESTORED_CONFIG /etc/config/sabai-new

#presence check of ovpn configuration
prefix_addr="/configs/"
prefix_file="backup_"

[ -z "${RESTORED_CONFIG##*$prefix_file*}" ] && conf_name=${RESTORED_CONFIG#$prefix_addr$prefix_file} || conf_name=${RESTORED_CONFIG#$prefix_addr}
ovpn_filename="/configs/ovpn_backup/ovpn.filename_$conf_name"
ovpn_config="/configs/ovpn_backup/ovpn.config_$conf_name"
ovpn_msg="/configs/ovpn_backup/ovpn.msg_$conf_name"

CONFIG_SECTIONS=$(cat $SABAI_CONFIG | grep config | awk '{print $3}' | sed ':a;N;$!ba;s/\n/ /g' | tr -d "'")
echo "CONFIG_SECTIONS=$CONFIG_SECTIONS"

for i in $CONFIG_SECTIONS; do
		logger "section: $i" 
		uci show sabai.$i | awk -F. '{$1=""; print $0}' > /tmp/$i.orig
		uci show sabai-new.$i | awk -F. '{$1=""; print $0}' > /tmp/$i.new
		diff="$(cmp /tmp/$i.orig /tmp/$i.new)"
		if [ "$i" = "vpn" ] && [ $? = 0 ]; then
			[ -e "/etc/sabai/openvpn/ovpn.current" ] && cmp /etc/sabai/openvpn/ovpn.current $ovpn_config
		fi
		if [ "$diff" ] || [ $? != 0 ]; then
			logger "config $i differ"
			case "$i" in
			lan) 
				logger "in lan" 
				/www/bin/lan.sh update
			;;
			dhcp) 
				logger "in dhcp" 
				/www/bin/lan.sh update
				/www/bin/dhcp.sh update	
			;;
			wan) 
				logger "in wan"
				/www/bin/wan.sh update
				/www/bin/lan.sh update
			;;
			tor)
				logger "in TOR"
			;;
			vpn) 
				logger "in vpn" 
				old_proto=$(uci get sabai.vpn.proto)
				proto=$(uci get sabai-new.vpn.proto)
				case "$old_proto" in
					ovpn)
						/www/bin/ovpn.sh stop
					;;
					pptp)
						/www/bin/pptp.sh stop update
					;;
					tor)
						/www/bin/tor.sh off
					;;
					none)
						logger "No VPN is running before update."
					;;
				esac
				if [ -e "/etc/sabai/openvpn/ovpn.current" ]; then
					rm -r /etc/sabai/openvpn/ovpn.filename
					rm -r /etc/sabai/openvpn/ovpn.current
					rm -r /etc/sabai/openvpn/ovpn
				fi				
				case "$proto" in
					ovpn)
						cp -fR $ovpn_filename /etc/sabai/openvpn/ovpn.filename
						cp -fR $ovpn_config /etc/sabai/openvpn/ovpn.current
						cp -fR $ovpn_msg /etc/sabai/openvpn/ovpn
						uci set openvpn.sabai.filename=$(cat /etc/sabai/openvpn/ovpn.filename)
						uci commit openvpn
						/www/bin/ovpn.sh start
					;;
					pptp)
						/www/bin/pptp.sh start update
					;;
					tor)
						echo "in tor" >> /tmp/.restart_service
						logger "TOR will be restarted."
					;;
					none)
						/www/bin/ovpn.sh stop
						/www/bin/pptp.sh stop update
						/www/bin/tor.sh off
						logger "No VPN will be started after update."
					;;
				esac
				if [ "$proto" != "ovpn" ] && [ -e "$ovpn_config" ]; then
					cp $ovpn_filename /etc/sabai/openvpn/ovpn.filename
					cp $ovpn_config /etc/sabai/openvpn/ovpn.current
					cp $ovpn_msg /etc/sabai/openvpn/ovpn
					uci set openvpn.sabai.filename=$(cat /etc/sabai/openvpn/ovpn.filename)
					uci commit openvpn
				fi
				echo "vpn" >> /tmp/.etc_service
			;;
			dns) 
				logger "in dns"
				/www/bin/wan.sh update
			;;
			time) 
				logger "in time"
				/www/bin/time.sh update
			;;
			firewall) 
				logger "in firewall"
				/www/bin/firewall.sh update
 			;;
			dmz)
				logger "in dmz"
				/www/bin/dmz.sh update
				logger "dmz" >> /tmp/.etc_service
			;;
			upnp)
				logger "in upnp"
				/www/bin/upnp.sh update
			;;
			pf)
				logger "in pf"
				/www/bin/portforwarding.sh update
			;;
			wlradio0)
				logger "in wlradio0"
				/www/bin/wl.sh update 0
			;;
			wlradio1)
				logger "in wlradio1"
				/www/bin/wl.sh update 1
			;;
			loopback)
				logger "loopback" >> /tmp/.etc_services
			;;
			general)
				logger "general" >> /tmp/.etc_service
			;;
			proxy)
				logger "proxy" >> /tmp/.etc_service
			;;
			dhcphost)
				logger "dhcphost" >> /tmp/.etc_service
			;;
			esac
		fi
#	rm /tmp/$i.orig /tmp/$i.new 
done

if [ ! -e /tmp/.restart_services ] && [ ! -e /tmp/.etc_service ]; then
	logger "Nothing to update in config files" 
	exit 0
elif [ ! -e /tmp/.restart_services ] && [ -e /tmp/.etc_service ]; then
	logger "Copying new config . . ."
else
	SERVICES=`sort -u /tmp/.restart_services`
	logger "SERVICES TO RESTART : $SERVICES"
fi


#restart affected services
for i in $SERVICES; do
        logger "checking section $i"
        logger "restart $i service to apply new config settings"
        if [ $i = "time" ]; then
	        /etc/init.d/ntpd restart
	elif [ $i = "network" ]; then
                /etc/init.d/$i restart
		ifup wan
		ifup wan
        else
        	echo "service $i restart"
		/etc/init.d/$i restart
        fi
done

mv /etc/config/sabai-new /etc/config/sabai
cp -r /etc/config/sabai /etc/configs
rm -f /tmp/.restart_services

#restart tor 
[ $(echo $SERVICES | grep tor) ] && /www/bin/tor.sh tun
