<?php

/**
 * Copyright 2001-2099 1314学习网.
 * This is NOT a freeware, use is subject to license terms
 * $Id: updatexml.inc.php 3589 2016-02-09 17:29:08Z zhuge $
 * 应用售后问题：http://www.discuz.1314study.com/services.php?mod=issue
 * 应用售前咨询：QQ 15326940
 * 应用定制开发：QQ 643306797
 * 本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
 * 未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
exit('Access Denied');
}
set_time_limit(0);
include_once DISCUZ_ROOT.'/source/plugin/addon_baidu_search/source/class/baidu_search_xml.class.php';
echo '<link href="./source/plugin/addon_baidu_search/images/manage.css?'.VERHASH.'" rel="stylesheet" type="text/css" />';
loadcache('plugin');
$splugin_setting = $_G['cache']['plugin']['addon_baidu_search'];
$splugin_lang = lang('plugin/addon_baidu_search');
$study_single_num = $splugin_setting['study_single_num'] ? $splugin_setting['study_single_num'] : 2000;
$study_type = (array)unserialize($splugin_setting['study_type']);
if(in_array(1314, $study_type)){
	cpmsg($splugin_lang['slang_003']);
}

if(empty($_GET['formhash']) || $_GET['formhash'] != formhash()) {
		showtips('
		<li>&#x6253;&#x5F00;&#xFF1A;<a href="http://zn.baidu.com/cse/schema/sitemap" target="_blank">http://zn.baidu.com/cse/schema/sitemap</a>&#xFF0C;&#x9009;&#x62E9;&#x4F7F;&#x7528;&#x7684;&#x7F51;&#x7AD9;</li>
		<li>&#x70B9;&#x51FB;&#x201C;&#x6DFB;&#x52A0;&#x65B0;&#x6570;&#x636E;&#x201D;&#xFF0C;&#x6587;&#x4EF6;&#x7C7B;&#x578B;&#x9009;&#x62E9;&#x201C;&#x901A;&#x7528;&#x201D;&#xFF0C;&#x6570;&#x636E;&#x6587;&#x4EF6;&#x5730;&#x5740;&#x586B;&#x5199;&#xFF1A;'.$_G['siteurl'].'baidu_search_index.xml</li>
		'.($splugin_setting['study_thread_way'] == 2 ? '<li>&#x70B9;&#x51FB;&#x201C;&#x6DFB;&#x52A0;&#x65B0;&#x6570;&#x636E;&#x201D;&#xFF0C;&#x6587;&#x4EF6;&#x7C7B;&#x578B;&#x9009;&#x62E9;&#x201C;&#x8BBA;&#x575B;&#x201D;&#xFF0C;&#x6570;&#x636E;&#x6587;&#x4EF6;&#x5730;&#x5740;&#x586B;&#x5199;&#xFF1A;'.$_G['siteurl'].'baidu_search_forum.xml</li>' : '').'
		');
		$count = countFile();
		if($count > 0){
			echo '<div class="tip_div">&#x76EE;&#x524D;&#x751F;&#x6210;&#x4E86; '.$count.' &#x4E2A;&#x6570;&#x636E;&#x7D22;&#x5F15;&#x6587;&#x4EF6;&#xFF0C;&#x5927;&#x7EA6; '.($count*$study_single_num).' &#x6761;&#x6570;&#x636E;</div>';
		}
		
		showformheader('plugins&operation=config&do='.$pluginid.'&identifier=addon_baidu_search&pmod=updatexml&formhash='.$_G['formhash']);
		showtableheader();
		showsubmit('submit', $splugin_lang['slang_002']);
		showtablefooter();
		showformfooter();
	
}else{
		if(submitcheck('submit')){
			delFileCache();
			$url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=addon_baidu_search&pmod=updatexml&formhash=' . $_G['formhash'];
            cpmsg('&#x6B63;&#x5728;&#x751F;&#x6210;XML', $url, 'loading');
		}
		$addon_baidu_search = new baidu_search_xml(1314);
		cpmsg($splugin_lang['slang_001'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=addon_baidu_search&pmod=updatexml', 'succeed');
}


function delFileCache(){
    $dirName = DISCUZ_ROOT . './data/baidu_search';
    if ($handle = opendir("$dirName")) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                if (!is_dir("$dirName/$item") && strstr($item, 'baidu_search_xml_')) {
                    @unlink("$dirName/$item");
                }
            }
        }
        closedir($handle);
    }
}
function countFile(){
		$count = 0;
    $dirName = DISCUZ_ROOT.'./data/baidu_search';
    if ($handle = opendir("$dirName")) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                if (!is_dir("$dirName/$item") && strstr($item, 'baidu_search_xml_')) {
                    $count++;
                }
            }
        }
        closedir($handle);
    }
    return $count;
}

//Copyright 2001-2099 1314学习网.
//This is NOT a freeware, use is subject to license terms
//$Id: updatexml.inc.php 4045 2016-02-09 09:29:08Z zhuge $
//应用售后问题：http://www.discuz.1314study.com/services.php?mod=issue
//应用售前咨询：QQ 15326940
//应用定制开发：QQ 643306797
//本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
//未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。