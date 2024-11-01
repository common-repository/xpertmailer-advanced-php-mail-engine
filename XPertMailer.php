<?php

/*
Plugin Name: XPertMailer
Plugin URI: http://www.xpertmailer.com
Description: XPertMailer - Advanced PHP Mail Engine
Author: Tanase Laurentiu Iulian
Version: 0.1 Beta
Author URI: http://www.xpertmailer.com
*/

$xpm4_def = array();
$xpm4_def['delivery'] = array(
1 => 'PHP mail()', 
2 => 'MX Zone', 
3 => 'SMTP Relay', 
4 => 'Command Line', 
5 => 'POP before SMTP'
);
$xpm4_def['mx_zone'] = $xpm4_def['relay_auth'] = array(
1 => 'No', 
2 => 'Yes'
);
$xpm4_def['log'] = array(
1 => 'Never', 
2 => 'PHP Log', 
3 => 'File Append'
);
$xpm4_def['relay_ssl'] = array(
1 => 'No', 
2 => 'TLS', 
3 => 'SSL', 
4 => 'SSLv2', 
5 => 'SSLv3'
);
$xpm4_def['auth_method'] = array(
1 => 'Detect', 
2 => 'Login', 
3 => 'Plain', 
4 => 'Cram-MD5'
);

if (isset($_GET['help']) && ($_GET['help'] == '1' || $_GET['help'] == '2' || $_GET['help'] == '3')) {
	$xpm4_help = '';
	if ($_GET['help'] == '1') {
		$xpm4_help = '
<h2>Mail Delivery</h2>
<ul>
<li><b>PHP mail()</b><br>Send mail using php mail() function.
<li><b>MX Zone</b><br>Send mail directly to client MX zone (port 25) without mail server support. This method is prefered by the users that can not have a mail server to use for sending mails.
<ul>
<li>TimeOut - the connection time out number in seconds.
<li>Name - the hostname (can be IPv4 address) used in EHLO/HELO SMTP conversation.
<li>BindTo - connect to internet using IPv4:PORT format (like: 192.168.1.2:0 or 192.168.1.2:100 or 0:100)
</ul>
<li><b>SMTP Relay</b><br>Send mail using a SMTP mail server.
<ul>
<li>HostName/IP - the SMTP mail server address.
<li>Authentication - if the SMTP mail server require authentication username and password.
<li>Method - the SMTP authentication method (detect - leave the XPertMailer to detect the method).
<li>Port - the SMTP mail server port number.
<li>TimeOut - the connection time out number in seconds.
<li>Name - the hostname (can be IPv4 address) used in EHLO/HELO SMTP conversation.
<li>SSL - the SSL encryption version if the SMTP mail server require to encrypt connection (No - doesn\'t use). To use this feature you must have OpenSSL module (extension) enable in your php configuration.
</ul>
<li><b>Command Line</b><br>Send mail using command line.
<ul>
<li>Path to "Unix SendMail like" mail program - the path to your mail program from this host.
</ul>
<li><b>POP before SMTP</b><br>Send mail using proxy mail method. Authentication on POP3 mail server before the SMTP mail server connection.
<ul>
<li>POP3 HostName/IP - the POP3 mail server address.
<li>SMTP HostName/IP - the SMTP mail server address.
<li>UserName - the POP3 account username.
<li>Password - the POP3 account password.
<li>POP3 Port - the POP3 mail server port number.
<li>SMTP Port - the SMTP mail server port number.
<li>TimeOut - the connections time out number in seconds.
<li>Name - the hostname (can be IPv4 address) used in EHLO/HELO SMTP conversation.
</ul>
</ul>
';
	} else if ($_GET['help'] == '2') {
		$xpm4_help = '
<h2>If mail delivery has failed</h2>
This option (2nd) if is enabled will send mails to MX zone only if the previous mail delivery method has failed. 
Using this method will send your mails without a mail server support, directly to client SMTP mail server (port 25) from MX zone.
<ul>
<li>TimeOut - the connection time out number in seconds.
<li>Name - the hostname (can be IPv4 address) used in EHLO/HELO SMTP conversation.
<li>BindTo - connect to internet using IPv4:PORT format (like: 192.168.1.2:0 or 192.168.1.2:100 or 0:100)
</ul>
';
	} else if ($_GET['help'] == '3') {
		$xpm4_help = '
<h2>Log XPertMailer errors</h2>
This option, if is enabled, will help you to debugging the mail delivery option.
<ul>
<li>Never - dont save any error.
<li>PHP Log - save the error in php log file.
<li>File Append - save the error to specified file name, this file must exist and have the write permissions.
</ul>
';
	}
	$xpm4_help = ''.
'<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>XPertMailer Help</title>
</head>
<body>
'.$xpm4_help.'
</body>
</html>';
	die($xpm4_help);
}

if (isset($_GET['img'])) {
	$xpm4_img = 'R0lGODlhUAAPAJEAABef2WZmZv///4mOeSH5BAAAAAAALAAAAABQAA8AAAKsjI+py+0Pj5i02ouz3rwbAYTiSALCgKbq'.
		'yrbuC6vCV1rkGef67s4BOAKKhCae8RjzEYEUU1CWOlFQ02plgMNScdLoBPtdKYNO51KsTXep7DT4zdVm5+0obVgGndFb7nda1yVV'.
		'5WaVZfdDxlQWcihXVxgZqPY46Yh119iodwPFdkhIafkJNrjmOEZWMoTU6kqVOdTU+VrLk7q6emnLy6LUARwsPIwRYXyMnKxQAAA7';
	$xpm4_img = base64_decode($xpm4_img);
	header('Content-Type: image/gif');
	header('Content-Length: '.strlen($xpm4_img));
	header('Content-Disposition: inline; filename="xpertmailer.gif"');
	die($xpm4_img);
}

function xpm4_extract($d) {
	$l = strlen($d);
	$a = $n = null;
	if (FUNC::is_mail($d)) $a = $d;
	if ($a == null) {
		$a = substr($d, strrpos($d, '<')+1, $l);
		$a = rtrim($a, '>');
		if (FUNC::is_mail($a)) {
			if ($p = strrpos($d, ' ')) {
				$n = trim(substr($d, 0, $p), '"');
				if ($n == '') $n = null;
			}
		} else $a = null;
	}
	if ($a == null) {
		if ($p = strrpos($d, ' ')) {
			$a = substr($d, $p+1, $l);
			if (FUNC::is_mail($a)) {
				$n = trim(substr($d, 0, $p), '"');
				if ($n == '') $n = null;
			} else $a = null;
		}
	}
	return array('address' => $a, 'name' => $n);
}

require_once 'XPM4.php';

if (isset($_GET['test'], $_POST['xmail'])) {
	$xpm4_js = array();
	if (!FUNC::is_mail($_POST['xmail'])) $xpm4_js[] = "alert('Invalid mail address !')";
	else {
		if (wp_mail($_POST['xmail'], 'XPertMailer Mail Delivery Test', 'It works !')) $xpm4_js[] = "alert('Successfully sent !\\nCheck your mail box.')";
		else $xpm4_js[] = "alert('Mail send failed !')";
	}
	$xpm4_out = ''.
'<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>XPertMailer Test</title>
</head>
<body onload="'.implode('; ', $xpm4_js).'">
</body>
</html>';
	die($xpm4_out);
}

if (isset($_GET['xpm4'])) add_action('init', 'xpm4_set');
function xpm4_set() {
	global $xpm4_def;
	$opt = $err = $js = array();
	if (!current_user_can('level_10')) $err[] = '- you don\'t have privileges';
	else if (!isset($_POST['delivery'])) $err[] = '- delivery post data';
	else {
		$opt['delivery'] = intval($_POST['delivery']);
		if (!isset($xpm4_def['delivery'][$opt['delivery']])) $err[] = '- delivery option';
		else {
			// delivery
			if ($opt['delivery'] == 2) {
				if (isset($_POST['mx_timeout'], $_POST['mx_name'], $_POST['mx_bindto'])) {
					$opt['mx_timeout'] = intval($_POST['mx_timeout']);
					if (!($_POST['mx_timeout'] === strval($opt['mx_timeout']) && xpm4_istime($opt['mx_timeout']))) $err[] = '- timeout, must be positive integer';
					if (xpm4_ishost($_POST['mx_name'])) $opt['mx_name'] = $_POST['mx_name'];
					else $err[] = '- name, must be hostname or IPv4';
					if (trim($_POST['mx_bindto']) == '' || (count($exp = explode(':', trim($_POST['mx_bindto']), 2)) == 2 && ($exp[0] == '0' || FUNC::is_ipv4($exp[0])) && $exp[1] === strval(intval($exp[1])) && ($exp[1] == '0' || xpm4_isport(intval($exp[1]))))) $opt['mx_bindto'] = trim($_POST['mx_bindto']);
					else $err[] = '- bindto, must be IPv4:PORT format';
				} else $err[] = '- MX zone post data';
			} else if ($opt['delivery'] == 3) {
				if (isset($_POST['relay_host'], $_POST['relay_auth'], $_POST['relay_port'], $_POST['relay_timeout'], $_POST['relay_name'], $_POST['relay_ssl'])) {
					if (xpm4_ishost($_POST['relay_host'])) $opt['relay_host'] = $_POST['relay_host'];
					else $err[] = '- hostname/ip';
					$opt['relay_auth'] = intval($_POST['relay_auth']);
					if (!isset($xpm4_def['relay_auth'][$opt['relay_auth']])) $err[] = '- authentication option';
					else if ($opt['relay_auth'] == 2) {
						if (!isset($_POST['relay_user'], $_POST['relay_pass'], $_POST['auth_method'])) $err[] = '- auth post data';
						else if (trim($_POST['relay_user']) == '' || trim($_POST['relay_pass']) == '') $err[] = '- authentication username or/and password';
						else {
							$opt['relay_user'] = $_POST['relay_user'];
							$opt['relay_pass'] = $_POST['relay_pass'];
						}
						$opt['auth_method'] = intval($_POST['auth_method']);
						if (!isset($xpm4_def['auth_method'][$opt['auth_method']])) $err[] = '- authentication method';
					}
					$opt['relay_port'] = intval($_POST['relay_port']);
					if (!($_POST['relay_port'] === strval($opt['relay_port']) && xpm4_isport($opt['relay_port']))) $err[] = '- port, must be positive integer';
					$opt['relay_timeout'] = intval($_POST['relay_timeout']);
					if (!($_POST['relay_timeout'] === strval($opt['relay_timeout']) && xpm4_istime($opt['relay_timeout']))) $err[] = '- timeout, must be positive integer';
					if (xpm4_ishost($_POST['relay_name'])) $opt['relay_name'] = $_POST['relay_name'];
					else $err[] = '- name, must be hostname or IPv4';
					$opt['relay_ssl'] = intval($_POST['relay_ssl']);
					if (!isset($xpm4_def['relay_ssl'][$opt['relay_ssl']])) $err[] = '- SSL option';
					else if ($opt['relay_ssl'] > 1) {
						if (!extension_loaded('openssl')) {
							$dl = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? @dl('php_openssl.dll') : @dl('openssl.so');
							if (!$dl) $err[] = '- OpenSSL extension, you must enable it in php configuration';
						}
					}
				} else $err[] = '- SMTP Relay post data';
			} else if ($opt['delivery'] == 4) {
				if (!isset($_POST['sendmail'])) $err[] = '- sendmail path post data';
				else {
					$opt['sendmail'] = $_POST['sendmail'];
					if (trim($opt['sendmail']) == '') $err[] = '- path to mail program';
				}
			} else if ($opt['delivery'] == 5) {
				if (isset($_POST['proxy_pop3'], $_POST['proxy_smtp'], $_POST['proxy_user'], $_POST['proxy_pass'], $_POST['port_pop3'], $_POST['port_smtp'], $_POST['proxy_timeout'], $_POST['proxy_name'])) {
					if (xpm4_ishost($_POST['proxy_pop3'])) $opt['proxy_pop3'] = $_POST['proxy_pop3'];
					else $err[] = '- pop3 hostname/ip';
					if (xpm4_ishost($_POST['proxy_smtp'])) $opt['proxy_smtp'] = $_POST['proxy_smtp'];
					else $err[] = '- smtp hostname/ip';
					if (trim($_POST['proxy_user']) == '' || trim($_POST['proxy_pass']) == '') $err[] = '- pop3 account username or/and password';
					else {
						$opt['proxy_user'] = $_POST['proxy_user'];
						$opt['proxy_pass'] = $_POST['proxy_pass'];
					}
					$opt['port_pop3'] = intval($_POST['port_pop3']);
					if (!($_POST['port_pop3'] === strval($opt['port_pop3']) && xpm4_isport($opt['port_pop3']))) $err[] = '- pop3 port, must be positive integer';
					$opt['port_smtp'] = intval($_POST['port_smtp']);
					if (!($_POST['port_smtp'] === strval($opt['port_smtp']) && xpm4_isport($opt['port_smtp']))) $err[] = '- smtp port, must be positive integer';
					$opt['proxy_timeout'] = intval($_POST['proxy_timeout']);
					if (!($_POST['proxy_timeout'] === strval($opt['proxy_timeout']) && xpm4_istime($opt['proxy_timeout']))) $err[] = '- timeout, must be positive integer';
					if (xpm4_ishost($_POST['proxy_name'])) $opt['proxy_name'] = $_POST['proxy_name'];
					else $err[] = '- name, must be hostname or IPv4';
				} else $err[] = '- POP before SMTP post data';
			}
			// 2nd
			if ($opt['delivery'] != 2 && isset($_POST['mx_zone'], $_POST['mx_timeout'], $_POST['mx_name'], $_POST['mx_bindto']) && $_POST['mx_zone'] == '2') {
				$opt['mx_zone'] = 2;
				$int['mx_timeout'] = intval($_POST['mx_timeout']);
				if ($_POST['mx_timeout'] === strval($int['mx_timeout']) && xpm4_istime($int['mx_timeout'])) $opt['mx_timeout'] = $int['mx_timeout'];
				else $err[] = '- timeout, must be positive integer';
				if (xpm4_ishost($_POST['mx_name'])) $opt['mx_name'] = $_POST['mx_name'];
				else $err[] = '- name, must be hostname or IPv4';
				if (trim($_POST['mx_bindto']) == '' || (count($exp = explode(':', trim($_POST['mx_bindto']), 2)) == 2 && ($exp[0] == '0' || FUNC::is_ipv4($exp[0])) && $exp[1] === strval(intval($exp[1])) && ($exp[1] == '0' || xpm4_isport(intval($exp[1]))))) $opt['mx_bindto'] = trim($_POST['mx_bindto']);
				else $err[] = '- bindto, must be IPv4:PORT format';
			}
			// log
			if (!isset($_POST['log'])) $err[] = '- log post data';
			else {
				$opt['log'] = intval($_POST['log']);
				if (!isset($xpm4_def['log'][$opt['log']])) $err[] = '- log option';
				else if ($opt['log'] == 3) {
					if (!isset($_POST['logfile'])) $err[] = '- logfile post data';
					else {
						$opt['logfile'] = trim(str_replace('\\\\', '/', $_POST['logfile']));
						if(!(is_file($opt['logfile']) && is_writable($opt['logfile']))) $err[] = '- log file, must exist and have the write permissions';
					}
				}
			}
		}
	}
	$js[] = "parent.document.getElementById('xf2').style.display='inline'";
	$js[] = "parent.document.getElementById('xf1').style.display='none'";
	if (count($err) > 0) $js[] = "alert('Invalid:\\n\\n".implode("\\n", $err)."')";
	else {
		update_option('xpm4', $opt);
		$js[] = "alert('Successfully updated !')";
	}
	$out = ''.
'<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>XPertMailer Update</title>
</head>
<body onload="'.implode('; ', $js).'">
</body>
</html>';
	die($out);
}

add_action('admin_menu', 'xpm4_menu');
function xpm4_menu() {
	add_options_page('XPertMailer', 'XPertMailer', 10, __FILE__, 'xpm4_show');
}
function xpm4_show() {
	global $xpm4_def;
	$cnf = xpm4_conf();
	$sel = array();
	foreach ($xpm4_def as $key => $val) $sel[$key] = '';
	foreach ($xpm4_def['delivery'] as $key => $val) $sel['delivery'] .= '<option value="'.$key.'"'.(($key == $cnf['delivery']) ? ' selected="true"' : '').'>'.$val.'</option>';
	foreach ($xpm4_def['relay_ssl'] as $key => $val) $sel['relay_ssl'] .= '<option value="'.$key.'"'.(($key == $cnf['relay_ssl']) ? ' selected="true"' : '').'>'.$val.'</option>';
	foreach ($xpm4_def['auth_method'] as $key => $val) $sel['auth_method'] .= '<option value="'.$key.'"'.(($key == $cnf['auth_method']) ? ' selected="true"' : '').'>'.$val.'</option>';
	foreach ($xpm4_def['mx_zone'] as $key => $val) $sel['mx_zone'] .= '<input onclick="xpm4_chx()" id="xm'.$key.'" type="radio" name="mx_zone" value="'.$key.'"'.(($key == $cnf['mx_zone']) ? ' checked="true"' : '').' /> <label for="xm'.$key.'">'.$val.'</label> ';
	foreach ($xpm4_def['relay_auth'] as $key => $val) $sel['relay_auth'] .= '<input onclick="xpm4_chr()" id="xr'.$key.'" type="radio" name="relay_auth" value="'.$key.'"'.(($key == $cnf['relay_auth']) ? ' checked="true"' : '').' /> <label for="xr'.$key.'">'.$val.'</label> ';
	foreach ($xpm4_def['log'] as $key => $val) $sel['log'] .= '<option value="'.$key.'"'.(($key == $cnf['log']) ? ' selected="true"' : '').'>'.$val.'</option>';

	$help = '( <a title="Help" onclick="xpm4_popup(\''.$_SERVER['REQUEST_URI'].'&amp;help=%s\', \'xwpop\')" href="'.$_SERVER['REQUEST_URI'].'&amp;help=%s" target="xwpop">?</a> )';

	echo '
<script type="text/javascript">
//<![CDATA[
var xfrm, xdel;
var xarr = [];
function xpm4_sel() {
	for (i = 1; i <= 5; i++) document.getElementById("x" + i).style.display = "none";
	if (xarr.length > 0) {
		for (i in xarr) document.getElementById("x" + xarr[i]).style.display = "inline";
	}
}
function xpm4_chk() {
	xfrm = document.forms["xfrm"];
	xdel = xfrm.elements["delivery"].value;
	if (xdel == "1") xarr = ["1"];
	else if (xdel == "2") xarr = ["2"];
	else if (xdel == "3") xarr = ["1","4"];
	else if (xdel == "4") xarr = ["1","3"];
	else if (xdel == "5") xarr = ["1","5"];
	xpm4_sel();
	document.getElementById("x2").style.display = (document.getElementById("xm2").checked || xdel == "2") ? "inline" : "none";
}
function xpm4_chx() {
	document.getElementById("x2").style.display = document.getElementById("xm1").checked ? "none" : "inline";
}
function xpm4_chr() {
	document.getElementById("xrelay").style.display = document.getElementById("xr1").checked ? "none" : "inline";
}
var xwin, xuri = null;
function xpm4_popup(xvurl, xvnam) {
	if (xwin == null || xwin.closed || xuri != xvurl) {
			xuri = xvurl;
			var xvw = 500;
			var xvh = 400;
			xwin = window.open(xvurl, xvnam, \'menubar=no,location=no,resizable=yes,scrollbars=yes,width=\'+xvw+\',height=\'+xvh+\',left=\'+((screen.width/2)-(xvw/2))+\',top=\'+((screen.height/2)-(xvh/2)));
	}
	xwin.focus();
}
function xpm4_chl() {
	xfrm = document.forms["xfrm"];
	document.getElementById("xlog").style.display = (xfrm.elements["log"].value == "3") ? "inline" : "none";
}
function xchg() {
	if (document.getElementById("xg").checked) {
		xfrm = document.forms["xfrm"];
		xfrm.elements["delivery"].selectedIndex = 2;
		xpm4_chk();
		document.getElementById("xr2").checked = true;
		xpm4_chr();
		xfrm.elements["relay_host"].value = "smtp.gmail.com";
		xfrm.elements["relay_user"].value = "username@gmail.com";
		xfrm.elements["relay_pass"].value = "";
		xfrm.elements["relay_port"].value = "465";
		xfrm.elements["relay_timeout"].value = "10";
		xfrm.elements["relay_name"].value = "localhost";
		xfrm.elements["auth_method"].selectedIndex = 0;
		xfrm.elements["relay_ssl"].selectedIndex = 1;
		alert("Write your Gmail account username and password in the specified fields.");
		xfrm.elements["relay_user"].focus();
		document.getElementById("xg").checked = false;
	}
}
function xchfrm() {
	document.getElementById("xf2").style.display = "none";
	document.getElementById("xf1").style.display = "inline";
	return true;
}
//]]>
</script>
<div class="wrap"><h2>XPertMailer Options</h2>
<span id="xf1" style="display: none"><i><b>Loading ...</b></i><br /><br /></span>
<span id="xf2">
<form name="xfrm" onsubmit="return xchfrm()" action="'.$_SERVER['REQUEST_URI'].'&amp;xpm4" method="post" target="ifr">
'.sprintf($help,1,1).' <b>Mail delivery:</b> <select name="delivery" onchange="xpm4_chk()">'.$sel['delivery'].'</select><br />
<span id="x4" style="display: '.(($cnf['delivery'] == 3) ? 'inline' : 'none').'">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HostName/IP: <input type="text" name="relay_host" size="22" maxlength="255" value="'.$cnf['relay_host'].'" /> 
Require Authentication ? '.$sel['relay_auth'].'<br />
<span id="xrelay" style="display: '.(($cnf['relay_auth'] == 2) ? 'inline' : 'none').'">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UserName: <input type="text" name="relay_user" size="16" maxlength="100" value="'.$cnf['relay_user'].'" /> 
Password: <input type="password" name="relay_pass" size="16" maxlength="100" value="'.$cnf['relay_pass'].'" /> 
Method: <select name="auth_method">'.$sel['auth_method'].'</select><br />
</span>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Port: <input type="text" name="relay_port" size="4" maxlength="6" value="'.$cnf['relay_port'].'" /> 
TimeOut: <input type="text" name="relay_timeout" size="4" maxlength="4" value="'.$cnf['relay_timeout'].'" /> 
Name: <input type="text" name="relay_name" size="16" maxlength="255" value="'.$cnf['relay_name'].'" /> 
SSL: <select name="relay_ssl">'.$sel['relay_ssl'].'</select>
<br /></span>
<span id="x5" style="display: '.(($cnf['delivery'] == 5) ? 'inline' : 'none').'">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;POP3 HostName/IP: <input type="text" name="proxy_pop3" size="16" maxlength="255" value="'.$cnf['proxy_pop3'].'" /> 
SMTP HostName/IP: <input type="text" name="proxy_smtp" size="16" maxlength="255" value="'.$cnf['proxy_smtp'].'" /><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UserName: <input type="text" name="proxy_user" size="22" maxlength="100" value="'.$cnf['proxy_user'].'" /> 
Password: <input type="password" name="proxy_pass" size="22" maxlength="100" value="'.$cnf['proxy_pass'].'" /><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;POP3 Port: <input type="text" name="port_pop3" size="4" maxlength="6" value="'.$cnf['port_pop3'].'" /> 
SMTP Port: <input type="text" name="port_smtp" size="4" maxlength="6" value="'.$cnf['port_smtp'].'" /> 
TimeOut: <input type="text" name="proxy_timeout" size="4" maxlength="4" value="'.$cnf['proxy_timeout'].'" /> 
Name: <input type="text" name="proxy_name" size="16" maxlength="255" value="'.$cnf['proxy_name'].'" />
<br /></span>
<span id="x3" style="display: '.(($cnf['delivery'] == 4) ? 'inline' : 'none').'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Path to "Unix SendMail like" mail program: <input type="text" name="sendmail" size="24" maxlength="200" value="'.$cnf['sendmail'].'" /><br /></span>
<span id="x1" style="display: '.(($cnf['delivery'] != 2) ? 'inline' : 'none').'">'.sprintf($help,2,2).' If mail delivery has failed, send mail to MX Zone ? '.$sel['mx_zone'].'<br /></span>
<span id="x2" style="display: '.(($cnf['delivery'] == 2 || $cnf['mx_zone'] == 2) ? 'inline' : 'none').'">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TimeOut: <input type="text" name="mx_timeout" size="4" maxlength="4" value="'.$cnf['mx_timeout'].'" /> 
Name: <input type="text" name="mx_name" size="16" maxlength="255" value="'.$cnf['mx_name'].'" /> 
BindTo: <input type="text" name="mx_bindto" size="16" maxlength="22" value="'.$cnf['mx_bindto'].'" />
<br /></span>
'.sprintf($help,3,3).' Log XPertMailer errors ? <select name="log" onchange="xpm4_chl()">'.$sel['log'].'</select>
<span id="xlog" style="display: '.(($cnf['log'] == 3) ? 'inline' : 'none').'">
File: <input type="text" name="logfile" size="24" maxlength="200" value="'.$cnf['logfile'].'" />
</span>
<br /><br /><input id="xg" onclick="xchg()" type="checkbox"> <label for="xg">I want to use my Gmail account to send mails.</label>
<p class="submit" style="text-align:left"><input type="submit" value="Update Options &raquo;" /></p>
</form>
</span>
For updates and donations, visit the XPertMailer project home page <a title="XPertMailer Home Page" href="http://www.xpertmailer.com/" target="_blank">www.xpertmailer.com</a> .
<h2>Mail Delivery Test</h2>
Once you\'ve saved your settings you can test the mail delivery method you choose.<br />
<form action="'.$_SERVER['REQUEST_URI'].'&amp;test" method="post" target="ifr">
Send test mail to this address: <input type="text" name="xmail" maxlength="255" size="24" value="@" /> <input type="submit" value="Send" />
<br />JavaScript alert window will appear with "Successfully sent !" message if the test is ok.
</form>
<br /><iframe name="ifr" width="0" height="0" marginwidth="0" marginheight="0" scrolling="no" frameborder="0"></iframe><br />
<i>Powered by</i> <a style="border: none" title="Powered by XPertMailer" href="http://www.xpertmailer.com/" width="80" height="15" target="_blank"><img src="'.$_SERVER['REQUEST_URI'].'&amp;img" border="0" alt="Powered by XPertMailer" /></a>
</div>
';
}

function xpm4_ishost($val) {
	return (strtolower($val) == 'localhost' || FUNC::is_hostname($val) || FUNC::is_ipv4($val));
}
function xpm4_isport($val) {
	return (is_int($val) && $val > 0 && $val <= 999999);
}
function xpm4_istime($val) {
	return (is_int($val) && $val > 0 && $val <= 9999);
}

function xpm4_conf() {
	global $xpm4_def;
	$cnf = $arr = array();
	$opt = get_option('xpm4');
	foreach ($xpm4_def as $key => $val) $cnf[$key] = (isset($opt[$key]) && isset($xpm4_def[$key][$opt[$key]])) ? $opt[$key] : 1;
	$cnf['mx_timeout']    = (isset($opt['mx_timeout']) && xpm4_istime($opt['mx_timeout'])) ? $opt['mx_timeout'] : 10;
	$cnf['mx_name']       = (isset($opt['mx_name']) && xpm4_ishost($opt['mx_name'])) ? strtolower($opt['mx_name']) : 'localhost';
	$cnf['mx_bindto']     = (isset($opt['mx_bindto']) && count($exp = explode(':', trim($opt['mx_bindto']), 2)) == 2 && ($exp[0] == '0' || FUNC::is_ipv4($exp[0])) && $exp[1] === strval(intval($exp[1])) && ($exp[1] == '0' || xpm4_isport(intval($exp[1])))) ? $opt['mx_bindto'] : '';
	$cnf['sendmail']      = (isset($opt['sendmail']) && trim($opt['sendmail']) != '') ? $opt['sendmail'] : '/usr/sbin/sendmail';
	$cnf['logfile']       = (isset($opt['logfile']) && is_file($opt['logfile']) && is_writable($opt['logfile'])) ? $opt['logfile'] : '';
	$cnf['relay_host']    = (isset($opt['relay_host']) && xpm4_ishost($opt['relay_host'])) ? strtolower($opt['relay_host']) : '';
	$cnf['relay_port']    = (isset($opt['relay_port']) && xpm4_isport($opt['relay_port'])) ? $opt['relay_port'] : 25;
	$cnf['relay_timeout'] = (isset($opt['relay_timeout']) && xpm4_istime($opt['relay_timeout'])) ? $opt['relay_timeout'] : 10;
	$cnf['relay_name']    = (isset($opt['relay_name']) && xpm4_ishost($opt['relay_name'])) ? strtolower($opt['relay_name']) : 'localhost';
	$cnf['relay_user']    = (isset($opt['relay_user'], $opt['relay_pass']) && $opt['relay_pass'] != '') ? substr($opt['relay_user'], 0, 100) : '';
	$cnf['relay_pass']    = (isset($opt['relay_user'], $opt['relay_pass']) && $opt['relay_user'] != '') ? substr($opt['relay_pass'], 0, 100) : '';
	$cnf['proxy_pop3']    = (isset($opt['proxy_pop3']) && xpm4_ishost($opt['proxy_pop3'])) ? strtolower($opt['proxy_pop3']) : '';
	$cnf['proxy_smtp']    = (isset($opt['proxy_smtp']) && xpm4_ishost($opt['proxy_smtp'])) ? strtolower($opt['proxy_smtp']) : '';
	$cnf['port_smtp']     = (isset($opt['port_smtp']) && xpm4_isport($opt['port_smtp'])) ? $opt['port_smtp'] : 25;
	$cnf['port_pop3']     = (isset($opt['port_pop3']) && xpm4_isport($opt['port_pop3'])) ? $opt['port_pop3'] : 110;
	$cnf['proxy_timeout'] = (isset($opt['proxy_timeout']) && xpm4_istime($opt['proxy_timeout'])) ? $opt['proxy_timeout'] : 10;
	$cnf['proxy_name']    = (isset($opt['proxy_name']) && xpm4_ishost($opt['proxy_name'])) ? strtolower($opt['proxy_name']) : 'localhost';
	$cnf['proxy_user']    = (isset($opt['proxy_user'], $opt['proxy_pass']) && $opt['proxy_pass'] != '') ? substr($opt['proxy_user'], 0, 100) : '';
	$cnf['proxy_pass']    = (isset($opt['proxy_user'], $opt['proxy_pass']) && $opt['proxy_user'] != '') ? substr($opt['proxy_pass'], 0, 100) : '';
	return $cnf;
}

?>