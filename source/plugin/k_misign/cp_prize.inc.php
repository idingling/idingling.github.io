<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$setting = $_G['cache']['plugin']['k_misign'];
$op = in_array($_GET['op'], array('add', 'edit', 'addkami', 'cpkami', 'prizelog')) ? $_GET['op'] : '';

if($op == ''){
	if(!submitcheck('prizesubmit')) {
		$perpage = 30;
		$page = intval($_GET['page']);
		$start = ($page-1) * $perpage;
		if(empty($page)){
			$page = 1;
		}
		if($start < 0){
			$start = 0;
		}
		$multi = '';
		$list = C::t("#k_misign#plugin_k_misign_prize")->fetch_all_cp($start, $perpage);
		foreach($list as $lists){
			$lists['updatetime'] = dgmdate($lists['updatetime']);
			$companylist .= showtablerow('', array('', 'class="td29"', '', '', '', '', '', ''), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[".$lists['prizeid']."]\" value=\"".$lists['prizeid']."\">",
				lang('plugin/k_misign', 'prize_type_'.$lists['prizetype']).":&nbsp;".$lists['prizename'],
				($lists['prizetype'] == '3' ? $lists['todaylast'] : $lists['todaylast']).'/'.$lists['everyday'],
				$lists['percent'].lang('plugin/k_misign', 'prize_percent_unit'),
				$lists['updatetime'],
				$lists['status'] ? '<font color="green">'.lang('plugin/k_misign', 'prize_status_on').'</font>' : '<font color="red">'.lang('plugin/k_misign', 'prize_status_off').'</font>',
				($lists['prizetype'] == '3' ? "<a href=\"".ADMINSCRIPT."?action=plugins&operation=config&do=".$pluginid."&identifier=k_misign&pmod=cp_prize&op=addkami&prizeid=".$lists['prizeid']."\" class=\"act\">".lang('plugin/k_misign', 'prize_addkami')."</a>" : "")."<a href=\"".ADMINSCRIPT."?action=plugins&operation=config&do=".$pluginid."&identifier=k_misign&pmod=cp_prize&op=edit&prizeid=".$lists['prizeid']."\" class=\"act\">".$lang['detail']."</a>",
			), TRUE);
		}
		$count =  0;
		$count = C::t("#k_misign#plugin_k_misign_prize")->count();
		$multi = multi($count, $perpage, $page, '?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_prize');
		
		showformheader('plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_prize');
		showtableheader(lang('plugin/k_misign', 'prize_cp').'&nbsp;&nbsp;<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_prize&op=cpkami">'.lang('plugin/k_misign', 'prize_kami_cp').'</a>&nbsp;&nbsp;<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_prize&op=prizelog">'.lang('plugin/k_misign', 'prize_log').'</a>', 'fixpadding');
		showsubtitle(array('', lang('plugin/k_misign', 'prize_name'), lang('plugin/k_misign', 'prize_last'), lang('plugin/k_misign', 'prize_percent'), lang('plugin/k_misign', 'prize_updatetime'), lang('plugin/k_misign', 'prize_status'), ''));
		echo $companylist;
		echo '<tr><td>&nbsp;</td><td colspan="8"><div><a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_prize&op=add" class="addtr">'.lang('plugin/k_misign', 'prize_add').'</a></div></td></tr>';
		showsubmit('prizesubmit', $lang['submit'], 'del', '', $multi);
		showtablefooter();
		showformfooter();
	}else{
		if(is_array($_GET['delete'])) {
			foreach($_GET['delete'] as $id) {
				C::t("#k_misign#plugin_k_misign_prize")->delete($id);
			}
		}
		cpmsg('update_success', "action=plugins&operation=config&do=".$do."&identifier=k_misign&pmod=cp_prize", 'succeed');
	}
}elseif($op == 'add'){
	if(!submitcheck('prizeaddsubmit')) {
		showformheader('plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_prize&op=add');
		showtableheader(lang('plugin/k_misign', 'prize_add'), 'fixpadding', '');
		showsetting(lang('plugin/k_misign', 'prize_status'), 'statusnew', $prize['status'], 'radio');
		$prizetype = array('prizetypenew', array(), 'isfloat');
		$prizetype[1][0] = array(0, lang('plugin/k_misign', 'please_select'));
		$prizetype[1][1] = array(1, lang('plugin/k_misign', 'prize_type_1'));
		$prizetype[1][2] = array(2, lang('plugin/k_misign', 'prize_type_2'));
		$prizetype[1][3] = array(3, lang('plugin/k_misign', 'prize_type_3'));
		showsetting(lang('plugin/k_misign', 'prize_type'), $prizetype, $prize['prizetype'], 'select');
		showsetting(lang('plugin/k_misign', 'prize_name'), 'prizenamenew', $prize['prizename'], 'text');
		showsetting(lang('plugin/k_misign', 'prize_everyday'), 'everydaynew', $prize['everyday'], 'number');
		showsetting(lang('plugin/k_misign', 'prize_percent'), 'percentnew', $prize['percent'], 'number', '', '', lang('plugin/k_misign', 'prize_percent_tips'));
		showsetting(lang('plugin/k_misign', 'prize_auto'), 'autonew', $prize['auto'], 'radio', '', '', lang('plugin/k_misign', 'prize_auto_tips'));
		$prizecredittype = array('prizecredittypenew', array(), 'isfloat');
		$prizecredittype[1][0] = array(0, lang('plugin/k_misign', 'please_select'));
		foreach($_G['setting']['extcredits'] as $key => $value){
			$prizecredittype[1][$key] = array($key, $value['title']);
		}
		showsetting(lang('plugin/k_misign', 'prize_credit_type'), $prizecredittype, $prize['prizecredittype'], 'select', '', '', lang('plugin/k_misign', 'prize_credit_tips'));
		showsetting(lang('plugin/k_misign', 'prize_credit_num'), 'prizecreditnumnew', $prize['prizecreditnum'], 'number', '', '', lang('plugin/k_misign', 'prize_credit_tips'));
		showsubmit('prizeaddsubmit', 'submit');
		showtablefooter();
		showformfooter();
	}else{
		$data = array(
			'prizename'=>addslashes($_GET['prizenamenew']),
			'todaylast'=>in_array(intval($_GET['prizetypenew']), array(1,2)) ? intval($_GET['everydaynew']) : 0,
			'everyday'=>in_array(intval($_GET['prizetypenew']), array(1,2)) ? intval($_GET['everydaynew']) : 0,
			'auto'=>intval($_GET['autonew']),
			'percent'=>intval($_GET['percentnew']),
			'status'=>intval($_GET['statusnew']),
			'updatetime' => $_G['timestamp'],
			'prizetype' => intval($_GET['prizetypenew']),
			'prizecredittype' => intval($_GET['prizecredittypenew']),
			'prizecreditnum' => intval($_GET['prizecreditnumnew'])
		);
		C::t("#k_misign#plugin_k_misign_prize")->insert($data);
		cpmsg('update_success', "action=plugins&operation=config&do=".$do."&identifier=k_misign&pmod=cp_prize", 'succeed');
	}
}elseif($op == 'edit'){
	$prizeid = intval($_GET['prizeid']);
	$prize = C::t("#k_misign#plugin_k_misign_prize")->fetch_by_prizeid($prizeid);
	if(!submitcheck('prizeaddsubmit')) {
		showformheader('plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_prize&op=edit&prizeid='.$prizeid);
		showtableheader(lang('plugin/k_misign', 'prize_edit'), 'fixpadding', '');
		showsetting(lang('plugin/k_misign', 'prize_status'), 'statusnew', $prize['status'], 'radio');
		$prizetype = array('prizetypenew', array(), 'isfloat');
		$prizetype[1][0] = array(0, lang('plugin/k_misign', 'please_select'));
		$prizetype[1][1] = array(1, lang('plugin/k_misign', 'prize_type_1'));
		$prizetype[1][2] = array(2, lang('plugin/k_misign', 'prize_type_2'));
		$prizetype[1][3] = array(3, lang('plugin/k_misign', 'prize_type_3'));
		showsetting(lang('plugin/k_misign', 'prize_type'), $prizetype, $prize['prizetype'], 'select');
		showsetting(lang('plugin/k_misign', 'prize_name'), 'prizenamenew', $prize['prizename'], 'text');
		showsetting(lang('plugin/k_misign', 'prize_everyday'), 'everydaynew', $prize['everyday'], 'number');
		showsetting(lang('plugin/k_misign', 'prize_percent'), 'percentnew', $prize['percent'], 'number', '', '', lang('plugin/k_misign', 'prize_percent_tips'));
		showsetting(lang('plugin/k_misign', 'prize_auto'), 'autonew', $prize['auto'], 'radio', '', '', lang('plugin/k_misign', 'prize_auto_tips'));
		$prizecredittype = array('prizecredittypenew', array(), 'isfloat');
		$prizecredittype[1][0] = array(0, lang('plugin/k_misign', 'please_select'));
		foreach($_G['setting']['extcredits'] as $key => $value){
			$prizecredittype[1][$key] = array($key, $value['title']);
		}
		showsetting(lang('plugin/k_misign', 'prize_credit_type'), $prizecredittype, $prize['prizecredittype'], 'select', '', '', lang('plugin/k_misign', 'prize_credit_tips'));
		showsetting(lang('plugin/k_misign', 'prize_credit_num'), 'prizecreditnumnew', $prize['prizecreditnum'], 'number', '', '', lang('plugin/k_misign', 'prize_credit_tips'));
		showsubmit('prizeaddsubmit', 'submit');
		showtablefooter();
		showformfooter();
	}else{
		$data = array(
			'prizename'=>addslashes($_GET['prizenamenew']), 
			'percent'=>intval($_GET['percentnew']), 
			'status'=>intval($_GET['statusnew']), 
			'updatetime' => $_G['timestamp'], 
			'prizetype' => intval($_GET['prizetypenew']), 
			'prizecredittype' => intval($_GET['prizecredittypenew']), 
			'prizecreditnum' => intval($_GET['prizecreditnumnew'])
		);
		if(in_array($data['prizetype'], array(1,2))){
			$data['everyday'] = intval($_GET['everydaynew']);
			$data['auto'] = intval($_GET['autonew']);
		}
		C::t("#k_misign#plugin_k_misign_prize")->update($prizeid, $data);
		cpmsg('update_success', "action=plugins&operation=config&do=".$do."&identifier=k_misign&pmod=cp_prize", 'succeed');
	}
}elseif($op == 'addkami'){
	$prizeid = intval($_GET['prizeid']);
	$prize = C::t("#k_misign#plugin_k_misign_prize")->fetch_by_prizeid($prizeid);
	if(!submitcheck('addkamisubmit')) {
		showformheader('plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_prize&op=addkami&prizeid='.$prizeid);
		showtableheader(lang('plugin/k_misign', 'prize_addkami'), 'fixpadding', '');
		showsetting(lang('plugin/k_misign', 'prize_addkami'), 'kaminew', $prize['kami'], 'textarea', '', '', lang('plugin/k_misign', 'prize_addkami_tips'));
		showsubmit('addkamisubmit', 'submit');
		showtablefooter();
		showformfooter();
	}else{
		$messagearray = explode("\n", addslashes($_GET['kaminew']));
		foreach($messagearray as $value){
			C::t("#k_misign#plugin_k_misign_prize_kami")->insert(array('message' => $value, 'prizeid' => $prizeid));
		}
		C::t("#k_misign#plugin_k_misign_prize")->update($prizeid, array('todaylast' => ($prize['todaylast']+count($messagearray)), 'everyday' => ($prize['everyday']+count($messagearray))));
		cpmsg('update_success', "action=plugins&operation=config&do=".$do."&identifier=k_misign&pmod=cp_prize", 'succeed');
	}
}elseif($op == 'cpkami'){
	if(!submitcheck('kamisubmit')) {
		$perpage = 30;
		$page = intval($_GET['page']);
		$start = ($page-1) * $perpage;
		if(empty($page)){
			$page = 1;
		}
		if($start < 0){
			$start = 0;
		}
		$multi = '';
		$list = C::t("#k_misign#plugin_k_misign_prize_kami")->fetch_all_cp($start, $perpage);
		foreach($list as $lists){
			$lists['usetime'] = dgmdate($lists['usetime']);
			$companylist .= showtablerow('', array('', 'class="td29"', '', '', '', ''), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[".$lists['kid']."]\" value=\"".$lists['kid']."\">",
				$lists['prizename'],
				$lists['uid'] ? "<a href=\"home.php?mod=space&uid=".$lists['uid']."\" target=\"_blank\">".$lists['username']."</a>" : lang('plugin/k_misign', 'prize_kami_unuse'),
				$lists['message'],
				$lists['uid'] ? $lists['usetime'] : lang('plugin/k_misign', 'prize_kami_unuse'),
			), TRUE);
		}
		$count =  0;
		$count = C::t("#k_misign#plugin_k_misign_prize_kami")->count();
		$multi = multi($count, $perpage, $page, '?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_prize&op=cpkami');
	
		showformheader('plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_prize&op=cpkami');
		showtableheader('<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_prize">'.lang('plugin/k_misign', 'prize_cp').'</a>&nbsp;&nbsp;'.lang('plugin/k_misign', 'prize_kami_cp').'&nbsp;&nbsp;<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_prize&op=prizelog">'.lang('plugin/k_misign', 'prize_log').'</a>', 'fixpadding');
		showsubtitle(array('', '', lang('plugin/k_misign', 'prize_kami_user'), lang('plugin/k_misign', 'prize_kami_name'), lang('plugin/k_misign', 'prize_kami_usetime')));
		echo $companylist;
		showsubmit('kamisubmit', $lang['submit'], 'del', '', $multi);
		showtablefooter();
		showformfooter();
	}else{
		if(is_array($_GET['delete'])) {
			foreach($_GET['delete'] as $id) {
				$kami = C::t("#k_misign#plugin_k_misign_prize_kami")->fetch_by_kid($id);
				$prize = C::t("#k_misign#plugin_k_misign_prize")->fetch_by_prizeid($kami['prizeid']);
				C::t("#k_misign#plugin_k_misign_prize_kami")->delete($id);
				C::t("#k_misign#plugin_k_misign_prize")->update($kami['prizeid'], array('todaylast' => ($prize['todaylast']-1), 'everyday' => ($prize['everyday']-1)));
			}
		}
		cpmsg('update_success', "action=plugins&operation=config&do=".$do."&identifier=k_misign&pmod=cp_prize&op=cpkami", 'succeed');
	}
}elseif($op == 'prizelog'){
	if(!submitcheck('prizelogsubmit')) {
		$perpage = 30;
		$page = intval($_GET['page']);
		$start = ($page-1) * $perpage;
		if(empty($page)){
			$page = 1;
		}
		if($start < 0){
			$start = 0;
		}
		$multi = '';
		$list = C::t("#k_misign#plugin_k_misign_prize_log")->fetch_all($start, $perpage);
		foreach($list as $lists){
			$lists['dateline'] = dgmdate($lists['dateline']);
			$companylist .= showtablerow('', array('', 'class="td29"', '', '', '', ''), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[".$lists['logid']."]\" value=\"".$lists['logid']."\">",
				"<a href=\"home.php?mod=space&uid=".$lists['uid']."\" target=\"_blank\">".$lists['username']."</a>",
				$lists['prizename'],
				$lists['dateline'],
			), TRUE);
		}
		$count =  0;
		$count = C::t("#k_misign#plugin_k_misign_prize_log")->count();
		$multi = multi($count, $perpage, $page, '?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_prize&op=prizelog');
	
		showformheader('plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_prize&op=prizelog');
		showtableheader('<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_prize">'.lang('plugin/k_misign', 'prize_cp').'</a>&nbsp;&nbsp;<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_prize&op=cpkami">'.lang('plugin/k_misign', 'prize_kami_cp').'</a>&nbsp;&nbsp;'.lang('plugin/k_misign', 'prize_log'), 'fixpadding', '');
		showsubtitle(array('', '', lang('plugin/k_misign', 'prize_name'), lang('plugin/k_misign', 'prize_updatetime')));
		echo $companylist;
		showsubmit('prizelogsubmit', $lang['submit'], 'del', '', $multi);
		showtablefooter();
		showformfooter();
	}else{
		if(is_array($_GET['delete'])) {
			foreach($_GET['delete'] as $id) {
				C::t("#k_misign#plugin_k_misign_prize_log")->delete($id);
			}
		}
		cpmsg(cplang(lang('plugin/k_misign', 'success')), "action=plugins&operation=config&do=".$do."&identifier=k_misign&pmod=cp_prize&op=prizelog", 'succeed');
	}
}
?>