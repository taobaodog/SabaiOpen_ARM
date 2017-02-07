#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
_setrule(){
    status=$1
    destination=$2

    uci del firewall.dmz 2> /dev/null
    if [ "$status" == "on" -a "$destination" != "" ]; then
        uci set firewall.dmz=redirect
        uci set firewall.dmz.src='wan'
        uci set firewall.dmz.proto='tcpudp'
        uci set firewall.dmz.dest='lan'
        uci set firewall.dmz.dest_ip="$destination"
    fi
    uci commit firewall
}

_update(){
    status=$(uci get sabai-new.dmz.status)
    destination=$(uci get sabai-new.dmz.destination)

    _setrule $status $destination

    echo "firewall" >> /tmp/.restart_services
    echo "res={ sabai: 1, msg: 'DMZ settings applied' };"
}

_restart(){
    status=$(uci get sabai.dmz.status)
    destination=$(uci get sabai.dmz.destination)

    _set $status $destination
}

_set(){
    status=$1
    destination=$2

    _setrule $status $destination

    logger "Full passthrough DMZ to $destination enabled."
    logger "Restarting firewall"
    /etc/init.d/firewall restart 2>/dev/null >/dev/null
    echo "res={ sabai: 1, msg: 'DMZ settings applied' };"
}

# Send completion message back to UI

case $1 in
    update) _update ;;
    restart) _restart ;;
    set) _set $2 $3;;
    *) _set $1 $2;;
esac
