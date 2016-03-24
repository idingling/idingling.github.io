<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * From www.1314study.com
 * 应用售后问题：http://www.discuz.1314study.com/services.php?mod=ask&sid=1
 * 应用售前咨询：http://www.discuz.1314study.com/services.php?mod=ask&sid=2
 * 二次开发定制：http://www.discuz.1314study.com/services.php?mod=ask&sid=22
 */

if(!defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$cachekey = 'scache_'.$pluginarray['plugin']['identifier'];
loadcache($cachekey);
$cachevalue = $_G['cache'][$cachekey];

if($operation == 'import' && empty($license)){
	$license = dfsockopen('http://addon.1314study.com/api/license.php?siteurl='.rawurlencode($_G['siteurl']).'&identifier='.$identifier, 0, '', '', false, '', 999);$cachevalue['license'] = 1;savecache($cachekey, $cachevalue);
	if(empty($_GET['license']) && $license) {
		$installtype = $_GET['installtype'];
		$dir = $_GET['dir'];
		require_once libfile('function/discuzcode');
		$pluginarray['license'] = discuzcode(strip_tags($pluginarray['license']), 1, 0);
		echo '<div class="infobox"><h4 class="infotitle2">'.$pluginarray['plugin']['name'].' '.$pluginarray['plugin']['version'].' '.$lang['plugins_import_license'].'</h4><div style="text-align:left;line-height:25px;">'.$license.'</div><br /><br /><center>'.
			'<button onclick="location.href=\''.ADMINSCRIPT.'?action=plugins&operation=import&dir='.$dir.'&installtype='.$installtype.'&license=yes\'">'.$lang['plugins_import_agree'].'</button>&nbsp;&nbsp;'.
			'<button onclick="location.href=\''.ADMINSCRIPT.'?action=plugins\'">'.$lang['plugins_import_pass'].'</button></center></div>';
		exit;
	}
}

$addonid = $pluginarray['plugin']['identifier'].'.plugin';
$array = cloudaddons_getmd5($addonid);
if(cloudaddons_open('&mod=app&ac=validator&addonid='.$addonid.($array !== false ? '&rid='.$array['RevisionID'].'&sn='.$array['SN'].'&rd='.$array['RevisionDateline'] : '')) === '0') {
	if($pluginarray['plugin']['identifier']){
		cloudaddons_cleardir(DISCUZ_ROOT.'./source/plugin/'.$pluginarray['plugin']['identifier'].'/');
	}
	cpmsg('clo'.'ud'.'addo'.'ns_genu'.'ine_mes'.'sage', '', 'error', array('addonid' => $addonid));
}else{
	$cachevalue['check'] = $pluginarray['plugin']['identifier'];
	savecache($cachekey, $cachevalue);
}