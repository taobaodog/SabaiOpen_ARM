#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
UCI_PATH=""
TMP_FILE='/tmp/upgrade/tmp.txt'

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
sysupgrade -v -n /tmp/upgrade/sabai-bundle.tar >> /var/log/messages

