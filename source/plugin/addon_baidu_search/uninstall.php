<?php
/*
 * Install Uninstall Upgrade AutoStat System Code 20160304148BrrC38E2B
 * This is NOT a freeware, use is subject to license terms
 * From www.1314study.com
 */
if(!defined('IN_ADMINCP')) {
	exit('Access Denied');
}

require_once DISCUZ_ROOT.'./source/discuz_version.php';
require_once 'installlang.lang.php';
$request_url = str_replace('&step='.$_GET['step'],'',$_SERVER['QUERY_STRING']);

//3.1以后版本直接跳到删除数据库
if(str_replace('X', '', DISCUZ_VERSION) >= 3.1){
	$_GET['step'] = 'sql';
	$_GET['deletesql'] = '20160304148BrrC38E2B';
}

empty($pluginarray['plugin']) && $pluginarray['plugin'] = $plugin;
$identifier = $identifier ? $identifier : $pluginarray['plugin']['identifier'];

$available = dfsockopen('http://addon.1314study.com/api/available.php?siteurl='.rawurlencode($_G['siteurl']).'&identifier='.$identifier);
if($available == 'succeed'){
	$available = 1;
}else{
	$available = 0;
}

//$sql = <<<EOF
//DROP TABLE IF EXISTS cdb_study_demo;
//EOF;
//runquery($sql);


require_once libfile('function/cloudaddons');
cloudaddons_deltree(DISCUZ_ROOT.'./data/baidu_search/');
if(file_exists(DISCUZ_ROOT.'/baidu_search_index.xml')){
		@unlink(DISCUZ_ROOT.'/baidu_search_index.xml');
}
if(file_exists(DISCUZ_ROOT.'/baidu_search_forum.xml')){
		@unlink(DISCUZ_ROOT.'/baidu_search_forum.xml');
}

$_statInfo = array();
$_statInfo['pluginName'] = $pluginarray['plugin']['identifier'];
$_statInfo['pluginVersion'] = $pluginarray['plugin']['version'];
$_statInfo['bbsVersion'] = DISCUZ_VERSION;
$_statInfo['bbsRelease'] = DISCUZ_RELEASE;
$_statInfo['timestamp'] = TIMESTAMP;
$_statInfo['bbsUrl'] = $_G['siteurl'];
$_statInfo['SiteUrl'] = 'http://www.5477dm.com/';
$_statInfo['ClientUrl'] = 'http://www.5477dm.com/';
$_statInfo['SiteID'] = '3F7B28B3-C8FF-EDF6-6EB6-9E69694679B0';
$_statInfo['bbsAdminEMail'] = $_G['setting']['adminemail'];
$_statInfo['action'] = 'uninstall';
$_statInfo['genuine'] = splugin_genuine($pluginarray['plugin']['identifier']);
$_statInfo = base64_encode(serialize($_statInfo));
$_md5Check = md5($_statInfo);
$StatUrl = 'http://addon.1314study.com/stat.php';
$_StatUrl = $StatUrl.'?info='.$_statInfo.'&md5check='.$_md5Check;
dfsockopen($_StatUrl, 0, '', '', false, '', 999);
$_statInfo = array();
$_statInfo['pluginName'] = $pluginarray['plugin']['identifier'];
$_statInfo['bbsVersion'] = DISCUZ_VERSION;
$_statInfo['bbsUrl'] = $_G['siteurl'];
$_statInfo['action'] = 'uninstall';
$_statInfo['nextUrl'] = ADMINSCRIPT.'?'.$request_url;
$_statInfo = base64_encode(serialize($_statInfo));
$_md5Check = md5($_statInfo);
$_StatUrl = 'http://addon.1314study.com/api/outer_addon.php?type=js&info='.$_statInfo.'&md5check='.$_md5Check;
if(preg_match("/^[a-z]+[a-z0-9_]*$/i", $identifier)){
	if(function_exists('cron_delete')) {
		cron_delete($identifier);
	}
	loadcache('pluginlanguage_install', 1);
	if(!empty($_G['cache']['pluginlanguage_install']) && isset($_G['cache']['pluginlanguage_install'][$identifier])) {
		unset($_G['cache']['pluginlanguage_install'][$identifier]);
		savecache('pluginlanguage_install', $_G['cache']['pluginlanguage_install']);
	}
	cloudaddons_uninstall($identifier.'.plugin', DISCUZ_ROOT.'./source/plugin/'.$identifier);
}
C::t('common_syscache')->delete('scache_'.$pluginarray['plugin']['identifier']);

cpmsg('plugins_delete_succeed', $_StatUrl, 'succeed');