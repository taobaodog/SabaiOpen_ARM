<?php 
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
	$url = "/index.php?panel=administration&section=settings";
	header( "Location: $url" );
}
$proxystatus = exec("uci get sabai.proxy.status"); 
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
Settings
</div>

<div class='controlBox'><span class='controlBoxTitle'>Router Name</span>
	<div class='controlBoxContent'>

		<div class ='form-group' style='margin-bottom: 5px;'>
        	<label class='col-md-2 col-lg-1 col-sm-2' for='pingAddress'>Name</label>
        	<div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
          		<input id='host' name='host' type='text' class='form-control'>
        	</div>
      	</div> 
<!-- 		<table class='fields'>
			<tr>
				<td class='title'>Name</td>
				<td><input type='text' name = 'host' id='host'></td>
			</tr>
		</table> -->
		<button class='btn btn-default btn-sm' type='button' id='nameupdate' class='firstButton' onclick='system("hostname")' value='Update'>Update</button>
	</div>
</div>


<div class='controlBox'><span class='controlBoxTitle'>Proxy</span>
	<div class='controlBoxContent'>
		<div class ='form-group' style='margin-bottom: 5px;'>
        	<label class='col-md-3 col-lg-2 col-sm-3' for='pingAddress'>Proxy Status</label>
        	<div name='proxy' id='proxy'></div>
      	</div> 

<!-- 			<table class='fields'>
				<tr>
					<td class='title'>Proxy Status</td><td><div name='proxy' id='proxy'></div></td>
				</tr>
			</table> -->
			<br>
			<button class='btn btn-default btn-sm' type='button' id='proxyStart' class='firstButton'value='Start' onclick='proxysave("proxystart")'>Start</button>
			<button class='btn btn-default btn-sm' type='button' id='proxyStop' value='Stop' onclick='proxysave("proxystop")'>Stop</button>
		</div>
	</div>

<div class='controlBox'><span class='controlBoxTitle'>Power</span>
	<div class='controlBoxContent'>
			<button class='btn btn-default btn-sm' type='button' name='power' id='power' value='Off' class='firstButton' onclick='system("halt")'>Off</button>
			<button class='btn btn-default btn-sm' type='button' name='restart' id='restart' value='Restart' onclick='system("reboot")'>Restart</button>
		</div>
	</div>

<div class='controlBox'><span class='controlBoxTitle'>Password</span>
	<div class='controlBoxContent'>


		<div class ='form-group' style='margin-bottom: 5px;'>
        	<label class='col-md-4 col-lg-2 col-sm-4' for='pingAddress'>New Password</label>
        	<div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
          		<input id='sabaiPassword' name='sabaiPassword' type='password' class='form-control adminTextBox'>
        	</div>
      	</div>
      	<div class ='col-md-offset-4 col-lg-offset-2 col-sm-offset-4' style='margin-bottom: 5px;'>
      	<span  id="password_strength_prefix"></span><span id="password_strength"></span>
      	</div>
      	<div class ='form-group' style='margin-bottom: 5px;'>
        	<label class='col-md-4 col-lg-2 col-sm-4' for='pingAddress'>Confirm Password</label>
        	<div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
          		<input id='sabaiPWConfirm' name='sabaiPWConfirm' type='password' class='form-control adminTextBox'>
        	</div>
      	</div>

<!-- 		<table class='fields'>
			<tr>
				<td class='title'>New Password</td>
				<td><input type='password' class='adminTextBox' name = 'sabaiPassword' id='sabaiPassword'></td>
			</tr>

			<tr>	
				<td></td>
				<td><span  id="password_strength_prefix"></span><span id="password_strength"></span></td>				
			</tr>			
			

			<tr>
				<td class='title'>Confirm Password </td>
				<td><input type='password' class='adminTextBox' name='sabaiPWConfirm' id='sabaiPWConfirm'></td>
			</tr>
		</table> -->
		<br>
		<button class='btn btn-default btn-sm' type='button' id='passUpdate' class='firstButton' onclick='pass("updatepass")' value='Update'>Update</button>
		<div id='saveError'> Passwords must match.</div>
	</div>
	</div>
	<br><b>
	<span id='messages'>&nbsp;</span></b>
	<pre class='noshow' id='response'></pre>
</form>

<div id='footer'> Copyright © 2016 Sabai Technology, LLC </div>
    <div id='hideme'>
        <div class='centercolumncontainer'>
            <div class='middlecontainer'>
                <div id='hiddentext'>Please wait...</div>
                <br>
            </div>
        </div>
    </div>

</body>
</html>

<script type="text/javascript">

//Adding text to help-modal
$(document).on('click', '#helpBtn', function (e) {
  var help = "";
    help += "<p><b>Router Name</b> and <b>Password</b> can be updated by user. <b>Password MUST be updated immediately after installation.</b></p>"
    help += "<br>"
    help += "<p><b>Power off</b> or <b>restart</b> your device direct from WEB UI.</p>"
    help += "<br>"
    help += "<p><b>Proxy</b> listening to port 8080. Turned off by default.</p>"
    help += "<br>"
  $('#help-modal').find('.modal-body').html("<div class='helpModal'" +help+ "</div>");
    $('#help-modal').modal('show')
});

var f = E('fe'); 
var hidden = E('hideme'); 
var hide = E('hiddentext');
var settingsWindow, oldip='',limit=10,info=null,ini=false;

var hostname='<?php
          echo exec("uci get sabai.general.hostname");
          ?>';

$("#host").val(hostname);

	function Settingsresp(res){ 
		eval(res); 
		msg(res.msg); 
		showUi(); 
	}

	function proxysave(act){ 
		hideUi("Adjusting Proxy..."); 
		E("act").value=act;  
		$.post("php/proxy.php", $("#fe").serialize(), function(res){
		// Detect if values have been passed back
    		if(res!=""){
      		Settingsresp(res);
    		};
      		showUi();
		});
	}

	function system(act){ 
		hideUi("Processing Request..."); 
		E("act").value=act;
		$.post('php/settings.php', $("#fe").serialize(), function(res){
				$("#proxy").val(info.proxy.status);
				// Detect if values have been passed back   
    			if(res!=""){
      			Settingsresp(res);
    			};
      		showUi();
			});
		setTimeout("window.location.reload()",60000);
	}

	function pass(act){ 
		if ( $('#sabaiPassword').val() == $('#sabaiPWConfirm').val() ) {
			hideUi("Updating Credentials..."); 
			E("act").value=act;
			$.post('php/settings.php', $("#fe").serialize(), function(res){
				// Detect if values have been passed back   
    			if(res!=""){
      			Settingsresp(res);
    			};
      		showUi();
			});
			$('#saveError').hide();
		} else {
			$('#saveError').css('display', 'inline-block').css("color","#262262").css("font-weight","bold");
			}
		}


	$(function () {
        $("#sabaiPassword").bind("keyup", function () {
            //TextBox left blank.
            if ($(this).val().length == 0) {
            	$("#password_strength_prefix").html("");	
            	$("#password_strength_prefix").removeClass("adminTextBox");	
                $("#password_strength").html("");
                return;
            }
 
            //Regular Expressions.
            var regex = new Array();
            regex.push("[A-Z]"); //Uppercase Alphabet.
            regex.push("[a-z]"); //Lowercase Alphabet.
            regex.push("[0-9]"); //Digit.
            regex.push("[$@$!%*#?&]"); //Special Character.
 
            var passed = 0;
 
            //Validate for each Regular Expression.
            for (var i = 0; i < regex.length; i++) {
                if (new RegExp(regex[i]).test($(this).val())) {
                    passed++;
                }
            }
 
 
            //Validate for length of Password.
            if (passed > 2 && $(this).val().length > 8) {
                passed++;
            }
 
            //Display status.
            var color = "";
            var strength = "";
            switch (passed) {
                case 0:
                case 1:
                    strength = "Weak";
                    color = "red";
                    break;
                case 2:
                    strength = "Good";
                    color = "darkorange";
                    break;
                case 3:
                case 4:
                    strength = "Strong";
                    color = "green";
                    break;
                case 5:
                    strength = "Very Strong";
                    color = "darkgreen";
                    break;
            }
            $("#password_strength_prefix").html("Strength: ");
            $("#password_strength_prefix").addClass("adminTextBox");	
            $("#password_strength").html(strength);
            $("#password_strength").css("color", color);
        });
    });

	</script>
	