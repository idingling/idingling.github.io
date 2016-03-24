<?php

//cronname:cron_name
//week: 
//day: 
//hour:1
//minute:1

/*计划任务文件*/
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include(DISCUZ_ROOT.'source/plugin/sitemap/sitemap.fun.php');
get_sitemap();
?>