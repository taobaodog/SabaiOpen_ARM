#!/bin/ash
# Sabai Technology - Apache v2 licence
# Copyright 2016 Sabai Technology
UCI_PATH=""

jsData=$(cat /tmp/tablejs)
#save table as single line json
uci $UCI_PATH set sabai.pf.tablejs="$jsData"
uci $UCI_PATH commit sabai

# Send completion message back to UI
echo "res={ sabai: 1, msg: 'Table Fixed' };"
