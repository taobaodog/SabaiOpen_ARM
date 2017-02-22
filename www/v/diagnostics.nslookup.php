<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=diagnostics&section=nslookup";
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
		Diagnostics: NS Lookup
	</div>
	<div class='controlBox'><span class='controlBoxTitle'>NS Lookup</span>
		<div class='controlBoxContent'>

			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<form id='fe' class="form-inline" role="form">
						<div class="form-group">
							<label class='col-md-2 col-lg-2 col-sm-2' for='ns_domain'>Domain:</label>
						</div>
						<div class="form-group">
							<input id='ns_domain' name='ns_domain' type='text' value='www.google.com' class='form-control'>
						</div>
						<div class="form-group">
							<button class='btn btn-default btn-sm' type='button' value='Lookup' id='Lookup' onclick='lookup()'>Lookup</button>
						</div>
					</form>
					
				</div>
			</div>    


<!-- 		<table class='controlTable smallwidth'><tbody>
			 <tr>
			 	<td>Domain</td>
			 	<td>
			 		<input id='ns_domain' name='ns_domain' value='www.google.com'/>
			 		<button class='btn btn-default btn-sm' type='button' value='Lookup' id='Lookup' onclick='lookup()'>Lookup</button>
			 	</td>
			 </tr>
			</tbody></table> -->


			<br>
			<textarea id='dnstxtarea' style="width: 90%; height: 30em" readonly></textarea>
			
		</div> <!-- End Control box content -->
	</div> <!-- end control box -->
	<div id='footer'> Copyright © 2016 Sabai Technology, LLC </div>
</body>
</html>

<script type='text/javascript' url='php/etc.php?q=nslookup'>

	//Adding text to help-modal
$(document).on('click', '#helpBtn', function (e) {
  var help = "";
    help += "<p><b>NS Lookup</b> is a network administration tool for querying the Domain Name System (DNS) to obtain domain name or IP address mapping or for any other specific DNS record.</p>"
    
  $('#help-modal').find('.modal-body').html("<div class='helpModal'" +help+ "</div>");
    $('#help-modal').modal('show')
});


	function lookup(){
		var domain = $('#ns_domain').val();
		
		$.ajax("php/nslookup.php", {
			success: function(data){
				var msg = data.replace(domain, '');
				$('#dnstxtarea').html(msg);
			},
			dataType: "text",
			data: $("#fe").serialize()
		})
	};

	// lookup input if user presses 'enter'
	$(document).ready(function() {
		$('#ns_domain').keypress(function(event) {
			if (event.keyCode == 13) {
			event.preventDefault();
			lookup();
			}
		});
	});

	
</script>