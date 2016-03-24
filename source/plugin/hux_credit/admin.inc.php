<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
loadcache('plugin');
$Plang = $scriptlang['hux_credit'];
$csetting = $_G['cache']['plugin']['hux_credit'];
$paymoney = 'extcredits'.$csetting['moneytype'];
$payhid = $csetting['hid'];
$paypass = $csetting['paypass'];
$param = array(
	'hid' => $payhid,
);

ksort($param);
$params = '';
foreach($param as $k => $v) {
	$params .= '&'.$k.'='.rawurlencode($v);
}

$params .= '&md5hash='.md5(substr($params, 1).$paypass);

$rrrrr = @implode('', file('http://api.k1cn.com/index.php?action=payurl&hid='.$payhid));
$r = @implode('', file('http://'.$rrrrr.'/plugin.php?id=hux_api:pay&action=getorder&'.substr($params, 1)));
$r = unserialize($r);
echo $jxlistad;
showtableheader();
echo '<tr class="header"><th>'.$Plang['zzorder'].'</th><th>'.$Plang['zzmoneynum'].'</th><th>UID</th><th>'.$Plang['zztime'].'</th><th>'.$Plang['zzop'].'</th></tr>';
foreach ($r as $v) {
	echo '<tr><td>'.$v['orderid'].'</td><td>'.$v['other'].'</td><td>'.$v['uid'].'</td><td>'.dgmdate($v['timestamp']).'</td><td><a href="plugin.php?id=hux_credit:payok&orderid='.$v['orderid'].'" target="_blank">'.$Plang['zzbd'].'</a></td></tr>';
}
showtablefooter();
?>