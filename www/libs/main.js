Array.prototype.find = function(v){ 
  var i = f.length; 
  while(this[--i]!=v && i>=0); 
  return i; 
}

Array.prototype.remove = function(v){ 
  var i = this.find(v); 
  if(i!=-1){ 
    this.splice(i, 1); 
    return true; 
  }else{ 
    return false; 
  } 
}

function E(e){ 
  return (typeof(e) == 'string') ? document.getElementById(e) : e; 
}

function peekaboo(id){ 
  var e = E(id); 
  e.type = e.type=='password' ? 'text' : 'password'; 
}

function reloadPage(){ 
  document.location.reload(1); 
}

var cookie = {
  set: function(key, value, days){ 
    document.cookie = 'vpna_' + key + '=' + value + '; expires=' + (new Date(new Date().getTime() + ((days ? days : 14) * 86400000))).toUTCString() + '; path=/'; 
  },
  get: function(key){ 
    var r = ('; ' + document.cookie + ';').match('; vpna_' + key + '=(.*?);'); 
    return r ? r[1] : null; 
  },
  unset: function(key) { 
    document.cookie = 'vpna_' + key + '=; expires=' + (new Date(1)).toUTCString() + '; path=/'; 
  }
};

function toggleChildMenu(){ 
  var ch = E('sub-'+this.id); 
  ch.className = (ch.className == 'shownChildMenu') ? 'hiddenChildMenu' : 'shownChildMenu'; 
}

function requeue(){
  var queue = []; 
  var workingNow = false; 
  var me = this; 
  var client; 
  var current = {};

  this.handle = function(){ 
    if((client!=null)&&(client.readyState == 4)&&(client.status==200)){ 
      current.handler(client.responseText); 
      me.crunch(); 
    } 
  }

  this.request = function(){ 
    client = new XMLHttpRequest(); 
    client.onreadystatechange = me.handle; 
    client.open(current.type||'POST',current.path,true); 
    client.setRequestHeader('Content-Type', current.header || "application/x-www-form-urlencoded"); 
    client.send(current.args); 
  }

  this.crunch = function(){ 
    if(current = queue.shift()){ 
      workingNow = true; 
      me.request(); 
    }else{ 
      workingNow = false; 
    } 
  }

  this.drop = function(q_path, q_handler, q_args, q_type, q_header){ 
    if(q_path!=null) 
      me.add(q_path, q_handler, q_args, q_type, q_header); 
    
    if(!workingNow){ 
      me.crunch(); 
    } 
  }

  this.add = function(q_path, q_handler, q_args, q_type, q_header){ 
    queue.push(
      { 
        path: q_path, 
        handler: q_handler, 
        args: q_args, 
        type: q_type, 
        header: q_header 
      }
    ); 
  }
}

var que = new requeue();

function verifyFields(){}

function hideUi(hide_msg){ 
  window.hidden_working = true; 
  hide.innerHTML = hide_msg; 
  hidden.style.display = 'block'; 
}

function hideUi_timer(hide_msg, time) {
  window.hidden_working = true;
  var count = time;
  var counter = setInterval(timer, 1000);

  function timer() {
  minutes = count/60 >> 0;
  seconds = count - minutes*60;
  zero_up = minutes*60 + 10;
  zero_low = minutes*60;
  
  if ((count > zero_low) && (count < zero_up)) {
                hide.innerHTML= hide_msg + " " + minutes + ":" + "0" + seconds;
        } else {
                hide.innerHTML= hide_msg + " " + minutes + ":" + seconds;
        }
    
  if ((count%60 == 1) && (count != 1)) {
    count = count - 2;
  } else {
    count = count - 1;
  }

    if (count < 0)
    {
        clearInterval(counter);
        return;
    }

}
  hidden.style.display = 'block';
}

function showUi(text){ 
  window.hidden_working = false; 
  hidden.style.display = 'none'; 
}

function msg(msg,wl){
  if(wl)
    E('messages1').innerHTML=msg;
  else
    E('messages').innerHTML=msg; 
}

function setVPNStats(){
  $('#vpnstats').innerHTML = (info.vpn.status == '-')?'':info.vpn.type +' is '+ info.vpn.status +' at '+info.vpn.ip;
  setTimeout(getUpdate,(info.vpn.status == '-')?30000:5000); 
  return;
}

// begin main - prior old sabaivpn

$.peekaboo = $.fn.peekaboo = function(test){
  if(test){
    $("#testing").append("Is ("+ this.attr("type") +"/"+ (this.attr("type") == "password") +") a password: ");

    if((this.attr("type") == "password"))
      $("#testing").append("Yes.\n")
    else
      $("#testing").append("No.\n")
  }
  $( this.selector || "input[type=password]" )
    .prop("type", "password")
    .focus(function(){ $(this).prop("type", "text"); })
    .blur(function(){ $(this).prop("type", "password"); })
    .keydown(function(event){ if(event.keyCode == 13){ $(this).prop("type", "password"); } });
}

function showSubMenu(){ $( "#sub"+ $(this).attr("id") ).slideToggle(500); }

$(function(){
 if(E('panel').value =='auth') {
   panel=E('panel').value;
 } else {
 if(panel==""){ panel = "network"; section = "wan"; };
 $("#mainTitle").append(" - "+$(".pageTitle").html());
 $(".subMenu").hide();
 $(".superMenuLink").click(showSubMenu);
 $("#submenu_"+ panel).show();
 $("#menu_"+ panel +((section)?("_"+ section):"") ).addClass("buttonSelected");
 $.peekaboo();
 }
});

//ipv4 address validation
jQuery.validator.addMethod('validip', function(value) {
    return /^((?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){0,1}$/.test(value);
}, ' *Invalid IP Address.');

// 'netmask': IPv4 Netmask Validator 
jQuery.validator.addMethod("netmask", function(value, element) {
    return /^((128|192|224|240|248|252|254)\.0\.0\.0)|(255\.(((0|128|192|224|240|248|252|254)\.0\.0)|(255\.(((0|128|192|224|240|248|252|254)\.0)|255\.(0|128|192|224|240|248|252|254)))))$/.test(value);
}, ' *Invalid IPv4 netmask.');

// mac address checker 
jQuery.validator.addMethod("macchecker", function(value, element) {                                                                  
    return /^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/.test(value);
  },' *Invalid mac address.');

// Diagnostics Ping address validator
jQuery.validator.addMethod('addressCheck', function(value){
  return /^(([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6})|((([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))$/.test(value);
}, " *Invalid address.");
