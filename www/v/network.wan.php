<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
  $url = "/index.php?panel=network&section=wan";
  header( "Location: $url" );     
}
?>
<!DOCTYPE html>
<html>
<head>
</head>

<body>
<form id="fe">
<input type='hidden' id='act' name='act'>
<div class='pageTitle'>
  <input id='helpBtn' name='helpBtn' class='helpBtn' title='Help' style="background-image: url('libs/img/help.png')"></input>
  Network: WAN
</div>
<div class='controlBox'><span class='controlBoxTitle'>WAN</span>
  <div class='controlBoxContent' id='wansetup'>
    <div class ='form-group'>
      <label class='col-md-2 col-lg-2 col-sm-2' for='wan_proto'>WAN proto</label>
      <select class='col-md-4 col-lg-4 col-sm-4' id='wan_proto' name='wan_proto' class='radioSwitchElement'>
        <option value='dhcp'>DHCP</option>
        <option value='static'>Static</option>
        <option value='lan'>LAN</option>
      </select>
    </div>


    <div class ='form-group wan_proto wan_proto-static' style='margin-bottom: 5px;'>
      <label class='col-md-2 col-lg-2 col-sm-2' for='wan_ip'>IP</label>
      <input id='wan_ip' name='wan_ip' type='text' class='form-control col-lg-4 '>

      <label  id='wan_ipLabel' class='errorLabel'></label>
    </div>
    <div class ='form-group wan_proto wan_proto-static' style='margin-bottom: 5px;'>
      <label class='col-md-2 col-lg-2 col-sm-2' for='wan_mask'>Network Mask</label>
      <input id='wan_mask' name='wan_mask' type='text' class='form-control col-md-4 col-lg-4 col-sm-4'>
      <label  id='wan_maskLabel' class='errorLabel'></label>
    </div>
    <div class ='form-group wan_proto wan_proto-static' style='margin-bottom: 5px;'>
      <label class='col-md-2 col-lg-2 col-sm-2' for='wan_gateway'>Gateway</label>
      <input id='wan_gateway' name='wan_gateway' type='text' class='form-control col-md-4 col-lg-4 col-sm-4'>
      <label  id='wan_gatewayLabel' class='errorLabel'></label>
    </div>
    <div class ='form-group' style='margin-bottom: 5px;'>
      <label class='col-md-2 col-lg-2 col-sm-2' for='wan_mtu'>MTU</label>
      <input id='wan_mtu' name='wan_mtu' type='text' class='form-control col-md-4 col-lg-4 col-sm-4'>
      <label  id='wan_mtuLabel' class='errorLabel'></label>
    </div>
    <div class ='form-group' style='margin-bottom: 5px;'>
      <label class='col-md-2 col-lg-2 col-sm-2' for='wan_mac'>MAC</label>
      <input id='wan_mac' name='wan_mac' type='text' class='form-control col-md-4 col-lg-4 col-sm-4'>
      <label  id='wan_macLabel' class='errorLabel'></label>
    </div>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'>DNS</span>
  <div class='controlBoxContent'>


    <div class ='form-group' style='margin-bottom: 5px;'>
      <label class='col-md-2 col-lg-2 col-sm-2' for='dns_servers'>DNS Servers</label>
      <div>
        <ul id='dns_servers' class='col-md-4 col-lg-4 col-sm-4' style='padding: 0px;'>
          <li><input type='text' placeholder='DNS 1' name='dns_server1' class='form-control'><a class='dns-delete deleteDNS'>✖</a><label id='dns_server1Label' class='errorLabel'></label></li>
          <li><input type='text' placeholder='DNS 2' name='dns_server2' class='form-control'><a class='dns-delete deleteDNS'>✖</a><label id='dns_server2Label' class='errorLabel'></label></li>
          <li><input type='text' placeholder='DNS 3' name='dns_server3' class='form-control'><a class='dns-delete deleteDNS'>✖</a><label id='dns_server3Label' class='errorLabel'></label></li>
          <li><input type='text' placeholder='DNS 4' name='dns_server4' class='form-control'><a class='dns-delete deleteDNS'>✖</a><label id='dns_server4Label' class='errorLabel'></label></li>
        </ul>
        <input type='hidden' name='dns_servers[]'>
        <input type='hidden' name='dns_servers[]'>
        <input type='hidden' name='dns_servers[]'>
        <input type='hidden' name='dns_servers[]'>
      </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div id="editableListDescription">
      <span id='dns_stat' color="red"></span><br><br>
      <span class ='xsmallText'>(These are the DNS servers the DHCP server will provide for devices also on the LAN)
      </span><br><br>
    </div>



<!--     <table class='controlTable'>
      <tbody>
      <tr>
        <td>DNS Servers</td>
        <td>
          <div>
            <ul id='dns_servers'>
              <li><input type='text' placeholder='DNS 1' name='dns_server1'><a class='dns-delete deleteDNS'>✖</a><label id='dns_server1Label' class='errorLabel'></label></li>
              <li><input type='text' placeholder='DNS 2' name='dns_server2'><a class='dns-delete deleteDNS'>✖</a><label id='dns_server2Label' class='errorLabel'></label></li>
              <li><input type='text' placeholder='DNS 3' name='dns_server3'><a class='dns-delete deleteDNS'>✖</a><label id='dns_server3Label' class='errorLabel'></label></li>
              <li><input type='text' placeholder='DNS 4' name='dns_server4'><a class='dns-delete deleteDNS'>✖</a><label id='dns_server4Label' class='errorLabel'></label></li>
            </ul>
            <input type='hidden' name='dns_servers[]'>
            <input type='hidden' name='dns_servers[]'>
            <input type='hidden' name='dns_servers[]'>
            <input type='hidden' name='dns_servers[]'>
          </div>
        </td>
          <div id="editableListDescription">
            <span id='dns_stat' color="red"></span><br><br>
            <span class ='xsmallText'>(These are the DNS servers the DHCP server will provide for devices also on the LAN)
            </span><br><br>
          </div>
        </td>
      </tr>
      </tbody>
    </table> -->

  </div>
</div>

  <div class='controlBoxFooter'>
    <button type='button' class='btn btn-default btn-sm' id='saveButton' value='Save' onclick='WANcall("save")'>Save</button>
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
    </div>
    <p>
        <div id='footer'>Copyright © 2016 Sabai Technology, LLC</div>
</p>
</form>

</body>
<script>

//Adding text to help-modal
$(document).on('click', '#helpBtn', function (e) {
  var help = "";
    help += "<p><b>WAN -</b> Wide Area Network. This network has to provide Internet access and route devices from LAN.</p>"
    help += "<br>"
    help += "<p><b>MTU -</b> Maximum Transmission Unit. This is the maximum size (in bytes) a single packet could have. Ethernet default is 1500. Maximum possible path MTU for IPv4 is 65536 (64 KiB) and minimum is 68. Changing MTU a bit may increase your connection speed, but it is recommended to keep the default one or consult your ISP.</p>"
    help += "<br>"
    help += "<p><b>MAC Address -</b> MAC Address Media Access Control Address, MAC addresses are distinct addresses on the device level and is comprised of a manufacturer number and serial number.</p>"
    help += "<br>"
    help += "<p><b>DNS -</b> DNS Domain Name System, translates people-friendly domain names (www.google.com) into computer-friendly IP addresses (1.1.1.1). A DNS is especially important for VPNs as some countries return improper results for domains intentionally as a way of blocking that web site.</p>"
 

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
});

//Using keyboard up- or downarrow
$(document).on('keyup', 'input', function (e) {
  if(e.keyCode == 38 || e.keyCode == 40){
    somethingChanged = true; 
    $("#cancelButton").removeAttr('disabled');
    }
});

//Click on spinner arrows
$(document).on('click', '.ui-spinner-button', function (e) {
    somethingChanged = true; 
    $("#cancelButton").removeAttr('disabled');
});

//Click on delete 'x' button
$(document).on('click', '.dns-delete', function (e) {
    somethingChanged = true; 
    $("#cancelButton").removeAttr('disabled');
});

//Resetting cancelButton to disabled-state when saving changes
$(document).on('click', '#saveButton', function (e) {
    $("#cancelButton").prop('disabled', 'disabled');  
    somethingChanged = false; 
});

//If any changes is detected then display alert
$(window).bind('beforeunload',function(){
   if(somethingChanged){
   return "";
    }
});

var validator;
$(function() {
  var errorLabel = "";
    validator = $( "#fe" ).validate({
      ignore: [],
    rules: {
      wan_ip: {
        required: true,
        validip: true 
      },
      wan_gateway: {
        required: true,
        validip: true 
      },      
      wan_mask: {
        required: true,
        netmask: true 
      },
      wan_mtu: {
        required: true,
        min: 576,
        max:1500
      },
      wan_mac: {
        required: true,
        macchecker: true 
      },
      dns_server1: {
        required: true,
        validip: true
      },
      dns_server2: {
        required: false,
        validip: true
      },
      dns_server3: {
        required: false,
        validip: true
      },
      dns_server4: {
        required: false,
        validip: true
      }
    },
    messages: {
      wan_mtu: "*Please enter number between 576 and 1500"
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
  //validator.form();
});

var hidden, hide, pForm = {};

var f = E('fe'); 
var hidden = E('hideme'); 
var hide = E('hiddentext');

var wan=$.parseJSON('{<?php
          $proto=exec("uci get sabai.wan.proto");
          $ip=trim(exec("uci get sabai.wan.ipaddr"));
          $mask=trim(exec("uci get sabai.wan.netmask"));
          $gateway=trim(exec("uci get sabai.wan.gateway"));
          if (exec("uci get network.wan.macaddr") != ""){
                $mac=trim(exec("uci get network.wan.macaddr"));
          } else {
            $mac=trim(exec("ifconfig eth0 | awk '/HWaddr/ { print $5 }'"));
          }
          $mtu=trim(exec("uci get sabai.wan.mtu"));
        echo "\"proto\": \"$proto\",\"ip\": \"$ip\",\"mask\": \"$mask\",\"gateway\": \"$gateway\",\"mac\": \"$mac\",\"mtu\": \"$mtu\"";
      ?>}');
var dnsraw='<?php
          $servers=exec("uci get sabai.wan.dns");
          echo "$servers"; 
          ?>';
var array = JSON.stringify(dnsraw.split(" "));
var dnsfin= "{\"servers\"" + ":" + array + "}";
var dns = $.parseJSON(dnsfin);
 
function WANcall(act){ 
  if( validator.numberOfInvalids() > 0){
    alert("Please fill the fields correctly.");
    return;
  }
  hideUi("Adjusting WAN settings..."); 
  E("act").value=act;
  $(document).ready( function(){
    // Pass the form values to the php file 

    var dns1 = $('#dns_servers').find('li').eq(0).find('input').val();
    var dns2 = $('#dns_servers').find('li').eq(1).find('input').val();
    var dns3 = $('#dns_servers').find('li').eq(2).find('input').val();
    var dns4 = $('#dns_servers').find('li').eq(3).find('input').val();


    $('#dns_servers').parent().find('input').eq(4).val(dns1);
    $('#dns_servers').parent().find('input').eq(5).val(dns2);
    $('#dns_servers').parent().find('input').eq(6).val(dns3);
    $('#dns_servers').parent().find('input').eq(7).val(dns4);


    $.post('php/wan.php', $("#fe").serialize())
      .done(function(res){
        // Detect if values have been passed back   
        if(res!=""){
          WANresp(res);
          }
        showUi();
      })
      .fail(function() {
        setTimeout(function(){hideUi("WAN settings was applied. Your device might have new IP address. Refresh the page.")}, 7000);
        setTimeout(function(){showUi()}, 12000);
      })
 
  // Important stops the page refreshing
  return false;

}); 


    if(act =='clear'){ 
    setTimeout("window.location.reload()",5000);
      }; 
}

function WANresp(res){ 
  eval(res); 
  msg(res.msg); 
  showUi(); 
  if(res.sabai){ 
    limit=10; 
    getUpdate(); 
  } 
}


function spinnerConstraint(spinner){
  var curv = $(spinner).ipspinner('value');
  if( curv < $(spinner).ipspinner('option','min') ) 
    $(spinner).ipspinner('value', $(spinner).ipspinner('option','min') );
  else if( curv > $(spinner).ipspinner('option','max') ) 
    $(spinner).ipspinner('value', $(spinner).ipspinner('option','max') );
}

$.widget("jai.wansetup", {
    
  //Adding to the built-in widget constructor method - do this when widget is instantiated
  _create: function(){
    //TO DO: check to see if containing element has a unique id
    
/*    // BUILDING DOM ELEMENTS
    $(this.element)
    .append( $(document.createElement('table')).addClass("controlTable")
      .append( $(document.createElement('tbody')) 
        
        .append( $(document.createElement('tr'))
          .append( $(document.createElement('td')).html('WAN proto') 
          )
          .append( $(document.createElement('td') ) 
            .append(
              $(document.createElement('select'))
                .prop("id","wan_proto")
                .prop("name","wan_proto")
                .prop("class", "radioSwitchElement")
              .append( $(document.createElement('option'))
                .prop("value", "dhcp")
                .prop("text", 'DHCP')
              )
              .append( $(document.createElement('option'))
                .prop("value", "static")
                .prop("text", 'Static')
              )
              .append( $(document.createElement('option'))
                .prop("value", "lan")
                .prop("text", 'LAN')
              )
            )

          )
        ) // end proto tr
      ) // end first tbody
      .append( $(document.createElement('tbody')).addClass("wan_proto wan_proto-static") 
        .append( $(document.createElement('tr') )
          .append( $(document.createElement('td')).html('IP') )
          .append( $(document.createElement('td') )
            .append(
              $(document.createElement('input'))
                .prop("id","wan_ip")
                .prop("name","wan_ip")
                .prop("type","text")
            )
            .append(
              $(document.createElement('label')).addClass('errorLabel')
              .prop("id", "wan_ipLabel")
            )
          )
        ) // end ip row
        .append( $(document.createElement('tr') )
          .append( $(document.createElement('td')).html('Network Mask') )
          .append( $(document.createElement('td') )
            .append(
              $(document.createElement('input'))
                .prop("id","wan_mask")
                .prop("name","wan_mask")
                .prop("type","text")
            )
            .append(
              $(document.createElement('label')).addClass('errorLabel')
              .prop("id", "wan_maskLabel")
            )
          )
        ) // end nmask row
        .append( $(document.createElement('tr') )
          .append( $(document.createElement('td')).html('Gateway') )
          .append( $(document.createElement('td') )
            .append(
              $(document.createElement('input'))
                .prop("id","wan_gateway")
                .prop("name","wan_gateway")
                .prop("type","text")
            )
            .append(
              $(document.createElement('label')).addClass('errorLabel')
              .prop("id", "wan_gatewayLabel")
            )
          )
        ) // end gateway row
      ) // end 2nd table body
      .append( $(document.createElement('tbody')) 
        .append( $(document.createElement('tr') )
          .append( $(document.createElement('td')).html('MTU') )
          .append( $(document.createElement('td') )
            .append(
              $(document.createElement('input'))
                .prop("id","wan_mtu")
                .prop("name","wan_mtu")
                .prop("type","text")
            )
            .append(
              $(document.createElement('label')).addClass('errorLabel')
              .prop("id", "wan_mtuLabel")
            )
          )
        ) //end MTU row
        .append( $(document.createElement('tr') )
          .append( $(document.createElement('td')).html('MAC') )
          .append( $(document.createElement('td') )
            .append(
              $(document.createElement('input'))
                .prop("id","wan_mac")
                .prop("name","wan_mac")
                .prop("type","text")
            )
            .append(
              $(document.createElement('label')).addClass('errorLabel')
              .prop("id", "wan_macLabel")
            )
          )
        ) //end Mac row
      ) // end bottom table body
    ) // end table*/

    // call maskspinner widget
    $('#wan_mask').maskspinner({
      spin: function(event,ui){ 
        $('#wan_ip').ipspinner('option','page', Math.pow(2,(32-ui.value)) ) 
      }
    }).maskspinner('value',this.options.conf.mask);


    $('#wan_mac').macspinner().macspinner('value',wan.mac);
    $('#wan_mtu').spinner({ min: 576, max: 1500 }).spinner('value',wan.mtu);
    $('#wan_gateway').ipspinner().ipspinner('value',wan.gateway);
    $('#wan_mask').maskspinner().maskspinner('value',wan.mask);
    $('#wan_ip').ipspinner().ipspinner('value',wan.ip);
    $('#wan_proto').radioswitch({ value: wan.proto, hasChildren: true });
    
    this._super();
  },
});

$(function(){
  //instatiate widgets on document ready
  $('#wansetup').wansetup({ conf: wan });
  
  //alert(('input[name=dns_server1]').val());
  //alert();
  for (i=0; i<4; i++){
    //$('#dns_servers').find('li').eq(i).find('input').val(dns.servers[i]);
    $('input[name=dns_server'+ (i+1) +']').ipspinner({
    min: '0.0.0.1',
    max: '255.255.255.254',
    change: function(event,ui){ 
      spinnerConstraint(this);
    }
  }).ipspinner('value',dns.servers[i]);
  
  
    
    $('#dns_servers').find('li').eq(i).find('a').click(function(el){
      $(el.target).parent().find('input').val('');
    });
  }
})



</script>
