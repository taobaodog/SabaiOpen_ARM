#!/bin/sh                                                                                                               
if ! fw_printenv "update_done" 1>/dev/null 2>&1 &&                                                                      
   ! fw_printenv "revert_enabled" 1>/dev/null 2>&1 ; then                                                               
  fw_setenv update_done 0                                                                                               
  fw_setenv revert_enabled 0                                                                                            
  fw_setenv first_boot 1       

  /sbin/merge_configs.sh                                                                                         
}
else                                                                                                                    
  fw_setenv first_boot 0                                                                                                
fi 
