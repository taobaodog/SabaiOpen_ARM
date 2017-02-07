<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=diagnostics&section=console";
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
     Diagnostics: Console
   </div>
   <div class='controlBox'><span class='controlBoxTitle'>Execute System Commands</span>
    <div class='controlBoxContent' id='console'>

     <textarea id='shellbox' name='cmd'></textarea><br>
     <div id='whatPage' class='noshow'>shell</div>

     <button class='btn btn-default btn-sm' type='button' class='button' value='Execute' onclick='CONSOLEcall()'>Execute</button>
     <br>
     <span id='messages' class='response'></span>
   </div>
   <div id='hideme'>
    <div class='centercolumncontainer'>
      <div class='middlecontainer'>
        <div id='hiddentext'></div>
        <br>
      </div>
    </div>
  </div>
  <p>
    <div id='footer'>Copyright © 2016 Sabai Technology, LLC</div>
  </p>
</form>    
</body>
</html>

<script type='text/javascript'>

//Adding text to help-modal
$(document).on('click', '#helpBtn', function (e) {
  var help = "";
  help += "<p><b>Console</b> is accessible for user for advanced diagnostic and management.</p>"

  $('#help-modal').find('.modal-body').html("<div class='helpModal'" +help+ "</div>");
  $('#help-modal').modal('show')
});

var logWindow, logForm, logSelect, hidden, hide;
var hidden = E('hideme'); 
var hide = E('hiddentext');

function CONSOLEresp(res){ 
  output=$.parseJSON(res);
  str = output.replace(/\n/g, "<br>");
  str = "<br>"+str;
  document.getElementById('messages').innerHTML=str;
}

function CONSOLEcall(){ 
 // 		hideUi("Applying Commands..."); 
  $(document).ready( function(){
    hideUi("Processing commands...");
			// Pass the form values to the php file 
			$.post('php/console.php', $("#fe").serialize(), function(res){
  			// Detect if values have been passed back   
      if(res!=""){
        CONSOLEresp(res);
      }
      showUi();
      });

  // Important stops the page refreshing
  return false;

  }); 
}

</script>