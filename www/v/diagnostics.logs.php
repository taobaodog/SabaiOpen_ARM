<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=diagnostics&section=logs";
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
		Diagnostics: Logs
	</div>

	<div class='controlBox'>
		<span class='controlBoxTitle'>Logs</span>
		<div class='controlBoxContent'>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<form id='fe' class="form-inline" role="form">
						<div class="form-group">
							<select id='log' class="form-control" name='log'>
								<option value='messages' selected>System log</option>
								<option value='privoxy.log'>Privoxy log</option>
								<option value='kernel.log'>Kernel log</option>
							</select>
						</div>
						<div class="form-group">
							<select id='act' name='act' class="form-control" onchange="toggleDetail();">
								<option value='all'>View all</option>
								<option value='head'>View first</option>
								<option value='tail' selected>View last</option>
								<option value='grep'>Search for</option>
								<option value='download'>Download file</option>
							</select>
						</div>
						<div class="form-group">
							<input type="text" name='detail' id='detail' class='form-control'><span id='detailSuffix'></span>
						</div>
					</form>
				</div>
			</div>
			<br>
			<div class='col-md-2 col-sm-2 col-lg-2 '>	
				<button class='btn btn-default btn-sm pull-left' id='goButton' type="button" value="Go" onclick="goLog();">Show</button>
			</div>
			<br>	
			<div id='hideme'>
				<div class='centercolumncontainer'>
					<div class='middlecontainer'>
						<div id='hiddentext'>Please wait...</div>
						<br>
					</div>
				</div>
			</div><br>
			<div class='logContent'>
				<textarea id='logContent' class='logContent' readonly></textarea>
			</div>
		</div>
	</div>
	<div>

	</div>
<div id='footer'> Copyright © 2016 Sabai Technology, LLC </div>

</body>
</html>
<script type='text/javascript'>

//Adding text to help-modal
$(document).on('click', '#helpBtn', function (e) {
  var help = "";
    help += "<p><b>Logging</b> is open for user. User can scroll log on page or download as a file to PC.</p>"
    
  $('#help-modal').find('.modal-body').html("<div class='helpModal'" +help+ "</div>");
    $('#help-modal').modal('show')
});

var hidden, hide, pForm = {};
var hidden = E('hideme');
var hide = E('hiddentext');

function goLog(n){
console.log($("#fe").serialize());
	if($("#act").val() == "download"){
		hideUi("Downloading ...");
		$.ajax("php/logs.php", {
			success: function(data){
				if (data.trim() == "false") {
					hideUi("Log file is missing.");
					setTimeout(function(){showUi()},4500);
				} else {
					window.location.href = data 
					hideUi("Downloading completed.");
					setTimeout(function(){showUi()},4500);
				}
			},
			error: function(data){ hideUi("Failed"); setTimeout(function(){showUi()},4500); },
			dataType: "text",
			data: $("#fe").serialize()
		});
	}else{
		$.ajax("php/logs.php", {
			success: function(data){ 
				if (data.trim() == "false") {
					hideUi("Log file is empty.");
					setTimeout(function(){showUi()},4500);
				} else {
					$('#logContents').html(data);
				}
			},
			dataType: "text",
			data: $("#fe").serialize()
		});
	}
}

function toggleDetail(){
	$('#detailSuffix').html('');
	switch($('#act').val()){
		case 'all':
		case 'download':
			$('#detail').hide();
		break;
		case 'head':
		case 'tail':
			$('#detail').show().val('25');
			$('#detailSuffix').html(' lines');
		break;
		case 'grep':
			$('#detail').show().val('');
			break;
	}
}

//Preventing page-reload on "enter"-keypress
  $(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
}); 

$(function(){
 toggleDetail();
});

</script>
