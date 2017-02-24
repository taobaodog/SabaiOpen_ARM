#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
UCI_PATH=""
TMP_FILE='/tmp/upgrade/tmp.txt'


_check_update_done(){
	status="$(fw_printenv | grep update_done | awk -F= '{print $2}')"
	if [ "$status" = 1 ]; then
		echo "Upgrade was done successfully!"
	else
		echo "Upgrade was not done. Sorry, something went wrong."
	fi
}

_upgrade(){
	# Signature check
	logger "SABAI:> OS upgrade has been started."
	tar -C /tmp/upgrade -xf /tmp/upgrade/sabai-bundle-secured.tar
	openssl dgst -sha256 < /tmp/upgrade/sabai-bundle.tar > /tmp/upgrade/hash
	openssl rsautl -verify -inkey /etc/sabai/keys/public.pem -keyform PEM -pubin -in /tmp/upgrade/signature > /tmp/upgrade/verified
	cmp -l /tmp/upgrade/verified /tmp/upgrade/hash > "$TMP_FILE"
	if [ -f "$TMP_FILE" ]; then
		OK=$(cat "$TMP_FILE" | head -1)
		if [ "$OK" != "" ]; then
			echo "ERROR 01 - Verification failed. Go away bad guy!"
			logger "ERROR 01 - Verification failed. Go away bad guy!"
			exit 1
		else
			logger "Verification finished with success!"
			fi
	else
			echo "ERROR 02 - Error occured during verification."
			logger "ERROR 02 - Error occured during verification."
	fi

	# Sysupgrade
	fw_setenv update_done 1
	fw_setenv revert_enabled 1

	sysupgrade -n /tmp/upgrade/sabai-bundle.tar >> /var/log/messages
}

case $1 in
	"exec")
		_upgrade
		;;
	"check")
		_check_update_done
		;;
	*)
		fw_setenv update_done 0
		;;
esac
