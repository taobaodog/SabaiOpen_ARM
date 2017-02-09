#!/bin/sh

PATCHES_DIR="../openwrt/patches"
OPENWRT_DIR="../openwrt"
SABAI_DIR="../SabaiOpen_ARM"
FILES_DIR="files"
_make() {
	if [ -z "$SABAI_KEYS" ]; then
		echo " \033[0;31m Please export SABAI_KEYS variable."
		echo " export SABAI_KEYS=/home/user/SabaiOpen/keys/"
		echo " BUILD IS FAILED! \033[0m"
		exit 1
	fi

	if [ ! -d "$FILES_DIR"  ]; then
		mkdir "$FILES_DIR"
	fi

	cp -r "$SABAI_DIR"/www "$FILES_DIR"
	cp -r "$SABAI_DIR"/etc "$FILES_DIR"


	echo "SOA 2: \033[1;32m Build is starting. \033[0m"
	make V=99
}


if [ ! -d "$PATCHES_DIR"  ]; then
	mkdir "$PATCHES_DIR"
	
	cp -r ./patches/* "$PATCHES_DIR"
	
	cd "$OPENWRT_DIR"
	git apply "$PATCHES_DIR/0001-SabaiOpen-build-support-linksys.patch"
	
	echo "SOA 1: \033[0;32m Patches has been applied.\033[0m"
	_make
else
	echo "SOA 1: \033[0;31m Patches has been already applied.\033[0m"
	cd "$OPENWRT_DIR"
	_make
fi

echo "SOA 3: \033[1;32m Build is ready. \033[0m"
