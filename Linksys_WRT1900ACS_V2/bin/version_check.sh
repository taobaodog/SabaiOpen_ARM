#!/bin/sh                                                                                                               
                                                                                                                        
get_sabai_version() {                                                                                                   
  echo $(uci get sabai.general.prev_version)                                                                            
}                                                                                                                       
                                                                                                                        
get_openwrt_version_build() {                                                                                           
  echo $(cat /etc/sabai/sabaiopen_version_prev)                                                                         
}                                                                                                                       
