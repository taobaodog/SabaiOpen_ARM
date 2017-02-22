<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {  
	$url = "/index.php?panel=help";
	header( "Location: $url" );     
}
?>
<div class='pageTitle'>SabaiOpen Manual</div>
<br>

<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='wan'></a>WAN</span>
  <div class='controlBoxContent'>
    WAN Type, MTU, MAC, DNS
    <span class='smallText'><br><br><b>WAN-</b> Wide Area Network. This network has to provide Internet access and route devices from LAN.</span>
    <span class='smallText'><br><br><b>MTU-</b> Maximum Transmission Unit. This is the maximum size (in bytes) a single packet could have. Ethernet default is 1500. Maximum possible path MTU for IPv4 is 65536 (64 KiB) and minimum is 68. Changing MTU a bit may increase your connection speed, but it is recommended to keep the default one or consult your ISP.</span>
    <span class='smallText'><br><br><b>MAC Address-</b> MAC Address Media Access Control Address, MAC addresses are distinct addresses on the device level and is comprised of a manufacturer number and serial number.</span>  
    <span class='smallText'><br><br><b>DNS-</b> DNS Domain Name System, translates people-friendly domain names (www.google.com) into computer-friendly IP addresses (1.1.1.1). A DNS is especially important for VPNs as some countries return improper results for domains intentionally as a way of blocking that web site.</span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='lan'></a>LAN</span>
  <div class='controlBoxContent'>
    LAN IP, Mask, DHCP Server, Lease/Range
    <span class='smallText'><br><br><b>LAN-</b> Local Area Network.The network that connects hosts at your area in one network including hosts connected by WI-FI. It is not accessible from WAN.</span>
    <span class='smallText'><br><br><b>LAN IP-</b> IP address of the router, that is used for LAN communication.</span>
    <span class='smallText'><br><br><b>LAN Mask-</b> mask, that denotes network's size. It is advised that you keep the default 255.255.255.0 or research network masking in more details.</span>
    <span class='smallText'><br><br><b>DHCP-</b> Dynamic Host Configuration Protocol, the method by which routers assign IP addresses automatically. This allows you to connect to the coffee shop wireless even after more than 254 people have already; IP addresses are recycled as wireless clients come and go.</span>
    <span class='smallText'><br><br><b>Lease time-</b> determines how long the IP address can be used by host. Host should ask for a new lease when half of this time has passed since it got the old one.</span>
    <span class='smallText'><br><br><b>DHCP range-</b> determines which IP addresses will be provided to LAN hosts. You should exclude router's own address, all statically assigned addresses, network address (0) and broadcast address (255) from the range. With default settings and no static assignments, 2-254 would be a fine range.</span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='time'></a>Time</span>
  <div class='controlBoxContent'>
    NTP Servers, Current Router Time and Timezone, Current Computer Time and Timezone
	<span class='smallText'><br><br><b>NTP Servers-</b> Network Time Protocol servers, that  set correct time on user device. </span>
    <span class='smallText'><br><br><b>Current Router Time and Current Computer Time </b> can be synchronized with button Synchronize. Set user Time Zone using interactive map and synchronize device to it.</span>  
 </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='radio'></a>Radio</span>
  <div class='controlBoxContent'>
    Mode, SSID, Channel mode, Channel, Channel width, Encryption, Key, Key Duration
    <span class='smallText'><br><br>This page provides Wi-Fi related settings that can be customized. There are to Wi-Fi spots that can be managed by user. Guest Wi-Fi WL1 spot is isolated from main one WL0. It is supposed to protect LAN from untrusted hosts.</span>
    <span class='smallText'><br><br><b>Mode - </b>user can turn on or turn off access point(AP). By default both acess point are available.  </span>
    <span class='smallText'><br><br><b>SSID - </b>name of access point.</span>
    <span class='smallText'><br><br><b>Channel mode - </b> Wi-Fi starts automatically with 1st channel. User can chose <b>manual mode</b> and take another one from the list.<b>Channel width </b>can be set manual as well. These parameters will be aplied for guest AP too. </span>
    <span class='smallText'><br><br><b>Encryption - </b> can be set in defferent ways or turned off at all. <b>None</b> turns off encryption and routers becomes very vulnarable. It is NOT recommended to turn off encryption. Chose security protocol and set the password.</span>
    <span class='smallText'><br><br><b>Key - </b> password for AP to secure network.</span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='pptp'></a>PPTP</span>
  <div class='controlBoxContent'>
    PPTP
    <span class='smallText'><br><br><b>PPTP Client</b> can be configured by setting user server, username and password data. Use Save/Clear buttons to hold setting or to remove it. Start/Stop will set up VPN tunnel using user profile.</span>
    <span class='smallText'><br><br><b>MPPE-128 </b> is encryption security protocol, that used by each VPN provider. Ask for this configuration your provider.</span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='openvpnclient'></a>Open VPN</span>
  <div class='controlBoxContent'>
    OpenVPN, Configuration
    <span class='smallText'><br><br><b>OVPN client</b> uses configuration file *.ovpn. Upload your configuration file from your PC direct to device and get VPN working. Start/Stop buttons are for VPN process managemet. Edit Config button will help in case if any changes to file are needed to be done. In case of any problem can be usefull to see log by pushing Show Log.</span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='gateways'></a>Gateways</span>
  <div class='controlBoxContent'>
    Default, Gateway By Device
    <span class='smallText'><br><br><b>Gateway - </b> A machine that serves internet; on most LANs this is the device the router's WAN connects to (like your modem). Sabai routers have the special gateway feature which gives the user simple access to both their local ISP's gateway and their remote VPN's gateway. SabaiOpen affords to set Accelerator as gateway to enhance facilities of network. New feature is anonymizing agateway TOR.</span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='tor'></a>Tor</span>
  <div class='controlBoxContent'>
    Tor tunnel
    <span class='smallText'><br><br><b>Tor</b> is new anonymizing feature in SabaiOpen. Turning on tunnel and forwarding any host to ACC will allow to anonymize traffic for the host. User can also set ACC IP and its port 8080 in browser for anonymous browsing. More information about Tor you can find on https://www.torproject.org/</span>
    <span class='smallText'><br><br>Using Tor protects you against a common form of Internet surveillance known as "traffic analysis." Traffic analysis can be used to infer who is talking to whom over a public network. Knowing the source and destination of your Internet traffic allows others to track your behavior and interests.  This TOR client is provided to give network access to TOR for devices which may not have the ability to run TOR locally.  The TOR organization recommends that due to the various methods of tracking traffic, the best way to remain fully anonymous on a computer is through use of the TOR Browser.</span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='ping'></a>Ping</span>
  <div class='controlBoxContent'>
    Ping Address, Count, Size
    <span class='smallText'><br><br><b>Ping</b> is a diagnostics tool of network connection. User can test connection adjusting adsress, count and packet size parameters.</span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='trace'></a>Trace</span>
  <div class='controlBoxContent'>
    Trace Address, Hops, Wait
    <span class='smallText'><br><br><b>Trace</b> is also a diagnostics feature of network connection. User can make diagnostic with displaying the route (path) and measuring transit delays of packets across an Internet Protocol (IP) network.</span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='nslookup'></a>NS Lookup</span>
  <div class='controlBoxContent'>
    Domain, Lookup
    <span class='smallText'><br><br><b>NS Lookup</b> is a network administration tool for querying the Domain Name System (DNS) to obtain domain name or IP address mapping or for any other specific DNS record.</span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='route'></a>Route</span>
  <div class='controlBoxContent'>
    Routing table, Genmask,Flags, MSS, Window, IRTT, Interface
    <span class='smallText'><br><br><b>Routing table</b> is a data table, that lists the routes to particular network destinations. The routing table contains information about the topology of the network. </span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='logs'></a>Logs</span>
  <div class='controlBoxContent'>
    Logs
    <span class='smallText'><br><br><b>Logging</b> is open for user. User can scroll log on page or download as a file to PC.</span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='console'></a>Console</span>
  <div class='controlBoxContent'>
    Console
    <span class='smallText'><br><br><b>Console</b> is accessible for user for advanced diagnostic and management.</span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='firewall'></a>Firewall</span>
  <div class='controlBoxContent'>
    ICMP ping, Multicase, SYn Cookies, WAN route input
    <span class='smallText'><br><br><b>Firewall - </b>is a program, that checks traffic coming in and out and sorts through it accordingly. It's usually used for blocking unauthorized or suspicious connections. A common setup in routers is to allow all outgoing traffic (assuming devices on the network are not malicious) and any incoming traffic that is part of an established connection. </span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='portforwarding'></a>Port Forwarding</span>
  <div class='controlBoxContent'>
    Proto, Ports
    <span class='smallText'><br><br><b>Port Forwarding</b> is a feature for network administration, that provides user with ability to allow external access to some ports on some LAN hosts to, for example, host your own server. Please, familiarize yourself with the concept of port forwarding prior to adding these.</span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='dmz'></a>DMZ</span>
  <div class='controlBoxContent'>
    DMZ, Restriction
    <span class='smallText'><br><br><b>DMZ - </b> DeMilitarized Zone. A network security concept of a LAN machine opened to WAN but doesn't allow to initiate LAN connections. Use this is you have a firewall set up on said machine and would like to have a 'transparent' router. </span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='upnp'></a>UPNP</span>
  <div class='controlBoxContent'>
    Settings, UPNP Ports
    <span class='smallText'><br><br><b>UPnP - </b> stands for “Universal Plug and Play.” Using UPnP an application can automatically forward a port on your router. This is a security risk, so you're advised to keep UPnP off and forward ports manually. It is disabled by default because of security risks.</span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='settings'></a>Settings</span>
  <div class='controlBoxContent'>
    Router Name, Proxy, Power, Password
    <span class='smallText'><br><br><b>Router Name</b> and <b>Password</b> can be updated by user. <b>Password MUST be updated immediately after installation.</b> </span>
    <span class='smallText'><br><br><b>Power</b> off or restart your device direct from WEB UI.</span>
    <span class='smallText'><br><br><b>Proxy</b> listening to port 8080. Turned off by default. </span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='upgrade'></a>Upgrade</span>
  <div class='controlBoxContent'>
    Server Update, Manual Update, Firmware Configuration
    <span class='smallText'><br><br><b>Server Update</b> is automatical upgrade process if new version of software is available.</span>
    <span class='smallText'><br><br><b>Manual Update</b> can be made by uploading .img file. It is available to revert last update and to make factory reset of the last update.</span>
    <span class='smallText'><br><br><b>Firmware Configuration</b> can be backuped or downloaded by user at any time. It ensures flexible switching between different settings. </span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='about'></a>About</span>
  <div class='controlBoxContent'>
    About SabaiOpen
    <span class='smallText'><br><br> Visit About page and learn more about SabaiOpen project. </span>
  </div>
</div>
<div class='controlBox'>
  <span class='controlBoxTitle'><a href='#' name='status'></a>Status</span>
  <div class='controlBoxContent'>
    System, WAN, VPN, Proxy
    <span class='smallText'><br><br>System overview and its status page.</span>
  </div>
</div>

<div id='footer'> Copyright © 2016 Sabai Technology, LLC </div>


<!-- <script type='text/ecmascript' src='/libs/jeditable.js'></script>
<script type='text/ecmascript'>

// $(function() {
// 	$('#accordion').accordion({
//     active:false,
//     animate: false,
//     collapsible:true,
//     heightStyle:"content",
//   });
//   $('#accordion').show();
//  })

</script> -->