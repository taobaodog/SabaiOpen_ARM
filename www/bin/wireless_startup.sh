#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology

# act=acc if configured device is VPNA
act="$1" 
dev="$(uci get system.@system[0].hostname)"

# remove any prior wireless configuration
rm -f /etc/config/wireless

# reset the wireless configuration based on the new card
wifi detect > /etc/config/wireless

# setting wifi configurations
#uci set wireless.@wifi-device[0].disabled=0; uci commit wireless; wifi
# enabling radio0
[ -n "$(uci get wireless.@wifi-device[0].disabled)" ] && uci set wireless.@wifi-device[0].disabled=0
uci set wireless.@wifi-iface[0].ifname="wlan0"
uci set wireless.@wifi-iface[0].macaddr="$(iw dev | grep addr | awk '{print $2}')"

# Setting country code
uci set wireless.@wifi-device[0].country="US"
uci commit wireless

# commit the current sabai wireless settings
# wlradio0
sh /www/bin/wl.sh $act 0
logger "Wireless configurations for wlan0 were set."

if [ "$dev" = "SabaiOpen" ]; then 
	# wlradio1 - guest ap
	uci add wireless wifi-iface
	uci commit wireless
	sh /www/bin/wl.sh $act 1
	logger "Wireless configurations for wlan1 were set."
fi

sleep 15

[ -z "$(ifconfig | grep wlan0)" ] && [ "$(uci get sabai.wlradio0.mode)" != "off" ] && logger "ERROR WIFI: wlradio0 configurations were corrupted."
[ -z "$(ifconfig | grep wlan1)" ] && [ "$(uci get sabai.wlradio1.mode)" != "off" ] && logger "ERROR WIFI: wlradio1 configurations were corrupted."
#log the finish
logger "Wireless configuration completed."
