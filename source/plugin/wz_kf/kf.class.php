<?php

/**
 *	[在线客户(wz_kf.{modulename})] (C)2011-2013 Powered by 王者社区.
 *	Version: 1.0
 *	Date: 2013-2-28 10:19
 */


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_wz_kf {
	
	function global_footer() {
	    global $_G;
	    $return = '';
        $wz_kf = $_G['cache']['plugin']['wz_kf'];

		if($wz_kf[off]) include template('wz_kf:kf');
		return $return;
	}
}

class plugin_wz_kf_forum extends plugin_wz_kf{
}




?>
