<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=vpn&section=tor";
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
		VPN: Tor - Anonymity Online
	</div>
	<form id="fe">
		<input type='hidden' id='form_tor' name='form_tor' value='tor'>
		<div class='controlBox'><span class='controlBoxTitle'>Tor Settings</span>
			<div class='controlBoxContent' id='tor_setup_wl'>
				<div class ='form-group'>
					<label class='col-md-1 col-lg-1 col-sm-1' for='tor_mode'>Mode</label>
					<select class='col-md-4 col-lg-4 col-sm-4' id='tor_mode' name='tor_mode' class='radioSwitchElement'>
						<option value='off'>Off</option>
						<option value='proxy'>Proxy</option>
						<option value='tun'>Tunnel</option>
					</select>
				</div>
			</div>
			<div class='controlBoxContent' id='tor_wl_config'>
				<div class ='form-group' style='margin-bottom: 5px;'>
					<label class='col-md-4 col-lg-2 col-sm-4' for='tor_ssid'>SSID:</label>
					<div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
						<input id='tor_ssid' name='tor_ssid' type='text' class='form-control'>
					</div>
				</div>

				<div class ='form-group' style='margin-bottom: 5px;'>
					<label class='col-md-4 col-lg-2 col-sm-4' for='tor_nw_ip'>TOR Wireless IP:</label>
					<div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
						<input id='tor_nw_ip' name='tor_nw_ip' type='text' class='form-control'>
					</div>
				</div>     	

				<div class ='form-group' style='margin-bottom: 5px;'>
					<label class='col-md-4 col-lg-2 col-sm-4' for='tor_nw_mask'>TOR Network Mask:</label>
					<div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
						<input id='tor_nw_mask' name='tor_nw_mask' type='text' class='form-control'>
					</div>
				</div>      	

				<div class ='form-group' style='margin-bottom: 5px;'>
					<label class='col-md-4 col-lg-2 col-sm-4' for='tor_server'>TOR Network IP:</label>
					<div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
						<input id='tor_server' name='tor_server' type='text' class='form-control'>
					</div>
				</div>
			</div>
			<div class='controlBoxContent'>
				<button class='btn btn-default btn-sm' type='button' value='Save' onclick='TORcall("#fe")'>Save</button><span id='messages'>&nbsp;</span>
			</div>
			<div id='hideme'>
				<div class='centercolumncontainer'>
					<div class='middlecontainer'>
						<div id='hiddentext'>Please wait...</div>
						<br>
					</div>
				</div>
			</div>
			<div class='controlBoxContent'>
				<div id='torUse'>Turn on TOR by choosing "Tunnel" and push "Save". It is possible to access TOR feature on proxy port 8080 or by setting accelerator as a gateway on the router.</div>
			</div>
		</form>
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
    help += "<p><b>Tor </b>is new anonymizing feature in SabaiOpen. Turning on tunnel and forwarding any host to ACC will allow to anonymize traffic for the host. User can also set ACC IP and its port 8080 in browser for anonymous browsing. More information about Tor you can find on www.torproject.org</p>"
    help += "<br>"
    help += "<p>Using Tor protects you against a common form of Internet surveillance known as \"traffic analysis.\" Traffic analysis can be used to infer who is talking to whom over a public network. Knowing the source and destination of your Internet traffic allows others to track your behavior and interests. This TOR client is provided to give network access to TOR for devices which may not have the ability to run TOR locally. The TOR organization recommends that due to the various methods of tracking traffic, the best way to remain fully anonymous on a computer is through use of the TOR Browser.</p>"

  $('#help-modal').find('.modal-body').html("<div class='helpModal'" +help+ "</div>");
    $('#help-modal').modal('show')
});

var hidden, hide, pForm = {};

var f = E('fe'); 
var hidden = E('hideme'); 
var hide = E('hiddentext');

var tor=$.parseJSON('{<?php
        $ssid=trim(exec("uci get sabai.wlradio0.ssid"));
        $ip=trim(exec("uci get sabai.tor.ipaddr"));
        $netmask=trim(exec("uci get sabai.tor.netmask"));
        $network=trim(exec("uci get sabai.tor.network"));
        $mode=trim(exec("uci get sabai.tor.mode"));
        echo "\"ip\": \"$ip\",\"ssid\": \"$ssid\",\"network\": \"$network\", \"netmask\": \"$netmask\", \"mode\": \"$mode\"";
    ?>}');


 function TORcall(torForm){ 
 	hideUi("Adjusting TOR settings...");
 	if (E("tor_mode").value == "tun") {
 		hideUi("TOR tunnel will be started.");
 		TORstart(torForm);
 	} else if (E("tor_mode").value == "proxy") {
 		hideUi("TOR proxy will be started.");
 		TORstart(torForm);
 	} else {
 		TORstart(torForm);
 	}
 	return false;
 };

function TORstart(torForm){
	$.post('php/tor.php', $(torForm).serialize(), function(res){
  	  // Detect if values have been passed back   
  	    if(res!=""){
  	      TORresp(res);
  	    }
  	      showUi();
  	});
}

 
 function TORresp(res){ 
 	eval(res); 
    msg(res.msg); 
    showUi(); 
    if(res.sabai){ 
    	limit=10; 
    	getUpdate(); 
    }
}



$.widget("jai.tor_setup_wl", {
	_create: function(){

/*		$(this.element)
		.append( $(document.createElement('table')).addClass("controlTable smallwidth")
	      .append( $(document.createElement('tbody')) 
	        
	        .append( $(document.createElement('tr'))
	          .append( $(document.createElement('td')).html('Mode') 
	          )
	          .append( $(document.createElement('td') ) 
	            .append(
	              $(document.createElement('select'))
	                .prop("id","tor_mode")
	                .prop("name","tor_mode")
	                .prop("class", "radioSwitchElement")
	              .append( $(document.createElement('option'))
	                .prop("value", "off")
	                .prop("text", 'Off')
	              )
	              .append( $(document.createElement('option'))
	                .prop("value", "proxy")
	                .prop("text", 'Proxy')
	              )
	              .append( $(document.createElement('option'))
	                .prop("value", "tun")
	                .prop("text", 'Tunnel')
	              )
	            )
	          )
	        ) // end mode tr

	      ) // end WPA tbody
	    ) // end lower table*/

	    $('#tor_mode').radioswitch({ 
			value: tor.mode
		});

		this._super();
	},
});

$.widget("jai.tor_wl_config", {
	_create: function(){

/*		$(this.element)
		.append( $(document.createElement('table')).addClass("controlTable smallwidth")
	      .append( $(document.createElement('tbody'))
	        .append( $(document.createElement('tr'))
	                .append( $(document.createElement('td')).html('SSID') 
	                )
	                .append( $(document.createElement('td') ) 
	                  .append(
	                    $(document.createElement('input'))
	                      .prop("id","tor_ssid")
	                      .prop("name","tor_ssid")
	                  )
	                )
	              ) // end SSID tr

	         .append( $(document.createElement('tr'))
				.append( $(document.createElement('td')).html('TOR Wireless IP')
						)
	  	                .append( $(document.createElement('td') ) 
	  	                  .append(
	  	                    $(document.createElement('input'))
	  	                      .prop("id","tor_nw_ip")
	  	                      .prop("name","tor_nw_ip")
	  	                      .prop("type","text")
	  	                  )
	  	                )
	  	              ) // end ip tr

	  	            .append( $(document.createElement('tr'))
	  	  				.append( $(document.createElement('td')).html('TOR Network Mask')
	  	  	  	                )
	  	  	  	                .append( $(document.createElement('td') ) 
	  	  	  	                  .append(
	  	  	  	                    $(document.createElement('input'))
	  	  	  	                      .prop("id","tor_nw_mask")
	  	  	  	                      .prop("name","tor_nw_mask")
	  	  	  	                      .prop("type","text")
	  	  	  	                    )
	  	  	  	                )
	  	  	  	              ) // end ip tr


	  	  	  	        .append( $(document.createElement('tr'))
	  		  	  				.append( $(document.createElement('td')).html('TOR Network IP') 
	  		  	  	  	                )
	  		  	  	  	                .append( $(document.createElement('td') ) 
	  		  	  	  	                  .append(
	  		  	  	  	                    $(document.createElement('input'))
	  		  	  	  	                      .prop("id","tor_server")
	  		  	  	  	                      .prop("name","tor_server")
	  		  	  	  	                      .prop("type","text")
	  		  	  	  	                      	  		  	  	  	                  )
	  		  	  	  	                )
	  		  	  	  	              ) // end ip tr
	      ) // end WPA tbody
	    )*/ // end lower table

	    $('#tor_ssid').val(tor.ssid);
		$('#tor_nw_ip').ipspinner().ipspinner('value', tor.ip);//tor.gateway);
		$('#tor_nw_mask').maskspinner().maskspinner('value', tor.netmask);//tor.mask);
		$('#tor_server').ipspinner().ipspinner('value', tor.network);//tor.server);

		this._super();
	},
});

$(function(){
	  //instatiate widgets on document ready
	  $('#tor_setup_wl').tor_setup_wl({ conf: 'tor'});
	  $('#tor_wl_config').tor_wl_config({ conf: 'tor'});

	})

</script>

