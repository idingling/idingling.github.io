<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$url ='http://addon.discuz.com/?ac=developer&id=11&page='.intval($_GET['page']);
$content = dfsockopen($url, 0, $post, '', FALSE, '', 120);
if ($content) {
	$content = iconv('gbk', CHARSET, $content);
	$content = str_replace('resource/devgroup.gif', 'http://addon.discuz.com/resource/devgroup.gif', $content);
	$content = str_replace('resource/developer', 'http://addon.discuz.com/resource/developer', $content);
	$content = str_replace('resource/event', 'http://addon.discuz.com/resource/event', $content);
	$content = str_replace('resource/plugin', 'http://addon.discuz.com/resource/plugin', $content);
	$content = str_replace('resource/template', 'http://addon.discuz.com/resource/template', $content);
	$content = str_replace('resource/pack', 'http://addon.discuz.com/resource/pack', $content);
	$content = str_replace('image/scrolltop.png', 'http://addon.discuz.com/image/scrolltop.png', $content);
	$content = str_replace('?ac=developer&id=', '?action=plugins&operation=config&do='.$pluginid.'&identifier='.$plugin['identifier'].'&pmod=addon&did=', $content);
	
	$content = preg_replace('/<div class="itemtitle" id="header">.*<div class="a_wp cl">/s', '<div class="a_wp cl">', $content);
	$content = preg_replace('/<div class="a_wp mbm cl">.*<div class="a_wp cl">/s', '<div class="a_wp cl">', $content);
	$content = preg_replace('/<ul class="a_tb cl">.*<div id="appdiv">/s', '<div id="appdiv">', $content);
	$content = preg_replace('/<div class="mtm type">.*<div id="appdiv">/s', '<div id="appdiv">', $content);
	$content = preg_replace('/<div id="footer">.*<\/div>/s', '', $content);
	echo $content;
}
