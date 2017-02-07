<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=diagnostics&section=ping";
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
  <form id='fe'><div class='pageTitle'>
    <input id='helpBtn' name='helpBtn' class='helpBtn' title='Help' style="background-image: url('libs/img/help.png')"></input>
    Diagnostics: Ping
  </div>

  <div class='controlBox'><span class='controlBoxTitle'>Ping</span>
    <div class='controlBoxContent'>

      <div class ='form-group' style='margin-bottom: 5px;'>
        <label class='col-md-4 col-lg-2 col-sm-4' for='pingAddress'>Address:</label>
        <div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
          <input id='pingAddress' name='pingAddress' type='text' value='google.com' class='form-control'>
        </div>
      </div>    

      <div class ='form-group' style='margin-bottom: 5px;'>
        <label class='col-md-4 col-lg-2 col-sm-4' for='pingCount'>Ping Count:</label>
        <div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
          <input id='pingCount' name='pingCount' value='4' class='form-control'>
        </div>
      </div>    

      <div class ='form-group' style='margin-bottom: 5px;'>
        <label class='col-md-4 col-lg-2 col-sm-4' for='pingSize'>Packet Size<span class='smallText'> (bytes)</span>:</label>
        <div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
          <input id='pingSize' name='pingSize' value='56' class='form-control'>
        </div>
      </div>
<!-- 
    <table class='controlTable'><tbody>
      <tr>
        <td>Address</td>
        <td><input id='pingAddress' name='pingAddress' value='google.com'></td>           
      </tr>
      <tr>
        <td>Ping Count</td>
        <td><input id='pingCount' name='pingCount' class='shortinput' value='4' /></td>
      </tr>
      <tr>
        <td>Packet Size</td>
        <td><input id='pingSize' name='pingSize' class='shortinput' value='56' /><span class='smallText'> (bytes)</span></td>
      </tr>
    </tbody></table> -->


    </form>
    <br>
    <button class='btn btn-default btn-sm' type='button' id='ping' value='Ping' onClick='getResults()'>Ping</button>
    <br>
    <div id='results' class='controlBoxContent noshow'>
      <div id='statistics' class='smallText'></div>
      <table id='resultTable' class='listTable'></table>
    </div>
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
    help += "<p><b>Ping</b> is a diagnostics tool of network connection. User can test connection adjusting adsress, count and packet size parameters.</p>"
    
  $('#help-modal').find('.modal-body').html("<div class='helpModal'" +help+ "</div>");
    $('#help-modal').modal('show')
});

function getResults(){
  $('#results').show();
  $('#statistics').html('');
    $('#resultTable').dataTable({
      "bDestroy":true,
      'bPaginate': false,
      'bInfo': false,
      'bFilter': false,
      "sAjaxDataProp": "pingResults",
      
      "fnServerParams": function(aoData){ 
        $.merge(aoData,$('#fe').serializeArray()); 
      },
      
      "fnInitComplete": function(oSettings, json) {
        var stats=json.pingStatistics.split(',');
        var info=json.pingInfo.split(',');
        $('#statistics').append('--Summary--<br><br>Round-Trip: '+stats[0]+' min, '+stats[1]+' avg, '+stats[2]+' max <br>');
        $('#statistics').append('Packets: '+info[0]+' transmitted, '+info[1]+' received, '+info[2]+'% lost<br><br>');
      },
      
      "sAjaxSource": "php/ping.php",
      "aoColumns": [
        { "sTitle": "Count",  "mData":"count" },
        { "sTitle": "Bytes",  "mData":"bytes" },
        { "sTitle": "TTL",    "mData":"ttl"   },
        { "sTitle": "Time",   "mData":"time"  }
      ]

  }); 
}

//validate the fields
$( "#fe" ).validate({
    rules: {
    pingAddress: {
      required: true,
      addressCheck: true
    },
    pingCount: {
      required: true,
      range: [1, 50]
    },
    pingSize: {
      required: true,
      range: [4, 84]
    }
  },
});


</script>

