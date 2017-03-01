#!/bin/sh

BACKUP_DIR="/tmp/syscfg/backup"
CONFIG_NAME="sabai"
BACKED_CONFIG_FILE="$BACKUP_DIR/$CONFIG_NAME"

if ! ls $BACKED_CONFIG ; then
  exit 0 
fi

params_to_restore="
lan.ipaddr
lan.netmask
wan.proto
wan.dns
wan.mtu
wan.macaddr
wan.factory_mac
wan.accelerator
wan.ipaddr
wan.netmask
wan.gateway
dhcp.leasetime
dhcp.start
dhcp.limit
time.location
time.timezone
time.servers
wlradio0.mode
wlradio0.wpa_psk
wlradio0.encryption
wlradio0.wpa_encryption
wlradio0.wpa_group_rekey
wlradio0.wepkeys
wlradio0.auto
wlradio0.freq
wlradio0.width
wlradio0.channel_freq
wlradio0.channels_qty
wlradio0.ssid
wlradio1.mode
wlradio1.ssid
wlradio1.wpa_psk
wlradio1.encryption
wlradio1.wpa_encryption
wlradio1.channel_freq
dmz.destination
dmz.status
upnp.intmin
upnp.intmax
upnp.extmin
upnp.secure
upnp.extmax
upnp.enable
upnp.clean
upnp.natpmp
pf.json
proxy.status
tor.proto
tor.ipaddr
tor.netmask
tor.network
tor.mode
"

set -- $params_to_restore
while [ -n "$1" ] ; do
  uci set $CONFIG_NAME.$1="$(uci -c $BACKUP_DIR get $CONFIG_NAME.$1)"
  shift
done

uci commit $CONFIG_NAME
/etc/init.d/network restart

rm $BACKED_CONFIG_FILE

