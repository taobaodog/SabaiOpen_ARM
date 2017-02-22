<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
  $url = "/index.php?panel=wireless&section=radio";
  header( "Location: $url" );     
}
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
<div class='pageTitle'>
  <input id='helpBtn' name='helpBtn' class='helpBtn' title='Help' style="background-image: url('libs/img/help.png')"></input>
  Wireless: Radio
</div>

<!-- WL0 content -->
<form id="fe">
  <input type='hidden' id='form_wl0' name='form_wl0' value='wl0'>
  <div class='controlBox'><span class='controlBoxTitle'>WL0</span>
    <div class='controlBoxContent controlTable smallwidth' id='wl_wl0'>

      <div class ='form-group' style='margin-bottom: 5px;'>
        <label class='col-md-3 col-lg-2 col-sm-4' for='wl_mode'>Mode</label>
        <select class='col-md-4 col-lg-4 col-sm-4' id='wl_mode' name='wl_mode' class='radioSwitchElement'>
          <option value='off'>Off</option>
          <option value='ap'>Wireless Server</option>
        </select>
      </div>


      <div class ='form-group' style='margin-bottom: 5px;'>
        <label class='col-md-3 col-lg-2 col-sm-4' for='wl_ssid'>SSID</label>
        <div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
          <input id='wl_ssid' name='wl_ssid' type='text' class='form-control'>
          <label  id='wl_ssidLabel' class='errorLabel'></label>
        </div>
      </div>

      <div class ='form-group' style='margin-bottom: 5px;'>
        <label class='col-md-3 col-lg-2 col-sm-4' for='channel_mode'>Channel mode</label>
        <select class='col-md-4 col-lg-4 col-sm-4' id='channel_mode' name='channel_mode' class='radioSwitchElement'>
          <option value='off'>Manual</option>
          <option value='auto'>Auto</option>
        </select>
      </div>

      <div class ='form-group channel control' style='height: 39px; margin-bottom: 5px;' >
        <label class='col-md-3 col-lg-2 col-sm-4' for='wl_channel_msg'>Channel</label>
        <div class='input-group input-group-lg-4 input-group-md-4 input-group-sm-4'>
          <input id='wl_channel_msg' name='wl_channel_msg' type='text' disabled='true' class='form-control col-md-4 col-lg-4 col-sm-4 channel_mode auto_on'>
          <input id='wl_channel' name='wl_channel' type='text' class='form-control channel_mode auto_off'>
          <label  id='wl_channelLabel' class='errorLabel channel_mode auto_off'></label>
        </div>
      </div>

      <div class ='form-group' style='margin-bottom: 5px;'>
        <label class='col-md-3 col-lg-2 col-sm-4' for='channel_width'>Channel width</label>
        <select class='col-md-4 col-lg-4 col-sm-4' id='channel_width' name='channel_width' class='radioSwitchElement'>
          <option value='20'>HT20</option>
          <option value='40'>HT40</option>
        </select>
      </div>

      <div class ='form-group' style='margin-bottom: 5px;'>
        <label class='col-md-2 col-lg-2 col-sm-2' for='wl_encryption'>Encryption</label>
        <select class='col-md-4 col-lg-4 col-sm-4' id='wl_encryption' name='wl_encryption' class='radioSwitchElement'>
          <option value='none'>None</option>
          <option value='wep'>WEP</option>
          <option value='psk'>WPA</option>
          <option value='psk2'>WPA2</option>
          <option value='mixed-psk'>WPA/WPA2</option>
        </select>
      </div>    

      <div class ='form-group wl_encryption wl_encryption-wep' style='margin-bottom: 5px;'>
        <br>
        <label class='col-md-2 col-lg-2 col-sm-2' for='wl_wep_keys'>WEP Keys</label>
        <div>
          <ul id='wl_wep_keys' class='col-md-4 col-lg-4 col-sm-4' style='padding: 0px;'>
          </ul>
        </div>
      </div>

      <div class ='form-group wl_encryption wl_encryption-psk wl_encryption-psk2 wl_encryption-mixed-psk' style='margin-bottom: 5px;'>
        <br>
        <div class ='form-group'>
          <label class='col-md-3 col-lg-2 col-sm-4' for='wl_wpa_encryption'>WPA Encryption</label>
          <select class='col-md-4 col-lg-4 col-sm-4' id='wl_wpa_encryption' name='wl_wpa_encryption' class='radioSwitchElement'>
            <option value='aes'>AES</option>
            <option value='tkip'>TKIP</option>
            <option value='tkip+aes'>AES/TKIP</option>
          </select>
        </div>    

        <div class ='form-group' style='margin-bottom: 5px;'>
          <label class='col-md-2 col-lg-2 col-sm-2' for='wl_wpa_psk'>Key</label>
          <div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
            <input id='wl_wpa_psk' name='wl_wpa_psk' type='password' onfocus='peekaboo("wl_wpa_psk")' onblur='peekaboo("wl_wpa_psk")' class='form-control col-md-4 col-lg-4 col-sm-4'>
          </div>
        </div>

        <div class ='form-group' style='margin-bottom: 5px;'>
          <label class='col-md-2 col-lg-2 col-sm-2' for='wl_ssid'>Key Duration</label>
          <input id='wl_wpa_rekey' name='wl_wpa_rekey' class='form-control col-lg-4 '>
          <label  id='wl_wpa_rekeyLabel' class='errorLabel'></label>
        </div>
      </div>
    </div>
  </div>

  <!-- WL0 save/cancel buttons -->
  <div class='controlBoxFooter'>
    <button type='button' class='btn btn-default btn-sm' id='saveButton' value='Save' onclick='WLcall("#fe")'>Save</button>
    <button type='button' class='btn btn-default btn-sm' id='cancelButton' value='Cancel' onClick="window.location.reload()" disabled>Cancel</button>
    <span id='messages'>&nbsp;</span>
  </div>
  <div id='hideme'>
    <div class='centercolumncontainer'>
      <div class='middlecontainer'>
        <div id='hiddentext'>Please wait...</div>
        <br>
      </div>
    </div>
  </div>
</form>

<!-- WL1 content -->
<form id="fe1">
  <input type='hidden' id='form_wl1' name='form_wl1' value='wl1'>
  <div class='controlBox'><span class='controlBoxTitle'>WL1 Guest access point</span>     
    <div class='controlBoxContent controlTable smallwidth' id='wl_wl1'>

      <div class ='form-group' style='margin-bottom: 5px;'>
        <label class='col-md-3 col-lg-2 col-sm-4' for='wl1_mode'>Mode</label>
        <select class='col-md-4 col-lg-4 col-sm-4' id='wl1_mode' name='wl1_mode' class='radioSwitchElement'>
          <option value='off'>Off</option>
          <option value='ap'>Wireless Server</option>
        </select>
      </div>


      <div class ='form-group' style='margin-bottom: 5px;'>
        <label class='col-md-3 col-lg-2 col-sm-4' for='wl1_ssid'>SSID</label>
        <div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
          <input id='wl1_ssid' name='wl1_ssid' type='text' class='form-control'>
          <label  id='wl1_ssidLabel' class='errorLabel'></label>
        </div>
      </div>

      <div class ='form-group' style='margin-bottom: 5px;'>
        <label class='col-md-3 col-lg-2 col-sm-4' for='wl1_encryption'>Encryption</label>
        <select class='col-md-4 col-lg-4 col-sm-4' id='wl1_encryption' name='wl1_encryption' class='radioSwitchElement'>
          <option value='none'>None</option>
          <option value='psk2'>WPA2</option>
        </select>
      </div>    

      <div class ='form-group wl1 wl1_encryption wl1_encryption-psk wl1_encryption-psk2 wl1_encryption-mixed-psk' style='margin-bottom: 5px;'>
        <br>
        <div class ='form-group'>
          <label class='col-md-3 col-lg-2 col-sm-4' for='wl1_wpa_encryption'>WPA Encryption</label>
          <select class='col-md-4 col-lg-4 col-sm-4' id='wl1_wpa_encryption' name='wl1_wpa_encryption' class='radioSwitchElement'>
            <option value='aes'>AES</option>
            <option value='tkip'>TKIP</option>
            <option value='tkip+aes'>AES/TKIP</option>
          </select>
        </div>    

        <div class ='form-group' style='margin-bottom: 5px;'>
          <label class='col-md-3 col-lg-2 col-sm-4' for='wl1_wpa_psk'>Key</label>
          <div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
            <input id='wl1_wpa_psk' name='wl1_wpa_psk' type='password' onfocus='peekaboo("wl1_wpa_psk")' onblur='peekaboo("wl1_wpa_psk")' class='form-control col-md-4 col-lg-4 col-sm-4'>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- WL1 save/cancel buttons -->
  <div class='controlBoxFooter'>
    <button type='button' class='btn btn-default btn-sm' id='saveButton1' value='Save' onclick='WLcall("#fe1")'>Save</button>
    <button type='button' class='btn btn-default btn-sm' id='cancelButton1' value='Cancel' onClick="window.location.reload()" disabled>Cancel</button>
    <span id='messages1'>&nbsp;</span>
  </div>                                                      
  <div id='hideme'>                                                                                                                          
    <div class='centercolumncontainer'>                                                                                                    
      <div class='middlecontainer'>                                                                                                      
        <div id='hiddentext'>Please wait...</div>                                                                                      
        <br>                                                                                                                           
      </div>                                                                                                                             
    </div>                                                                                                                                 
  </div>
  <p>
    <div id='footer'> Copyright © 2016 Sabai Technology, LLC </div>                                                                         
  </p>
</form>

</body>
</html>

<script type='text/ecmascript'>

//Adding text to help-modal
$(document).on('click', '#helpBtn', function (e) {
  var help = "";
    help += "<p>This page provides Wi-Fi related settings that can be customized. There are two Wi-Fi spots that can be managed by user. Guest Wi-Fi WL1 spot is isolated from main one WL0. It is supposed to protect LAN from untrusted hosts.</p>"
    help += "<br>"    
    help += "<p><b>Mode -</b> user can turn on or turn off access point(AP). By default both acess point are available.</p>"
    help += "<br>"    
    help += "<p><b>SSID -</b> name of access point. </p>"
    help += "<br>"    
    help += "<p><b>Channel mode -</b> Wi-Fi starts automatically with 1st channel. User can chose manual mode and take another one from the list.Channel width can be set manual as well. These parameters will be aplied for guest AP too.</p>"
    help += "<br>"    
    help += "<p><b>Encryption -</b> can be set in defferent ways or turned off at all. None turns off encryption and routers becomes very vulnarable. It is NOT recommended to turn off encryption. Chose security protocol and set the password.</p>"
    help += "<br>"    
    help += "<p><b>Key -</b> password for AP to secure network.</p>"
    help += "<br>"
   

  $('#help-modal').find('.modal-body').html("<div class='helpModal'" +help+ "</div>");
    $('#help-modal').modal('show')
});

//Detecting different changes on page
//and displaying an alert if leaving/reloading 
//the page or pressing 'Cancel'.
var somethingChanged = false;

//Any manual change to inputs
$(document).on('change', 'input', function (e) {
    somethingChanged = true; 
    $("#cancelButton").removeAttr('disabled');
    $("#cancelButton1").removeAttr('disabled');
});

//Using keyboard up- or downarrow
$(document).on('keyup', 'input', function (e) {
  if(e.keyCode == 38 || e.keyCode == 40){
    somethingChanged = true; 
    $("#cancelButton").removeAttr('disabled');
    $("#cancelButton1").removeAttr('disabled');
    }
});

//Click on spinner arrows
$(document).on('click', '.ui-spinner-button', function (e) {
    somethingChanged = true; 
    $("#cancelButton").removeAttr('disabled');
    $("#cancelButton1").removeAttr('disabled');    
});

//Click on radioSwitch buttons
$(document).on('click', '.radioSwitch', function (e) {
    somethingChanged = true; 
    $("#cancelButton").removeAttr('disabled');
    $("#cancelButton1").removeAttr('disabled');    
});

//Resetting cancelButton to disabled-state when saving changes
$(document).on('click', '#saveButton', function (e) {
    $("#cancelButton").prop('disabled', 'disabled');  
    $("#cancelButton1").prop('disabled', 'disabled');  
    somethingChanged = false; 
});

//Resetting cancelButton to disabled-state when saving changes
$(document).on('click', '#saveButton1', function (e) {
    $("#cancelButton").prop('disabled', 'disabled');  
    $("#cancelButton1").prop('disabled', 'disabled');  
    somethingChanged = false; 
});

//If any changes is detected then display alert
$(window).bind('beforeunload',function(){
   if(somethingChanged){
   return "";
    }
});

var pForm = {}
var hidden, hide;
var f = E('fe'); 
var hidden = E('hideme'); 
var hide = E('hiddentext');

//WL init
$.get('php/get_wl_channel.php');

//TODO: remove wpa_type
var wl0=$.parseJSON('{<?php
          $mode=exec("uci get sabai.wlradio0.mode");
          $ssid=trim(exec("uci get sabai.wlradio0.ssid"));
          $encryption=trim(exec("uci get sabai.wlradio0.encryption"));
          $wpa_type=trim(exec("uci get sabai.wlradio0.wpa_type"));
          $wpa_encryption=trim(exec("uci get sabai.wlradio0.wpa_encryption"));
          $wpa_psk=trim(exec("uci get sabai.wlradio0.wpa_psk"));
          $wpa_rekey=trim(exec("uci get sabai.wlradio0.wpa_group_rekey"));
          $channels_qty=trim(exec("uci get sabai.wlradio0.channels_qty"));      
          $channel=trim(exec("uci get sabai.wlradio0.channel_freq"));
          $auto=trim(exec("uci get sabai.wlradio0.auto"));
          $width=trim(exec("uci get sabai.wlradio0.width"));
          echo "\"mode\": \"$mode\",\"ssid\": \"$ssid\",\"encryption\": \"$encryption\",\"wpa_type\": \"$wpa_type\",\"wpa_encryption\": \"$wpa_encryption\",\"wpa_psk\": \"$wpa_psk\",\"wpa_rekey\": \"$wpa_rekey\", \"channel\": \"$channel\", \"auto\": \"$auto\", \"channels_qty\": \"$channels_qty\", \"width\": \"$width\"";
      ?>}');

var wl0_wepkeyraw='<?php
          $servers=exec("uci get sabai.wlradio0.wepkeys");
          echo "$servers"; 
          ?>';         
var wl0_array = JSON.stringify(wl0_wepkeyraw.split(" "));
var wl0_wepkeyfin= "{\"keys\"" + ":" + wl0_array + "}";
var wl0_wepkey = $.parseJSON(wl0_wepkeyfin);

var wl1=$.parseJSON('{<?php
  $mode=exec("uci get sabai.wlradio1.mode");
  $ssid=trim(exec("uci get sabai.wlradio1.ssid"));
  $encryption=trim(exec("uci get sabai.wlradio1.encryption"));
  $wpa_encryption=trim(exec("uci get sabai.wlradio1.wpa_encryption"));
  $wpa_psk=trim(exec("uci get sabai.wlradio1.wpa_psk"));
  $channel=trim(exec("uci get sabai.wlradio1.channel_freq"));
  $auto=trim(exec("uci get sabai.wlradio1.auto"));
  
  echo "\"mode\": \"$mode\",\"ssid\": \"$ssid\",\"encryption\": \"$encryption\",\"wpa_encryption\": \"$wpa_encryption\",\"wpa_psk\": \"$wpa_psk\",\"channel\": \"$channel\", \"auto\": \"$auto\"";?>}');

//TODO: remove everything from here. Dublicated.
$('#wl_mode').val(wl0.mode);   
$('#wl_ssid').val(wl0.ssid); 
$('#wl_encryption').val(wl0.encryption); 
$('#wl_wpa_type').val(wl0.wpa_type); 
$('#wl_wpa_encryption').val(wl0.wpa_encryption); 
$('#wl_wpa_psk').val(wl0.wpa_psk);  
$('#wl_wpa_rekey').val(wl0.wpa_rekey);  
$('#wl_channel').val(wl0.channel);
$('#channel_mode').val(wl0.auto);
$('#wl_channel_msg').val(wl0.channel);

$('#wl1_mode').val(wl1.mode);
$('#wl1_ssid').val(wl1.ssid); 
$('#wl1_encryption').val(wl1.encryption); 
$('#wl1_wpa_type').val(wl1.wpa_type); 
$('#wl1_wpa_encryption').val(wl1.wpa_encryption); 
$('#wl1_wpa_psk').val(wl1.wpa_psk);  

function WLcall(wlForm){ 
  hideUi("Adjusting Wireless settings..."); 
$(document).ready( function(){
// Pass the form values to the php file 
$.post('php/wl.php', $(wlForm).serialize(), function(res){
  // Detect if values have been passed back   
    if(res!="" && wlForm =="#fe"){
      WLresp(res);
    }
    else {
      WLresp(res,wlForm);
    }
      showUi();
});
 
// Refresh in case of bad data
location.reload();

}); 

}

function WLresp(res,wlForm){ 
  eval(res); 
  msg(res.msg,wlForm); 
  showUi(); 
  if(res.sabai){ 
    limit=10; 
    getUpdate(); 
  } 
}

$.widget("jai.wl_wl0", {
    
  //Adding to the built-in widget constructor method - do this when widget is instantiated
  _create: function(){
    //TO DO: check to see if containing element has a unique id
    
    // BUILDING DOM ELEMENTS
    /*$(this.element)
    .append( $(document.createElement('table')).addClass("controlTable smallwidth")
      .append( $(document.createElement('tbody')) 
        
        .append( $(document.createElement('tr'))
          .append( $(document.createElement('td')).html('Mode') 
          )
          .append( $(document.createElement('td') ) 
            .append(
              $(document.createElement('select'))
                .prop("id","wl_mode")
                .prop("name","wl_mode")
                .prop("class", "radioSwitchElement")
              .append( $(document.createElement('option'))
                .prop("value", "off")
                .prop("text", 'Off')
              )
              .append( $(document.createElement('option'))
                .prop("value", "ap")
                .prop("text", 'Wireless Server')
              )
            )
          )
        ) // end mode tr

  .append( $(document.createElement('tr'))
          .append( $(document.createElement('td')).html('SSID') 
          )
          .append( $(document.createElement('td') ) 
            .append(
              $(document.createElement('input'))
                .prop("id","wl_ssid")
                .prop("name","wl_ssid")
            )
            .append(
              $(document.createElement('label')).addClass('errorLabel')
              .prop("id", "wl_ssidLabel")
            )
          )
        ) // end SSID tr

  .append( $(document.createElement('tr'))
    .append( $(document.createElement('td')).html('Channel mode')                                                          
         )
      .append( $(document.createElement('td') )                                                                                                              
            .append(                                                                                                     
              $(document.createElement('select'))                                                                        
                .prop("id", "channel_mode")                                                                                                     
                .prop("name", "channel_mode")                                                                            
                .prop("class", "radioSwitchElement")                                                                     
              .append( $(document.createElement('option'))                                                               
                .prop("value", "off")                                                                                     
                .prop("text", "Manual")                                                                                                             
              )                                                                                                          
              .append( $(document.createElement('option'))                                                       
                .prop("value", "auto")                                                                            
                .prop("text", "Auto")                                                                                     
              )                                                                                                                                 
      )
    )
        ) // End tr

  .append( $(document.createElement('tr')).addClass("channel control")
    .append( $(document.createElement('td')).html('Channel')                                       
          ) 
          .append( $(document.createElement('td')).addClass("channel_mode auto_on")
            .append(                                                                                                                            
              $(document.createElement('input'))                                                                         
                .prop("id","wl_channel_msg")                                                                                 
                .prop("name","wl_channel_msg")                                                                               
                .prop("disabled", "true")
            )                                                                                                            
    )

    .append( $(document.createElement('td')).addClass("channel_mode auto_off")
            .append(                                                                                             
              $(document.createElement('input'))                                                                    
                .prop("id","wl_channel")                                                                 
                .prop("name","wl_channel")                                                                       
            )
            .append(
              $(document.createElement('label')).addClass('errorLabel')
              .prop("id", "wl_channelLabel")
            )                                                                                                    
          )


  )
  .append( $(document.createElement('tr'))
      .append( $(document.createElement('td')).html('Channel width')                                                          
      )
      .append( $(document.createElement('td'))
        .append(                                                                                                     
            $(document.createElement('select'))                                                                        
                .prop("id", "channel_width")                                                                                                     
                .prop("name", "channel_width")
                .prop("class", "radioSwitchElement")                                                                           
            .append( $(document.createElement('option'))
                .prop("value", "20")
                .prop("text", "HT20")
            )
            .append( $(document.createElement('option'))
                .prop("value", "40")
                .prop("text", "HT40")
            )
            )
        )
      ) 
  
        .append( $(document.createElement('tr'))
          .append( $(document.createElement('td')).html('Encryption') 
          )
          .append( $(document.createElement('td') ) 
            .append(
              $(document.createElement('select'))
                .prop("id","wl_encryption")
                .prop("name","wl_encryption")
                .prop("class", "radioSwitchElement")
              .append( $(document.createElement('option'))
                .prop("value", "none")
                .prop("text", 'None')
              )
              .append( $(document.createElement('option'))
                .prop("value", "wep")
                .prop("text", 'WEP')
              )
              .append( $(document.createElement('option'))
                .prop("value", "psk")
                .prop("text", 'WPA')
              )
              .append( $(document.createElement('option'))
                .prop("value", "psk2")
                .prop("text", 'WPA2')
              )
              .append( $(document.createElement('option'))
                .prop("value", "mixed-psk")
                .prop("text", 'WPA/WPA2')
              )
            )
          )
        ) // end ssid tr
      ) // end first tbody
    ) // end table

    // LOWER TABLE, DEPENDS ON SECURITY SELECTION
    //wep table body
    .append( $(document.createElement('table')).addClass("controlTable indented")
      .append( $(document.createElement('tbody')).addClass("wl_encryption wl_encryption-wep") 
        
        .append( $(document.createElement('tr'))
          .append( $(document.createElement('td')).html('WEP Keys') 
          )
          .append( $(document.createElement('td') ) 
            .append(
              $(document.createElement('ul')).prop("id","wl_wep_keys")
            )
          )
        ) // end WEP keys tr
      ) // end WEP tbody

      //wpa tbody
      .append( $(document.createElement('tbody'))
        .addClass("wl_encryption wl_encryption-psk wl_encryption-psk2 wl_encryption-mixed-psk") 
        
        .append( $(document.createElement('tr'))
          .append( $(document.createElement('td')).html('&nbsp') 
          )
          .append( $(document.createElement('td')).html('&nbsp') 
          )
        ) // end empty tr



        .append( $(document.createElement('tr'))
          .append( $(document.createElement('td')).html('WPA Encryption') 
          )
          .append( $(document.createElement('td') ) 
            .append(
              $(document.createElement('select'))
                .prop("id","wl_wpa_encryption")
                .prop("name","wl_wpa_encryption")
                .prop("class", "radioSwitchElement")
              .append( $(document.createElement('option'))
                .prop("value", "aes")
                .prop("text", 'AES')
              )
              .append( $(document.createElement('option'))
                .prop("value", "tkip")
                .prop("text", 'TKIP')
              )
              .append( $(document.createElement('option'))
                .prop("value", "tkip+aes")
                .prop("text", 'AES/TKIP')
              )
            )
          )
        ) // end WPA Encryption tr

        .append( $(document.createElement('tr'))
          .append( $(document.createElement('td')).html('Key') 
          )
          .append( $(document.createElement('td') ) 
            .append(
              $(document.createElement('input'))
                .prop("id","wl_wpa_psk")
                .prop("name","wl_wpa_psk")
                .prop("type", "password")
                .attr("onfocus", "peekaboo('wl_wpa_psk')")
                .attr("onblur", "peekaboo('wl_wpa_psk')")
            )
          )
        ) // end PSK tr

        .append( $(document.createElement('tr'))
          .append( $(document.createElement('td')).html('Key Duration') 
          )
          .append( $(document.createElement('td') ) 
            .append(
              $(document.createElement('input'))
                .prop("id","wl_wpa_rekey")
                .prop("name","wl_wpa_rekey")
            )
            .append(
              $(document.createElement('label')).addClass('errorLabel')
              .prop("id", "wl_wpa_rekeyLabel")
            ) 
          )
        ) // end PSK tr
      ) // end WPA tbody
    ) // end lower table*/


  $('#wl_encryption').change(function(){
    $('.wl_encryption').hide(); 
    $('.wl_encryption-'+ $('#wl_encryption').val() ).show(); 
  })

  $('#wl_mode').radioswitch({
    value: wl0.mode,
    change: function(event,ui){ 
      $('.wl_mode').hide(); 
      $('.wl_mode-'+ wl0.encryption ).show(); 
    }
  });

  $('#wl_ssid').val(wl0.ssid);

  $('#channel_mode').radioswitch({ 
    value: wl0.auto
  });

  $('#channel_mode').change(function(){
    var selectOption = $(this).find(":selected").val();
    if (selectOption.trim() == "off") {
      $('.auto_on').hide();
      $('.auto_off').show();
    } else {
      $('.auto_off').hide();
      $('.auto_on').show();
    }
  })

  if (wl0.auto == "off") {
    $('.auto_on').hide();
    $('.auto_off').show();
  } else {
    $('.auto_off').hide();
    $('.auto_on').show();
  }

  $('#wl_channel').spinner({ min: 1, max: wl0.channels_qty }).spinner('value',wl0.channel);
  $('#wl_channel_msg').val(wl0.channel);

  $('#channel_width').radioswitch({ 
    value: wl0.width
  });

  $('#wl_encryption').radioswitch({
    value: wl0.encryption
  });

  $('#wl_wpa_type').radioswitch({
   value: wl0.wpa_type
  });

  $('#wl_wpa_encryption').radioswitch({
   value: wl0.wpa_encryption
  });

  $('#wl_wpa_psk').val(wl0.wpa_psk);

  $('#wl_wpa_rekey').spinner({ min: 600, max: 7200 }).spinner('value',wl0.wpa_rekey);

  $('#wl_wep_keys').oldeditablelist({ list: wl0_wepkey.keys, fixed: false });

    
    this._super();
  },

  //global save method
  saveWL0: function(){  

    //   $('#save').click( function() {
  //     var rawForm = $('#fe').serializeArray()
  //     var pForm = {}
  //     for(var i in rawForm){
  //       pForm[ rawForm[i].name ] = rawForm[i].value;
  //     }
  //     // if(!pForm['dhcp_on']) pForm['dhcp_on'] = 'off'
  // //    $('#testing').html( JSON.stringify(pForm) )
  //     toServer(pForm, 'save');
  //   }); 
  
    var rawForm = $('#wl_wl0 input').serializeArray();
    for(var i in rawForm){
      pForm[ rawForm[i].name ] = rawForm[i].value;
    }
    $('#testing').html( rawForm )
    console.log(pForm)
    return pForm;
 
  } //end save WL0
});

$.widget("jai.wl_wl1", {
  //Adding to the built-in widget constructor method - do this when widget is instantiated
  _create: function(){
    // BUILDING DOM ELEMENTS
    /*$(this.element)
    .append( $(document.createElement('table')).addClass("controlTable smallwidth")
      .append( $(document.createElement('tbody'))
        .append( $(document.createElement('tr'))
          .append( $(document.createElement('td')).html('Mode')
          )
            .append( $(document.createElement('td') )
              .append(
                $(document.createElement('select'))
                  .prop("id","wl1_mode")
                  .prop("name","wl1_mode")
                  .prop("class", "radioSwitchElement")
                .append( $(document.createElement('option'))
                  .prop("value", "off")
                  .prop("text", "Off")
                )
                .append( $(document.createElement('option'))
                  .prop("value", "ap")
                  .prop("text", "On")
                )
              )
            )
        ) // end mode tr
        
        .append( $(document.createElement('tr'))
            .append( $(document.createElement('td')).html('SSID')
            )
            .append( $(document.createElement('td') )
              .append(
                  $(document.createElement('input'))
                    .prop("id","wl1_ssid")
                .prop("name","wl1_ssid")
              )
              .append(
              $(document.createElement('label')).addClass('errorLabel')
              .prop("id", "wl1_ssidLabel")
            )
            )
        ) // End SSID

        .append( $(document.createElement('tr'))
          .append( $(document.createElement('td')).html('Encryption')
          )
            .append( $(document.createElement('td') )
              .append(
                $(document.createElement('select'))
                  .prop("id","wl1_encryption")
                  .prop("name","wl1_encryption")
                  .prop("class", "radioSwitchElement")
                .append( $(document.createElement('option'))
                  .prop("value", "none")
                  .prop("text", 'None')
                )
                .append( $(document.createElement('option'))
                  .prop("value", "psk2")
                          .prop("text", 'WPA2')
                        )
                    )
                  ) // end td
                ) // end tr
       ) // End tbody
    ) // End table

             // LOWER TABLE, DEPENDS ON SECURITY SELECTION
                //wep table body
                .append( $(document.createElement('table')).addClass("controlTable indented wl1")
              .append( $(document.createElement('tbody'))
                .addClass("wl1_encryption wl1_encryption-psk wl1_encryption-psk2 wl1_encryption-mixed-psk")
                  .append( $(document.createElement('tr'))
                    .append( $(document.createElement('td')).html('&nbsp')
                    )
                    .append( $(document.createElement('td')).html('&nbsp')
                    )
                        ) // end empty tr

                        .append( $(document.createElement('tr'))
                          .append( $(document.createElement('td')).html('WPA Encryption')
                    )
                      .append( $(document.createElement('td') )
                        .append(
                          $(document.createElement('select'))
                            .prop("id","wl1_wpa_encryption")
                            .prop("name","wl1_wpa_encryption")
                            .prop("class", "radioSwitchElement")
                          .append( $(document.createElement('option'))
                            .prop("value", "aes")
                            .prop("text", 'AES')
                          )
                          .append( $(document.createElement('option'))
                            .prop("value", "tkip")
                            .prop("text", 'TKIP')
                          )
                          .append( $(document.createElement('option'))
                            .prop("value", "tkip+aes")
                            .prop("text", 'AES/TKIP')
                          )
                        )
                      )
                  ) // end WPA Encryption tr

                  .append( $(document.createElement('tr'))
                    .append( $(document.createElement('td')).html('Key')
                    )
                      .append( $(document.createElement('td') )
                        .append(
                          $(document.createElement('input'))
                            .prop("id","wl1_wpa_psk")
                            .prop("name","wl1_wpa_psk")
                            .prop("type", "password")
                            .attr("onfocus", "peekaboo('wl1_wpa_psk')")
                            .attr("onblur", "peekaboo('wl1_wpa_psk')")
                        )
                      )
                  ) // end PSK tr
                ) // end tbody
              ) // end class*/
        
     
  $('#wl1_mode').radioswitch({ value: wl1.mode });
  $('#wl1_ssid').val(wl1.ssid);

  $('#wl1_encryption').radioswitch({
    value: wl1.encryption
  });
    
  $('#wl1_encryption').change(function(){
    $('.wl1_encryption').hide();
    $('.wl1_encryption-'+ $('#wl1_encryption').val() ).show(); 
  })

  $('#wl1_wpa_encryption').radioswitch({
    value: wl1.wpa_encryption
  });

  $('#wl1_wpa_psk').val(wl1.wpa_psk);

  this._super();
  
  }, // ENd create

});




$(function(){
  //instatiate widgets on document ready
  $('#wl_wl0').wl_wl0({ conf: wl0 });
})

$(function(){
  //instatiate widgets on document ready
  $('#wl_wl1').wl_wl1({ conf: wl1 });
})

$('#save').click( function() {
  //FIGURE OUT HOW TO join pforms
  $('#wl_wl0').wl_wl0('saveWL0')
  toServer(pForm, 'save');
});  

//validate the fields
$(function() {
  var errorLabel = "";
$( "#fe" ).validate({
  rules: {
    wl_ssid: {
      required: true,
      nameCheck: true
    },
    wl_channel:{
      required: true,
      range: [1, 14]
    },
    wl_encryption: {
      required: true
    },
    wl_wpa_psk : {
      required: true,
      minlength: 8,
      maxlength: 63
    },
    wl_wpa_rekey: {
      required: true,
      range: [600, 7200]
    }
  },
    //Changing error position to custom label
    errorPlacement: function(error, element) {
      errorLabel = "#" + element[0].name + "Label"
        $(errorLabel).text(error[0].innerHTML);
        $(errorLabel).show();
    },
    success: function(error) {
        $(errorLabel).hide();
    }
  });
});

//validate the fields
$(function() {
  var errorLabel = "";
$( "#fe1" ).validate({
  rules: {
    wl1_ssid: {
      required: true,
      nameCheck: true
    },
    wl1_encryption: {
      required: true,
    },
    wl1_wpa_psk : {
      required: true,
      minlength: 8,
      maxlength: 63
    }
  },
      //Changing error position to custom label
    errorPlacement: function(error, element) {
      errorLabel = "#" + element[0].name + "Label"
        $(errorLabel).text(error[0].innerHTML);
        $(errorLabel).show();
    },
    success: function(error) {
        $(errorLabel).hide();
    }
  });
});

</script>
