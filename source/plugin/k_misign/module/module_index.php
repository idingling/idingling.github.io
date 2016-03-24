<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$todaystar = C::t("#k_misign#plugin_k_misign")->gettodaystar($tdtime);
$todaystarinfo = getuserbyuid($todaystar['uid']);
$todaystar = array_merge($todaystar, $todaystarinfo);

if ($todaystar['days'] >= '1500') {
	$todaystar['level'] = "[LV.Master]{$lvmastername}";
} elseif ($todaystar['days'] >= '750') {
	$todaystar['level'] = "[LV.10]{$lv10name}";
} elseif ($todaystar['days'] >= '365') {
	$todaystar['level'] = "[LV.9]{$lv9name}";
} elseif ($todaystar['days'] >= '240') {
	$todaystar['level'] = "[LV.8]{$lv8name}";
} elseif ($todaystar['days'] >= '120') {
	$todaystar['level'] = "[LV.7]{$lv7name}";
} elseif ($todaystar['days'] >= '60') {
	$todaystar['level'] = "[LV.6]{$lv6name}";
} elseif ($todaystar['days'] >= '30') {
	$todaystar['level'] = "[LV.5]{$lv5name}";
} elseif ($todaystar['days'] >= '15') {
	$todaystar['level'] = "[LV.4]{$lv4name}";
} elseif ($todaystar['days'] >= '7') {
	$todaystar['level'] = "[LV.3]{$lv3name}";
} elseif ($todaystar['days'] >= '3') {
	$todaystar['level'] = "[LV.2]{$lv2name}";
} elseif ($todaystar['days'] >= '1') {
	$todaystar['level'] = "[LV.1]{$lv1name}";
}

//print_r($todaystar['level']);
if ($qiandaodb['days'] >= '1500') {
	$qiandaodb['level'] = 99;
} elseif ($qiandaodb['days'] >= '750') {
	$qiandaodb['level'] = 10;
} elseif ($qiandaodb['days'] >= '365') {
	$qiandaodb['level'] = 9;
} elseif ($qiandaodb['days'] >= '240') {
	$qiandaodb['level'] = 8;
} elseif ($qiandaodb['days'] >= '120') {
	$qiandaodb['level'] = 7;
} elseif ($qiandaodb['days'] >= '60') {
	$qiandaodb['level'] = 6;
} elseif ($qiandaodb['days'] >= '30') {
	$qiandaodb['level'] = 5;
} elseif ($qiandaodb['days'] >= '15') {
	$qiandaodb['level'] = 4;
} elseif ($qiandaodb['days'] >= '7') {
	$qiandaodb['level'] = 3;
} elseif ($qiandaodb['days'] >= '3') {
	$qiandaodb['level'] = 2;
} elseif ($qiandaodb['days'] >= '1') {
	$qiandaodb['level'] = 1;
}
if ($qiandaodb['days'] >= '1500') {
	$q['level'] = "{$lang['level']}<font color=green><b>[LV.Master]{$lvmastername}</b></font> .";
} elseif ($qiandaodb['days'] >= '750') {
	$q['lvqd'] = 1500 - $qiandaodb['days'];
	$q['level'] = "{$lang['level']}<font color=green><b>[LV.10]{$lv10name}{$lang['level2']} <font color=#FF0000><b>{$q['lvqd']}</b></font> {$lang['level3']} <font color=#FF0000><b>[LV.Master]{$lvmastername}</b></font> .";
} elseif ($qiandaodb['days'] >= '365') {
	$q['lvqd'] = 750 - $qiandaodb['days'];
	$q['level'] = "{$lang['level']}<font color=green><b>[LV.9]{$lv9name}</b></font>{$lang['level2']} <font color=#FF0000><b>{$q['lvqd']}</b></font> {$lang['level3']} <font color=#FF0000><b>[LV.10]{$lv10name}</b></font> .";
} elseif ($qiandaodb['days'] >= '240') {
	$q['lvqd'] = 365 - $qiandaodb['days'];
	$q['level'] = "{$lang['level']}<font color=green><b>[LV.8]{$lv8name}</b></font>{$lang['level2']} <font color=#FF0000><b>{$q['lvqd']}</b></font> {$lang['level3']} <font color=#FF0000><b>[LV.9]{$lv9name}</b></font> .";
} elseif ($qiandaodb['days'] >= '120') {
	$q['lvqd'] = 240 - $qiandaodb['days'];
	$q['level'] = "{$lang['level']}<font color=green><b>[LV.7]{$lv7name}</b></font>{$lang['level2']} <font color=#FF0000><b>{$q['lvqd']}</b></font> {$lang['level3']} <font color=#FF0000><b>[LV.8]{$lv8name}</b></font> .";
} elseif ($qiandaodb['days'] >= '60') {
	$q['lvqd'] = 120 - $qiandaodb['days'];
	$q['level'] = "{$lang['level']}<font color=green><b>[LV.6]{$lv6name}</b></font>{$lang['level2']} <font color=#FF0000><b>{$q['lvqd']}</b></font> {$lang['level3']} <font color=#FF0000><b>[LV.7]{$lv7name}</b></font> .";
} elseif ($qiandaodb['days'] >= '30') {
	$q['lvqd'] = 60 - $qiandaodb['days'];
	$q['level'] = "{$lang['level']}<font color=green><b>[LV.5]{$lv5name}</b></font>{$lang['level2']} <font color=#FF0000><b>{$q['lvqd']}</b></font> {$lang['level3']} <font color=#FF0000><b>[LV.6]{$lv6name}</b></font> .";
} elseif ($qiandaodb['days'] >= '15') {
	$q['lvqd'] = 30 - $qiandaodb['days'];
	$q['level'] = "{$lang['level']}<font color=green><b>[LV.4]{$lv4name}</b></font>{$lang['level2']} <font color=#FF0000><b>{$q['lvqd']}</b></font> {$lang['level3']} <font color=#FF0000><b>[LV.5]{$lv5name}</b></font> .";
} elseif ($qiandaodb['days'] >= '7') {
	$q['lvqd'] = 15 - $qiandaodb['days'];
	$q['level'] = "{$lang['level']}<font color=green><b>[LV.3]{$lv3name}</b></font>{$lang['level2']} <font color=#FF0000><b>{$q['lvqd']}</b></font> {$lang['level3']} <font color=#FF0000><b>[LV.4]{$lv4name}</b></font> .";
} elseif ($qiandaodb['days'] >= '3') {
	$q['lvqd'] = 7 - $qiandaodb['days'];
	$q['level'] = "{$lang['level']}<font color=green><b>[LV.2]{$lv2name}</b></font>{$lang['level2']} <font color=#FF0000><b>{$q['lvqd']}</b></font> {$lang['level3']} <font color=#FF0000><b>[LV.3]{$lv3name}</b></font> .";
} elseif ($qiandaodb['days'] >= '1') {
	$q['lvqd'] = 3 - $qiandaodb['days'];
	$q['level'] = "{$lang['level']}<font color=green><b>[LV.1]{$lv1name}</b></font>{$lang['level2']} <font color=#FF0000><b>{$q['lvqd']}</b></font> {$lang['level3']} <font color=#FF0000><b>[LV.2]{$lv2name}</b></font> .";
}
$numtostr = array(
	0 => 'zero',
	1 => 'one',
	2 => 'two',
	3 => 'three',
	4 => 'four',
	5 => 'five',
	6 => 'six',
	7 => 'seven',
	8 => 'eight',
	9 => 'nine',
);

if($setting['prizestatus']){
	$prizememberlists = C::t("#k_misign#plugin_k_misign_prize_log")->fetch_all(0, 12);
	foreach($prizememberlists as $value){
		$value['avatar'] = avatar($value['uid'], 'small', 1);
		$prizememberlist[] = $value;
	}
}

$rewardmemberlists = C::t("#k_misign#plugin_k_misign")->getsignlist('q.time', 'DESC', 0, 100);
$pushmemberlist = C::t("#k_misign#plugin_k_misign_push")->fetch_all_cp(0, 6);
foreach($pushmemberlist as $push){
		if($push['pic']) {
			$valueparse = parse_url($push['pic']);
			if(isset($valueparse['host'])) {
				$push['pic'] = $push['pic'];
			} else {
				$push['pic'] = $_G['setting']['attachurl'].$push['pic'].'?'.random(6);
			}
		}
		$pushmemberlists[] = $push;
}

if($extend['k_migeyan']){//小米每日格言点赞插件
	$k_migeyan['cache_file'] = DISCUZ_ROOT.'./data/sysdata/cache_k_migeyan.php';
	if(@filemtime($k_migeyan['cache_file']) > $tdtime){
		@include $k_migeyan['cache_file'];
		if($geyanCache['gid'] > 0){
			$k_migeyan['geyan'] = $geyanCache;
		}else{
			$k_migeyan['geyan'] = C::t("#k_migeyan#k_migeyan")->fetch_by_rand(0);
			if(!$k_migeyan['geyan']){
				$k_migeyan['geyan']['subject'] = lang('plugin/k_migeyan', 'houtaiadd');
				$k_migeyan['geyan']['zannum'] = 0;
				$k_migeyan['geyan']['day']='1970-1-1';
				$k_migeyan['geyan']['gid']='0';
				k_migeyan_writegeyan($k_migeyan['geyan']);
			}
		}
	}
	function k_migeyan_writegeyan($geyan){
		$geyanCache['gid'] = $geyan['gid'];
		$geyanCache['day'] = date("Y-m-d");
		$geyanCache['subject'] = addslashes($geyan['subject']);
		$geyanCache['zannum'] = $geyan['zannum'];
		require_once libfile('function/cache');
		writetocache('k_migeyan', "\$geyanCache=".arrayeval($geyanCache).";\n"); 
	}
}

if($_G['uid'] && $extend['magic']['bq'] && $extend['magicdetail']['bq']['available']){
	$bqstarttime = $tdtime - 86400*30;
	$bq = C::t("#k_misign#plugin_k_misign_bq")->fetch_by_time($_G['uid'], $bqstarttime);
	$tobqday = intval(($bq['thistime'] - $bq['lasttime']) / 86400);
	$bqeddays = $bq['bqdays'] + $tobqday + $qiandaodb['lasted'];
	$bqshowtip = str_replace(array('{starttime}', '{endtime}', '{tobqdays}', '{lasted}'), array(dgmdate($bq['lasttime'], 'm-d'),dgmdate($bq['thistime'], 'm-d'), $tobqday, $bqeddays), $setting['bq_tips']);
	$bqshowtip = $bqshowtip ? $bqshowtip : lang('plugin/k_misign','magic_bq_tips', array('starttime' => dgmdate($bq['lasttime'], 'm-d'), 'endtime' => dgmdate($bq['thistime'], 'm-d'), 'tobqdays' => $tobqday, 'lasted' => $bqeddays));
}
$navigation = $setting['title'];
$navtitle = "$navigation";

if(defined('IN_MOBILE')){
	include template('diy:k_misign_index', '', 'source/plugin/k_misign/template/'.($setting['styles']['mobile'] ? $setting['styles']['mobile'] : 'mobile_default'));
}else{
	include template('diy:k_misign_index', '', 'source/plugin/k_misign/template/'.($setting['styles']['pc'] ? $setting['styles']['pc'] : 'default'));
}
?>