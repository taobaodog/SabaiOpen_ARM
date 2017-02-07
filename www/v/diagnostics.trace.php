<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=diagnostics&section=trace";
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
  <form id='fe'>
    <div class='pageTitle'>
      <input id='helpBtn' name='helpBtn' class='helpBtn' title='Help' style="background-image: url('libs/img/help.png')"></input>
      Diagnostics: Trace
    </div>

    <!-- TODO: Have aaData created at trace.php so that hideui gives message during trace time.-->

    <div class='controlBox'><span class='controlBoxTitle'>Traceroute</span>
      <div class='controlBoxContent'>


        <div class ='form-group' style='margin-bottom: 5px;'>
          <label class='col-md-4 col-lg-2 col-sm-4' for='traceAddress'>Address:</label>
          <div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
            <input id='traceAddress' name='traceAddress' type='text' value='google.com' class='form-control'>
          </div>
        </div>    

        <div class ='form-group' style='margin-bottom: 5px;'>
          <label class='col-md-4 col-lg-2 col-sm-4' for='maxHops'>Max Hops:</label>
          <div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
            <input id='maxHops' name='maxHops' value='4' class='form-control'>
          </div>
        </div>    

        <div class ='form-group' style='margin-bottom: 5px;'>
          <label class='col-md-4 col-lg-2 col-sm-4' for='maxWait'>Max Wait Time:</label>
          <div class='input-group input-group-lg-5 input-group-md-5 input-group-sm-5'>
            <input id='maxWait' name='maxWait' value='56' class='form-control'>
          </div>
        </div>


<!--     <table class='controlTable'><tbody>
      <tr>
        <td>Address</td>
        <td><input id='traceAddress' name='traceAddress' value='google.com'></td>           
      </tr>
      <tr>
        <td>Max Hops</td>
        <td><input id='maxHops' name='maxHops' class='shortinput' value='20'/></td>
      </tr>
      <tr>
        <td>Max Wait Time</td>
        <td><input id='maxWait' name='maxWait' class='shortinput' value='5' /><span class='smallText'></span></td>
      </tr>
    </tbody></table> -->
  </form>
  <br>
  <button class='btn btn-default btn-sm' type='button' id='trace' value='Trace' onClick='TRACEcall()'>Trace</button>
  <br>
  <div id='results' class='controlBoxContent noshow'>
    <table id='resultTable' class='listTable'></table>
  </div>



  <div id='hideme'><span id='messages'>&nbsp;</span>
    <div class='centercolumncontainer'>
      <div class='middlecontainer'>
        <div id='hiddentext' value-'Please wait...' ></div>
        <br>
      </div>
    </div>
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
    help += "<p><b>Trace</b> is a diagnostics feature of network connection. User can make diagnostic with displaying the route (path) and measuring transit delays of packets across an Internet Protocol (IP) network.</p>"
    
  $('#help-modal').find('.modal-body').html("<div class='helpModal'" +help+ "</div>");
    $('#help-modal').modal('show')
});

  var hidden, hide,res;
  var f = E('fe'); 
  var hidden = E('hideme'); 
  var hide = E('hiddentext');

  function TRACEcall(){
   hideUi("Tracing the route settings...");
   getResults();
   showUi();
  // Important stops the page refreshing
  return false;
  }

  function getResults(){ 
    $.fn.dataTable.ext.errMode='none';
    $('#results').show();
    $('#resultTable').dataTable({
      "bDestroy":true,
      'bAutoWidth': false,
      'bPaginate': false,
      'bInfo': false,
      'bFilter': false,
      'bSort': false,
      "sAjaxDataProp": "traceResults",
      "sAjaxSource": "php/trace.php",
      "fnServerData": function ( sSource, aoData, fnCallback, oSettings ) {
        $.merge(aoData,$('#fe').serializeArray());
        oSettings.jqXHR = $.ajax( {
          "dataType": 'json',
          "type": "POST",
          "url": sSource,
          "data": aoData,
          "timeout": 60000,
          "success": fnCallback,
          "error": function() {
            hideUi("Tracing failed by selected host.");
            setTimeout(function(){showUi()},4000);
            window.location.reload();
          }
        });
      },
      
      'aoColumns': [
        { 'sTitle': 'Hop', "mData":"Hop" },
        { 'sTitle': 'Address',"mData":"Address" },
        { 'sTitle': 'Time (ms)', "mData":"Time (ms)"   },
        { 'sTitle': 'Address 2' , "mData":"Address2" },
        { 'sTitle': 'Time (ms)', "mData":"Time2 (ms)" },
        { 'sTitle': 'Address 3', "mData":"Address3" },
        { 'sTitle': 'Time (ms)', "mData":"Time3 (ms)" }
        ]
});

  };

//validate the fields
$( "#fe" ).validate({
    rules: {
    traceAddress: {
      required: true
    },
    maxHops: {
      required: true,
      range: [1, 100]
    },
    maxWait: {
      required: true,
      range: [1, 60]
    }
  }
});

</script>
