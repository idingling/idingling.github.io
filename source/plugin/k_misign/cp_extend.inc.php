<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
loadcache('plugin');
$setting = $_G['cache']['plugin']['k_misign'];
require_once libfile('function/core', 'plugin/k_misign');

$act = $_GET['act'];
$formhash = isset($_GET['formhash']) ? trim($_GET['formhash']) : '';
if($act){
	require_once libfile('extend/'.$act, 'plugin/k_misign');
}else{
	//辅助系统
	showtableheader(lang('plugin/k_misign', 'extend'), 'psetting');
	//仿小米每日格言点赞
	showtablerow('class="hover"', array('valign="top" style="width:45px"', 'valign="top"', 'align="right" valign="bottom" style="width:160px"'), array(
					'<img src="http://open.discuz.net/resource/plugin/k_migeyan.png" onerror="this.src=\'static/image/admincp/plugin_logo.png\';this.onerror=null" width="40" height="40" align="left" />',
					'<span '.($extend['k_migeyan'] ? 'class="bold"' : 'class="bold light"').'>'.lang('plugin/k_misign', 'extend_k_migeyan').'</span>'.
					'<p><span class="bold light">'.($extend['k_migeyan'] ? lang('plugin/k_misign', 'extend_installed') : '<a href="http://addon.discuz.com/?@k_migeyan.plugin">'.lang('plugin/k_misign', 'extend_toinstall').'</a>').'</span></p>'
	) );
	showtablefooter();
	
	//增值组件
	showtableheader(lang('plugin/k_misign', 'extend'));
		//数据导入
		showtablerow('', array('class="td24 lineheight"','class="td24 lineheight smallfont"','class="lineheight smallfont"'), array(lang('plugin/k_misign', 'extend_dataimport'),($extend['dataimport'] ? lang('plugin/k_misign', 'extend_installed') : '<a href="http://addon.discuz.com/?@k_misign.plugin.49727">'.lang('plugin/k_misign', 'extend_toinstall').'</a>'),($extend['dataimport'] ? '<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$do.'&identifier=k_misign&pmod=cp_extend&act=dataimport">'.$lang['config'].'</a> - <a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$do.'&identifier=k_misign&pmod=cp_extend&act=dataimport&op=uninstall">'.lang('plugin/k_misign', 'extend_uninstall').'</a>' : ''), ($extend['dataimport'] ? '' : '')));
		//数据导出
		showtablerow('', array('class="td24 lineheight"','class="td24 lineheight smallfont"','class="lineheight smallfont"'), array(lang('plugin/k_misign', 'extend_dataexport'),($extend['dataexport'] ? lang('plugin/k_misign', 'extend_installed') : '<a href="http://addon.discuz.com/?@k_misign.plugin.50175">'.lang('plugin/k_misign', 'extend_toinstall').'</a>'),($extend['dataexport'] ? '<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$do.'&identifier=k_misign&pmod=cp_extend&act=dataexport">'.$lang['config'].'</a> - <a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$do.'&identifier=k_misign&pmod=cp_extend&act=dataexport&op=uninstall">'.lang('plugin/k_misign', 'extend_uninstall').'</a>' : '')));
		//连续签到规则
		showtablerow('', array('class="td24 lineheight"','class="td24 lineheight smallfont"','class="lineheight smallfont"'), array(lang('plugin/k_misign', 'extend_lastrule'),($extend['lastrule'] ? lang('plugin/k_misign', 'extend_installed') : '<a href="http://addon.discuz.com/?@k_misign.plugin.55676">'.lang('plugin/k_misign', 'extend_toinstall').'</a>'),($extend['lastrule'] ? '<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$do.'&identifier=k_misign&pmod=cp_extend&act=lastrule">'.$lang['config'].'</a> - <a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$do.'&identifier=k_misign&pmod=cp_extend&act=lastrule&op=uninstall">'.lang('plugin/k_misign', 'extend_uninstall').'</a>' : '')));
		//连续签到维护
		showtablerow('', array('class="td24 lineheight"','class="td24 lineheight smallfont"','class="lineheight smallfont"'), array(lang('plugin/k_misign', 'extend_lastdefend'),($extend['lastdefend'] ? lang('plugin/k_misign', 'extend_installed') : '<a href="http://addon.discuz.com/?@k_misign.plugin.49732">'.lang('plugin/k_misign', 'extend_toinstall').'</a>'),($extend['lastdefend'] ? '<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$do.'&identifier=k_misign&pmod=cp_extend&act=lastdefend">'.$lang['config'].'</a> - <a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$do.'&identifier=k_misign&pmod=cp_extend&act=lastdefend&op=uninstall">'.lang('plugin/k_misign', 'extend_uninstall').'</a>' : '')));
		//累计签到规则
		showtablerow('', array('class="td24 lineheight"','class="td24 lineheight smallfont"','class="lineheight smallfont"'), array(lang('plugin/k_misign', 'extend_totalrule'),($extend['totalrule'] ? lang('plugin/k_misign', 'extend_installed') : '<a href="http://addon.discuz.com/?@k_misign.plugin.55677">'.lang('plugin/k_misign', 'extend_toinstall').'</a>'),($extend['totalrule'] ? '<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$do.'&identifier=k_misign&pmod=cp_extend&act=totalrule">'.$lang['config'].'</a> - <a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$do.'&identifier=k_misign&pmod=cp_extend&act=totalrule&op=uninstall">'.lang('plugin/k_misign', 'extend_uninstall').'</a>' : '')));
		//等级管理地址待改
		showtablerow('', array('class="td24 lineheight"','class="td24 lineheight smallfont"','class="lineheight smallfont"'), array(lang('plugin/k_misign', 'extend_leval'),($extend['leval'] ? lang('plugin/k_misign', 'extend_installed') : '<a href="javascript:;">'.lang('plugin/k_misign', 'extend_toinstall').'</a>'),($extend['leval'] ? '<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$do.'&identifier=k_misign&pmod=cp_extend&act=leval">'.$lang['config'].'</a> - <a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$do.'&identifier=k_misign&pmod=cp_extend&act=leval&op=uninstall">'.lang('plugin/k_misign', 'extend_uninstall').'</a>' : '')));
	showtablefooter();
	
	//道具扩展
	showtableheader(lang('plugin/k_misign', 'extend_magics'));
		//补签卡
		showtablerow('', array('class="td24 lineheight"','class="td24 lineheight smallfont"','class="lineheight smallfont"'), array(lang('plugin/k_misign', 'magic_bq'),($extend['magic']['bq'] ? lang('plugin/k_misign', 'extend_installed') : '<a href="http://addon.discuz.com/?@k_misign.plugin.49922">'.lang('plugin/k_misign', 'extend_toinstall').'</a>'), ''));
	showtablefooter();

	//嵌入点
	showtableheader(lang('plugin/k_misign', 'extend_hooks'));
		//global_usernav_extra3
		showtablerow('', array('class="td24 lineheight"','class="td24 lineheight smallfont"','class="lineheight smallfont"'), array(lang('plugin/k_misign', 'hook_global_usernav_extra3'), '',($hook['global_usernav_extra3'] ? '<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$do.'&identifier=k_misign&pmod=cp_extend&act=hook_global_usernav_extra3&op=uninstall">'.lang('plugin/k_misign', 'extend_uninstall').'</a>' : '<a href="http://addon.discuz.com/?@k_misign.plugin.57770">'.lang('plugin/k_misign', 'extend_toinstall').'</a>')));
		//viewthread_sidetop
		showtablerow('', array('class="td24 lineheight"','class="td24 lineheight smallfont"','class="lineheight smallfont"'), array(lang('plugin/k_misign', 'hook_viewthread_sidetop'), '',($hook['viewthread_sidetop'] ? '<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$do.'&identifier=k_misign&pmod=cp_extend&act=hook_viewthread_sidetop&op=uninstall">'.lang('plugin/k_misign', 'extend_uninstall').'</a>' : '<a href="http://addon.discuz.com/?@k_misign.plugin.57771">'.lang('plugin/k_misign', 'extend_toinstall').'</a>')));
	showtablefooter();
}
?>