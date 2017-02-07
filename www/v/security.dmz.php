<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=security&section=dmz";
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
<input type='hidden' id='act' name='act'>
<div class='pageTitle'>
	<input id='helpBtn' name='helpBtn' class='helpBtn' title='Help' style="background-image: url('libs/img/help.png')"></input>
Security: DMZ
</div>
<!-- TODO: -->

<div class='controlBox'><span class='controlBoxTitle'>DMZ</span>
	<div class='controlBoxContent'>

		<div class ='form-group' style='margin-bottom: 5px;'>
		<input type="checkbox" id="dmzToggle" name='dmzToggle' class="slideToggle"/>
		<label class="slideToggleViewport" for="dmzToggle">
			<div class="slideToggleSlider">
			  <div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
			  <div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
			  <div class="slideToggleContent slideToggleRight button"><span>Off</span></div>
			</div>
		</label>
		</div>

		<div class ='form-group' style='margin-bottom: 5px;'>
      		<label  for='dmz_destination'>Destination Address</label>
      		<input id='dmz_destination' name='dmz_destination' type='text' class='form-control '>
    	</div>

<!-- 

		<table>
		 	<tr><td>Destination Address</td> <td><input id='dmz_destination' name='dmz_destination'></input><td></tr>
		</table> -->


		<div><span class='xsmallText'>
			(optional; ex: "1.1.1.1", "1.1.1.0/24", "1.1.1.1 - 2.2.2.2" or "me.example.com")
    <div id='hideme'>
        <div class='centercolumncontainer'>
            <div class='middlecontainer'>
                <div id='hiddentext' value-'Please wait...' ></div>
                <br>
            </div>
        </div>
        </div>
        </span>
        </div>
        </td>
        </td>
        </tr>
        </table>
        </div>
        </div>
<button class='btn btn-default btn-sm' type='button' value='Save' onclick='DMZcall()'>Save</button><span id='messages'>&nbsp;</span>
<p>
        <div id='footer'>Copyright © 2016 Sabai Technology, LLC</div>
</p>
</form>

</body>
</html>

<script type='text/ecmascript'>

//Adding text to help-modal
$(document).on('click', '#helpBtn', function (e) {
  var help = "";
    help += "<p><b>DMZ -</b> DeMilitarized Zone. A network security concept of a LAN machine opened to WAN but doesn't allow to initiate LAN connections. Use this is you have a firewall set up on said machine and would like to have a 'transparent' router.</p>"
    
  $('#help-modal').find('.modal-body').html("<div class='helpModal'" +help+ "</div>");
    $('#help-modal').modal('show')
});

var hidden, hide;
var f = E('fe'); 
var hidden = E('hideme'); 
var hide = E('hiddentext');
var dmz=$.parseJSON('{<?php
          $status=exec("uci get sabai.dmz.status");
          $destination=trim(exec("uci get sabai.dmz.destination"));
          echo "\"status\": \"$status\",\"destination\": \"$destination\"";
      ?>}');

function DMZcall(){ 
	hideUi("Adjusting DMZ settings..."); 
	// Pass the form values to the php file 
	$.post('php/dmz.php', $("#fe").serialize(), function(res){
	// Detect if values have been passed back   
	if(res!=""){
		DMZresp(res);
    	};
});
	// Important stops the page refreshing
	return false;
} 


function DMZresp(res){ 
	eval(res); 
  	msg(res.msg); 
	setTimeout(function(){showUi()},2000); 
} 

$('#dmz_destination').ipspinner().ipspinner('value',dmz.destination).spinner({disabled: false });

if (dmz.status.trim() == 'on') {                                                                        
	$('#dmzToggle').prop({ 'checked': true });
} else {                                                                                                             
	$('#dmzToggle').prop({ 'checked': false });
};
</script>

