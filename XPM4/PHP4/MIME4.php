<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                                                                         *
 *  XPertMailer is a PHP Mail Class that can send and read messages in MIME format.        *
 *  This file is part of the XPertMailer package (http://xpertmailer.sourceforge.net/)     *
 *  Copyright (C) 2007 Tanase Laurentiu Iulian                                             *
 *                                                                                         *
 *  This library is free software; you can redistribute it and/or modify it under the      *
 *  terms of the GNU Lesser General Public License as published by the Free Software       *
 *  Foundation; either version 2.1 of the License, or (at your option) any later version.  *
 *                                                                                         *
 *  This library is distributed in the hope that it will be useful, but WITHOUT ANY        *
 *  WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A        *
 *  PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.        *
 *                                                                                         *
 *  You should have received a copy of the GNU Lesser General Public License along with    *
 *  this library; if not, write to the Free Software Foundation, Inc., 51 Franklin Street, *
 *  Fifth Floor, Boston, MA 02110-1301, USA                                                *
 *                                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if (!class_exists('FUNC4')) require_once 'FUNC4.php';

class MIME4 {

	var $LE = "\r\n";
	var $HLEN = 52;
	var $MLEN = 73;

	var $HCHARSET = 'utf-8';
	var $MCHARSET = 'us-ascii';

	var $HENCDEF = 'quoted-printable';
	var $MENCDEF = 'quoted-printable';

	var $hencarr = array('quoted-printable' => '', 'base64' => '');
	var $mencarr = array('7bit' => '', '8bit' => '', 'quoted-printable' => '', 'base64' => '', 'binary' => '');

	var $qpkeys = array(
			"\x00","\x01","\x02","\x03","\x04","\x05","\x06","\x07",
			"\x08","\x09","\x0A","\x0B","\x0C","\x0D","\x0E","\x0F",
			"\x10","\x11","\x12","\x13","\x14","\x15","\x16","\x17",
			"\x18","\x19","\x1A","\x1B","\x1C","\x1D","\x1E","\x1F",
			"\x7F","\x80","\x81","\x82","\x83","\x84","\x85","\x86",
			"\x87","\x88","\x89","\x8A","\x8B","\x8C","\x8D","\x8E",
			"\x8F","\x90","\x91","\x92","\x93","\x94","\x95","\x96",
			"\x97","\x98","\x99","\x9A","\x9B","\x9C","\x9D","\x9E",
			"\x9F","\xA0","\xA1","\xA2","\xA3","\xA4","\xA5","\xA6",
			"\xA7","\xA8","\xA9","\xAA","\xAB","\xAC","\xAD","\xAE",
			"\xAF","\xB0","\xB1","\xB2","\xB3","\xB4","\xB5","\xB6",
			"\xB7","\xB8","\xB9","\xBA","\xBB","\xBC","\xBD","\xBE",
			"\xBF","\xC0","\xC1","\xC2","\xC3","\xC4","\xC5","\xC6",
			"\xC7","\xC8","\xC9","\xCA","\xCB","\xCC","\xCD","\xCE",
			"\xCF","\xD0","\xD1","\xD2","\xD3","\xD4","\xD5","\xD6",
			"\xD7","\xD8","\xD9","\xDA","\xDB","\xDC","\xDD","\xDE",
			"\xDF","\xE0","\xE1","\xE2","\xE3","\xE4","\xE5","\xE6",
			"\xE7","\xE8","\xE9","\xEA","\xEB","\xEC","\xED","\xEE",
			"\xEF","\xF0","\xF1","\xF2","\xF3","\xF4","\xF5","\xF6",
			"\xF7","\xF8","\xF9","\xFA","\xFB","\xFC","\xFD","\xFE",
			"\xFF");

	var $qpvrep = array(
			"=00","=01","=02","=03","=04","=05","=06","=07",
			"=08","=09","=0A","=0B","=0C","=0D","=0E","=0F",
			"=10","=11","=12","=13","=14","=15","=16","=17",
			"=18","=19","=1A","=1B","=1C","=1D","=1E","=1F",
			"=7F","=80","=81","=82","=83","=84","=85","=86",
			"=87","=88","=89","=8A","=8B","=8C","=8D","=8E",
			"=8F","=90","=91","=92","=93","=94","=95","=96",
			"=97","=98","=99","=9A","=9B","=9C","=9D","=9E",
			"=9F","=A0","=A1","=A2","=A3","=A4","=A5","=A6",
			"=A7","=A8","=A9","=AA","=AB","=AC","=AD","=AE",
			"=AF","=B0","=B1","=B2","=B3","=B4","=B5","=B6",
			"=B7","=B8","=B9","=BA","=BB","=BC","=BD","=BE",
			"=BF","=C0","=C1","=C2","=C3","=C4","=C5","=C6",
			"=C7","=C8","=C9","=CA","=CB","=CC","=CD","=CE",
			"=CF","=D0","=D1","=D2","=D3","=D4","=D5","=D6",
			"=D7","=D8","=D9","=DA","=DB","=DC","=DD","=DE",
			"=DF","=E0","=E1","=E2","=E3","=E4","=E5","=E6",
			"=E7","=E8","=E9","=EA","=EB","=EC","=ED","=EE",
			"=EF","=F0","=F1","=F2","=F3","=F4","=F5","=F6",
			"=F7","=F8","=F9","=FA","=FB","=FC","=FD","=FE",
			"=FF");

	function unique($add = null) {
		return md5(FUNC4::microtime_float().$add);
	}

	function is_printable($str = null, $debug = null) {
		if (!FUNC4::is_debug($debug)) $debug = debug_backtrace();
		if (!is_string($str)) FUNC4::trace($debug, 'invalid argument type');
		else {
			$_mime = new MIME4;
			$contain = implode('', $_mime->qpkeys);
			return (strcspn($str, $contain) == strlen($str));
		}
	}

	function qp_encode($str = null, $len = null, $end = null, $debug = null) {
		if (!FUNC4::is_debug($debug)) $debug = debug_backtrace();
		$_mime = new MIME4;
		$err = array();
		if (!is_string($str)) $err[] = 'invalid argument type';
		if ($len == null) $len = $_mime->MLEN;
		else if (!(is_int($len) && $len > 1)) $err[] = 'invalid line length value';
		if ($end == null) $end = $_mime->LE;
		else if (!is_string($end)) $err[] = 'invalid line end value';
		if (count($err) > 0) FUNC4::trace($debug, implode(', ', $err));
		else {
			if ($str == '') return $str;
			else {
				$out = array();
				foreach (explode($end, $str) as $line) {
					if ($line == '') $out[] = '';
					else {
						$line = str_replace('=', '=3D', $line);
						$line = str_replace($_mime->qpkeys, $_mime->qpvrep, $line);
						preg_match_all('/.{1,'.$len.'}([^=]{0,2})?/', $line, $match);
						$mcnt = count($match[0]);
						for ($i = 0; $i < $mcnt; $i++) {
							$line = (substr($match[0][$i], -1) == ' ') ? substr($match[0][$i], 0, -1).'=20' : $match[0][$i];
							if (($i+1) < $mcnt) $line .= '=';
							$out[] = $line;
						}
					}
				}
				return implode($end, $out);
			}
		}
	}

	function encode_header($str = null, $charset = null, $encoding = null, $len = null, $end = null, $debug = null) {
		if (!FUNC4::is_debug($debug)) $debug = debug_backtrace();
		$_mime = new MIME4;
		$err = array();
		if (!is_string($str)) $err[] = 'invalid argument type';
		if ($charset == null) $charset = $_mime->HCHARSET;
		else if (!is_string($charset)) $err[] = 'invalid charset type';
		else if (!(strlen($charset) >= 2 && FUNC4::is_alpha($charset, true, '-'))) $err[] = 'invalid charset value';
		if ($encoding == null) $encoding = $_mime->HENCDEF;
		else if (!is_string($encoding)) $err[] = 'invalid encoding type';
		else {
			$encoding = strtolower(FUNC4::str_clear($encoding));
			if (!isset($_mime->hencarr[$encoding])) $err[] = 'invalid encoding value';
		}
		if ($len == null) $len = $_mime->HLEN;
		else if (!(is_int($len) && $len > 1)) $err[] = 'invalid line length value';
		if ($end == null) $end = $_mime->LE;
		else if (!is_string($end)) $err[] = 'invalid line end value';
		if (count($err) > 0) FUNC4::trace($debug, implode(', ', $err));
		else {
			if ($str == '') return $str;
			else {
				$enc = false;
				$dif = $len - strlen('=?'.$charset.'?X??=');
				if ($encoding == 'quoted-printable') {
					if (!MIME4::is_printable($str)) {
						$new = (($dif-4) > 2) ? ($dif-4) : $len;
						$enc = MIME4::qp_encode($str, $new, $end);
						$enc = str_replace(array('?', ' ', '='.$end), array('=3F', '_', $end), $enc);
					}
				} else if ($encoding == 'base64') {
					$new = ($dif > 3) ? $dif : $len;
					if ($new > 3) {
						for ($i = $new; $i > 2; $i--) {
							$crt = '';
							for ($j = 0; $j <= $i; $j++) $crt .= 'x';
							if (strlen(base64_encode($crt)) <= $new) {
								$new = $i;
								break;
							}
						}
					}
					$cnk = rtrim(chunk_split($str, $new, $end));
					$imp = array();
					foreach (explode($end, $cnk) as $line) if ($line != '') $imp[] = base64_encode($line);
					$enc = implode($end, $imp);
				}
				$res = array();
				if ($enc) {
					$chr = ($encoding == 'base64') ? 'B' : 'Q';
					foreach (explode($end, $enc) as $val) if ($val != '') $res[] = '=?'.$charset.'?'.$chr.'?'.$val.'?=';
				} else {
					$cnk = rtrim(chunk_split($str, $len, $end));
					foreach (explode($end, $cnk) as $val) if ($val != '') $res[] = $val;
				}
				return implode($end."\t", $res);
			}
		}
	}

	function decode_header($str = null, $debug = null) {
		if (!FUNC4::is_debug($debug)) $debug = debug_backtrace();
		if (!is_string($str)) FUNC4::trace($debug, 'invalid argument type');
		else {
			$_mime = new MIME4;
			$str = trim(FUNC4::str_clear($str));
			$arr = array();
			if ($str == '') $arr[] = array('charset' => $_mime->HCHARSET, 'value' => '');
			else {
				foreach (explode('?=', $str) as $str1) {
					foreach (explode('=?', $str1, 2) as $str2) {
						$def = false;
						if (count($exp = explode('?B?', $str2)) == 2) {
							if (strlen($exp[0]) >= 2 && FUNC4::is_alpha($exp[0], true, '-') && trim($exp[1]) != '') $def = array('charset' => $exp[0], 'value' => base64_decode(trim($exp[1])));
						} else if (count($exp = explode('?b?', $str2)) == 2) {
							if (strlen($exp[0]) >= 2 && FUNC4::is_alpha($exp[0], true, '-') && trim($exp[1]) != '') $def = array('charset' => $exp[0], 'value' => base64_decode(trim($exp[1])));
						} else if (count($exp = explode('?Q?', $str2)) == 2) {
							if (strlen($exp[0]) >= 2 && FUNC4::is_alpha($exp[0], true, '-') && $exp[1] != '') $def = array('charset' => $exp[0], 'value' => quoted_printable_decode(str_replace('_', ' ', $exp[1])));
						} else if (count($exp = explode('?q?', $str2)) == 2) {
							if (strlen($exp[0]) >= 2 && FUNC4::is_alpha($exp[0], true, '-') && $exp[1] != '') $def = array('charset' => $exp[0], 'value' => quoted_printable_decode(str_replace('_', ' ', $exp[1])));
						}
						if ($def) {
							if ($def['value'] != '') $arr[] = array('charset' => $def['charset'], 'value' => $def['value']);
						} else {
							if ($str2 != '') $arr[] = array('charset' => $_mime->HCHARSET, 'value' => $str2);
						}
					}
				}
			}
			return $arr;
		}
	}

	function decode_content($str = null, $encoding = null, $debug = null) {
		if (!FUNC4::is_debug($debug)) $debug = debug_backtrace();
		$err = array();
		if (!is_string($str)) $err[] = 'invalid content type';
		if ($encoding == null) $encoding = '7bit';
		else if (!is_string($encoding)) $err[] = 'invalid encoding type';
		else {
			$_mime = new MIME4;
			$encoding = strtolower($encoding);
			if (!isset($_mime->mencarr[$encoding])) $err[] = 'invalid encoding value';
		}
		if (count($err) > 0) FUNC4::trace($debug, implode(', ', $err));
		else {
			if ($encoding == 'base64') {
				$str = trim(FUNC4::str_clear($str));
				return base64_decode($str);
			} else if ($encoding == 'quoted-printable') {
				return quoted_printable_decode($str);
			} else return $str;
		}
	}

	function message($content = null, $type = null, $name = null, $charset = null, $encoding = null, $disposition = null, $id = null, $len = null, $end = null, $debug = null) {
		if (!FUNC4::is_debug($debug)) $debug = debug_backtrace();
		$_mime = new MIME4;
		$err = array();
		if (!(is_string($content) && $content != '')) $err[] = 'invalid content type';
		if ($type == null) $type = 'application/octet-stream';
		else if (is_string($type)) {
			$type = trim(FUNC4::str_clear($type));
			if (strlen($type) < 4) $err[] = 'invalid type value';
		} else $err[] = 'invalid type';
		if (is_string($name)) {
			$name = trim(FUNC4::str_clear($name));
			if ($name == '') $err[] = 'invalid name value';
		} else if ($name != null) $err[] = 'invalid name type';
		if ($charset == null) $charset = $_mime->MCHARSET;
		else if (!is_string($charset)) $err[] = 'invalid charset type';
		else if (!(strlen($charset) >= 2 && FUNC4::is_alpha($charset, true, '-'))) $err[] = 'invalid charset value';
		if ($encoding == null) $encoding = $_mime->MENCDEF;
		else if (!is_string($encoding)) $err[] = 'invalid encoding type';
		else {
			$encoding = strtolower(FUNC4::str_clear($encoding));
			if (!isset($_mime->mencarr[$encoding])) $err[] = 'invalid encoding value';
		}
		if ($disposition == null) $disposition = 'inline';
		else if (is_string($disposition)) {
			$disposition = strtolower(FUNC4::str_clear($disposition));
			if (!($disposition == 'inline' || $disposition == 'attachment')) $err[] = 'invalid disposition value';
		} else $err[] = 'invalid disposition type';
		if (is_string($id)) {
			$id = FUNC4::str_clear($id, array(' '));
			if ($id == '') $err[] = 'invalid id value';
		} else if ($id != null) $err[] = 'invalid id type';
		if ($len == null) $len = $_mime->MLEN;
		else if (!(is_int($len) && $len > 1)) $err[] = 'invalid line length value';
		if ($end == null) $end = $_mime->LE;
		else if (!is_string($end)) $err[] = 'invalid line end value';
		if (count($err) > 0) FUNC4::trace($debug, implode(', ', $err));
		else {
			$header = ''.
				'Content-Type: '.$type.';'.$end."\t".'charset="'.$charset.'"'.
				(($name == null) ? '' : ';'.$end."\t".'name="'.$name.'"').$end.
				'Content-Transfer-Encoding: '.$encoding.$end.
				'Content-Disposition: '.$disposition.
				(($name == null) ? '' : ';'.$end."\t".'filename="'.$name.'"').
				(($id == null) ? '' : $end.'Content-ID: <'.$id.'>');
			if ($encoding == '7bit' || $encoding == '8bit') $content = wordwrap(MIME4::fix_eol($content), $len, $end, true);
			else if ($encoding == 'base64') $content = rtrim(chunk_split(base64_encode($content), $len, $end));
			else if ($encoding == 'quoted-printable') $content = MIME4::qp_encode(MIME4::fix_eol($content), $len, $end);
			return array('header' => $header, 'content' => $content);
		}
	}

	function compose($text = null, $html = null, $attach = null, $uniq = null, $end = null, $debug = null) {
		if (!FUNC4::is_debug($debug)) $debug = debug_backtrace();
		$_mime = new MIME4;
		$err = array();
		if ($text == null && $html == null) $err[] = 'message is not set';
		else {
			if ($text != null) {
				if (!(is_array($text) && isset($text['header'], $text['content']) && is_string($text['header']) && is_string($text['content']) && MIME4::isset_header($text['header'], 'content-type', 'text/plain', $debug))) $err[] = 'invalid text message type';
			}
			if ($html != null) {
				if (!(is_array($html) && isset($html['header'], $html['content']) && is_string($html['header']) && is_string($html['content']) && MIME4::isset_header($html['header'], 'content-type', 'text/html', $debug))) $err[] = 'invalid html message type';
			}
		}
		if ($attach != null) {
			if (is_array($attach) && count($attach) > 0) {
				foreach ($attach as $arr) {
					if (!(is_array($arr) && isset($arr['header'], $arr['content']) && is_string($arr['header']) && is_string($arr['content']) && (MIME4::isset_header($arr['header'], 'content-disposition', 'inline', $debug) || MIME4::isset_header($arr['header'], 'content-disposition', 'attachment', $debug)))) {
						$err[] = 'invalid attachment type';
						break;
					}
				}
			} else $err[] = 'invalid attachment format';
		}
		if ($end == null) $end = $_mime->LE;
		else if (!is_string($end)) $err[] = 'invalid line end value';
		if (count($err) > 0) FUNC4::trace($debug, implode(', ', $err));
		else {
			$multipart = false;
			if ($text && $html) $multipart = true;
			if ($attach) $multipart = true;
			$header = $body = array();
			$header[] = 'Date: '.date('r');
			$header[] = base64_decode('WC1NYWlsZXI6IFhQTTQgdi4wLjMgPCB3d3cueHBlcnRtYWlsZXIuY29tID4=');
			if ($multipart) {
				$uniq = ($uniq == null) ? 0 : intval($uniq);
				$boundary1 = '=_1.'.MIME4::unique($uniq++);
				$boundary2 = '=_2.'.MIME4::unique($uniq++);
				$boundary3 = '=_3.'.MIME4::unique($uniq++);
				$disp['inline'] = $disp['attachment'] = false;
				if ($attach != null) {
					foreach ($attach as $darr) {
						if (MIME4::isset_header($darr['header'], 'content-disposition', 'inline', $debug)) $disp['inline'] = true;
						else if (MIME4::isset_header($darr['header'], 'content-disposition', 'attachment', $debug)) $disp['attachment'] = true;
					}
				}
				$hstr = 'Content-Type: multipart/%s;'.$end."\t".'boundary="%s"';
				$bstr = '--%s'.$end.'%s'.$end.$end.'%s';
				$body[] = 'This is a message in MIME Format. If you see this, your mail reader does not support this format.'.$end;
				if ($text && $html) {
						if ($disp['inline'] && $disp['attachment']) {
							$header[] = sprintf($hstr, 'mixed', $boundary1);
							$body[] = '--'.$boundary1;
							$body[] = sprintf($hstr, 'related', $boundary2).$end;
							$body[] = '--'.$boundary2;
							$body[] = sprintf($hstr, 'alternative', $boundary3).$end;
							$body[] = sprintf($bstr, $boundary3, $text['header'], $text['content']);
							$body[] = sprintf($bstr, $boundary3, $html['header'], $html['content']);
							$body[] = '--'.$boundary3.'--';
							foreach ($attach as $desc) if (MIME4::isset_header($desc['header'], 'content-disposition', 'inline', $debug)) $body[] = sprintf($bstr, $boundary2, $desc['header'], $desc['content']);
							$body[] = '--'.$boundary2.'--';
							foreach ($attach as $desc) if (MIME4::isset_header($desc['header'], 'content-disposition', 'attachment', $debug)) $body[] = sprintf($bstr, $boundary1, $desc['header'], $desc['content']);
							$body[] = '--'.$boundary1.'--';
						} else if ($disp['inline']) {
							$header[] = sprintf($hstr, 'related', $boundary1);
							$body[] = '--'.$boundary1;
							$body[] = sprintf($hstr, 'alternative', $boundary2).$end;
							$body[] = sprintf($bstr, $boundary2, $text['header'], $text['content']);
							$body[] = sprintf($bstr, $boundary2, $html['header'], $html['content']);
							$body[] = '--'.$boundary2.'--';
							foreach ($attach as $desc) $body[] = sprintf($bstr, $boundary1, $desc['header'], $desc['content']);
							$body[] = '--'.$boundary1.'--';
						} else if ($disp['attachment']) {
							$header[] = sprintf($hstr, 'mixed', $boundary1);
							$body[] = '--'.$boundary1;
							$body[] = sprintf($hstr, 'alternative', $boundary2).$end;
							$body[] = sprintf($bstr, $boundary2, $text['header'], $text['content']);
							$body[] = sprintf($bstr, $boundary2, $html['header'], $html['content']);
							$body[] = '--'.$boundary2.'--';
							foreach ($attach as $desc) $body[] = sprintf($bstr, $boundary1, $desc['header'], $desc['content']);
							$body[] = '--'.$boundary1.'--';
						} else {
							$header[] = sprintf($hstr, 'alternative', $boundary1);
							$body[] = sprintf($bstr, $boundary1, $text['header'], $text['content']);
							$body[] = sprintf($bstr, $boundary1, $html['header'], $html['content']);
							$body[] = '--'.$boundary1.'--';
						}
				} else if ($text) {
					$header[] = sprintf($hstr, 'mixed', $boundary1);
					$body[] = sprintf($bstr, $boundary1, $text['header'], $text['content']);
					foreach ($attach as $desc) $body[] = sprintf($bstr, $boundary1, $desc['header'], $desc['content']);
					$body[] = '--'.$boundary1.'--';
				} else if ($html) {
					if ($disp['inline'] && $disp['attachment']) {
						$header[] = sprintf($hstr, 'mixed', $boundary1);
						$body[] = '--'.$boundary1;
						$body[] = sprintf($hstr, 'related', $boundary2).$end;
						$body[] = sprintf($bstr, $boundary2, $html['header'], $html['content']);
						foreach ($attach as $desc) if (MIME4::isset_header($desc['header'], 'content-disposition', 'inline', $debug)) $body[] = sprintf($bstr, $boundary2, $desc['header'], $desc['content']);
						$body[] = '--'.$boundary2.'--';
						foreach ($attach as $desc) if (MIME4::isset_header($desc['header'], 'content-disposition', 'attachment', $debug)) $body[] = sprintf($bstr, $boundary1, $desc['header'], $desc['content']);
						$body[] = '--'.$boundary1.'--';
					} else if ($disp['inline']) {
						$header[] = sprintf($hstr, 'related', $boundary1);
						$body[] = sprintf($bstr, $boundary1, $html['header'], $html['content']);
						foreach ($attach as $desc) $body[] = sprintf($bstr, $boundary1, $desc['header'], $desc['content']);
						$body[] = '--'.$boundary1.'--';
					} else if ($disp['attachment']) {
						$header[] = sprintf($hstr, 'mixed', $boundary1);
						$body[] = sprintf($bstr, $boundary1, $html['header'], $html['content']);
						foreach ($attach as $desc) $body[] = sprintf($bstr, $boundary1, $desc['header'], $desc['content']);
						$body[] = '--'.$boundary1.'--';
					}
				}
			} else {
				if ($text) {
					$header[] = $text['header'];
					$body[] = $text['content'];
				} else if ($html) {
					$header[] = $html['header'];
					$body[] = $html['content'];
				}
			}
			$header[] = 'MIME-Version: 1.0';
			return array('header' => implode($end, $header), 'content' => implode($end, $body));
		}
	}

	function isset_header($str = null, $name = null, $value = null, $debug = null) {
		if (!FUNC4::is_debug($debug)) $debug = debug_backtrace();
		$err = array();
		if (!(is_string($str) && $str != '')) $err[] = 'invalid header type';
		if (!(is_string($name) && $name != '')) $err[] = 'invalid name type';
		if ($value != null && !is_string($value)) $err[] = 'invalid value type';
		if (count($err) > 0) FUNC4::trace($debug, implode(', ', $err));
		else {
			$ret = false;
			if ($split = MIME4::split_header($str, $debug)) {
				foreach ($split as $tarr) {
					foreach ($tarr as $hname => $hvalue) {
						if (strtolower($hname) == strtolower($name)) {
							if ($value != null) $ret = (strtolower($hvalue) == strtolower($value));
							else $ret = $hvalue;
							if ($ret) {
								break 2;
								break;
							}
						}
					}
				}
			}
		}
		return $ret;
	}

	function split_header($str = null, $debug = null) {
		if (!FUNC4::is_debug($debug)) $debug = debug_backtrace();
		if (!(is_string($str) && $str != '')) FUNC4::trace($debug, 'invalid header value');
		else {
			$str = str_replace(array(";\r\n\t", "; \r\n\t", ";\r\n ", "; \r\n "), '; ', $str);
			$str = str_replace(array(";\n\t", "; \n\t", ";\n ", "; \n "), '; ', $str);
			$str = str_replace(array("\r\n\t", "\r\n "), '', $str);
			$str = str_replace(array("\n\t", "\n "), '', $str);
			$arr = array();
			foreach (explode("\n", $str) as $line) {
				$line = trim(FUNC4::str_clear($line));
				if (trim($line) != '') {
					if (count($exp1 = explode(':', $line, 2)) == 2) {
						$name = trim($exp1[0]);
						$val1 = trim($exp1[1]);
						if (strlen($name) > 1 && $val1 != '') {
							$name = ucfirst($name);
							$hadd = array();
							if (substr(strtolower($name), 0, 8) == 'content-') {
								$exp2 = explode('; ', $val1);
								$cnt2 = count($exp2);
								if ($cnt2 > 1) {
									for ($i = 1; $i < $cnt2; $i++) {
										if (count($exp3 = explode('=', $exp2[$i], 2)) == 2) {
											$hset = trim($exp3[0]);
											$hval = trim($exp3[1], ' "');
											if ($hset != '' && $hval != '') $hadd[strtolower($hset)] = $hval;
										}
									}
								}
							}
							$arr[] = (count($hadd) > 0) ? array($name => trim($exp2[0]), '+' => $hadd) : array($name => $val1);
						}
					}
				}
			}
			if (count($arr) > 0) return $arr;
			else FUNC4::trace($debug, 'invalid header value');
		}
	}

	function fix_eol($str = null, $debug = null) {
		if (!FUNC4::is_debug($debug)) $debug = debug_backtrace();
		if (!(is_string($str) && $str != '')) FUNC4::trace($debug, 'invalid content value');
		else {
			$_mime = new MIME4;
			$str = str_replace("\r\n", "\n", $str);
			$str = str_replace("\r", "\n", $str);
			if ($_mime->LE != "\n") $str = str_replace("\n", $_mime->LE, $str);
			return $str;
		}
	}

}

?>