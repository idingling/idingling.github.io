<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$setting['style'] = C::t('common_setting')->fetch('k_misignstyle', '1');
$setting['styles'] = $setting['style']['svalue'] ? unserialize($setting['style']['svalue']) : array('pc' => 'default', 'mobile' => 'mobile_default');

if(!submitcheck('stylesubmit')) {
	$narray = array();
	$sarray[0] = array(
		'name' => 'default',
		'directory' => './source/plugin/k_misign/template/default',
		'tplname' => lang('plugin/k_misign', 'cp_style_default'),
	);
	$dir = DISCUZ_ROOT.'./source/plugin/k_misign/template/';
	$templatedir = dir($dir);
	while($entry = $templatedir->read()) {
		$tpldir = realpath($dir.'/'.$entry);
		if(!in_array($entry, array('.', '..', 'touch', 'mobile')) && is_dir($tpldir)) {
			if($entry != 'default'){
				$sarray[] = array(
					'name' => $entry,
					'directory' => './source/plugin/k_misign/template/'.$entry,
					'tplname' => in_array($entry, array('default', 'mobile_default')) ? lang('plugin/k_misign', 'cp_style_'.$entry) : $entry,
					'filemtime' => @filemtime($dir.'/'.$entry),
					'mobile' => (strstr($entry, 'mobile_') ? true : false),
				);
			}
		}
	}

	$stylelist = '';
	$i = 0;
	$updatestring = array();
	foreach($sarray as $id => $style) {
		$style['name'] = dhtmlspecialchars($style['name']);
		$isdefault['pc'] = $style['name'] == $setting['styles']['pc'] ? 'checked' : '';
		$isdefault['mobile'] = $style['name'] == $setting['styles']['mobile'] ? 'checked' : '';
		
		$preview = file_exists($style['directory'].'/preview.jpg') ? $style['directory'].'/preview.jpg' : './static/image/admincp/stylepreview.gif';
		$previewlarge = file_exists($style['directory'].'/preview_large.jpg') ? $style['directory'].'/preview_large.jpg' : '';

		$stylelist .=
			'<table cellspacing="0" cellpadding="0" style="margin-left: 10px; width: 110px;height: 200px;" class="left"><tr><th class="partition"><center>'.$style['tplname'].'<center></th></tr><tr><td style="width: 130px;height:170px" valign="top">'.
			"<p style=\"margin-bottom: 12px;\"><img width=\"110\" height=\"120\" ".($previewlarge ? 'style="cursor:pointer" title="'.$lang['preview_large'].'" onclick="zoom(this, \''.$previewlarge.'\', 1)" ' : '')."src=\"$preview\" alt=\"$lang[preview]\"/></p>".
			"<p style=\"margin: 1px 0\"><label><input type=\"radio\" class=\"radio\" name=\"defaultnew[pc]\" value=\"".$style['name']."\" ".$isdefault['pc'].($style['mobile'] ? ' disabled="disabled"' : '')." /> ".lang('plugin/k_misign', 'cp_style_usestyle')."</label></p>".
			"<p style=\"margin: 1px 0\"><label><input type=\"radio\" class=\"radio\" name=\"defaultnew[mobile]\" value=\"".$style['name']."\" ".$isdefault['mobile'].($style['mobile'] ? '' : ' disabled="disabled"')." /> ".lang('plugin/k_misign', 'cp_style_usestyle_mobile')."</label></p>".
			"</td></tr></table>\n".($i == 3 ? '</tr>' : '');
		$i++;
		if($i == 3) {
			$i = 0;
		}
	}
	if($i > 0) {
		$stylelist .= str_repeat('<td></td>', 3 - $i);
	}

	showformheader('plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_style');
	showtableheader();
	echo $stylelist;
	showtablefooter();
	showtableheader();
	echo '<tr><td><a href="http://addon.discuz.com/?@k_misign.plugin">'.cplang('cloudaddons_style_link').'</a>';
	showsubmit('stylesubmit', 'submit', '', '');
	showtablefooter();
	showformfooter();

} else {
	if($defaultstyle != $_GET['defaultnew']) {
		$defaultstyle = serialize($_GET['defaultnew']);
		C::t('common_setting')->update('k_misignstyle', array('svalue' => $defaultstyle));
	}
	
	cpmsg('k_misign:success', 'action=plugins&operation=config&do='.$pluginid.'&identifier=k_misign&pmod=cp_style', 'succeed');

}


?>