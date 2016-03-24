<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$tid = getgpc("tid");
if(empty($tid)){
	$tid=1;
}

$tagurl = urlencode($_G['siteurl']."forum.php?mod=viewthread&tid=".$tid);
include template("micxp_wxshare:qrcode");