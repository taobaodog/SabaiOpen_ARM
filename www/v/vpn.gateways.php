<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=vpn&section=gateways";
	header( "Location: $url" );     
}
?>
<!DOCTYPE html>
<!--Sabai Technology - Apache v2 licence
    copyright 2016 Sabai Technology -->
<meta charset="utf-8"><html>
<head>
  <script src="libs/bootstrap.min.js"></script>
  <script src="libs/jquery.dataTables.min.js"></script>
  <script src="libs/dataTables.bootstrap.min.js"></script>
  <script src="libs/dataTables.altEditor.free.js"></script>
  <script src="libs/dataTables.buttons.min.js"></script>
  <script src="libs/buttons.bootstrap.min.js"></script>
  <script src="libs/dataTables.select.min.js"></script>
</head>
<body>
<form id="fe">
<input type='hidden' id='dhcptable' name='dhcptable'>
<input type='hidden' id='act' name='act'>
	<div class='pageTitle'>
    <input id='helpBtn' name='helpBtn' class='helpBtn' title='Help' style="background-image: url('libs/img/help.png')"></input>
 Network: DHCP/Gateways
</div>

<div class='controlBox'>
	<span class='controlBoxTitle'>Summary</span>
	<div class='controlBoxContent'>
      <table class="table table-striped" id="gateTable">
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
          </tr>        
        </tbody>
      </table>

  <div class='controlBoxFooter'>
    <button type='button' class='btn btn-default btn-sm' id='saveButton' onclick='DHCPcall("save")' value='Save'>Save</button>
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
			<br><b>Click a device entry and click Edit to adjust the information provided:</b></li>
			<br><b>IP Address: </b> The IP address assigned to the device. You can click in this field and change the IP address. </li>
      			<br><b>MAC: </b> The hardware address of the unit. This is hardcoded into the device. </li>
			<br><b>Name: </b> The name of the device. You can click in this field and change the name. </li>
			<br><b>Static: </b> Choose "on" to make lease permanent. </li>
			<br><b>Expiration time: </b> The time when the lease expires.  </li>
			<br><b>Status: </b> Current status of the device. </li>
			<br><b>Routing option: </b>Choose the default route for this device. "vpn_fallback" will continue access through internet if VPN is down. </li>
		</div>
	</div>	
</div>
<p>
	<div id='footer'>Copyright © 2016 Sabai Technology, LLC</div>
</p>
</form>
</body>
</html>
<script type='text/javascript'>

//Adding text to help-modal
$(document).on('click', '#helpBtn', function (e) {
  var help = "";
    help += "<p><b>Gateway - </b>A machine that serves internet; on most LANs this is the device the router's WAN connects to (like your modem). Sabai routers have the special gateway feature which gives the user simple access to both their local ISP's gateway and their remote VPN's gateway. SabaiOpen affords to set Accelerator as gateway to enhance facilities of network. New feature is anonymizing agateway TOR.</p>"
   
  $('#help-modal').find('.modal-body').html("<div class='helpModal'" +help+ "</div>");
    $('#help-modal').modal('show')
});

  var hidden, hide,res;
  var f = E('fe'); 
  var hidden = E('hideme'); 
  var hide = E('hiddentext');

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
  }, {
    id: "ip",
    title: "IP address",
    data: "ip",
    type: "text",
    pattern: "^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$",
    errorMsg: "*Invalid address - Enter valid ip",
    hoverMsg: "Ex: 82.84.86.88",
    unique: true
  }, {
    id: "mac",
    title: "Mac",
    data: "mac",
    type: "readonly"
  }, {
    id: "name",
    title: "Name",
    data: "name",
    type: "text",
    pattern: "^[a-zA-Z0-9_-]+$",
    errorMsg: "*Invalid name - Allowed: A-z0-9 _ -",
    hoverMsg: "Ex: UserPhone-22_Android",
    unique: true
  }, {
    id: "static",
    title: "Static",
    data: "static",
    type: "select",
    "options": [
    "on",
    "off"
    ]
  }, {
    id: "time",
    title: "Expiration time",
    data: "time",
    type: "readonly"
  }, {
    id: "stat",
    title: "Status",
    data: "stat",
    type: "readonly"
  }, {
    id: "route",
    title: "Routing option",
    data: "route",
    type: "select",
    "options": [
    "default",
    "local",
    "vpn_fallback",
    "vpn_only",
    "accelerator",
    "tor"
    ]
  }];

//Making errors show in console rather than alerts
$.fn.dataTable.ext.errMode = 'none';

$('#gateTable').on( 'error.dt', function ( e, settings, techNote, message ) {
console.log( 'An error has been reported by DataTables: ', message );
} );
$.post('php/dhcp.php', {'act': 'get'})
   .done(function(res) {            
//Creating table
 var table = $('#gateTable').DataTable( {
    dom: "Bfrl<'#routeGlobal'>tip",
    ajax: "libs/data/dhcp.json",        
    columns: columnDefs,
    select: "single",
    altEditor: true,
    responsive: true,
    language: {
      "emptyTable": "No connected hosts."
    },
    buttons: [{ 
            extend: 'selected', 
            text: 'Edit',
            name: 'edit'        
    },{ 
            text: 'Refresh',
            name: 'refresh'        
    }]
  });


   //Auto-refresh of the table
  setInterval( function () {
    if($('#cancelButton').is(':disabled')){
      $.post('php/dhcp.php', {'act': 'get'})
      .done(function(res){
      table.ajax.reload();
      })
    console.log("Datatable auto-reloaded")
    }
  }, 5000 );


  (function createGlobalSettings(){
    var divContainer = "";
    var options = "";
    var defaultSetting;

    for (var i = 0; i < columnDefs[7].options.length; i++) {
      options += "<option value='" + columnDefs[7].options[i] + "'>" + columnDefs[7].options[i] + "</option>";
    }

    divContainer += "<div class='row' style='border-top: 1px;border-top-style: dashed;padding-top: 5px'>"
    divContainer += "<div class='col-sm-5 col-md-5 col-lg-5'>Default setting: "
    divContainer += "<div id='defaultButtons' class='btn-group' data-toggle='buttons'>"
    divContainer +=  "<label class='btn btn-default'><input type='radio' name='none' id='none' autocomplete='off'> None</label>"
    divContainer +=  "<label class='btn btn-default'><input type='radio' name='local' id='local' autocomplete='off'>Local</label>"
    divContainer +=  "<label class='btn btn-default'><input type='radio' name='vpn_only' id='vpn_only' autocomplete='off'>VPN</label>"

    divContainer +=  "<label class='btn btn-default'><input type='radio' name='accelerator' id='accelerator' autocomplete='off'>Accelerator</label></div></div>"
    divContainer += "<div class= 'col-sm-offset-4 col-sm-3 col-md-offset-4 col-md-3 col-lg-offset-4 col-lg-3'>Assign all to: "
    divContainer += "<select id='changeSelect' class='form-control'>" + options + "</select></div>"
    divContainer += "</div>"

    $("#routeGlobal").append(divContainer);


    table.on( 'xhr', function () {  
     if(typeof defaultSetting === 'undefined'){
       defaultSetting = table.ajax.json().defSetting;
     }else if(defaultSetting != table.ajax.json().defSetting){
       $("#"+defaultSetting).parent().removeClass('active');  
       defaultSetting = table.ajax.json().defSetting;
     }
     $("#"+defaultSetting).parent().addClass('active');
   });

    
  })();

    //Dropdown for changing 'Routing option' in all rows         
    $(document).on('change', '#changeSelect', function(e){
    var val = $(this).find("option:selected").attr('value');
      for(var i = 0, columnLength = table.columns(7).data()[0].length; i < columnLength; i++){
        table.row(i).data().route = val;
        table.row(i).invalidate(); 
      }
       $("#cancelButton").prop('disabled', false);          
    });   

    $(document).on('click', '#defaultButtons', function(e){
      $("#cancelButton").prop('disabled', false);          
    });

    $(document).on('click', '#cancelConfirm', function(e){
      $("#defaultButtons").children().removeClass('active');   
    });

  });
});

function DHCPcall(act){ 
    E("act").value=act;
      hideUi("Adjusting DHCP settings..."); 
      // Pass the form values to the php file 
      setTimeout(function(){
        $.post('php/dhcp.php', {'act': act })
          .done(function(res) {
            eval(res);
            msg(res.msg);                                                                            
            showUi();
          });
      }, 7000);
      // Important stops the page refreshing
      return false;
}

</script>
   
