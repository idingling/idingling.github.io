<?php
/*
 * This is NOT a freeware, use is subject to license terms
 * From www.1314study.com ver 2.0
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
define('STUDYADDONS_FEEDBACK_URL', 'http://addon.1314study.com/feedback/index.php');
require_once ('pluginvar.func.php');
require_once DISCUZ_ROOT.'./source/discuz_version.php';
$data = 'pid='.$plugin[identifier].'&siteurl='.rawurlencode($_G['siteurl']).'&sitever='.DISCUZ_VERSION.'/'.DISCUZ_RELEASE.'&sitecharset='.CHARSET.'&pversion='.rawurlencode($plugin[version]);splugin_thinks($plugin['identifier'],0);
$param = 'data='.rawurlencode(base64_encode($data));
$param .= '&md5hash='.substr(md5($data.TIMESTAMP), 8, 8).'&timestamp='.TIMESTAMP;
s_addon_stat($plugin,'feedback');
?>
