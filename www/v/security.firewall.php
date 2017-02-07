<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=security&section=firewall";
	header( "Location: $url" );     
}
?>
<!--  TODO:
-->

<!DOCTYPE html>
<html>
<head>
<!--Sabai Technology - Apache v2 licence
    Copyright 2016 Sabai Technology -->
</head>
<body>

<form id="fe">
<div class='pageTitle'>
	 <input id='helpBtn' name='helpBtn' class='helpBtn' title='Help' style="background-image: url('libs/img/help.png')"></input>
Security: Firewall
</div>
<input type='hidden' id='act' name='act'>
<div class='controlBox'><span class='controlBoxTitle'>Firewall</span>
	<div class='controlBoxContent'>

		<div class ='form-group'>
        	<label class='col-md-4 col-lg-3 col-sm-4'>Respond to ICMP ping</label>
        	<input type="checkbox" id="respondToggle" name='respondToggle' class="slideToggle" />
			<label class="slideToggleViewport" for="respondToggle">
				<div class="slideToggleSlider">
					<div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
					<div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
					<div class="slideToggleContent slideToggleRight button"><span>Off</span></div>
				</div>
			</label>
      	</div>

      	<div class ='form-group'>
			<label class='col-md-4 col-lg-3 col-sm-4'>Allow multicast</label>
        	<input type="checkbox" id="multicastToggle" name='multicastToggle' class="slideToggle" />
			<label class="slideToggleViewport" for="multicastToggle">
				<div class="slideToggleSlider">
					<div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
					<div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
					<div class="slideToggleContent slideToggleRight button"><span>Off</span></div>
				</div>
			</label>
      	</div>

		<div class ='form-group'>
			<label class='col-md-4 col-lg-3 col-sm-4'>Enable SYN cookies</label>
       		<input type="checkbox" id="synToggle" name='synToggle' class="slideToggle" /> 
			<label class="slideToggleViewport" for="synToggle">
				<div class="slideToggleSlider">
					<div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
					<div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
					<div class="slideToggleContent slideToggleRight button"><span>Off</span></div>
				</div>
			</label>
      	</div>
      	
		<div class ='form-group'>
        	<label class='col-md-4 col-lg-3 col-sm-4'>Enable WAN access to router</label>
        	<input type="checkbox" id="wanToggle" name='wanToggle' class="slideToggle" /> 
			<label class="slideToggleViewport" for="wanToggle">
				<div class="slideToggleSlider">
					<div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
					<div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
					<div class="slideToggleContent slideToggleRight button"><span>Off</span></div>
				</div>
			</label>
      	</div>

<!-- 


		<table>
			<tr><td>Respond to ICMP ping</td>
				<td><input type="checkbox" id="respondToggle" name='respondToggle' class="slideToggle" />
					 <label class="slideToggleViewport" for="respondToggle">
					 <div class="slideToggleSlider">
					   <div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
					   <div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
					   <div class="slideToggleContent slideToggleRight button"><span>Off</span></div>
					  </div>
					 </label>
				</td>
			</tr>
			<tr><td>Allow multicast</td>
				<td><input type="checkbox" id="multicastToggle" name='multicastToggle' class="slideToggle" />
				 	<label class="slideToggleViewport" for="multicastToggle">
					 <div class="slideToggleSlider">
					   <div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
					   <div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
					   <div class="slideToggleContent slideToggleRight button"><span>Off</span></div>
					  </div>
					</label>
				</td>
			</tr>
			<tr><td>Enable SYN cookies</td>
				<td><input type="checkbox" id="synToggle" name='synToggle' class="slideToggle" /> 
					<label class="slideToggleViewport" for="synToggle">
					 <div class="slideToggleSlider">
					   <div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
					   <div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
					   <div class="slideToggleContent slideToggleRight button"><span>Off</span></div>
					  </div>
					 </label>
				</td>
			</tr>
			<tr><td>Enable WAN access to router</td>
				<td><input type="checkbox" id="wanToggle" name='wanToggle' class="slideToggle" /> 
					<label class="slideToggleViewport" for="wanToggle">
					 <div class="slideToggleSlider">
					   <div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
					   <div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
					   <div class="slideToggleContent slideToggleRight button"><span>Off</span></div>
					  </div>
					 </label>
				</td>
			</tr>
		</table> -->


    <div id='hideme'>
        <div class='centercolumncontainer'>
            <div class='middlecontainer'>
                <div id='hiddentext' value-'Please wait...' ></div>
                <br>
            </div>
        </div>
    </div>
    </div>
</div>
  <div class='controlBoxFooter'>
    <button type='button' class='btn btn-default btn-sm' id='saveButton' value='Save' onclick='FIREcall()'>Save</button>
    <button type='button' class='btn btn-default btn-sm' id='cancelButton' value='Cancel' onClick="window.location.reload()" disabled>Cancel</button>
    <span id='messages'>&nbsp;</span>
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
    help += "<p><b>Firewall -</b> is a program, that checks traffic coming in and out and sorts through it accordingly. It's usually used for blocking unauthorized or suspicious connections. A common setup in routers is to allow all outgoing traffic (assuming devices on the network are not malicious) and any incoming traffic that is part of an established connection.</p>"
    
  $('#help-modal').find('.modal-body').html("<div class='helpModal'" +help+ "</div>");
    $('#help-modal').modal('show')
});

//Detecting different changes on page
//and displaying an alert if leaving/reloading 
//the page or pressing 'Cancel'.
var somethingChanged = false;

//Click on slide button
$(document).on('click', '.slideToggleButton', function (e) {
    somethingChanged = true; 
    $("#cancelButton").removeAttr('disabled');
});

//Click on slide background
$(document).on('click', '.slideToggleContent', function (e) {
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

var hidden, hide;
var f = E('fe'); 
var hidden = E('hideme'); 
var hide = E('hiddentext');

var firewall=$.parseJSON('{<?php
          $icmp=exec("uci get sabai.firewall.icmp");
          $multicast=trim(exec("uci get sabai.firewall.multicast"));
          $cookies=trim(exec("uci get sabai.firewall.cookies"));
          $wanroute=trim(exec("uci get sabai.firewall.wanroute"));
          echo "\"icmp\": \"$icmp\",\"multicast\": \"$multicast\",\"cookies\": \"$cookies\",\"wanroute\": \"$wanroute\"";
      ?>}');

function FIREcall(){ 
	hideUi("Adjusting Firewall settings..."); 
// Pass the form values to the php file 
	$.post('php/firewall.php', $("#fe").serialize(), function(res){
// Detect if values have been passed back   
    if(res!=""){
      FIREresp(res);
    };
      showUi();
});
// Important stops the page refreshing
	return false;
} 


function FIREresp(res){ 
  eval(res); 
  msg(res.msg); 
  showUi(); 
  } 

	$('#respondToggle').prop({'checked': firewall.icmp});
	$('#multicastToggle').prop({'checked': firewall.multicast});
	$('#synToggle').prop({'checked': firewall.cookies});
	$('#wanToggle').prop({'checked':firewall.wanroute});

</script>
