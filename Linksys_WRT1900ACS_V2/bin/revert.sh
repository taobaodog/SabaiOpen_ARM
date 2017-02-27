#!/bin/sh                                                                                                               
                                                                                                                        
source version_check.sh                                                                                                 
                                                                                                                        
getenv() {                                                                                                              
  echo $(fw_printenv $1 2>/dev/null | cut -d"=" -f2)                                                                    
}                                                                                                                       
                                                                                                                        
if [[ $(getenv revert_enabled) -ne 1 ]] ; then                                                                          
  exit 0                                                                                                                
fi                                                                                                                      
                                                                                                                        
sabai_ver=$(get_sabai_version)                                                                                          
version_build=$(get_openwrt_version_build)                                                                              
                                                                                                                        
echo "Your system will be reverted to version $sabai_ver / $version_build"                                              
echo "Continue? [y/n]"                                                                                                  
                                                                                                                        
read confirm                                                                                                            
                                                                                                                        
if [[ $confirm != "y" ]] ; then                                                                                         
  exit 0                                                                                                                
fi                                                                                                                      
                                                                                                                        
if [[ $(getenv boot_part) -eq 1 ]] ; then                                                                               
  fw_setenv boot_part 2                                                                                                 
else                                                                                                                    
  fw_setenv boot_part 1                                                                                                 
fi                                                                                                                      
                                                                                                                        
reboot                                                                                                                  
