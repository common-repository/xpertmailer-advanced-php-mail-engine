<?php

define('DISPLAY_XPM4_ERRORS', false);
$xpm4_global = get_option('xpm4');
if (isset($xpm4_global['log'])) {
	if ($xpm4_global['log'] == 2) define('LOG_XPM4_ERRORS', serialize(array('type' => 0)));
	else if ($xpm4_global['log'] == 3 && isset($xpm4_global['logfile'])) define('LOG_XPM4_ERRORS', serialize(array('type' => 3, 'destination' => $xpm4_global['logfile'])));
}
require_once 'XPM4/MAIL.php';
require_once 'XPM4/POP3.php';

if (!function_exists('wp_mail')) {

function wp_mail($to, $subject, $body, $headers = '') {
	global $xpm4_def;
	$x = get_option('xpm4');
	if (!isset($x['delivery'])) $x['delivery'] = 1;
	$r = $c = false;
	$m = new MAIL;
	if (trim($headers) != '') {
		if ($h = MIME::isset_header($headers, 'from')) {
			$z = MIME::decode_header($h);
			$d = '';
			foreach ($z as $b) $d .= $b['value'];
			$s = xpm4_extract($d);
			if ($s['address'] != null) $m->From($s['address'], $s['name']);
		}
		if ($h = MIME::isset_header($headers, 'cc')) {
			$z = MIME::decode_header($h);
			$d = '';
			foreach ($z as $b) $d .= $b['value'];
			foreach (explode(', ', $d) as $e) {
				$s = xpm4_extract($e);
				if ($s['address'] != null) $m->AddCc($s['address'], $s['name']);
			}
		}
		if ($h = MIME::isset_header($headers, 'bcc')) {
			$z = MIME::decode_header($h);
			$d = '';
			foreach ($z as $b) $d .= $b['value'];
			foreach (explode(', ', $d) as $e) {
				$s = xpm4_extract($e);
				if ($s['address'] != null) $m->AddBcc($s['address'], $s['name']);
			}
		}
		if ($h = MIME::isset_header($headers, 'content-type')) $c = strtolower($h);
	}
	foreach (explode(', ', $to) as $t) {
		$s = xpm4_extract($t);
		if ($s['address'] != null) $m->AddTo($s['address'], $s['name']);
	}
	$m->Subject($subject);
	if ($c == 'text/html') $m->Html($body);
	else $m->Text($body);
	if ($m->From == null) {
		$s = @ini_get('sendmail_from');
		if ($s != '' && FUNC::is_mail($s)) $m->From($s);
		else {
			$s = strtolower($_SERVER['SERVER_NAME']);
			if (substr($s, 0, 4 ) == 'www.') $s = substr($s, 4);
			if (FUNC::is_hostname($s)) $m->From('xpertmailer@'.$s);
			else $m->From('xpertmailer@127.0.0.1');
		}
	}
	// delivery
	if ($x['delivery'] == 1) {
		$r = $m->Send();
	} else if ($x['delivery'] == 2) {
		if (isset($x['mx_timeout'], $x['mx_name'], $x['mx_bindto'])) {
			if (intval($x['mx_timeout']) > 0) $m->Tout = intval($x['mx_timeout']);
			if ($x['mx_name'] != '') $m->Name($x['mx_name']);
			if (trim($x['mx_bindto']) != '') $m->Context(array('socket' => array('bindto' => $x['mx_bindto'])));
			$r = $m->Send('client');
		}
	} else if ($x['delivery'] == 3) {
		if (isset($x['relay_host'], $x['relay_auth'], $x['relay_port'], $x['relay_timeout'], $x['relay_name'], $x['relay_ssl'])) {
			if (!($x['relay_auth'] == 2 && isset($x['relay_user'], $x['relay_pass'], $x['auth_method']))) $x['relay_user'] = $x['relay_pass'] = $x['auth_method'] = null;
			$f = array();
			$f['ssl'] = $f['auth'] = null;
			if ($x['relay_ssl'] > 1 && isset($xpm4_def['relay_ssl'][$x['relay_ssl']])) $f['ssl'] = $xpm4_def['relay_ssl'][$x['relay_ssl']];
			if ($x['auth_method'] > 1 && isset($xpm4_def['auth_method'][$x['auth_method']])) $f['auth'] = $xpm4_def['auth_method'][$x['auth_method']];
			if ($o = $m->Connect($x['relay_host'], $x['relay_port'], $x['relay_user'], $x['relay_pass'], $f['ssl'], $x['relay_timeout'], $x['relay_name'], null, $f['auth'])) {
				$r = $m->Send($o);
				$m->Disconnect($o);
			}
		}
	} else if ($x['delivery'] == 4) {
		if (isset($x['sendmail'])) {
			$m->SendMail = $x['sendmail'];
			$r = $m->Send('sendmail');
		}
	} else if ($x['delivery'] == 5) {
		if (isset($x['proxy_pop3'], $x['proxy_smtp'], $x['proxy_user'], $x['proxy_pass'], $x['port_pop3'], $x['port_smtp'], $x['proxy_timeout'], $x['proxy_name'])) {
			if ($p = POP3::Connect($x['proxy_pop3'], $x['proxy_user'], $x['proxy_pass'], $x['port_pop3'], null, $x['proxy_timeout'])) {
				if ($o = $m->Connect($x['proxy_smtp'], $x['port_smtp'], null, null, null, $x['proxy_timeout'], $x['proxy_name'])) {
					$r = $m->Send($o);
					$m->Disconnect($o);
				}
				POP3::Disconnect($p);
			}
		}
	}
	// 2nd
	if (!$r && $x['delivery'] != 2 && $x['mx_zone'] == 2) {
		if (isset($x['mx_timeout'], $x['mx_name'], $x['mx_bindto'])) {
			if (intval($x['mx_timeout']) > 0) $m->Tout = intval($x['mx_timeout']);
			if ($x['mx_name'] != '') $m->Name($x['mx_name']);
			if ($x['mx_bindto'] != '') $m->Context(array('socket' => array('bindto' => $x['mx_bindto'])));
			$r = $m->Send('client');
		}
	}
	return $r;
}

}

?>