<?php
/**
 *	[手机视频播放(weilaiweb_mobilevideo.{modulename})] (C)2015-2099 Powered by WeilaiWEB.
 *	Version: 1.0
 *	Date: 2015-8-29 17:47
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class mobileplugin_weilaiweb_mobilevideo {
	//TODO - Insert your code here
	function discuzcode($arr) {
		require_once libfile('function/mobilevideo','plugin/weilaiweb_mobilevideo');
		global $_G;
		$conf = $_G['cache']['plugin']['weilaiweb_mobilevideo'];
		if($arr['caller']=='discuzcode' && in_array($_G['fid'], unserialize($conf['forums']))){
			if (strpos(strtolower($_G['discuzcodemessage']), '[/media]') !== false) {
				$_G['discuzcodemessage'] = preg_replace("/\[media=([\w,]+)\]\s*([^\[\<\r\n]+?)\s*\[\/media\]/ies", "transition_video('\\2')", $_G['discuzcodemessage']);
			}
		}
	}
}
?>