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

if(!$_G['uid']) {
	showmessage('not_loggedin', NULL, array(), array('login' => 1));
}

if(submitcheck("bindsubmit")) {
	C::t('#mpage_weibo#mpage_weibo')->update($_G['uid'], array(
		'thread' => $_GET['thread'],
		'reply' => $_GET['reply'],
		'follow' => $_GET['follow'],
		'blog' => $_GET['blog'],
		'doing' => $_GET['doing'],
		'share' => $_GET['share'],
		'article' => $_GET['article'],
	));

	showmessage('mpage_weibo:sync_succeed', 'home.php?mod=spacecp&ac=plugin&id=mpage_weibo:bind');
}

if(submitcheck("unbindsubmit")) {
	C::t('#mpage_weibo#mpage_weibo')->delete($_G['uid']);

	showmessage('mpage_weibo:unbind_succeed', 'home.php?mod=spacecp&ac=plugin&id=mpage_weibo:bind');
}

$bind = C::t('#mpage_weibo#mpage_weibo')->fetch($_G['uid']);
$bind['dateline'] = dgmdate($bind['dateline']);

?>