<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=vpn&section=openvpnserver";
	header( "Location: $url" );     
}
?>
<!DOCTYPE html>
<html>
<!--Sabai Technology - Apache v2 licence
    Copyright 2016 Sabai Technology -->

<body onload='init();' id='topmost'>

<div class='pageTitle'>VPN: OpenVPN Server</div>
<div class='controlBox'>

<span class='controlBoxTitle'>Server Control</span>

<div class='controlBoxContent'>
	<table style='xfont: bold 18pt Arial'>
	<tr><td width='200'>
<input id='setupErase' type='button' value='Setup' onclick='setupBut(this.value, true)'/>&nbsp;&nbsp;&nbsp;
<select id='keyLen'>
    <option value='1024'>good (1024)</option>
    <option value='2048' SELECTED>optimal (2048)</option>
    <option value='4096'>best (4096)</option>
</select>&nbsp; &nbsp; &nbsp;
<input id='startStop' type='button' value='Start' onclick='startBut(this.value, true)' style='xfont: bold 18pt Arial; display:none'></td>
	<td id='msg2' style='font: 10pt Arial'>Select the key length and click on Setup to create the vpn server</td></tr>
	</table></div>


<span class='controlBoxTitle'>Server Client(s) &nbsp; &nbsp; <input id='clientName' type='text'/>&nbsp;&nbsp;&nbsp;<input type='button' onclick="oVPNserver('client', E('clientName').value)" value='Create Client'/></span>

<div class='controlBoxContent'>
	<div id='clientList'></div></div>


<span class='controlBoxTitle'>Server Status &nbsp; &nbsp; <span id='stat'>.</span></span>

<div class='controlBoxContent'>
	<div><input id='check' type='button' value='Check' onclick='checkServer(true)' style='xfont: bold 18pt Arial'/></div></div>
</div>


<div id='hideme'>
	<div class='centercolumncontainer'>
	<div class='middlecontainer'><div id='hiddentext'>Please wait...</div></div>
        </div>
</div>

<div id='footer'>Copyright © 2016 Sabai Technology, LLC</div>
</body>

<script type='text/javascript'>
	var hidden,hide

function oVPNserver(action, parm1) {
	var apiAction = action.toLowerCase();
	hideUi('OpenVPN Server ' +apiAction +' requested');
	var parms= parm1 == '' ? {'action': apiAction} : {'action': apiAction, 'parm1': parm1};
	$.post('php/ovpnserver.php', parms, function(res) {
		if (res != '') {
			eval(res);
			console.log(res)
			hideUi(apiAction +' response: ' +res.msg);

		} else {
			hideUi('No response from ' +apiAction);
		}
		setTimeout(function(){showUi()},3000);
	});
}

function setupBut(btnVal, doit) { //toggle Setup/Erase button
    if (btnVal == 'Setup') { E('setupErase').value = 'Erase'
	startBut('Stop', false)
	E('startStop').style.display = 'inline'; E('keyLen').style.display = 'none'
	E('msg2').innerHTML = 'Click on the start button to run the server'
	if (doit) oVPNserver('setup', E('keyLen').options[E('keyLen').selectedIndex].value);
    } else { E('setupErase').value = 'Setup'
	E('startStop').style.display = 'none'; E('keyLen').style.display = 'inline'
	E('msg2').innerHTML = 'Select the key length and click on Setup to create the vpn server'
	if (doit) oVPNserver('clear')
    }
}

function startBut(btnVal, doit) { //toggle Start/Stop button
    if (btnVal == 'Start') { E('startStop').value = 'Stop'
	E('msg2').innerHTML = 'Click stop button to disable the openVPN server'
	if (doit) oVPNserver('start')
    } else { E('startStop').value = 'Start'
	E('msg2').innerHTML = 'Click start button to enable the openVPN server'
	if (doit) oVPNserver('stop')
    }
}

function clientTable(clientList) { //display list of clients
    if (clientList == '' || clientList == 'none') return;
    var cTable= '<table><tr><th>Client</th><th>Status</th></tr>';
    for (var i= 0; i < clientList.clients.length; i++) {
	var client= clientList.clients[i]
	var cStat= client.status ? 'active &nbsp; <input id="check" type="button" value="Disable" onclick="oVPNserver(\'disable\', client.name)"/> &nbsp; <input id="dload" type="button" value="Download" onclick="downloadClient(client.name)"/>' : 'disabled'
	cTable+= '<tr><td>' +client.name +'</td><td>' +cStat +'</td></tr>'
    }
    E('clientList').innerHTML= cTable +'</table>'
}

function downloadClient(client) { //download client key file
    hideUi('Preparing ' +client +' key file for download');
    $.post('php/clientKey_download.php')
	.done(function() {
	    hideUi(client +' key file downloaded');
	    setTimeout(function(){showUi()},3000);
	})
	.fail(function() {
	    hideUi(client +' key file downloaded');
	    setTimeout(function(){showUi()},3000);
	})
}

function checkServer(verbose) { //check status of server and update client table
   if (verbose) hideUi('Checking OpenVPN Server');
   $.post('php/ovpnserver.php', {action: 'check'}, function(res) {
	if (res != '') {
	    eval(res);
	    if (verbose) hideUi(res.msg);
	    if (res.sabai) {
		setupBut('Setup', false);
		startBut('Start', false);
		clientTable(res.data);
		E('stat').innerHTML= ''
	    } else E('stat').innerHTML= res.data
	}
    if (verbose) setTimeout(function(){showUi()},3000);
    });
}

function checkServer(verbose) { //check status of server and update client table
}

function init() { 
	hidden = E('hideme')
	hide = E('hiddentext')
//	console.log('init')
	checkServer(false)
}
  
</script>
