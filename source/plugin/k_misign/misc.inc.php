<?php
!defined('IN_DISCUZ') && exit('Access Denied');
define('IN_k_misign', '1');

require_once libfile('function/core', 'plugin/k_misign');
$operation = $_GET['operation'];

if($operation == 'rewardrule') {
	$qiandaodb = C::t("#k_misign#plugin_k_misign")->fetch_by_uid($_G['uid']);
	$njlmain =str_replace(array("\r\n", "\n", "\r"), '/hhf/', $setting['jlmain']);
	$extrewards = explode("/hhf/", $njlmain);
	$extreward_num = count($extrewards);
	foreach($extrewards as $key => $value){
		$extrewardlist[$key]['ul'] = lang('plugin/k_misign', 'reward_top_number', array('number' => $key+1));
		list($extrewardlist[$key]['type'],$extrewardlist[$key]['number']) = explode("|", $value);
	}
	
	$medallist = C::t("forum_medal")->fetch_all_name_by_available(1);
	if($extend['lastrule'] && $setting['extends']['lastrule']){
		require_once DISCUZ_ROOT.'./source/plugin/k_misign/language/extend_lastrule.'.currentlang().'.php';
		$exlang['lastrule'] = $extendlang;
		$lastrewards = C::t("#k_misign#plugin_k_misign_lastrule")->fetch_all(0, 9999);
		foreach($lastrewards as $key => $value){
			$value['reward'] =str_replace(array("\r\n", "\n", "\r"), '/hhf/', $value['reward']);
			$value['rewards'] = explode("/hhf/", $value['reward']);
			unset($value['reward']);
			$lastrewardlist[$key] = $value;
			foreach($value['rewards'] as $k => $v){
				list($lastrewardlist[$key]['reward'][$k]['type'],$lastrewardlist[$key]['reward'][$k]['number']) = explode("|", $v);
			}
		}
	}
	if($extend['totalrule'] && $setting['extends']['totalrule']){
		require_once DISCUZ_ROOT.'./source/plugin/k_misign/language/extend_totalrule.'.currentlang().'.php';
		$exlang['totalrule'] = $extendlang;
		$totalrewards = C::t("#k_misign#plugin_k_misign_totalrule")->fetch_all(0, 9999);
		foreach($totalrewards as $key => $value){
			$value['reward'] =str_replace(array("\r\n", "\n", "\r"), '/hhf/', $value['reward']);
			$value['rewards'] = explode("/hhf/", $value['reward']);
			unset($value['reward']);
			$totalrewardlist[$key] = $value;
			foreach($value['rewards'] as $k => $v){
				list($totalrewardlist[$key]['reward'][$k]['type'],$totalrewardlist[$key]['reward'][$k]['number']) = explode("|", $v);
			}
		}
	}
	$navigation = $setting['title'];
	$navtitle = lang('plugin/k_misign', 'rewardrule').' - '.$setting['title'];
	if(defined('IN_MOBILE')){
		include template('diy:misc_rewardrule', '', 'source/plugin/k_misign/template/'.($setting['styles']['mobile'] ? $setting['styles']['mobile'] : 'mobile_default'));
	}else{
		include template('diy:misc_rewardrule', '', 'source/plugin/k_misign/template/'.($setting['styles']['pc'] ? $setting['styles']['pc'] : 'default'));
	}
}elseif($operation == 'magics'){
	
	$qiandaodb = C::t("#k_misign#plugin_k_misign")->fetch_by_uid($_G['uid']);
	
	if($extend['magic']['bq'] && $extend['magicdetail']['bq']['available']){
		$bqstarttime = $tdtime - 86400*30;
		$bq = C::t("#k_misign#plugin_k_misign_bq")->fetch_by_time($_G['uid'], $bqstarttime);
		$tobqday = intval(($bq['thistime'] - $bq['lasttime']) / 86400);
		$bqeddays = $bq['bqdays'] + $tobqday + $qiandaodb['lasted'];
		$bqshowtip = str_replace(array('{starttime}', '{endtime}', '{tobqdays}', '{lasted}'), array(dgmdate($bq['lasttime'], 'm-d'),dgmdate($bq['thistime'], 'm-d'), $tobqday, $bqeddays), $setting['bq_tips']);
		$bqshowtip = $bqshowtip ? $bqshowtip : lang('plugin/k_misign','magic_bq_tips', array('starttime' => dgmdate($bq['lasttime'], 'm-d'), 'endtime' => dgmdate($bq['thistime'], 'm-d'), 'tobqdays' => $tobqday, 'lasted' => $bqeddays));
	}

	$navigation = $setting['title'];
	$navtitle = lang('plugin/k_misign', 'extend_magics').' - '.$setting['title'];
	
	if(defined('IN_MOBILE')){
		include template('diy:misc_magics', '', 'source/plugin/k_misign/template/'.($setting['styles']['mobile'] ? $setting['styles']['mobile'] : 'mobile_default'));
	}else{
		include template('diy:misc_magics', '', 'source/plugin/k_misign/template/'.($setting['styles']['pc'] ? $setting['styles']['pc'] : 'default'));
	}

}

?>