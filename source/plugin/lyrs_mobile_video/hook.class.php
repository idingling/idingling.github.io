<?php

if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function _parsemedia($url) {
	global $_G;
	$setting = $_G['cache']['plugin']['lyrs_mobile_video'];
	$height = $setting['height'];
	$width = $setting['width'];
	$lowerurl = strtolower($url);
	if (strpos($lowerurl, 'v.youku.com/v_show/') !== FALSE) {
		if (preg_match("/http:\/\/v.youku.com\/v_show\/id_([^\/]+)(.html|)/i", $url, $matches)) {
			$iframe = 'http://player.youku.com/embed/'.$matches[1];
		}
	} elseif (strpos($lowerurl, 'player.youku.com/') !== FALSE) {
		if (preg_match("/http:\/\/player.youku.com\/player.php\/(.+)\/sid\/([^\/]+)\/v.swf/i", $url, $matches)) {
			$iframe = 'http://player.youku.com/embed/'.$matches[2];
		}
	} elseif(strpos($lowerurl, 'http://www.56.com') !== FALSE) {
		if(preg_match("/http:\/\/www.56.com\/(.+)(v_|vid-)([^\/]+).html/i", $url, $matches)) {
			$iframe = 'http://www.56.com/iframe/'.$matches[3];
		}
	} elseif(strpos($lowerurl, '56.com/') !== FALSE) {
		if(preg_match("/http:\/\/player.56.com\/(v_|web2_)([^\/]+).swf/i", $url, $matches)) {
			$iframe = 'http://www.56.com/iframe/'.$matches[2];
		}
	} elseif(strpos($lowerurl, 'qq.com/') !== FALSE) {
		if(preg_match("/http:\/\/static.video.qq.com\/TPout.swf\?vid=([^\/]+)/i", $url, $matches)) {
			$iframe = 'http://v.qq.com/iframe/player.html?vid='.$matches[1].'&tiny=0&auto=0';
		}
	}
	return '<iframe height="'.$height.'" width="'.$width.'" src="'.$iframe.'" frameborder=0 allowfullscreen></iframe>';
}

class mobileplugin_lyrs_mobile_video {

	function discuzcode($param) {
		global $_G;
		if ($param['caller'] == 'discuzcode' && in_array($_G['fid'], unserialize($_G['cache']['plugin']['lyrs_mobile_video']['forum']))) {
			if (strpos(strtolower($_G['discuzcodemessage']), '[/media]') !== false) {
				$_G['discuzcodemessage'] = preg_replace("/\[media=([\w,]+)\]\s*([^\[\<\r\n]+?)\s*\[\/media\]/ies", "_parsemedia('\\2')", $_G['discuzcodemessage']);
			}
		}
	}

}
