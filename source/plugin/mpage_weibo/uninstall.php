<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: uninstall.php 24473 2013-05-06 23:24:07Z mpage $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF
DROP TABLE IF EXISTS `pre_mpage_weibo`;
DROP TABLE IF EXISTS `pre_mpage_weibo_sync`;
EOF;

runquery($sql);

$finish = TRUE;