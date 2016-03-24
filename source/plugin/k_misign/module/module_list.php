<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$op = addslashes($_GET['op']);

$page = max(1, intval($_GET['page']));
$perpage = $setting['listppp'] ? intval($setting['listppp']) : 10;
$start_limit = ($page - 1) * $perpage;
if($op == 'month'){
	$num = C::t("#k_misign#plugin_k_misign")->getcount('mdays', '0', 'notin');
	$multipage = multi($num, $perpage, $page, "plugin.php?id=k_misign:sign&operation=list&op=".$op);
} elseif($op == ''){
	$num = C::t("#k_misign#plugin_k_misign")->getcount('time', $tdtime, '>=');
	$multipage = multi($num, $perpage, $page, "plugin.php?id=k_misign:sign&operation=list&op=".$op);
} else {
	$num = C::t("#k_misign#plugin_k_misign")->getcount();
	$multipage = multi($num, $perpage, $page, "plugin.php?id=k_misign:sign&operation=list&op=".$op);
}
	$list_turn = 'DESC';
if($op == 'zong'){
	$list_type = 'q.days';
} elseif ($op == 'month') {
	$list_type = 'q.mdays';
} elseif ($op == 'rewardlist') {
	$list_type = 'q.reward';
} elseif ($op == '') {
	$list_type = 'q.time';
	$list_tdtime = $tdtime;
	if($setting['qddesc']) {
		$list_turn = 'DESC';
	} else {
		$list_turn = 'ASC';
	}
}
$mrcs = array();
foreach(C::t("#k_misign#plugin_k_misign")->getsignlist($list_type, $list_turn, $start_limit, $perpage, $list_tdtime) as $mrc) {
	if(defined('IN_MOBILE')){
		$mrc['time'] = dgmdate($mrc['time'], 'm-d H:i');
	}else{
		$mrc['time'] = dgmdate($mrc['time'], 'Y-m-d H:i');
	}
	if ($mrc['days'] >= '1500') {
		$mrc['level'] = "[LV.Master]{$lvmastername}";
	} elseif ($mrc['days'] >= '750') {
		$mrc['level'] = "[LV.10]{$lv10name}";
	} elseif ($mrc['days'] >= '365') {
		$mrc['level'] = "[LV.9]{$lv9name}";
	} elseif ($mrc['days'] >= '240') {
		$mrc['level'] = "[LV.8]{$lv8name}";
	} elseif ($mrc['days'] >= '120') {
		$mrc['level'] = "[LV.7]{$lv7name}";
	} elseif ($mrc['days'] >= '60') {
		$mrc['level'] = "[LV.6]{$lv6name}";
	} elseif ($mrc['days'] >= '30') {
		$mrc['level'] = "[LV.5]{$lv5name}";
	} elseif ($mrc['days'] >= '15') {
		$mrc['level'] = "[LV.4]{$lv4name}";
	} elseif ($mrc['days'] >= '7') {
		$mrc['level'] = "[LV.3]{$lv3name}";
	} elseif ($mrc['days'] >= '3') {
		$mrc['level'] = "[LV.2]{$lv2name}";
	} elseif ($mrc['days'] >= '1') {
		$mrc['level'] = "[LV.1]{$lv1name}";
	}
	$mrcs[] = $mrc;
}
if(defined('IN_MOBILE')){
	include template('diy:k_misign_list', '', 'source/plugin/k_misign/template/'.($setting['styles']['mobile'] ? $setting['styles']['mobile'] : 'mobile_default'));
}else{
	include template('diy:k_misign_list', '', 'source/plugin/k_misign/template/'.($setting['styles']['pc'] ? $setting['styles']['pc'] : 'default'));
}
?>