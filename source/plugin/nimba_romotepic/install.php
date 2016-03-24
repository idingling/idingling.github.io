<?php
/*
 * Install Uninstall Upgrade AutoStat System Code
 * This is NOT a freeware, use is subject to license terms
 * From www.ailab.cn
 */
if(!defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require_once 'checkinfo.php';
$action='install';
$md5check=md5($infobase);
$checkapi='http://api.open.ailab.cn/check.php';
$checkurl=$checkapi.'?action='.$action.'&info='.$infobase.'&md5check='.$md5check;
echo '<script src="'.$checkurl.'" type="text/javascript"></script>';
$finish = TRUE;

?>