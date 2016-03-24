<?php
!defined('IN_DISCUZ') && exit('Access Denied');
$op = in_array($_GET['op'], array('install', 'uninstall')) ? $_GET['op'] : '';
@include_once DISCUZ_ROOT.'./source/plugin/k_misign/language/extend_hook_global_usernav_extra3.'.currentlang().'.php';

if($op == 'install'){
	
}elseif($op == 'uninstall'){
	@unlink(DISCUZ_ROOT.'./source/plugin/k_misign/language/extend_hook_global_usernav_extra3.'.currentlang().'.php');
	@unlink(DISCUZ_ROOT.'./source/plugin/k_misign/extend/extend_hook_global_usernav_extra3.php');
	cpmsg('update_success', "action=plugins&operation=config&do=".$do."&identifier=k_misign&pmod=cp_extend", 'succeed');
}else{
	$setting = $_G['cache']['plugin']['k_misign'];
	$setting['pluginurl'] = $setting['pluginurl'] ? $setting['pluginurl'] : 'plugin.php?id=k_misign:';
	
	$stats = C::t("#k_misign#plugin_k_misignset")->fetch(1);
	$tdtime = gmmktime(0,0,0,dgmdate($_G['timestamp'], 'n',$setting['tos']),dgmdate($_G['timestamp'], 'j',$setting['tos']),dgmdate($_G['timestamp'], 'Y',$setting['tos'])) - $setting['tos']*3600;
	if($_G['uid'])$qiandaodb = C::t("#k_misign#plugin_k_misign")->fetch_by_uid($_G['uid']);
	
	if ($qiandaodb['time'] >= $tdtime){	
		$html = '<a href="'.$setting['pluginurl'].'sign" id="k_misign_topb"><img id="fx_checkin_b" src="source/plugin/k_misign/static/mini2.gif" alt="'.lang('plugin/k_misign', 'tdyq').'" style="position:relative;top:5px;height:18px;"></a>';			
	}else{
		$html = '<i id="k_misign_topb"><a href="'.$setting['pluginurl'].'sign&operation=qiandao&format=global_usernav_extra&formhash='.$_G['formhash'].'" onclick="ajaxget(this.href, \'k_misign_topb\', \'\', \'\', \'\', \'\');return false;"><img id="fx_checkin_b" src="source/plugin/k_misign/static/mini.gif" alt="'.lang('plugin/k_misign', 'djqd').'" style="position:relative;top:5px;height:18px;"></a></i>';
	}
	return $html;
}
?>