#!/bin/ash
DEVICE=$(dmesg | grep "Command line" | awk -F: '{print $2}' | awk '{print $2}' | awk -F/ '{print $3}')

logger "Filesystem check has been started."
while true; do
	ERR=$(dmesg | grep "Remounting filesystem read-only")
	if [ -z "$ERR" ]; then
		sleep 15
	else
		logger "Read-only ERROR was detected."
		fsck.ext4 -y /dev/$DEVICE
		reboot
		break
	fi
done
