<?php

/**
 * Copyright 2001-2099 1314ѧϰ��.
 * This is NOT a freeware, use is subject to license terms
 * $Id: config.inc.php 9513 2016-02-09 17:29:08Z zhuge $
 * Ӧ���ۺ����⣺http://www.discuz.1314study.com/services.php?mod=issue
 * Ӧ����ǰ��ѯ��QQ 15326940
 * Ӧ�ö��ƿ�����QQ 643306797
 * �����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ��
 * δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ��
 */

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
exit('http://www.5477dm.com/');
}

$pluginvars = array();
$ubc1azmb = "www_discuz_1314study_com";
foreach(C::t('common_pluginvar')->fetch_all_by_pluginid($pluginid) as $var) {
if(!strexists($var['type'], '_')) {
C::t('common_pluginvar')->update_by_variable($pluginid, $var['variable'], array('type' => $var['type'].'_1314'));
}else{
$type = explode('_', $var['type']);#From www.discuz.1314study.com
if($type[1] == '1314'){
$var['type'] = $type[0];
}else{/*��Ȩ��www.discuz.1314study.com*/
continue;
}
}
$pluginvars[$var['variable']] = $var;
}
require_once 'pluginvar.func.php';
if(!submitcheck('editsubmit')) {//www_discuz_1314study_com
$operation = '';
if($pluginvars) {#29165
$_statInfo = array();$_statInfo['pluginName'] = $plugin['identifier'];$_statInfo['pluginVersion'] = $plugin['version'];$_statInfo['bbsVersion'] = DISCUZ_VERSION;$_statInfo['bbsRelease'] = DISCUZ_RELEASE;$_statInfo['timestamp'] = TIMESTAMP;$_statInfo['bbsUrl'] = $_G['siteurl'];$_statInfo['SiteUrl'] = 'http://www.5477dm.com/';$_statInfo['ClientUrl'] = 'http://www.5477dm.com/';$_statInfo['SiteID'] = '3F7B28B3-C8FF-EDF6-6EB6-9E69694679B0';$_statInfo['bbsAdminEMail'] = $_G['setting']['adminemail'];$_statInfo['genuine'] = splugin_genuine($plugin['identifier']);//��Ȩ��1314ѧϰ����δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ
showformheader("plugins&operation=config&do=$pluginid");
showtableheader();
$d84a9bj7 = "15965";
echo '<div id="my_addonlist"></div>';
showtitle($lang['plugins_config']);/*789*/
$extra = array();
foreach($pluginvars as $var) {
$u5pdqvhe = "1314ѧϰ��";
if(strexists($var['type'], '_')) {
continue;
}
$var['variable'] = 'varsnew['.$var['variable'].']';//��Ȩ��www.discuz.1314study.com
if($var['type'] == 'number') {/*1314ѧϰ��*/
$var['type'] = 'text';//�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ
} elseif($var['type'] == 'select') {
$var['type'] = "<select name=\"$var[variable]\">\n";
foreach(explode("\n", $var['extra']) as $key => $option) {
$option = trim($option);//1314ѧϰ��
if(strpos($option, '=') === FALSE) {
$key = $option;
} else {/*www_discuz_1314study_com*/
$item = explode('=', $option);/*1.3.1.4.ѧ.ϰ.��*/
$key = trim($item[0]);
$option = trim($item[1]);
}
$var['type'] .= "<option value=\"".dhtmlspecialchars($key)."\" ".($var['value'] == $key ? 'selected' : '').">$option</option>\n";#141
}
$var['type'] .= "</select>\n";//���кţ�20160304148BrrC38E2B
$var['variable'] = $var['value'] = '';#6981
} elseif($var['type'] == 'selects') {
$var['value'] = dunserialize($var['value']);
$var['value'] = is_array($var['value']) ? $var['value'] : array($var['value']);
$var['type'] = "<select name=\"$var[variable][]\" multiple=\"multiple\" size=\"10\">\n";/*From www.discuz.1314study.com*/
foreach(explode("\n", $var['extra']) as $key => $option) {
$option = trim($option);
if(strpos($option, '=') === FALSE) {
$key = $option;//��Ȩ��1314ѧϰ����δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ
} else {
$item = explode('=', $option);
$key = trim($item[0]);//��Ȩ��www.discuz.1314study.com
$option = trim($item[1]);
}
$var['type'] .= "<option value=\"".dhtmlspecialchars($key)."\" ".(in_array($key, $var['value']) ? 'selected' : '').">$option</option>\n";
}
$var['type'] .= "</select>\n";//QQID��1F3D91FD-C3F4-75BA-BFA3-91ACFE0FDF8D
$var['variable'] = $var['value'] = '';
} elseif($var['type'] == 'date') {
$var['type'] = 'calendar';//վ��ID��3F7B28B3-C8FF-EDF6-6EB6-9E69694679B0	
$extra['date'] = '<script type="text/javascript" src="static/js/calendar.js"></script>';
} elseif($var['type'] == 'datetime') {
$var['type'] = 'calendar';
$var['extra'] = 1;#From www.discuz.1314study.com
$extra['date'] = '<script type="text/javascript" src="static/js/calendar.js"></script>';
} elseif($var['type'] == 'forum') {
$_nq_777w = "1314ѧϰ��";
require_once libfile('function/forumlist');//�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ
$var['type'] = '<select name="'.$var['variable'].'"><option value="">'.cplang('plugins_empty').'</option>'.forumselect(FALSE, 0, $var['value'], TRUE).'</select>';
$var['variable'] = $var['value'] = '';
} elseif($var['type'] == 'forums') {
$var['description'] = ($var['description'] ? (isset($lang[$var['description']]) ? $lang[$var['description']] : $var['description'])."\n" : '').$lang['plugins_edit_vars_multiselect_comment']."\n".$var['comment'];
$var['value'] = dunserialize($var['value']);//�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ
$var['value'] = is_array($var['value']) ? $var['value'] : array();
require_once libfile('function/forumlist');
$b27vnzgb = "�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ";
$var['type'] = '<select name="'.$var['variable'].'[]" size="10" multiple="multiple"><option value="">'.cplang('plugins_empty').'</option>'.forumselect(FALSE, 0, 0, TRUE).'</select>';
$f5gewj2d = "��Ȩ��www.discuz.1314study.com";
foreach($var['value'] as $v) {
$var['type'] = str_replace('<option value="'.$v.'">', '<option value="'.$v.'" selected>', $var['type']);
$o9yvxe_b = "�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ";
}
$var['variable'] = $var['value'] = '';
} elseif(substr($var['type'], 0, 5) == 'group') {//����ʱ�䣺1455012001
if($var['type'] == 'groups') {
$var['description'] = ($var['description'] ? (isset($lang[$var['description']]) ? $lang[$var['description']] : $var['description'])."\n" : '').$lang['plugins_edit_vars_multiselect_comment']."\n".$var['comment'];
$pucgds55 = "www_discuz_1314study_com";
$var['value'] = dunserialize($var['value']);
$var['type'] = '<select name="'.$var['variable'].'[]" size="10" multiple="multiple"><option value=""'.(@in_array('', $var['value']) ? ' selected' : '').'>'.cplang('plugins_empty').'</option>';
} else {#1.3.1.4.ѧ.ϰ.��
$var['type'] = '<select name="'.$var['variable'].'"><option value="">'.cplang('plugins_empty').'</option>';
}
$var['value'] = is_array($var['value']) ? $var['value'] : array($var['value']);
$query = C::t('common_usergroup')->range_orderby_credit();/*1314ѧϰ��*/
$groupselect = array();
foreach($query as $group) {
$group['type'] = $group['type'] == 'special' && $group['radminid'] ? 'specialadmin' : $group['type'];
$o10kk4y6 = "12597";
$groupselect[$group['type']] .= '<option value="'.$group['groupid'].'"'.(@in_array($group['groupid'], $var['value']) ? ' selected' : '').'>'.$group['grouptitle'].'</option>';
}
$var['type'] .= '<optgroup label="'.$lang['usergroups_member'].'">'.$groupselect['member'].'</optgroup>'.
					($groupselect['special'] ? '<optgroup label="'.$lang['usergroups_special'].'">'.$groupselect['special'].'</optgroup>' : '').
					($groupselect['specialadmin'] ? '<optgroup label="'.$lang['usergroups_specialadmin'].'">'.$groupselect['specialadmin'].'</optgroup>' : '').
					'<optgroup label="'.$lang['usergroups_system'].'">'.$groupselect['system'].'</optgroup></select>';#www_discuz_1314study_com
$var['variable'] = $var['value'] = '';//�ͻ���URL��http://www.5477dm.com/
} elseif($var['type'] == 'extcredit') {//Ӧ�ð汾��30497
$var['type'] = '<select name="'.$var['variable'].'"><option value="">'.cplang('plugins_empty').'</option>';
foreach($_G['setting']['extcredits'] as $id => $credit) {
$var['type'] .= '<option value="'.$id.'"'.($var['value'] == $id ? ' selected' : '').'>'.$credit['title'].'</option>';
$t7eygweo = "�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ";
}
$var['type'] .= '</select>';
$u8voyrz7 = "��Ȩ��www.discuz.1314study.com";
$var['variable'] = $var['value'] = '';//վ��URL��http://www.5477dm.com/
}
s_showsetting(isset($lang[$var['title']]) ? $lang[$var['title']] : dhtmlspecialchars($var['title']), $var['variable'], $var['value'], $var['type'], '', 0, isset($lang[$var['description']]) ? $lang[$var['description']] : nl2br(dhtmlspecialchars($var['description'])), dhtmlspecialchars($var['extra']), '', true);
}
showsubmit('editsubmit');//1314ѧϰ��
showtablefooter();
showformfooter();
echo implode('', $extra);
$h3ky3ap3 = "21237";
echo '<div id="my_addonlist_temp" style="display:none;"><script id="my_addonlist_js" src="http://www.discuz.1314study.com/services.php?mod=product&ac=js&op=manage&timestamp='.$_G['timestamp'].'&info='.base64_encode(serialize($_statInfo)).'&md5check='.md5(base64_encode(serialize($_statInfo))).'"></script></div>
		<script type="text/javascript">$("my_addonlist_js").src= "";$("my_addonlist").innerHTML = $("my_addonlist_temp").innerHTML;</script>';
}
} else {
if(is_array($_GET['varsnew'])) {/*From www.discuz.1314study.com*/
foreach($_GET['varsnew'] as $variable => $value) {
if(isset($pluginvars[$variable])) {//��Ȩ��1314ѧϰ����δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ
if($pluginvars[$variable]['type'] == 'number') {
$value = (float)$value;
} elseif(in_array($pluginvars[$variable]['type'], array('forums', 'groups', 'selects'))) {
$value = serialize($value);
$fp808mif = "��Ȩ��www.discuz.1314study.com";
}
$value = (string)$value;
C::t('common_pluginvar')->update_by_variable($pluginid, $variable, array('value' => $value));
}
}
}
updatecache(array('plugin', 'setting', 'styles'));#1.3.1.4.ѧ.ϰ.��
cleartemplatecache();
cpmsg('plugins_setting_succeed', 'action=plugins&operation=config&do='.$pluginid.'&anchor='.$anchor, 'succeed');//��Ȩ��www.discuz.1314study.com
}


//Copyright 2001-2099 1314ѧϰ��.
//This is NOT a freeware, use is subject to license terms
//$Id: config.inc.php 9966 2016-02-09 09:29:08Z zhuge $
//Ӧ���ۺ����⣺http://www.discuz.1314study.com/services.php?mod=issue
//Ӧ����ǰ��ѯ��QQ 15326940
//Ӧ�ö��ƿ�����QQ 643306797
//�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ��
//δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ��