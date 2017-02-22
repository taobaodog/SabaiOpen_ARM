<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=security&section=upnp";
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
<div class='pageTitle'>
	<input id='helpBtn' name='helpBtn' class='helpBtn' title='Help' style="background-image: url('libs/img/help.png')"></input>
 Security: UPnP
</div>

<div class='controlBox'><span class='controlBoxTitle'>Settings</span>
	<div class='controlBoxContent'>
		<div class ='form-group'>
        	<label class='col-md-4 col-lg-3 col-sm-5'>Enable UPnP</label>
        	<input type="checkbox" id="enableToggle" name='enableToggle' class="slideToggle" />
			<label class="slideToggleViewport" for="enableToggle">
				<div class="slideToggleSlider">
					<div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
					<div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
					<div class="slideToggleContent slideToggleRight button"><span>Off</span></div>
				</div>
			</label>
      	</div>

      	<div class ='form-group'>
			<label class='col-md-4 col-lg-3 col-sm-5'>Enable NAT-PMP</label>
        	<input type="checkbox" id="natpmpToggle" name='natpmpToggle' class="slideToggle" />
			<label class="slideToggleViewport" for="natpmpToggle">
				<div class="slideToggleSlider">
					<div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
					<div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
					<div class="slideToggleContent slideToggleRight button"><span>Off</span></div>
				</div>
			</label>
      	</div>

		<div class ='form-group'>
			<label class='col-md-4 col-lg-3 col-sm-5'>Inactive Rules Cleaning</label>
       		<input type="checkbox" id="cleanToggle" name='cleanToggle' class="slideToggle" /> 
			<label class="slideToggleViewport" for="cleanToggle">
				<div class="slideToggleSlider">
					<div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
					<div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
					<div class="slideToggleContent slideToggleRight button"><span>Off</span></div>
				</div>
			</label>
      	</div>
      	
		<div class ='form-group'>
        	<label class='col-md-4 col-lg-3 col-sm-5'>Secure Mode</label>
        	<input type="checkbox" id="secureToggle" name='secureToggle' class="slideToggle" /> 
			<label class="slideToggleViewport" for="secureToggle">
				<div class="slideToggleSlider">
					<div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
					<div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
					<div class="slideToggleContent slideToggleRight button"><span>Off</span></div>
				</div>
			</label>
      	</div>
	</div>
</div>
<!-- 

		<table><tbody>
		<tr><td>Enable UPnP</td>
			<td><input type="checkbox" id="enableToggle" name='enableToggle' class="slideToggle" />
				 <label class="slideToggleViewport" for="enableToggle">
				 <div class="slideToggleSlider">
				   <div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
				   <div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
				   <div class="slideToggleContent slideToggleRight button"><span>Off</span></div>
				  </div>
				 </label>
			</td>
		</tr>
		<tr><td>Enable NAT-PMP</td>
			<td><input type="checkbox" id="natpmpToggle" name='natpmpToggle' class="slideToggle" />
				 <label class="slideToggleViewport" for="natpmpToggle">
				 <div class="slideToggleSlider">
				   <div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
				   <div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
				   <div class="slideToggleContent slideToggleRight button"><span>Off</span>
				 </label></div>
				  </div>				 
			</td>
		</tr>
		<tr><td>Inactive Rules Cleaning</td>
			<td><input type="checkbox" id="cleanToggle" name='cleanToggle' class="slideToggle" />
			 	<label class="slideToggleViewport" for="cleanToggle">
				 <div class="slideToggleSlider">
				   <div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
				   <div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
				   <div class="slideToggleContent slideToggleRight button"><span>Off</span></div>
				  </div>
				</label>
			</td>
		</tr>
		<tr><td>Secure Mode</td>
			<td><input type="checkbox" id="secureToggle" name='secureToggle' class="slideToggle" /> 
				<label class="slideToggleViewport" for="secureToggle">
				 <div class="slideToggleSlider">
				   <div class="slideToggleButton slideToggleButtonBackground">&nbsp;</div>
				   <div class="slideToggleContent slideToggleLeft button buttonSelected"><span>On</span></div>
				   <div class="slideToggleContent slideToggleRight button"><span>Off</span></div>
				  </div>
				 </label>
			</td>
		</tr>
		<tr>
			<td> </td>
			<td><span class='xsmallText'>
				NAT-PMP requires UPnP to be on.</span>
			</td>
		</tr>
	</tbody></table> -->

<div class='controlBox'><span class='controlBoxTitle'>Allowed UPnP Ports*</span>
	<div class='controlBoxContent'>

		<div class ='form-group' style='margin-bottom: 5px;'>
      		<label class='col-md-4 col-lg-3 col-sm-4'  for='intmin'>Destination Address</label>
      		<input id='intmin' name='intmin' class='shortinput'/> - 
      		<input id='intmax' name='intmax' class='shortinput'/>
    	</div>	

		<div class ='form-group' style='margin-bottom: 5px;'>
      		<label id='intminLabel' name='intminLabel' class='errorLabel col-md-offset-4 col-lg-offset-3 col-sm-offset-4'/>
    	</div>	

    	<div class ='form-group' style='margin-bottom: 5px;'>
      		<label id='intmaxLabel' name='intmaxLabel' class='errorLabel col-md-offset-4 col-lg-offset-3 col-sm-offset-4'/>
    	</div>	

		<div class ='form-group' style='margin-bottom: 5px;'>
      		<label class='col-md-4 col-lg-3 col-sm-4'  for='extmin'>External Ports</label>
      		<input id='extmin' name='extmin' class='shortinput'/> - 
      		<input id='extmax' name='extmax' class='shortinput'/>
    	</div>

    	<div class ='form-group' style='margin-bottom: 5px;'>
      		<label id='extminLabel' name='extminLabel' class='errorLabel col-md-offset-4 col-lg-offset-3 col-sm-offset-4'/>
    	</div>	

    	<div class ='form-group' style='margin-bottom: 5px;'>
      		<label id='extmaxLabel' name='extmaxLabel' class='errorLabel col-md-offset-4 col-lg-offset-3 col-sm-offset-4'/>
    	</div>		    	    	

<!-- 
	<table>
		<tbody>
			<tr>
				<td>Internal Ports</td>
				<td><input id='intmin' name='intmin' class='shortinput'/> - <input id='intmax' name='intmax' class='shortinput'/>
			</tr>

			<tr><td></td><td><label id='intminLabel' name='intLabel' class='errorLabel'/></td></tr>
			<tr><td></td><td><label id='intmaxLabel' name='intLabel' class='errorLabel'/></td></tr>
			<tr><td><br></td><td></td></tr>
			<tr>
				<td>External Ports</td>
				<td><input id='extmin' name='extmin' class='shortinput'/> - <input id='extmax' name='extmax' class='shortinput'/>
			</tr>
			<tr><td><br></td><td><label id='extminLabel' name='extminLabel' class='errorLabel'/></td></tr>
			<tr><td></td><td><label id='extmaxLabel' name='extmaxLabel' class='errorLabel'/></td></tr>
		</tbody>
	</table>

 -->








		<br>
		<span class='xsmallText'> *Values must be between 1024-65535 and startport must be less than endport</span>
		<br>
		<span class='xsmallText'> *Setting lower bound to less than 1024 may interfere with network services</span>
		<br>
		<span class='xsmallText'> *UPnP clients will only be allowed to map ports in the external range to ports in the internal range</span>

	</div>
</div>
  <div class='controlBoxFooter'>
    <button type='button' class='btn btn-default btn-sm' id='saveButton' value='Save' onclick='UPNPcall()'>Save</button>
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
    </td></tr></td></tr></tbody></table></div>
    	<div id='footer'> Copyright © 2016 Sabai Technology, LLC </div>
	</div>
</form>

</body>
</html>

<script type='text/javascript'>

//Adding text to help-modal
$(document).on('click', '#helpBtn', function (e) {
  var help = "";
    help += "<p><b>UPnP</b> stands for “Universal Plug and Play.” Using UPnP an application can automatically forward a port on your router. This is a security risk, so you're advised to keep UPnP off and forward ports manually. It is disabled by default because of security risks.</p>"
    
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

var hidden, hide, pForm = {}; pForm2 = {}

var f = E('fe'); 
var hidden = E('hideme'); 
var hide = E('hiddentext');

var upnp=$.parseJSON('{<?php
          	$enable=exec("uci get sabai.upnp.enable");
          	$natpmp=exec("uci get sabai.upnp.natpmp");
			$clean=exec("uci get sabai.upnp.clean");
			$secure=exec("uci get sabai.upnp.secure");
			$intmin=exec("uci get sabai.upnp.intmin");
			$intmax=exec("uci get sabai.upnp.intmax");
			$extmin=exec("uci get sabai.upnp.extmin");
			$extmax=exec("uci get sabai.upnp.extmax");
        echo "\"enable\": \"$enable\",\"clean\": \"$clean\",\"secure\": \"$secure\",\"intmin\": \"$intmin\",\"intmax\": \"$intmax\",\"extmin\": \"$extmin\",\"extmax\": \"$extmax\"";
      ?>}');

	$('#enableToggle').prop({'checked': upnp.enable});
	$('#natpmpToggle').prop({'checked': upnp.enable});
	$('#cleanToggle').prop({'checked': upnp.clean});
	$('#secureToggle').prop({'checked': upnp.secure});

function UPNPcall(){ 
  hideUi("Adjusting UPNP settings..."); 
$(document).ready( function(){
// Pass the form values to the php file 
$.post('php/upnp.php', $("#fe").serialize(), function(res){
  // Detect if values have been passed back   
    if(res!=""){
      UPNPresp(res);
    }
      showUi();
});
 
// Important stops the page refreshing
return false;

}); 

}

function UPNPresp(res){ 
  eval(res); 
  msg(res.msg); 
  showUi(); 
  if(res.sabai){ 
    limit=10; 
    getUpdate(); 
  } 
}

//end of wm add

	$('#intmin').spinner({ min: 1024, max: 65534 }).spinner('value',upnp.intmin);
		$('#intmax').spinner({ min: 1025, max: 65535 }).spinner('value',upnp.intmax);
		$('#extmin').spinner({ min: 1024, max: 65534 }).spinner('value',upnp.extmin);
		$('#extmax').spinner({ min: 1025, max: 65535 }).spinner('value',upnp.extmax);

	function changeRange(){
		if($('#advanced').is(':checked')){
			$('#intmin').spinner({ min: 2, max: 65534 });
			$('#intmax').spinner({ min: 3, max: 65535 });
			$('#extmin').spinner({ min: 2, max: 65534 });
			$('#extmax').spinner({ min: 3, max: 65535 });
		} else {
			$('#intmin').spinner({ min: 1024, max: 65534 });
			$('#intmax').spinner({ min: 1025, max: 65535 });
			$('#extmin').spinner({ min: 1024, max: 65534 });
			$('#extmax').spinner({ min: 1025, max: 65535 });
		}

	};

//validate the fields
$(function() {
  var errorLabel = "";
$( "#fe" ).validate({
  rules: {
    intmin: {
      number: true,
      required: true,
      range: [1024, 65534]
    },
    intmax: {
      number: true,
      required: true,
      range: [1025, 65535]
    },
    extmin: {
      number: true,
      required: true,
      range: [1024, 65534]
    },
    extmax: {
      number: true,
      required: true,
      range: [1025, 65535]
    }
  },
  messages: {
    intmin:{
    	required: "*Startport is required.",
    	range: "*Startport must be higher than 1024 and less than endport.",
    	number: "*Startport must be a valid number."
    },
    intmax:{
    	required: "*Endport is required.",
    	range: "*Endport must be less than 65535 and higher than startport.",
    	number: "*Endport must be a valid number."
    },
    extmin:{
    	required: "*Startport is required.",
    	range: "*Startport must be higher than 1024 and less than endport.",
    	number: "*Startport must be a valid number."
    },
    extmax:{
    	required: "*Endport is required.",
    	range: "*Endport must be less than 65535 and higher than startport.",
    	number: "*Endport must be a valid number."
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
