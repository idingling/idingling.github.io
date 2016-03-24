<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
loadcache('plugin');
$setting = $_G['cache']['plugin']['k_misign'];
$setting['pextend'] = unserialize($setting['pextend']);

if(submitcheck('pluginsubmit')) {
	//超强帖子聚合
	if(is_file(DISCUZ_ROOT.'./source/plugin/tie/index.inc.php')){
		$data['value']['tie_button']['status'] = intval($_GET['tie_button']['status']);
		$data['value']['tie_button']['width'] = intval($_GET['tie_button']['width']);
	}
	//多彩名片
	if(is_file(DISCUZ_ROOT.'./source/plugin/k_usercard/style.inc.php')){
		$data['value']['k_usercard']['status'] = intval($_GET['k_usercard']['status']);
		$data['value']['k_usercard']['width'] = intval($_GET['k_usercard']['width']);
	}
	//矿工游戏
	if(is_file(DISCUZ_ROOT.'./source/plugin/miner/miner.inc.php')){
		$data['value']['miner']['status'] = intval($_GET['miner']['status']);
		$data['value']['miner']['width'] = intval($_GET['miner']['width']);
	}
	$data['value'] = serialize($data['value']);
	C::t("common_pluginvar")->update_by_variable($do, 'pextend', $data);
	updatecache(array('plugin'));
	cpmsg(lang('plugin/k_misign', 'success'), "action=plugins&operation=config&do=".$do."&identifier=k_misign&pmod=cp_pluginextend", 'succeed');
}else{
	
	showformheader('plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_pluginextend', 'enctype');
	showtableheader();
	//超强帖子聚合
	if(is_file(DISCUZ_ROOT.'./source/plugin/tie/index.inc.php')){
		showtableheader(lang('plugin/tie', 'plugintitle'));
		showsetting(lang('plugin/k_misign', 'button_status'), 'tie_button[status]', (($setting['pextend']['tie_button']['status'] == 1) ? 1 : 0), 'radio');
		showsetting(lang('plugin/k_misign', 'button_width'), 'tie_button[width]', $setting['pextend']['tie_button']['width'], 'text');
		showtablefooter();
	}
	//多彩名片
	if(is_file(DISCUZ_ROOT.'./source/plugin/k_usercard/style.inc.php')){
		showtableheader(lang('plugin/k_usercard', 'k_usercard'));
		showsetting(lang('plugin/k_misign', 'button_status'), 'k_usercard[status]', (($setting['pextend']['k_usercard']['status'] == 1) ? 1 : 0), 'radio');
		showsetting(lang('plugin/k_misign', 'button_width'), 'k_usercard[width]', $setting['pextend']['k_usercard']['width'], 'text');
		showtablefooter();
	}
	//矿工游戏
	if(is_file(DISCUZ_ROOT.'./source/plugin/miner/miner.inc.php')){
		showtableheader(lang('plugin/miner', 'plugintitle'));
		showsetting(lang('plugin/k_misign', 'button_status'), 'miner[status]', (($setting['pextend']['miner']['status'] == 1) ? 1 : 0), 'radio');
		showsetting(lang('plugin/k_misign', 'button_width'), 'miner[width]', $setting['pextend']['miner']['width'], 'text');
		showtablefooter();
	}
	showsubmit('pluginsubmit', 'submit');
	showtablefooter();
	showformfooter();
}
?>