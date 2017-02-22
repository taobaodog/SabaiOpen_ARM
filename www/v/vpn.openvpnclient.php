<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=vpn&section=openvpnclient";
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
	<div class='pageTitle'>
		<input id='helpBtn' name='helpBtn' class='helpBtn' title='Help' style="background-image: url('libs/img/help.png')"></input>
		VPN: OpenVPN Client
	</div>
	<div class='controlBox'><span class='controlBoxTitle'>OpenVPN Settings</span>
		<div class='controlBoxContent'>
			<body onload='init();' id='topmost'>
				<form id='new_file' method="post" enctype="multipart/form-data">
					<input id='act' type='hidden' name='act' value='newfile'>
					<span id='ovpn_file'></span>
					<br>
					<span id='upload'>
						<p>
							<ul class="list-inline">
								<li><input id='browse' type='file' name='browse'></li>
								<li><button id='submit' class='btn btn-default btn-sm' type='button' value='Upload'>Upload</button></li>
							</ul>
						</p>
					</span>

					<p>
						<span id='messages'>&nbsp;</span>
					</p>
					<div id='hideme'>
						<div class='centercolumncontainer'>
							<div class='middlecontainer'>
								<div id='hiddentext'>Please wait...</div>
							</div>
						</div>
					</div>
				</form>
				<form id='fe'>
					<span id='ovpn_controls'>
						<input type='hidden' id='_act' name='act' value=''>
						<button class='btn btn-default btn-sm' type='button' value='Start' onclick='OVPNsave("start");'>Start</button>
						<button class='btn btn-default btn-sm' type='button' value='Stop' onclick='OVPNsave("stop");'>Stop</button>
						<button class='btn btn-default btn-sm' id='clear' type='button' value='Clear' onclick='OVPNsave("clear");'>Clear</button></span>
						<button class='btn btn-default btn-sm' type='button' value='Show Log' id='logButton' onclick='toggleLog();'>Show Log</button>
						<button class='btn btn-default btn-sm' type='button' value='Edit Config' id='editButton' onclick='toggleEdit();'>Edit Config</button>
					</div>



					<textarea id='response' class='hiddenChildMenu'></textarea>
					<div id='edit' class='hiddenChildMenu'>

						<div class ='form-group' style='margin-bottom: 5px;'>
                    	<label class='col-md-2 col-lg-2 col-sm-2' for='VPNname'>Name:</label>
                    		<div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
                        	<input id='server' name='VPNname' type='VPNname' placeholder='(optional)' class='form-control'>
                    		</div>
                		</div>

						<div class ='form-group' style='margin-bottom: 5px;'>
                    	<label class='col-md-2 col-lg-2 col-sm-2' for='VPNpassword'>Password:</label>
                    		<div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
                        	<input id='server' name='VPNpassword' type='VPNpassword' placeholder='(optional)' class='form-control'>
                    		</div>
                		</div>

<!-- 						<table>
							<tr><td>Name: </td><td><input type='text' name='VPNname' id='VPNname' placeholder='(optional)'></td></tr>
							<tr><td>Password:</td><td><input type='text' name='VPNpassword' id='VPNpassword' placeholder='(optional)'></td></tr>
						</table> -->

						<br>
						<textarea id='conf' class='tall' name='conf'>
							<?php 
							$file = '/etc/sabai/openvpn/ovpn.current';
							if (file_exists($file)) {
								readfile($file);
							} 
							?>
						</textarea> <br>
						<input type='button' value='Save' onclick='saveEdit();'>
						<input type='button' value='Cancel' onclick='window.location.reload();'>
					</div>
				</tbody>
			</table>
		</div>
	</form>
	<div id='hideme'>
		<div class='centercolumncontainer'>
			<div class='middlecontainer'>
				<div id='hiddentext'>Please wait...</div>
				<br>
			</div>
		</div>
	</div>
	<p>
		<div id='footer'>Copyright © 2016 Sabai Technology, LLC</div>
	</p>
</body>
</html>

<script type='text/javascript'>

//Adding text to help-modal
$(document).on('click', '#helpBtn', function (e) {
  var help = "";
    help += "<p><b>OVPN client </b>uses configuration file *.ovpn. Upload your configuration file from your PC direct to device and get VPN working. Start/Stop buttons are for VPN process managemet. Edit Config button will help in case if any changes to file are needed to be done. In case of any problem can be usefull to see log by pushing Show Log.</p>"
  
  $('#help-modal').find('.modal-body').html("<div class='helpModal'" +help+ "</div>");
    $('#help-modal').modal('show')
});


		var f,oldip='',limit=10,logon=false,info=null;
		var ovpnTry = 0;
		var hidden, hide, pForm = {};

		function setLog(res){ 
			E('response').value = res; 
		}

		function saveEdit(){ 
			hideUi("Adjusting OpenVPN..."); 
			E("_act").value='save'; 
			que.drop( "php/ovpn.php", OVPNresp, $("#fe").serialize() );
		}

		function toggleEdit(){
		 $('#ovpn_controls').hide();
		 E('logButton').style.display='none';
		 E('edit').className='';
		 E('editButton').style.display='none';
<?php
        if (file_exists('/etc/sabai/openvpn/auth-pass')) {
                $authpass = file('/etc/sabai/openvpn/auth-pass');
                echo "uname =  '";
                echo rtrim($authpass[0]);
                echo "';\npass = '" . rtrim($authpass[1]) . "';\n";
}
?>
 	         typeof uname === 'undefined' || $('#VPNname').val(uname);
                 typeof pass === 'undefined'  || $('#VPNpassword').val(pass);		

		 // var conf=E('conf');
		 // var leng=(conf.value.match(/\n/g)||'').length;
		 // conf.style.height=(leng<15?'15':leng)+'em';
		}

		function toggleLog(){
		 if(logon=!logon){ 
		 	que.drop('php/ovpn.php', setLog, 'act=log'); 
		 }
		 E('logButton').value = (logon?'Hide':'Show') + " Log";
		 E('response').className = (logon?'tall':'hiddenChildMenu');
		 $('#editButton').toggle();
		}

		function load(){
		var ovpnfile='<?php $filename=exec('uci get openvpn.sabai.filename'); echo $filename; ?>';
		document.getElementById('ovpn_file').innerHTML = ovpnfile;
		E('ovpn_file').innerHTML = 'Current File: ' + ovpnfile;
		 msg('Please supply a .conf/.ovpn configuration file.');
		}

		function setUpdate(res){ 
			if(info) oldip = info.vpn.ip; 
			eval(res); 
			for(i in info.vpn){ 
		 		E('vpn'+i).innerHTML = info.vpn[i]; 
		 	}
		 	for(i in info.tor_proxy){ 
		 		E('tor_'+i).innerHTML = info.tor_proxy[i]; 
		 	}
			if (info.vpn.status == "Connected" && info.vpn.type == 'OpenVPN') {
				E('clear').hidden = true;
	    	} else {
				E('clear').hidden = false;
			}
		}

		function getUpdate(ipref){ 
			que.drop('php/info.php',setUpdate,ipref?'do=ip':null); 
	   $.get('php/get_remote_ip.php', function( data ) {
	     donde = $.parseJSON(data);
	     console.log(donde);
	     for(i in donde) E('loc'+i).innerHTML = donde[i];
	   });
		}

		function OVPNresp(res){ 
			eval(res); 
			msg(res.msg); 
			showUi(); 
			if(res.reload){ 
				window.location.reload(); 
			}; 
			if(res.sabai){ 
				limit=10; getUpdate(); 
			} 
		}

		function OVPNsave(act){ 
			hideUi("Adjusting OpenVPN..."); 
			E("_act").value=act;
			if (act=='start') {
				if (info.vpn.type == 'PPTP') {
					hideUi("PPTP will be stopped.");
					$.post('php/pptp.php', {'switch': 'stop'}, function(res){
						if(res!=""){
							eval(res);
							hideUi(res.msg);
							OVPNcall();
						}
					});
				/* } else if (info.vpn.type == 'TOR') {
					hideUi("TOR will be stopped.");
					$.post('php/tor.php', {'switch': 'off'}, function(res){
						if(res!=""){
							eval(res);
							hideUi(res.msg);
							OVPNcall();
						}
					}); */
				} else {
					OVPNcall();
				}
			} else {
				$.post("php/ovpn.php", $("#fe").serialize(), function(res){
					if(res!=""){
						OVPNresp(res);
					}
					showUi();
				});  
			}
		}

		function OVPNcall(){
			$.post("php/ovpn.php", $("#fe").serialize(), function(res){
				if(res!=""){
					eval(res);
					hideUi(res.msg);
					setTimeout(function(){hideUi("Checking OVPN status...")},5000);
					setTimeout(check,10000);
				}
			});
		}

		
		function init(){ 
			f = E('fe'); 
			hidden = E('hideme'); 
			hide = E('hiddentext'); 
			load(); 
	   getUpdate();
	   setInterval (getUpdate, 5000); 
	}

		function check(){
			E("_act").value='check';
			$.post('php/ovpn.php', $("#fe").serialize(), function(res){
				if(res.indexOf("not start") < 0){
					OVPNresp(res);
					showUi();
				} else {
					if (ovpnTry < 3) {
						setTimeout(check,10000);
						ovpnTry++;
					} else {
						OVPNresp(res);
						showUi();
					}
				}
			});
		}

$(document).ready(function(){
	$("#submit").on("click", function() {
		hideUi("Uploading ...");
		var ovpnFile=$("#browse").val();
		E("act").value='newfile';
		if ( ovpnFile != '') {
			$("#new_file").submit(function() { return false; });
			var form = document.forms.new_file;
			var formData = new FormData(form);
			var xhr = new XMLHttpRequest();
			xhr.open("POST", "php/ovpn.php");
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4) {
					if(xhr.status == 200) {
						var res = eval(xhr.responseText);
						hideUi(res.msg);
						setTimeout(function(){showUi()},3000);
						E('ovpn_file').innerHTML = "Current File: " + res.file;
						setTimeout(function(){OVPNresp(res)},1000);
					} else {
						hideUi("Failed to upload the file.");
						setTimeout(function(){showUi()},3000);
					}
				}
			};
			xhr.send(formData);
			//setTimeout(function(){window.location.reload()},3000);
		} else {
			$('input[type="file"]').css("border","2px solid red");
			$('input[type="file"]').css("box-shadow","0 0 3px red");
			hideUi('Please, choose the file!');
			setTimeout(function(){showUi()},2000);
		}
	});
});


</script>
