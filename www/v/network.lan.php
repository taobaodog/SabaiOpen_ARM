<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
  $url = "/index.php?panel=network&section=lan";
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
  Network: LAN
</div>
<div class='controlBox'><span class='controlBoxTitle'>Address</span>
  <div class='controlBoxContent' id='lanaddress'>
    <div class ='form-group' style='margin-bottom: 5px;'>
      <label class='col-md-2 col-lg-2 col-sm-2' for='lan_ip'>LAN IP</label>
      <input id='lan_ip' name='lan_ip' type='text' class='form-control col-md-4 col-lg-4 col-sm-4'>
      <label  id='lan_ipLabel' class='errorLabel'></label>
    </div>
    <div class ='form-group' style='margin-bottom: 5px;'>
      <label class='col-md-2 col-lg-2 col-sm-2' for='lan_mask'>LAN Mask</label>
      <input id='lan_mask' name='lan_mask' type='text' class='form-control col-md-4 col-lg-4 col-sm-4'>
      <label  id='lan_maskLabel' class='errorLabel'></label>
    </div>
  </div>
</div>

<div class='controlBox'><span class='controlBoxTitle'>DHCP Server</span>
  <div class='controlBoxContent' id='dhcpserver'>  
    <div class ='form-group' style='margin-bottom: 5px;'>
      <label class='col-md-2 col-lg-2 col-sm-2' for='dhcp_lease'>Lease Hours</label>
      <input id='dhcp_lease' name='dhcp_lease' type='text' class='form-control col-md-4 col-lg-4 col-sm-4'>
      <label  id='dhcp_leaseLabel' class='errorLabel'></label>
    </div>
    <div class ='form-group' style='margin-bottom: 5px;'>
      <label class='col-md-2 col-lg-2 col-sm-2' for='dhcp_start'>DHCP Range</label>
      <input id='dhcp_start' name='dhcp_start' type='text' class='form-control col-md-4 col-lg-4 col-sm-4'>
      <input id='dhcp_limit' name='dhcp_limit' type='text' class='form-control col-md-4 col-lg-4 col-sm-4'>
    </div>    
    <div class ='form-group' style='margin-bottom: 5px;'>
      <label class='col-md-2 col-lg-2 col-sm-2' for='dhcp_startLabel'></label>
      <label  id='dhcp_startLabel' class='errorLabel'></label>
      <label  id='dhcp_limitLabel' class='errorLabel col-md-push-4'></label>
    </div>
  </div>
</div>

  <div class='controlBoxFooter'>
    <button type='button' class='btn btn-default btn-sm' id='saveButton' value='Save' onclick='LANcall("save")'>Save</button>
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
</html>
<script>

$(document).on('click', '#helpBtn', function (e) {
  var help = "";
    help += "<p><b>LAN -</b> Local Area Network.The network that connects hosts at your area in one network including hosts connected by WI-FI. It is not accessible from WAN.</p>"
    help += "<br>"
    help += "<p><b>LAN IP -</b> IP address of the router, that is used for LAN communication.</p>"
    help += "<br>"
    help += "<p><b>LAN Mask -</b> mask, that denotes network's size. It is advised that you keep the default 255.255.255.0 or research network masking in more details. </p>"
    help += "<br>"
    help += "<p><b>DHCP -</b> Dynamic Host Configuration Protocol, the method by which routers assign IP addresses automatically. This allows you to connect to the coffee shop wireless even after more than 254 people have already; IP addresses are recycled as wireless clients come and go.</p>"
    help += "<br>"
    help += "<p><b>Lease time -</b> determines how long the IP address can be used by host. Host should ask for a new lease when half of this time has passed since it got the old one.</p>"
    help += "<br>"
    help += "<p><b>DHCP range -</b> determines which IP addresses will be provided to LAN hosts. You should exclude router's own address, all statically assigned addresses, network address (0) and broadcast address (255) from the range. With default settings and no static assignments, 2-254 would be a fine range.</p>"

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

var hidden, hide, pForm = {}; pForm2 = {}

var f = E('fe'); 
var hidden = E('hideme'); 
var hide = E('hiddentext');

var lan=$.parseJSON('{<?php
          $ip=trim(exec("uci get network.lan.ipaddr"));
          $mask=trim(exec("uci get network.lan.netmask"));
        echo "\"ip\": \"$ip\",\"mask\": \"$mask\"";
      ?>}');
var dhcp=$.parseJSON('{<?php
          $lease=trim(exec("uci get dhcp.lan.leasetime"));
          $start=trim(exec("uci get dhcp.lan.start"));
          $limit=trim(exec("uci get dhcp.lan.limit"));
        echo "\"lease\": \"$lease\",\"start\": \"$start\",\"limit\": \"$limit\"";
      ?>}');
 
function LANcall(act){ 
  hideUi("Adjusting LAN settings..."); 
E("act").value=act;
$(document).ready( function(){
// Pass the form values to the php file 
$.post('php/lan.php', $("#fe").serialize(), function(res){
  // Detect if values have been passed back   
    if(res!=""){
      LANresp(res);
    }
      showUi();
});
 
// Important stops the page refreshing
return false;

}); 


//  E("_act").value=act; 
//  que.drop("php/wan.php",WANresp, $("fe").serialize() ); 
    if(act =='clear'){ 
    setTimeout("window.location.reload()",5000);
      }; 
}

function LANresp(res){ 
  eval(res); 
  msg(res.msg); 
  showUi(); 
  if(res.sabai){ 
    limit=10; 
    getUpdate(); 
  } 
}

//end of wm add


function spinnerConstraint(spinner){

}

//  _      _   _  _   __      ___    _          _   
// | |    /_\ | \| |__\ \    / (_)__| |__ _ ___| |_ 
// | |__ / _ \| .` |___\ \/\/ /| / _` / _` / -_)  _|
// |____/_/ \_\_|\_|    \_/\_/ |_\__,_\__, \___|\__|
//                                    |___/         

$.widget("jai.lanaddress", {
    
  //Adding to the built-in widget constructor method - do this when widget is instantiated
  _create: function(){
    //TO DO: check to see if containing element has a unique id
    /*
    // BUILDING DOM ELEMENTS
    $(this.element)
    .append( $(document.createElement('table')).addClass("controlTable").prop("id","lanaddress") 
      .append( $(document.createElement('tbody')) 
        
        .append( $(document.createElement('tr'))
          .append( $(document.createElement('td')).html('LAN IP') 
          )
          .append( $(document.createElement('td') ) 
            .append(
              $(document.createElement('input'))
                .prop("id","lan_ip")
                .prop("name","lan_ip")
                .prop("type","text")
            )
            .append(
              $(document.createElement('label')).addClass('errorLabel')
              .prop("id", "lan_ipLabel")
            )
          )
        )
        )
        
        .append( $(document.createElement('tr') )
          .append( $(document.createElement('td')).html('LAN Mask') )
          .append( $(document.createElement('td') )
            .append(
              $(document.createElement('input'))
                .prop("id","lan_mask")
                .prop("name","lan_mask")
                .prop("type","text")
            )
            .append(
              $(document.createElement('label')).addClass('errorLabel')
              .prop("id", "lan_maskLabel")
          )
        )
      )
    )*/

    // call ipspinner widget
    $('#lan_ip').ipspinner({
      min: '0.0.0.1', max: '255.255.255.254',
      page: Math.pow(2,(32-mask2cidr(this.options.conf.mask))),
      change: function(event,ui){ 
        spinnerConstraint(this);
      }
    }).ipspinner('value',this.options.conf.ip);

    // call maskspinner widget
    $('#lan_mask').maskspinner({
      spin: function(event,ui){ 
        $('#lan_ip').ipspinner('option','page', Math.pow(2,(32-ui.value)) ) 
      }
    }).maskspinner('value',this.options.conf.mask);

    //add to built-in widget functionality
    this._super();
  },

  //global save method
  saveLAN: function(){  
  
    var rawForm = $('#lanaddress input').serializeArray();
    for(var i in rawForm){
      pForm[ rawForm[i].name ] = rawForm[i].value;
    }
    $('#testing').html( rawForm )

    return pForm;
 
  }
});




 //  ___  _  _  ___ ___   __      ___    _          _   
 // |   \| || |/ __| _ \__\ \    / (_)__| |__ _ ___| |_ 
 // | |) | __ | (__|  _/___\ \/\/ /| / _` / _` / -_)  _|
 // |___/|_||_|\___|_|      \_/\_/ |_\__,_\__, \___|\__|
 //                                       |___/         



$.widget("jai.dhcpserver", {
    
  //Adding to the built-in widget constructor method - do this when widget is instantiated
  _create: function(){
    //TO DO: check to see if containing element has a unique id
   /* $(this.element)
    .append( $(document.createElement('table')).addClass("controlTable").prop("id","dhcpserver") 
      .append( $(document.createElement('tbody')) 
        
        .append( $(document.createElement('tr'))
          .append( $(document.createElement('td')).html('Lease Hours') 
          )
          .append( $(document.createElement('td') ) 
            .append(
              $(document.createElement('input'))
                .prop("id","dhcp_lease")
                .prop("name","dhcp_lease")
                .prop("type","text")
            )
          )
        )
        .append( $(document.createElement('tr'))
          .append( $(document.createElement('td'))
          )
          .append( $(document.createElement('td') ) 
            .append(
              $(document.createElement('label')).addClass('errorLabel')
              .prop("id", "dhcp_leaseLabel")
            )
          )
        )
        .append( $(document.createElement('tr') )
          .append( $(document.createElement('td')).html('DHCP Range') )
          .append( $(document.createElement('td') )
            .append(
              $(document.createElement('input'))
                .prop("id","dhcp_start")
                .prop("name","dhcp_start")
                .prop("type","text")
            )
          )
          .append(
              $(document.createElement('input'))
                .prop("id","dhcp_limit")
                .prop("name","dhcp_limit")
                .prop("type","text")
          )
        )
        .append( $(document.createElement('tr'))
          .append( $(document.createElement('td'))
          )
          .append( $(document.createElement('td') ) 
            .append(
              $(document.createElement('label')).addClass('errorLabel')
              .prop("id", "dhcp_startLabel")
            )
          )
          .append( $(document.createElement('td') ) 
            .append(
              $(document.createElement('label')).addClass('errorLabel')
              .prop("id", "dhcp_limitLabel")
            )
          )
        )    
      )
    )*/

    // call ipspinner widget
    var network = {}
    var dhcpRangeMin = ip2long('0.0.0.1');
    var dhcpRangeMax = ip2long('255.255.255.254');

    $('#dhcp_lease').spinner({ min: 1, max: 72 });
    $('#dhcp_start').spinner({ min: 2, max: 254 });
    $('#dhcp_limit').spinner({ min: 2, max: 254 });


    $('#dhcp_start').spinner({
      min: dhcpRangeMin, max: this.options.conf.limit,
      spin: function(event, ui){
        // $('#dhcpSlider').slider('values', '0', ui.value);
        $('#dhcp_limit').spinner('option','min', ui.value );
      },
      change: function(event,ui){ 
        spinnerConstraint(this);
        //var curv = $(this).ipspinner('value');
        //if( curv < $(this).ipspinner('option','min') ) $(this).ipspinner('value', $(this).ipspinner('option','min') );
        //else if( curv > $(this).ipspinner('option','max') ) $(this).ipspinner('value', $(this).ipspinner('option','max') );
        var curv = $(this).spinner('value');
        //$('#dhcpSlider').slider('values', '0', curv );
        $('#dhcp_limit').spinner('option','min', curv );
      }
    })


    // TODO: Surely there's a better way! (insert infomercial here)
    //  $("#dhcpEdit").attr("checked", false)
    //set initial valies for lease/range inputs
    $('#dhcp_lease').spinner({ min: 1, max: 72 });
    $('#dhcp_lease').spinner('value', dhcp.lease );
    $('#dhcp_start').spinner('value', dhcp.start );
    
    
    $('#dhcp_limit').spinner('value', dhcp.limit );


    // $('#dhcp_lease').spinner('option','disabled', true );
    // $('#dhcp_upper').ipspinner('option','disabled', true );
    // $('#dhcp_lower').ipspinner('option','disabled', true );

    //add to built-in widget functionality
    this._super();
  },

  //global save method
  saveDHCP: function(){  
  
    var rawForm = $('#dhcpserver input').serializeArray();
    for(var i in rawForm){
      pForm2[ rawForm[i].name ] = rawForm[i].value;
    }
    $('#testing').append( rawForm )
    console.log(pForm2)
    return pForm2;
 
  }
});

$(function(){
  //instatiate widgets on document ready
  $('#lanaddress').lanaddress({ conf: lan });
  $('#dhcpserver').dhcpserver({ conf: dhcp });
})

$('#save').click( function() {
  //FIGURE OUT HOW TO join pforms
  $('#lanaddress').lanaddress('saveLAN')
  $('#dhcpserver').dhcpserver('saveDHCP')
  toServer(pForm, 'save');
});  

//validate the fields
$(function() {
  var errorLabel = "";
  var errorRow = "";
$( "#fe" ).validate({
  rules: {
    lan_ip: {
      required: true,
      validip: true
    },
    lan_mask: {
      required: true,
      netmask: true
    },
    dhcp_lease: {
      required: true,
      range: [1, 72]
    },
    dhcp_start: {
      required: true,
      range: [2, 254]
    },
    dhcp_limit: {
      required: true,
      range: [2, 254]
    }
  },
  messages: {
    dhcp_start:"*Must be a numeric value bigger than 2",
    dhcp_limit:"*Must be a numeric value less than 254"
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
