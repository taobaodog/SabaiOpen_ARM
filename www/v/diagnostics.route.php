<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=diagnostics&section=route";
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
Diagnostics: Route
</div>
<!-- TODO: 
-->
<div class='controlBox'>

    <span class='controlBoxTitle'>Current Routing Table</span>
    <div class='controlBoxContent'>
        <table id='resultTable' class='listTable'></table>
        <button class='btn btn-default btn-sm' type='button' id='reload' value='Reload' onclick='route();'>Reload</button>
      
    </div> <!--end control box content -->
</div> <!--end control box  -->
<div id='footer'> Copyright © 2016 Sabai Technology, LLC </div>
</body>
</html>


<script src="libs/jquery.dataTables.min.js"></script>
<script type='text/ecmascript'>

//Adding text to help-modal
$(document).on('click', '#helpBtn', function (e) {
  var help = "";
    help += "<p><b>Routing table</b> is a data table, that lists the routes to particular network destinations. The routing table contains information about the topology of the network.</p>"
    
  $('#help-modal').find('.modal-body').html("<div class='helpModal'" +help+ "</div>");
    $('#help-modal').modal('show')
});

function route(){
  $('#resultTable').dataTable({
    'bDestroy': true,
    'bPaginate': false,
    'bInfo': false,
    'bFilter': false,
    'bSort': false,
    'sAjaxDataProp': 'routeResults',
    'sAjaxSource': 'php/route.php',
    'aoColumns': [
     { 'sTitle': 'Destination',   'mData':'destination' },
     { 'sTitle': 'Gateway ',  'mData':'gateway' },
     { 'sTitle': 'Genmask',   'mData':'genmask' },
     { 'sTitle': 'Flags',   'mData':'flags' },
     { 'sTitle': 'MSS',   'mData':'mss' },
     { 'sTitle': 'Window',   'mData':'window' },
     { 'sTitle': 'IRTT',   'mData':'irtt' },
     { 'sTitle': 'Interface',   'mData':'interface' }
    ]
   });
}

$(function(){
   route();
});

</script>
