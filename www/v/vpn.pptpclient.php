<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=vpn&section=pptpclient";
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
           VPN: PPTP Client
       </div>
       <div class='controlBox'><span class='controlBoxTitle'>PPTP Settings</span>
        <div class='controlBoxContent'>
            <body onload='init();' id='topmost'>
                <input type='hidden' id='act' name='act'>

                <div class ='form-group' style='margin-bottom: 5px;'>
                    <label class='col-md-2 col-lg-2 col-sm-2' for='server'>Server:</label>
                    <div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
                        <input id='server' name='server' type='text' class='form-control'>
                    </div>
                </div>

                <div class ='form-group' style='margin-bottom: 5px;'>
                    <label class='col-md-2 col-lg-2 col-sm-2' for='user'>Username:</label>
                    <div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
                        <input id='user' name='user' type='text' class='form-control'>
                    </div>
                </div>

                <div class ='form-group' style='margin-bottom: 5px;'>
                    <label class='col-md-2 col-lg-2 col-sm-2' for='pass'>Password:</label>
                    <div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
                        <input id='pass' name='pass' type='password' autocomplete="off" onfocus='peekaboo("pass")' onblur='peekaboo("pass")' class='form-control'>
                    </div>
                </div>

                <div id='mppe_conf' class ='form-group' style='margin-bottom: 5px;'>
                    <label class='col-md-2 col-lg-2 col-sm-2' for='mppe'>MPPE-128</label>
                    <select class='col-md-4 col-lg-4 col-sm-4' id='mppe' name='mppe' class='radioSwitch'>
                        <option value='stateless'>Stateless</option>
                        <option value='nomppe'>No mppe</option>
                    </select>
                </div>   
                <br>


<!--             <table class="fields">
                <tbody>
                    <tr>
                        <td class="title indent1 shortWidth">Server</td>
                        <td class="content">
                            <input name="server" id="server" class='longinput' type="text">
                        </td>
                    </tr>
                    <tr>
                        <td class="title indent1 shortWidth">Username</td>
                        <td class="content">
                            <input name="user" id="user" class='longinput' type="text">
                        </td>
                    </tr>
                    <tr>
                        <td class="title indent1 shortWidth">Password</td>
                        <td class="content">
                            <input name="pass" id="pass" class='longinput' autocomplete="off" onfocus='peekaboo("pass")' onblur='peekaboo("pass")' type="password">
                        </td>
                    </tr>
                    <tr>
                        <td class="title indent1 shortWidth"> MPPE-128 </td>
                        <td class="content">
                            <div id='mppe_conf'></div>
                        </td>
                    </tr>
                </tbody>
            </table> -->


            <button class='btn btn-default btn-sm' id='start' type='button' class='firstButton' value='Start' onclick='PPTPcall("start")'>Start</button>
            <button class='btn btn-default btn-sm' id='stop' type='button' value='Stop' onclick='PPTPcall("stop")'>Stop</button>
            <button class='btn btn-default btn-sm' id='save' type='button' value='Save' onclick='PPTPcall("save")'>Save</button>
            <button class='btn btn-default btn-sm' id='clear' type='button' value='Clear' onclick='PPTPcall("clear")'>Clear</button>
            <span id='messages'>&nbsp;</span>
        </div>
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
    help += "<p><b>PPTP Client </b>can be configured by setting user server, username and password data. Use Save/Clear buttons to hold setting or to remove it. Start/Stop will set up VPN tunnel using user profile.</p>"
    help += "<br>"
    help += "<p><b>MPPE-128 </b> is encryption security protocol, that used by each VPN provider. Ask for this configuration your provider.</p>"

  $('#help-modal').find('.modal-body').html("<div class='helpModal'" +help+ "</div>");
    $('#help-modal').modal('show')
});

    var hidden, hide, f,oldip='',limit=10,info=null,ini=false;
    var pptpTry = 0;
    pptp = {<?php
        $user=trim(exec("uci get sabai.vpn.username"));
        $pass=trim(exec("uci get sabai.vpn.password"));
        $server=trim(exec("uci get sabai.vpn.server"));
        $mppe=trim(exec("uci get sabai.vpn.mppe_mode"));
        if( $user!="" ) echo "\n\tuser: '". $user ."',\n\tpass: '". $pass ."',\n\tserver: '". $server ."',\n\tmppe: '". $mppe ."'\n";
        else echo " user: '', pass: '', server: '', mppe: '' ";
    ?>}

$.widget("jai.mppe", {
    _create: function(){
/*        $(this.element)
            .append(
                $(document.createElement('select'))
                    .prop("id","mppe")
                    .prop("name","mppe")
                    .prop("class", "radioSwitch")
                .append( $(document.createElement('option'))
                    .prop("value", "stateless")
                    .prop("text", 'Stateless')
                )
                .append( $(document.createElement('option'))
                    .prop("value", "nomppe")
                    .prop("text", 'No mppe')
                )
            )*/

    $('#mppe').radioswitch({ value: pptp.mppe, hasChildren: true });
},
});



function setUpdate(res){
    if(info) oldip = info.vpn.ip; 
    eval(res); 
    for(i in info.vpn){ 
        E('vpn'+i).innerHTML = info.vpn[i]; 
    }
    if (info.vpn.status == "Connected" && info.vpn.type == 'PPTP') {
        E('clear').hidden = true;;
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

function PPTPresp(res){ 
  eval(res); 
  msg(res.msg); 
  showUi(); 
  if(res.sabai){ 
    limit=10; 
    getUpdate(); 
  } 
}

function PPTPcall(act){ 
 
 if(act =='clear') {
            E('server').value = "";
            E('user').value = "";
            E('pass').value = "";
            // Refactor it with CSS
            $('#mppe').val("");
            $('#mppe option:selected').attr('selected', false);
            $('#mpperadioSwitch').children().removeClass("buttonSelected");
 }
    
 if($("#mppe_stateless").hasClass("buttonSelected") || $("#mppe_nomppe").hasClass("buttonSelected")){
	   hideUi("Adjusting PPTP settings..."); 
	   E("act").value=act;
	   // Pass the form values to the php file
	   if (act=='start') {
		  if (info.vpn.type == 'OpenVPN') {
			hideUi("OpenVPN will be stopped.");
			$.post("php/ovpn.php", {'switch': 'stop'}, function(res){
				if(res!=""){
					eval(res);
					hideUi(res.msg);
					PPTPstart();
				}
			});
		/*  } else if (info.vpn.type == 'TOR') {
			hideUi("TOR will be stopped.");
			$.post('php/tor.php', {'switch': 'off'}, function(res){
				if(res!=""){
					eval(res);
					hideUi(res.msg);
					PPTPstart();
				}
			}); */
		  } else {
			PPTPstart();
		  }
	   } else {
		  $.post('php/pptp.php', $("#fe").serialize(), function(res){
			if(res!=""){
				PPTPresp(res);
			}
			showUi();
		  });
    	}
 }else{
        alert('Please choose a MPPE-128 setting before starting')
 }
// Important stops the page refreshing
return false;

}; 

function PPTPstart(){
	$.post('php/pptp.php', $("#fe").serialize(), function(res){
		// Detect if values have been passed back   
		if(res!=""){
			eval(res);
			hideUi(res.msg);
			setTimeout(function(){hideUi("Checking PPTP status...")},10000);
			setTimeout(check,10000);
		}
	});
}

function check(){
	$.post('php/pptp.php',{'check': 'status'}, function(res){
		if(res.indexOf("disconnected") < 0){
			PPTPresp(res);
			showUi();
		} else {
			if (pptpTry < 3) {
				setTimeout(check,10000);
				pptpTry++;
			} else {
				PPTPresp(res);
				showUi();
			}
		}
	});
}
$(function(){
    $('#mppe_conf').mppe();
})

function init(){ 
    f = E('fe'); 
    hidden = E('hideme'); 
    hide = E('hiddentext'); 
    for(var i in pptp){ 
        E(i).value = pptp[i]; 
    }; 
                <?php if (file_exists('/etc/sabai/stat/ip') && file_get_contents("/etc/sabai/stat/ip") != '') {
       echo "donde = $.parseJSON('" . strstr(file_get_contents("/etc/sabai/stat/ip"), "{") . "');\n";
       echo "for(i in donde){E('loc'+i).innerHTML = donde[i];}"; } ?>
       getUpdate();
       setInterval (getUpdate, 5000)
       
}
  
</script>

