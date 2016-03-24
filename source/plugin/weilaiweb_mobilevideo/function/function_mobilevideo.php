<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
function transition_video($url) {
	global $_G;
	$conf = $_G['cache']['plugin']['weilaiweb_mobilevideo'];
	$height = $conf['height'];
	$width = $conf['width']?$conf['width']:'100%';
	$ifurl = strtolower($url);
	if (in_array('youku', unserialize($conf['types'])) && strpos($ifurl, 'v.youku.com/v_show/') !== FALSE) {
		if (preg_match("/http:\/\/v.youku.com\/v_show\/id_([^\/]+)(.html?)/i", $url, $arr)) {
			$src = 'http://player.youku.com/embed/'.$arr[1];
			return '<iframe width="'.$width.'" height="'.$height.'" src="'.$src.'" frameborder="0" allowfullscreen></iframe>';
		}
	}
	if (in_array('tudou', unserialize($conf['types'])) && strpos($ifurl, 'www.tudou.com/programs/view/') !== FALSE) {
		if (preg_match("/http:\/\/www.tudou.com\/programs\/view\/([^\/]+)(\/?)/i", $url, $arr)) {
			$src = 'http://www.tudou.com/programs/view/html5embed.action?type=0&code='.$arr[1];
			return '<iframe width="'.$width.'" height="'.$height.'" src="'.$src.'" allowtransparency="true" allowfullscreen="true" allowfullscreenInteractive="true" scrolling="no" border="0" frameborder="0"></iframe>';
		}
	}
	if (in_array('youtube', unserialize($conf['types'])) && strpos($ifurl, 'www.youtube.com/watch') !== FALSE) {
		if (preg_match("/https:\/\/www.youtube.com\/watch\?v=([^\/]+)/i", $url, $arr)) {
			$src = 'https://www.youtube.com/embed/'.$arr[1];
			return '<iframe width="'.$width.'" height="'.$height.'" src="'.$src.'" allowfullscreen></iframe>';
		}
	}
}
?>