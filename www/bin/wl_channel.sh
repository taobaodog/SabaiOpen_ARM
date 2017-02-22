#!/bin/ash                                                                                            
# Sabai Technology - Apache v2 licence                                                                
# Copyright 2016 Sabai Technology 

UCI_PATH=""

device="$(uci get system.@system[0].hostname)"
freq="$(uci get sabai.wlradio0.freq)"

if [ $device = "SabaiOpen" ]; then
	# get all channels on Wifi device
	channel_all=$(iw list | grep "\[" | awk '{print $4}' | cut -d "[" -f2 | cut -d "]" -f1 | tail -n 1)

	# get only available Wifi device                                                              
	channel="$(iw list | grep "\[" | grep "disabled" | awk '{print $4}' | cut -d "[" -f2 | cut -d "]" -f1 | head -1)"
	channel_aval="$(( channel-1 ))"

elif [ $device = "vpna" ] && [ $freq = "2" ]; then
	#get all channels for 2.4 GHz
	iw list > /tmp/wl
	sed -i '/Band\ 2\:/q' /tmp/wl
	channel_all_24=$(cat /tmp/wl | grep "\[" | awk '{print $4}' | cut -d "[" -f2 | cut -d "]" -f1 | tail -n 1)	
	# get only available channels for 2.4
	channel_24="$(cat /tmp/wl | grep "\[" | grep "disabled" | awk '{print $4}' | cut -d "[" -f2 | cut -d "]" -f1 | head -1)"
	channel_aval="$(( channel_24-1 ))"
elif [ $device = "vpna" ] && [ $freq = "5" ]; then
	#get all channels for 5 GHz
	iw list > /tmp/wl
	sed -i '1,/Band\ 2\:/d' /tmp/wl
	channel_all_58=$(cat /tmp/wl | grep "\[" | awk '{print $4}' | cut -d "[" -f2 | cut -d "]" -f1 > /etc/wl_channels_58)
	channel_disabled_58=$(cat /tmp/wl | grep "\[" | grep "disabled" | awk '{print $4}' | cut -d "[" -f2 | cut -d "]" -f1 > /tmp/wl_disabled_58)
	while read -r line_rm
	do
		while read -r line
		do
			if [ "$line_rm" = "$line" ]; then
				sed -i '/'$line_rm'/d' /etc/wl_channels_58
			else
				echo -e "\n" > /dev/null
			fi
		done < /etc/wl_channels_58
	done < /tmp/wl_disabled_58
	channel_aval="$(wc -l /etc/wl_channels_58 | awk '{print substr ($0, 0, 2)}')"
	#channel_aval="$(cat /etc/wl_channels_58 | tr '\n' ' ' | sed 's/.$//')"
	rm /tmp/wl*
else
	logger "ERROR: Undefined device! Please check your configuration."
fi

# set number of Wifi channels to sabai config
# set current channel
channel_curr=$(iw dev wlan0 info | grep "channel" | awk '{print $2}')
uci $UCI_PATH set sabai.wlradio0.channel_freq="$channel_curr"
uci $UCI_PATH set sabai.wlradio0.channels_qty="$channel_aval"
uci $UCI_PATH commit sabai
cp -r /etc/config/sabai /configs/
	
