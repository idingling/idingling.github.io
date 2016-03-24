<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: bind.inc.php 31649 2013-05-01 00:07:06Z mpage $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define("WB_AKEY" , $_G['cache']['plugin']['mpage_weibo']['app_key']);
define("WB_SKEY" , $_G['cache']['plugin']['mpage_weibo']['app_secret']);
define("WB_CALLBACK_URL" , $_G['siteurl'].'plugin.php?id=mpage_weibo:callback');

?>