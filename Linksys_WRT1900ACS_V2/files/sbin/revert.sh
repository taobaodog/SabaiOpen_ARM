#!/bin/sh                                                                                                               

getenv() {                                                                                                              
  echo $(fw_printenv $1 2>/dev/null | cut -d"=" -f2)                                                                    
}                                                                                                                       
                                                                                                                        
if [[ $(getenv revert_enabled) -ne 1 ]] ; then                                                                          
  exit 0                                                                                                                
fi

if [[ $(getenv boot_part) -eq 1 ]] ; then                                                                               
  prev_part_num=2                                                                                                 
else                                                                                                                    
  prev_part_num=1                                                                                                 
fi                                                                                                                       

sabai_ver=$(cat /tmp/syscfg/part_info | grep boot_part$prev_part_num.sabai_version | cut -d"=" -f2)                                                                                          
version_build=$(cat /tmp/syscfg/part_info | grep boot_part$prev_part_num.version_build | cut -d"=" -f2)

echo "Your system will be reverted to version $sabai_ver / $version_build"                                              
echo "Continue? [y/n]"                                                                                                  
                                                                                                                        
read confirm                                                                                                            
                                                                                                                        
if [[ $confirm != "y" ]] ; then                                                                                         
  exit 0                                                                                                                
fi                                                                                                                      
                                                                                                                        
fw_setenv boot_part $prev_part_num
                                                                                                                        
reboot                                                                                                                  
