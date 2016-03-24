<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
loadcache('plugin');
$setting = $_G['cache']['plugin']['k_misign'];
require_once libfile('function/core', 'plugin/k_misign');

$status = intval($_GET['status']) ? intval($_GET['status']) : 1;
$uid = intval($_GET['uid']) ? intval($_GET['uid']) : 0;
$act = $_GET['act'];
$formhash = isset($_GET['formhash']) ? trim($_GET['formhash']) : '';

if($act == 'detail'){
?>
<script type="text/javascript" src="./static/js/calendar.js"></script>
<?php
	$info = C::t("#k_misign#plugin_k_misign")->fetch_by_uid($uid);
	if(!submitcheck('detailsubmit')) {
		showtableheader(''.$info['username']);
		showformheader('plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_users&act=detail&uid='.$uid, '');
		showsetting($lang['username'], 'username', $info['username'], 'text', 1);
		showsetting(lang('plugin/k_misign', 'ranklist_days'), 'daysnew', $info['days'], 'text');
		showsetting(lang('plugin/k_misign', 'ranklist_monthdays'), 'mdaysnew', $info['mdays'], 'text');
		showsetting(lang('plugin/k_misign', 'cp_last_lastday'), 'lastednew', $info['lasted'], 'text');
		showsetting(lang('plugin/k_misign', 'ranklist_rewards'), 'rewardnew', $info['reward'], 'text');
		showsetting(lang('plugin/k_misign', 'ranklist_lasttime'), 'timenew', dgmdate($info['time']), 'calendar','','','','1');
		showsubmit('detailsubmit', 'submit');
		showformfooter();
		showtablefooter();
		//print_r($info['time']);
	}else{
		$data = array('days' => intval($_GET['daysnew']), 'mdays' => intval($_GET['mdaysnew']), 'lasted' => intval($_GET['lastednew']), 'reward' => intval($_GET['rewardnew']));
		if(dgmdate($info['time']) != $_GET['timenew']){
			$data['time'] = strtotime($_GET['timenew']);
		}
		C::t("#k_misign#plugin_k_misign")->update($uid, $data);
		cpmsg(lang('plugin/k_misign', 'success'), "action=plugins&operation=config&do=".$do."&identifier=k_misign&pmod=cp_users&act=detail&uid=".$uid, 'succeed');
	}
}else{
	if(!submitcheck('userssubmit')) {
		$perpage = 30;
		$page = intval($_GET['page']);
		$orderby = addslashes($_GET['orderby']) ? addslashes($_GET['orderby']) : 'uid';
		$start = ($page-1) * $perpage;
		if(empty($page)){
			$page = 1;
		}
		if($start < 0){
			$start = 0;
		}
		$multi = '';
		
		$search['username'] = addslashes($_GET['username']);
		if($search['username']){
			$searchuser = C::t('common_member')->fetch_by_username($search['username']);
			if($searchuser){
				$uid = $searchuser['uid'];
			}else{
				cpmsg(lang('plugin/k_misign', 'usernotexist'), "action=plugins&operation=config&do=".$do."&identifier=k_misign&pmod=cp_users");
			}
		}
		
		if($uid){
			$list[0] = C::t("#k_misign#plugin_k_misign")->fetch_by_uid($uid);
		}else{
			$list = C::t("#k_misign#plugin_k_misign")->fetch_all_by_order($orderby, $start, $perpage);
		}
		foreach($list as $lists){
			$lists['updatetime'] = dgmdate($lists['updatetime'], 'u');
			$companylist .= showtablerow('', array('', '', 'class="td32"', 'class="td32"', 'class="td32"', '', 'class="td32"'), array(
				'<input class="checkbox" type="checkbox" name="delete['.$lists['uid'].']" value="'.$lists['uid'].'">',
				'<a href="home.php?mod=space&uid='.$lists['uid'].'" target="_blank">'.dhtmlspecialchars($lists['username']).'</a>',
				$lists['days'],
				$lists['mdays'],
				$lists['reward'],
				dgmdate($lists['time']),
				'<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_users&act=detail&uid='.$lists['uid'].'">'.$lang['detail'].'</a>', 
			), TRUE);
		}
		$count =  0;
		if(!$uid){
			$count = C::t("#k_misign#plugin_k_misign")->count();
			$multi = multi($count, $perpage, $page, ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_users&orderby='.$orderby);
		}
		showtableheader();
		showformheader('plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_users', 'searchsubmit');
		showsubmit('searchsubmit', lang('plugin/k_misign', 'searchuser'), $lang['username'].lang('plugin/k_misign', 'maohao').'<input name="username" value="'.stripslashes($search['username']).'" class="txt" />'.$lang['uid'].lang('plugin/k_misign', 'maohao').'<input name="uid" value="'.(intval($_GET['uid']) ? intval($_GET['uid']) : '').'" class="txt" />', $searchtext);
		showformfooter();
		showtablefooter();
		
		showformheader('plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_users');
		showtableheader('', 'fixpadding', '');
		showsubtitle(
			array(
				'', 
				'<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_users&orderby=uid">'.$lang['username'].($orderby == 'uid' ? '*' : '').'</a>', 
				'<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_users&orderby=days">'.lang('plugin/k_misign', 'ranklist_days').($orderby == 'days' ? '*' : '').'</a>', 
				'<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_users&orderby=mdays">'.lang('plugin/k_misign', 'ranklist_monthdays').($orderby == 'mdays' ? '*' : '').'</a>', 
				'<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_users&orderby=reward">'.lang('plugin/k_misign', 'ranklist_rewards').($orderby == 'mdays' ? '*' : '').'</a>', 
				'<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_users&orderby=time">'.lang('plugin/k_misign', 'ranklist_lasttime').($orderby == 'time' ? '*' : '').'</a>', ''
			)
		);
		echo $companylist;
		showsubmit('userssubmit', $lang['submit'], 'del', '', $multi);
		showtablefooter();
		showformfooter();
	}else{
		if(is_array($_GET['delete'])) {
			foreach($_GET['delete'] as $id) {
				C::t("#k_misign#plugin_k_misign")->delete($id);
			}
		}
		cpmsg(lang('plugin/k_misign', 'success'), "action=plugins&operation=config&do=".$do."&identifier=k_misign&pmod=cp_users", 'succeed');
	}
}

?>