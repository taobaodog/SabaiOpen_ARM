<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=administration&section=upgrade";
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
<form id='fe' method="post" enctype="multipart/form-data">
<input type='hidden' name='version' id='_version'>
<input type='hidden' id='act' name='act'>
<div class='pageTitle'>
	<input id='helpBtn' name='helpBtn' class='helpBtn' title='Help' style="background-image: url('libs/img/help.png')"></input>
Upgrade
</div>
<div class='controlBox'><span class='controlBoxTitle'>SabaiOpen Server Update</span>
 	<div class='controlBoxContent'>
    	<div>Current Version: <span id='cversion'></span></div><br>
    	<button class='btn btn-default btn-sm' id='check_version' type='button' name='check_version'>Check Update</button>
    	<div id='hideme'>
            	<div class='centercolumncontainer'>
                	<div class='middlecontainer'>
                    	<div id='hiddentext'>Please wait...</div>
                	</div>
            	</div>
        </div>
		<div class='controlBoxContent' id='update_form'> </div>
	</div>   
</div>
<div class='controlBox'><span class='controlBoxTitle'>SabaiOpen Manual Update</span>
	<div class='controlBoxContent'>


    	<span class='uploadButton btn btn-default btn-sm'> Browse for Update</font></span>

    	<input id='browse' name='_browse' type='file' hidden='true' onchange="fileInput(this, 'tar');"/><t>
    	<input id='fileName' name='_fileName' type='text'>

    	<button class='btn btn-default btn-sm' id='download' name='submit'>Download</button><br><br>
    	<button class='btn btn-default btn-sm' id='upgrade' onclick="Upgrade('upgrading');">Run Update</button>
    	<button class='btn btn-default btn-sm' id='revert' name='Revert'>Revert Update</button>
    	<button class='btn btn-default btn-sm' id='reset' name='Reset'>Factory Reset</button><br>
        	<div id='hideme'>
            	<div class='centercolumncontainer'>
                	<div class='middlecontainer'>
                    	<div id='hiddentext'>Please wait...</div>
                    	<br>
                	</div>
            	</div>
        	</div><br>
  	</div>
</div>
<div class='controlBox'><span class='controlBoxTitle'>Firmware Configuration</span>
  <div class='controlBoxContent'>
    <div>Available user configurations: <span id='config'></span></div><br>
    <div class='radioSwitchElement' id='configList'></div><br>
    <button class='btn btn-default btn-sm' id='restore' name='Restore' type='button' value='Restore'>Restore</button>
    <button class='btn btn-default btn-sm' id='backUp' name='backUp_config' type='button' value='Backup'>Backup</button>
    <span id='aMsg' style="color:blue" ></span><br><br>
    <button class='btn btn-default btn-sm' id='saveConf' name='SaveConf'>Download config</button>
    <button class='btn btn-default btn-sm' id='loadConf' name='LoadConf'>Upload config</button>
    <button class='btn btn-default btn-sm' id='remove' name='Remove'>Remove config</button>
    <input id='browse1' name='_browse1' type='file' hidden='true' onchange="fileInput(this, 'conf')"/><br>
    <input id='fileName1' name='_fileName1' type='text' hidden='true'/>
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
    help += "<p><b>Server Update</b> is automatical upgrade process if new version of software is available.</p>"
    help += "<br>"
    help += "<p><b>Manual Update</b> can be made by uploading .img file. It is available to revert last update and to make factory reset of the last update.</p>"
    help += "<br>"
    help += "<p><b>Firmware Configuration</b> can be backed up or downloaded by user at any time. It ensures flexible switching between different settings.</p>"
    help += "<br>"
  $('#help-modal').find('.modal-body').html("<div class='helpModal'" +help+ "</div>");
    $('#help-modal').modal('show')
});


$('#browse').hide();
$('#browse1').hide();	
$('#restore').hide();
$('#remove').hide();


var hidden, hide, pForm = {};
var hidden = E('hideme');
var hide = E('hiddentext');

var list = $.parseJSON('{<?php $config = exec("sh /www/bin/config_search.sh");
			       echo $config;?>}');

var soft=$.parseJSON('{<?php
						$old_sabai_version=trim(exec("uci get sabai.general.version"));
						$new_sabai_version=trim(exec("uci get sabai.general.new_version"));
						echo "\"new_sabai_version\": \"$new_sabai_version\",\"old_sabai_version\": \"$old_sabai_version\"";							
						?>}');

E('cversion').innerHTML = soft.old_sabai_version;
E('fileName').value = '';
E('fileName1').value = '';
E('browse').value = '';
E('browse1').value = '';
E('aMsg').innerHTML = ' * Sabai - is the currently running configuration.';

$('#check_version').on("click", function () {
	hideUi("Checking ...");
	$.get('php/check_update.php')
		.done(function(data) {
		if (data.trim() == "false") {
			hideUi("No new version yet.");
		} else {
			hideUi("New " + data + " version is available.");
			ServerUpdateForm();
			E('update_msg').innerHTML = 'New available version: '+ data;
		}
		setTimeout(function(){showUi()},4500);
	})
	.fail(function() {
		hideUi("Failed to check.");
		setTimeout(function(){showUi()},4500);
	})
});

$.widget("jai.update_form", {
	_create: function(){
		$(this.element)
		.append( $(document.createElement('table')).addClass("controlTable smallwidth")
			.append( $(document.createElement('tbody'))
				.append( $(document.createElement('tr'))
					.append( $(document.createElement('td'))
						.append(
               				$(document.createElement('div')).html('New available version: '+ soft.new_sabai_version)
               					.prop("id","update_msg")
               					.prop("name","update_msg")
               			)
					)
               	)
               	
               	.append( $(document.createElement('tr'))
               		.append( $(document.createElement('td') )
               			.append(
               				$(document.createElement('input'))
               					.prop("id","update_download")
               					.prop("name","update_download")
               					.prop("type","button")
               					.prop("value", "Server Update")
               			)
               			.append(
               				$(document.createTextNode("  *Click to download new version and run update automaticaly."))
               			)            			
               		)
               	)
			) // end tbody
		) // end table

		$('#update_download').on("click", function() {
			hideUi("Please wait...");
			$.ajax({
				url: 'php/update_download.php',
				type: "POST",
				timeout: 120000,
				success: function() {
					hideUi("New firmware was downloaded!");
					setTimeout(function(){Upgrade('upgrading')},2000);
				},
				error: function() {
					hideUi("Failed");
					setTimeout(function(){showUi()},4500);
				}
			})
		});
		
		this._super();
	},
});

function ServerUpdateForm(){
	//instatiate widgets on document ready
	$('#update_form').update_form({ conf: soft });
}

//save result of last request for available version
if (soft.new_sabai_version.trim() != "" && soft.new_sabai_version != soft.old_sabai_version)	{
	ServerUpdateForm();
}

// jQuery uploadbutton implementation
$('.uploadButton').bind("click" , function () {
        $('#browse').click();
});
// View the file`s name
function fileInput(obj, type) {
		var path = obj.value;
        var browseName = obj.value.split('.').pop().toLowerCase();           
        if ($.inArray(browseName, [type]) != -1) {
                E('fileName').value = path.replace(/^.*\\/, "");              
        } else {                                                                                    
                E('fileName').value = 'Please select an image file.';                       
        }
}

$('#backUp').on("click", function() {
	if (selectOption == '') {
		hideUi("Please, select the configuration.");
		setTimeout(function(){showUi()},3000);
	} else {
	       	var backUpName = prompt("Please enter new user config name.");
		if (backUpName.trim() == null) {
        	        hideUi("Backup wasn`t done.");
                              setTimeout(function(){showUi()},3000);
                        } else {
				$.post('php/backUp.php', {'newName': backUpName})
					.done(function(data) {
						if (data.trim() === "false") {
							hideUi("Backup wasn`t done. The name is incorrect.")
						} else {
							hideUi(data);
						}
                       				setTimeout(function(){showUi()},3000);
						setTimeout(function(){location.reload()},3100);
					})
					.fail(function(data) {
						hideUi("Failed");
                              			setTimeout(function(){showUi()},3000);
					})
                      	}
	}
});
$('#restore').on("click", function() {
	var selectOption = $("#configs").find(":selected").text();
	hideUi("Restoring in process ...");
	$.post('php/restore.php', {'restoreName': selectOption})
		.done(function(data) {
			if (data.trim() === "OK") {
				setTimeout(function(){hideUi("Restored configuration settings from backup file.")},3000);
			} else {
				setTimeout(function(){hideUi("Something went wrong.")},3000);
			}
			setTimeout(function(){showUi()},7000);
			setTimeout(function(){location.reload()},7100);
		})
		.fail(function(data) {
			setTimeout(function(){hideUi("Failed")},3000);
			setTimeout(function(){showUi()},4500);
		}) 

});

// Remove selected config from the list
$("#remove").on("click", function () {
	var selectOption = $("#configs").find(":selected").text();
	hideUi("Removing in process ...")
	$.post('php/remove.php', {'removeName': selectOption})
                .done(function(data) {
			if (data.trim() === "OK") {
                        	setTimeout(function(){hideUi("Configuration file was removed successfully.")},3000);
                        } else {
                                setTimeout(function(){hideUi("Something went wrong.")},3000);
                        }
			setTimeout(function(){showUi()},7000);
                        setTimeout(function(){location.reload()},7100);
                })
                .fail(function(data) {
                        setTimeout(function(){hideUi("Failed")},3000);
                        setTimeout(function(){showUi()},4500);
                })

});

// Download selected config on own pc
$("#saveConf").on("click", function() { 
	var selectOption = $("#configs").find(":selected").text();
	$.post('php/saveFile.php', {'loadFile': selectOption})
		.done(function(data) {
			window.location.href = data;
		})
		.fail(function() {
			hideUi("Failed");
			setTimeout(function(){showUi()},4500);
		})
});

// Upload file from client to server
$("#loadConf").on("click", function() {
	$('#browse1').click();
	$("#browse1").on("change", function(){
		$("#fe").submit(function() { return false; });
                hideUi("Downloading ...");
		var form = document.forms.fe;
                var formData = new FormData(form);

                var xhr = new XMLHttpRequest();
                xhr.open("POST", "php/download_config.php");

                xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4) {
				if(xhr.status == 200) {
                                	data = xhr.responseText;
                                        if(data.trim() === "true") {
                                                setTimeout(function(){hideUi("New configuration file was downloaded successfully!")},3000);
                                                setTimeout(function(){showUi()},7000);
                                                setTimeout(function(){location.reload()},7100);
                                        } else {
                                                setTimeout(function(){hideUi("Failed")},3000);
                                                setTimeout(function(){showUi()},7000);
                                        }
				} else {
                                        setTimeout(function(){hideUi(xhr.status)},3000);
                                        setTimeout(function(){showUi()},7000);
                                }
			}
                };
                xhr.send(formData);
        });
});


$(document).ready( function() {
$("#fe").submit(function() { return false; });
$("#download").on("click", function(){
		hideUi("Downloading ...");
		E("act").value='download';

                var form = document.forms.fe;
                var formData = new FormData(form);

                var xhr = new XMLHttpRequest();
                xhr.open("POST", "php/download.php");

                xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4) {
                                if(xhr.status == 200) {
                                        data = xhr.responseText;
                                        if(data.trim() === "true") {
						setTimeout(function(){hideUi("New firmware was downloaded succesfully!")},3000);
						setTimeout(function(){showUi()},7000);
                                        } else {
						setTimeout(function(){hideUi("Download FAILED! Refresh page and try again.")},3000);
						setTimeout(function(){showUi()},7000);
                                        }
                                } else {
									setTimeout(function(){hideUi(xhr.status)},3000);
 									setTimeout(function(){showUi()},7000);
				}

                        }
                };
                xhr.send(formData);
        });
});


function Upgrade(act)	{
	$(document).ready( function() {
		hideUi("Please wait. Preparing installation.");
		E("act").value=act;
		$.get('php/update.php')
  			.done(function(data) {
				if (data.trim() == "false") {
					hideUi("No image file selected.");
					setTimeout(function(){showUi()},3000);
				} else {
					setTimeout(function(){hideUi(data)},3000);
 					setTimeout(function(){showUi()},5000);
				}
			})
			.fail(function() {
				hideUi_timer("Firmware was transferred. Please wait. Upgrade in progress...", 270);
				setTimeout(function(){hideUi("Please wait. Checking installation status.")},271000);
				setTimeout(function(){checkUpdate()}, 280000);
			})
	});

}

function checkUpdate() {
	$.get('resUpgrade.txt')
		.done(function(res) {
			if (res != '')	{
				var text = res.slice(7);
				setTimeout(function(){hideUi(text)},2000);
				setTimeout(function(){showUi()},5000);
			} else {
				setTimeout(function(){hideUi("Something went wrong.")},2000);
	            setTimeout(function(){showUi()},5000);
			}
		})
		.fail(function() {
			setTimeout(function(){hideUi("Upgrade was not done.")},2000);
			setTimeout(function(){showUi()},5000);
		})
}

$("#revert").on("click", function () {
	$(document).ready( function() {
		hideUi("Please wait. System Restore is in progress...");
		$.get('php/revert.php')
			.done(function(res) {
				eval(res);
				if (res.msg.indexOf("NOT") < 0)	{
					setTimeout(function(){hideUi(res.msg)},2000);
					setTimeout(function(){checkUpdate()},38000);
				} else {
					setTimeout(function(){hideUi(res.msg)},2000);
                                        setTimeout(function(){showUi()},5000);
				}
			})
			.fail(function() {
				setTimeout(function(){hideUi("Something went wrong. System Restore has failed.")},2000);
            	setTimeout(function(){showUi()},5000);
			})
	})
});

$("#reset").on("click", function () {
	$(document).ready( function() {
		hideUi("Please wait. Factory Reset is in progress...");
		$.get('php/reset.php')
			.done(function(res) {
				eval(res); 
				if (res.msg.indexOf("NOT") < 0)	{
					setTimeout(function(){hideUi(res.msg)},2000);
					setTimeout(function(){checkUpdate()},50000);
				} else {
					setTimeout(function(){hideUi(res.msg)},2000);
					setTimeout(function(){showUi()},5000);
				}
			})
			.fail(function() {
				setTimeout(function(){hideUi("Something went wrong. Factory Reset has failed.")},2000);
            	setTimeout(function(){showUi()},5000);
			})
	})
});

$.widget("jai.config", {
	_create: function(){
		$(this.element)
			.append(
				$(document.createElement('select'))
					.prop("id","configs")
					.prop("name","configs")
					.prop("class", "radioSwitchElement")
			);
		$.each( list, function( key, value ){
			$('#configs').append (
				$(document.createElement('option'))
				 .prop("value", key)
				 .prop("text", value)
				)
		});

	var currConf = $('#configs option').filter(function() { return $(this).html() == "sabai"; }).val();
	$('#configs').radioswitchH({ value: currConf , hasChildren: true });
},
});

// Display radioswitch element
$("#configList").config();
var selectOption = $("#configs").find(":selected").text();

$('#configs').change(function() {
	selectOption = $(this).find(":selected").text();
	if (selectOption.trim() == 'sabai') {
		$('#backUp').show();
		$('#restore').hide();
		$('#remove').hide();
		E('aMsg').innerHTML = ' * Sabai - is the currently running configuration.';
	} else {
		$('#restore').show();
		$('#backUp').hide();
		$('#remove').show();
		E('aMsg').innerHTML = ' * This is a previous user backup of Sabai configuration.';
	}
});

 
</script>
