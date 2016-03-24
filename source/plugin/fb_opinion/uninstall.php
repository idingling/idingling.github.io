<?php

/**
 *
 *      $Id: uninstall.php  2013-9-10  bing$
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE IF EXISTS pre_fb_opinion;
DROP TABLE IF EXISTS pre_fb_opinion_comment;

EOF;

runquery($sql);

$finish = TRUE;
