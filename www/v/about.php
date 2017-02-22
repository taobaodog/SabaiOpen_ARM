<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=administration&section=about";
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
<div class='pageTitle'>About</div>

<div class='controlBox'><span class='controlBoxTitle'>About SabaiOpen</span>
	<div class='controlBoxContent'>
	
		<p class='smallText'> SabaiOpen is an open source project created with the goal of being a easy to use but highly powerful interface to OpenWRT. The software is created to run on mips through 64-bit x86 hardware and driven by you, the end user.</p>
		
		<p class='smallText'>SabaiOpen is a great router interface and is special for several reasons:</p>
		
		<ul class='smallText'>			
			<li> First, it is cross-platform, able to work on a wide array of hardware. The software will be released under an open source license and based on OpenWRT, meaning it will work on MIPS, ARM and x86 platforms.
			</li>
			<br>
			<li> Secondly, SabaiOpen is driven by the consumer / enthusiast. The open source community will have the opportunity to develop for this platform and will actually support the functionality they are looking for at the heart of the network. The extensibility opportunity is amazing and until now, the platform just hasn&#39;t existed. 
			</li>
		</ul>	
	</div> <!-- End Control box content -->
</div> <!-- end control box -->

<div class='controlBox'><span class='controlBoxTitle'>Sponsored By Sabai Technology</span>
	<div class='xsmallText' id='aboutSabai'> Sabai Technology LLC is a networking solutions company based in South Carolina. The Company has earned a reputation for products that are easy to use and for outstanding customer service. Sabai is preparing to introduce a revolutionary router product that the Company believes will become an industry standard device for home and small business networking.
	</div>
</div>
<div id='footer'> Copyright © 2016 Sabai Technology, LLC </div>

</body>
</html>