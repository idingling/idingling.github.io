<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * From www.1314study.com
 */
 
//cronname:cronname
//hour:6

if(!defined('IN_DISCUZ')) {
		exit('Access Denied');
}

@set_time_limit(0);
include_once DISCUZ_ROOT.'/source/plugin/addon_baidu_search/source/class/baidu_search_xml.class.php';
loadcache('plugin');
$splugin_setting = $_G['cache']['plugin']['addon_baidu_search'];
if($splugin_setting['study_auto']){
		$addon_baidu_search = new baidu_search_xml();
}
?>