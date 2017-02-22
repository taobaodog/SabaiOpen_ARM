#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
#this script developed to set three variables to be
#on or off according to user input
#As well as specify the start and end of the internal and external ports

#Used Variables

action=$1

if [ "$action" = "update" ]; then
	config_file=sabai-new
else
	config_file=sabai
fi

enable=$(uci get $config_file.upnp.enable);
natpmp=$(uci get $config_file.upnp.natpmp);
clean=$(uci get $config_file.upnp.clean);
secure=$(uci get $config_file.upnp.secure);
intmin=$(uci get $config_file.upnp.intmin);
intmax=$(uci get $config_file.upnp.intmax);
intrange="$intmin-$intmax"
extmin=$(uci get $config_file.upnp.extmin);
extmax=$(uci get $config_file.upnp.extmax);
extrange="$extmin-$extmax"


#Script Function
if [ "$enable" = "on" ]; then
	uci set upnpd.config.enable_upnp=1
	uci set upnpd.@perm_rule[0].int_ports=$intrange
	uci set upnpd.@perm_rule[0].ext_ports=$extrange
else
	uci set upnpd.enable_upnp=0
fi

if [ "$natpmp" = "on" ]; then
	uci set upnpd.config.enable_natpmp=1
else
	uci set upnpd.config.enable_natpmp=0
fi

if [ "$clean" = "on" ]; then
	uci set upnpd.config.clean_ruleset_interval=600
else
	uci set upnpd.config.clean_ruleset_interval=0
fi


if [ "$secure" = "on" ]; then
	uci set upnpd.config.secure_mode=1
else
	uci set upnpd.config.secure_mode=0
fi


uci commit upnpd

if [ "$action" = "update" ]; then
	echo "firewall" >> /tmp/.restart_services
else
	/etc/init.d/miniupnpd restart
	/etc/init.d/firewall restart
	logger "upnp script run and firewall restarted"
fi

ls >/dev/null 2>/dev/null
