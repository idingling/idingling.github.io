<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

echo str_replace('resource/template/','http://addon.discuz.com/resource/template/',str_replace('resource/developer/','http://addon.discuz.com/resource/developer/',str_replace('resource/plugin/','http://addon.discuz.com/resource/plugin/',file_get_contents('http://addon.discuz.com/?@wz'))));
?>