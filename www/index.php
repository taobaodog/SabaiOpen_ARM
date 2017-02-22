<?php
session_start();
if (!isset($_SESSION['login'])){
	header("Location: v/administration.auth.php");
	die();
}
?>
<!DOCTYPE html>
<!--Sabai Technology - Apache v2 licence 
    copyright 2014 Sabai Technology -->
<meta charset="utf-8"><html><head>
<title id="mainTitle">SabaiOpen</title>

<link rel="stylesheet" type="text/css" href="libs/jqueryui.css">
<link rel="stylesheet" type="text/css" href="libs/jai-widgets.css">
<link rel="stylesheet" type="text/css" href="libs/css/main.css">
<link rel="stylesheet" href="libs/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="libs/css/select.dataTables.min.css">
<link rel="stylesheet" href="libs/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="libs/css/main.css">
<link rel="stylesheet" href="libs/css/bootstrap.min.css">

<?php include("php/libs.php"); ?>
<script>
var hidden,hide,f,oldip='',logon=false,info=null;



function setUpdate(res){
			if(info) oldip = info.vpn.ip; 
			eval(res); 
			for(i in info.vpn){ 
		 		E('vpn'+i).innerHTML = info.vpn[i]; 
		 	}
		 	for(i in info.tor_proxy){ 
		 		E('tor_'+i).innerHTML = info.tor_proxy[i]; 
		 	}
		 	$('#proxy').text(info.proxy.status);

		 	if (info.vpn.status == "Connected"){
		 		$('#dns_stat').text("VPN is runnig. Check DNS servers on the status page. ");
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

function init(){ 
	   <?php if (file_exists('/etc/sabai/stat/ip') && file_get_contents("/etc/sabai/stat/ip") != '') {
		   echo "donde = $.parseJSON('" . strstr(file_get_contents("/etc/sabai/stat/ip"), "{") . "');\n";
		   echo "for(i in donde){E('loc'+i).innerHTML = donde[i];}"; } ?>
		   getUpdate();
		   setInterval (getUpdate, 5000); 
		   setInterval (setUpdate, 5000);
		   $('#status').addClass('active')
		 }

/*
function toggleHelpSection() {
	$( "#helpClose").show();
	$( "#helpSection" ).toggle( "slide", { direction: "right" }, 500 );
	$( "#helpButton" ).hide();
	return false;
};

function closeHelpSection() {
	$( "#helpClose").hide();
	$( "#helpSection" ).toggle( "slide", { direction: "right" }, 500 );
	$( "#helpButton" ).show();
	return false;
}*/

<?php
 $template = array_key_exists('t',$_REQUEST);
 $panel = ( array_key_exists('panel',$_REQUEST) ? preg_replace('/[^a-z\d]/i', '', $_REQUEST['panel']) : null );
 $section = ( array_key_exists('section',$_REQUEST) ? preg_replace('/[^a-z\d]/i', '', $_REQUEST['section']) : null );
 if( empty($panel) ){ $panel = 'administration'; $section = 'status'; }
 $page = ( $template ?'m':'v') ."/$panel". ( empty($section) ? '' : ".$section") .".php";
 if(!file_exists($page)) $page = 'v/lorem.php';
 if($page == "192.168.199.1/php") $page = 'v/lorem.php';
 echo "var template = ". ($template?'true':'false') ."; var panel = '$panel'; var section = '$section';\n";
?>

$(function(){
	$("#goToHelp").attr("href", "?panel=help#" + section);
	$("#goToWiki").attr("href", "?panel=help#" + section);
/*	$( "#helpButton" ).click(toggleHelpSection);
	$( "#helpClose").click(closeHelpSection)*/
});

</script>
</head><body onload='init()'>		
<br>
<div id="backdrop">
	<?php include('php/menu.php'); ?>

	<div id="panelContainer">

		<div id="helpArea">
				<div class='fright' id='torstats'>
		<div id='tor_proxy'>TOR proxy</div>
			<div id='tor_status'>-</div> 
			<div id='tor_port'>-</div> 
		</div>
			<div class='fright' id='vpnstats'>
			<div id='vpntype'></div>
			<table>
			<tr>
			<div id='vpnstatus'></div>
			</tr>
			<tr>
			<div id='vpnip'></div>
			</tr>
			</table>
		</div>
		<div class='fright' id='locstats'>
			<div id='locquery'></div>
			<table>
			<tr>
			<div id='loccity'></div> 
			</tr>
			<tr>
			<div id='loccountry'></div>
			</tr>
			</table>
				<div class= 'noshow' id='locregion'></div>
					<div class= 'noshow' id='loclat'></div>
					<div class= 'noshow' id='loclon'></div>
					<div class= 'noshow' id='locas'></div>
					<div class= 'noshow' id='loccountryCode'></div>
					<div class= 'noshow' id='locisp'></div>
					<div class= 'noshow' id='locorg'></div>
					<div class= 'noshow' id='locquery'></div>
					<div class= 'noshow' id='locregionName'></div>
					<div class= 'noshow' id='locstatus'></div>
					<div class= 'noshow' id='loctimezone'></div>
					<div class= 'noshow' id='loczip'></div>
		</div>

		</div>
		<div id="panel">
			<?php include($page); ?>
		</div>
	</div>
</div>

<!-- Help modal -->
<div class="modal fade" id="help-modal" tabindex="-1" role="dialog">\
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Help</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</body></html>
