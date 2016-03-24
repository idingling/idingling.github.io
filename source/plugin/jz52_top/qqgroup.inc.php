<?php
/** 
 *	极致站长资源论坛 www.jz52.com 原创插件
 *	转载请保留版权
 *	极致站长资源QQ群：238148290
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(!$_G['uid'] && !$_G['cache']['plugin']['jz52_top']['jz52_openlist']){
	showmessage(lang('plugin/jz52_top', 'jz_qqqnoyk'));
}

$types = array();
$newgroup = '';
$tnum = 0;
$ckey = $tkey = 1;
$qqgroupsm=$_G['cache']['plugin']['jz52_top']['jz52_qqgroupsm'];
$jz52_qqqqrkg=$_G['cache']['plugin']['jz52_top']['jz52_qqqqrkg'];
$qqqqrlo=$_G['cache']['plugin']['jz52_top']['jz52_qqqqrlo'];
$qqgroup = explode("\r\n", $_G['cache']['plugin']['jz52_top']['jz52_qqgrouplist']);


if($qqgroup[0]===0) {
	$qqgroup = $newgroup = '';
} else {
	foreach($qqgroup as $key => $value) {
		$value=explode(':', $value);
		$type = array_shift($value);
		$types[$type] = $type;
		$newgroup[$type][] = $value;
		$qqcount++;
	}
	unset($value);
	$qqgroup = $newgroup;
	$tnum = count($types);
}


if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0') == true )
{
 // ie 6.0
$jz52ie6 = 1;
}

include_once template('jz52_top:jz_qqq');
?>