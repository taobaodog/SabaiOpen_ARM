#!/bin/sh

if ! ls /tmp/syscfg/prev_config ; then
  exit 1
fi

#TODO merge required settings from prev config file

#rm /tmp/syscfg/prev/config
