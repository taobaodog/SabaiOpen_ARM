<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=security&section=portforwarding";
	header( "Location: $url" );     
}
?>
<!DOCTYPE html>
<html>
<head>
<!--Sabai Technology - Apache v2 licence
    Copyright 2016 Sabai Technology -->
</head>
<body>
<form id="fe">
  <input type='hidden' id='pftable' name='pftable'>
  
  <div class='pageTitle'>
      <input id='helpBtn' name='helpBtn' class='helpBtn' title='Help' style="background-image: url('libs/img/help.png')"></input>
  Security: Port Forwarding
  </div>


  <div class='controlBox'>
    <span class='controlBoxTitle'>Port Forwarding</span>
    <div class='controlBoxContent'> 
     <table class="table table-striped" id="portTable">
      <thead>
        <tr>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>

        </tr>
      </thead>
      <tbody>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>        
      </tbody>
    </table>
  <div class='controlBoxFooter'>
    <button type='button' class='btn btn-default btn-sm' id='saveButton' onclick="PORTcall()" value='Save' disabled='true'>Save</button>
    <button type='button' class='btn btn-default btn-sm' id='cancelButton' value='Cancel' disabled='true'>Cancel</button>
    <span id='messages'>&nbsp;</span>
  </div>

      <div id='hideme'>
        <div class='centercolumncontainer'>
          <div class='middlecontainer'>
            <div id='hiddentext' value-'Please wait...' ></div>
            <br>
          </div>
        </div>
      </div>

      <div class="smallText">
        <br><b>Protocol</b> - Which protocol (tcp or udp) to forward. </li>
        <br><b>VPN</b> - Forward ports through the normal internet connection (WAN) or through the tunnel (VPN), or both. Note that the Gateways feature may result in may result in undefined behavior when devices routed through an interface have ports forwarded through a different interface. Additionally, ports will only be forwarded through the VPN when the VPN service is active. </li>
        <br><b>Src. Address</b> - (optional) - Forward only if from this address. Ex: "25.25.25.25". </li>
        <br><b>Src. Ports</b> - The port(s) to be forwarded, as seen from the WAN. Ex: "2345", "6112:6120". </li>
        <br><b>Dest. Port</b> - The destination port(s) inside the LAN. Ex: "80", "27015:27060". </li>
        <br><b>Dest. Address</b> - (optional) - The destination address inside the LAN. </li>
        <br><b>Description</b> - (optional) - Characters allowed: A-z, 0-9, underscore(_) and dash(-) </li>
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
  
//Adding text to help-modal
$(document).on('click', '#helpBtn', function (e) {
  var help = "";
    help += "<p><b>Port Forwarding </b>is a feature for network administration, that provides user with ability to allow external access to some ports on some LAN hosts to, for example, host your own server. Please, familiarize yourself with the concept of port forwarding prior to adding these.</p>"
    
  $('#help-modal').find('.modal-body').html("<div class='helpModal'" +help+ "</div>");
    $('#help-modal').modal('show')
});

  var hidden, hide,res;
  var f = E('fe'); 
  var hidden = E('hideme'); 
  var hide = E('hiddentext');

function PORTcall(){
  setTimeout(function(){ 
   $.post('php/portforwarding.php', function(res){
    if( res != "" ){
      eval(res);                                                                                                                                   
      msg(res.msg);                                                                                
    };
    showUi();
   });
  }, 7000);
   // Important stops the page refreshing
   return false;
} 

function PORTresp(){ 
  msg(res.rMessage); 
  showUi(); 
} 

//Confirm reload/leaving page with unsaved changes.
$(window).bind('beforeunload',function(){

   if(!$('#cancelButton').is(':disabled')){
   return "";
    }
});


$(document).ready(function() {


//////////////////////////////////////////
/*
IMPORTANT - COLUMNDEFS
Always add the ID row.
Visibility state doesnt matter but searchable
state should be set to the same value.

Always add a type.
Current supported type parameters:
text - for editable textfields (including numbers etc.)
select - for select menues, if used then options should be specified aswell
readonly - for fields with readonly attribute.

*/
//////////////////////////////////////////

var columnDefs = [{
    id: "DT_RowId",
    data: "DT_RowId",
    type: "text",
    "visible": false,
    "searchable": false
  },{
      title: "Status",
      id: "status",
      data: "status",
      type: "select",
      "options": [
      "on",
      "off"
      ]
    }, {
      title: "Protocol",
      id: "protocol",
      data: "protocol",
      type: "select",
      "options": [
      "UDP",
      "TCP",
      "Both"
      ]
    }, {
      title: "Gateway",
      id: "gateway",
      data: "gateway",
      type: "select",
      "options": [
      "LAN",
      "WAN",
      "PPTP",
      "OVPN",
      ]
    }, {
      title: "Source Address",
      id: "src",
      data: "src",
      type: "text",
      pattern: "^((?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){0,1}$",
      errorMsg: "*Invalid address - Enter valid ip.",
      hoverMsg: "(Optional) - Ex: 82.84.86.88"

    }, {
      title: "Source Port",
      id: "int",
      data: "int",
      type: "text",
      special: "portRange",
      pattern: "^([0-9]{1,4}|[1-5][0-9]{4}|6[0-4][0-9]{3}|65[0-4][0-9]{2}|655[0-2][0-9]|6553[0-5])$",
      errorMsg: "*Invalid port - Enter valid port or range.",
      hoverMsg: "Ex: 6112 (single)   or   6111:6333 (range)",
      unique: true
    }, {
      title: "Destination Port",
      id: "ext",
      data: "ext",
      type: "text",
      special: "portRange",
      pattern: "^([0-9]{1,4}|[1-5][0-9]{4}|6[0-4][0-9]{3}|65[0-4][0-9]{2}|655[0-2][0-9]|6553[0-5])$",
      errorMsg: "*Invalid port - Enter valid port or range.",
      hoverMsg: "Ex: 6221 (single)   or   7222:7333 (range)",
      unique: true
    }, {
      title: "Destination Address",
      id: "address",
      data: "address",
      type: "text",
      pattern: "^((?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){0,1}$",
      errorMsg: "*Invalid address - Enter valid ip.",
      hoverMsg: "(Optional) - Ex: 81.83.85.87"
    },{
      title: "Description",
      id: "description",
      data: "description",
      type: "text",
      pattern: "^[a-zA-Z0-9_-]*$",
      errorMsg: "*Invalid description - Allowed: A-z0-9_-",
      hoverMsg: "(Optional) - Ex: 1_Description-Text"
    }]


//Making errors show in console rather than alerts
$.fn.dataTable.ext.errMode = 'none';

$('#portTable').on( 'error.dt', function ( e, settings, techNote, message ) {
console.log( 'An error has been reported by DataTables: ', message );
} ); 

//Table creation
$('#portTable').DataTable({
  dom: 'Bfrltip', 
  ajax: "libs/data/port_forwarding.json",
    columns: columnDefs,
    select: 'single',
    altEditor: true,    
    responsive: true, 
    language: {
      "emptyTable": "No ports have been forwarded."
    }, 
    buttons: [{
            text: 'Create',
            name: 'add'        
          },
          {
            extend: 'selected', 
            text: 'Edit',
            name: 'edit'        
          },
          {
            extend: 'selected', 
            text: 'Delete',
            name: 'delete'      
          },]
        });
  } );



</script>
  <script src="libs/bootstrap.min.js"></script>
  <script src="libs/jquery.dataTables.min.js"></script>
  <script src="libs/dataTables.bootstrap.min.js"></script>
  <script src="libs/dataTables.altEditor.free.js"></script>
  <script src="libs/dataTables.buttons.min.js"></script>
  <script src="libs/buttons.bootstrap.min.js"></script>
  <script src="libs/dataTables.select.min.js"></script>
