<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: login.inc.php 31649 2013-05-01 18:40:54Z mpage $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once DISCUZ_ROOT.'./source/plugin/mpage_weibo/config.php';
require_once DISCUZ_ROOT.'./source/plugin/mpage_weibo/saetv2.ex.class.php';

$o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
$code_url = $o->getAuthorizeURL(WB_CALLBACK_URL);

dheader("Location: $code_url");

?>