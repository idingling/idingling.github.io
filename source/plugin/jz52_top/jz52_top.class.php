<?php
/** 
 *	极致站长资源论坛 www.jz52.com 原创插件
 *	转载请保留版权
 *	极致站长资源QQ群：238148290
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

loadcache('plugin');



class plugin_jz52_top {


    function plugin_jz52_top() {
		global $_G;
		$this->jz52_kf = $_G['cache']['plugin']['jz52_top']['jz52_kf'];
		$this->jz52_kfurl = $_G['cache']['plugin']['jz52_top']['jz52_kfurl'];
        $this->jz52_fenxiang = $_G['cache']['plugin']['jz52_top']['jz52_fenxiang'];
        $this->jz52_fenxidm = $_G['cache']['plugin']['jz52_top']['jz52_fenxidm'];
		$this->jz52_qr = $_G['cache']['plugin']['jz52_top']['jz52_qr'];
		$this->jz52_qrimg = $_G['cache']['plugin']['jz52_top']['jz52_qrimg'];
		$this->jz52_gzwx = $_G['cache']['plugin']['jz52_top']['jz52_gzwx'];
		$this->jz52_gzwxdm = $_G['cache']['plugin']['jz52_top']['jz52_gzwxdm'];
		$this->jz52_thew = $_G['cache']['plugin']['jz52_top']['jz52_thew'];
		$this->jz52_mw = $_G['cache']['plugin']['jz52_top']['jz52_mw'];
		$this->jz52_ln = $_G['cache']['plugin']['jz52_top']['jz52_ln'];
		$this->jz52_sctz = $_G['cache']['plugin']['jz52_top']['jz52_sctz'];
		$this->jz52_scbk = $_G['cache']['plugin']['jz52_top']['jz52_scbk'];
		$this->jz52_qqqan = $_G['cache']['plugin']['jz52_top']['jz52_qqqan'];
		$this->jz52_zdy = $_G['cache']['plugin']['jz52_top']['jz52_zdy'];
		$this->jz52_zdybt = $_G['cache']['plugin']['jz52_top']['jz52_zdybt'];		
		$this->jz52_grzx = $_G['cache']['plugin']['jz52_top']['jz52_grzx'];
		$this->jz52_temp = $_G['cache']['plugin']['jz52_top']['jz52_temp'];
		$this->jz52_grzxbjs = $_G['cache']['plugin']['jz52_top']['jz52_grzxbjs'];
		$this->jz52_grzxbjthe = $_G['cache']['plugin']['jz52_top']['jz52_grzxbjthe'];
		$this->jz52_uc = $_G['cache']['plugin']['jz52_top']['jz52_uc'];
		$this->jz52_fbztkg = $_G['cache']['plugin']['jz52_top']['jz52_fbztkg'];

	}



	function global_footer(){
	global $_G;
	
	if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0') == true )
{
    // ie 6.0
	
	
    $jz52ie6 = yes;
	
	include template('jz52_top:jz_topboxie6');
		return $return;
 
}
else
{
    //not ie 6.0
    $jz52w = $this->jz52_mw/2+50;
	
	include template('jz52_top:jz_topbox');
		return $return;
}
	
	

	
	}
	
}




?>