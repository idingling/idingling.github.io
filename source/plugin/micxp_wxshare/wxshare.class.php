<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_micxp_wxshare{
	
	
}
class plugin_micxp_wxshare_forum extends plugin_micxp_wxshare{	
	function viewthread_useraction(){
		return '<a class="followp" href="" onclick=showWindow("micxp_wxshare_qrcode","plugin.php?id=micxp_wxshare:index&tid='.getgpc('tid').'","get",0); return false; ><i><img src="source/plugin/micxp_wxshare/image/share.png" >'.lang('plugin/micxp_wxshare', 'weixin').'</i></a>';
	}
}