<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$csetting = $_G['cache']['plugin']['hux_credit'];
$paymoney = 'extcredits'.$csetting['moneytype'];
$payhid = $csetting['hid'];
$paypass = $csetting['paypass'];
$orderid = addslashes($_GET['orderid']);
$param = array(
	'hid' => $payhid,
	'orderid' => $orderid,
);

ksort($param);
$params = '';
foreach($param as $k => $v) {
	$params .= '&'.$k.'='.rawurlencode($v);
}

$params .= '&md5hash='.md5(substr($params, 1).$paypass);

$rrrrr = @implode('', file('http://api.k1cn.com/index.php?action=payurl&hid='.$payhid));
$r = @implode('', file('http://'.$rrrrr.'/plugin.php?id=hux_api:pay&action=getresult&'.substr($params, 1)));
$paystatus = explode(',',$r);
if ($paystatus[0] == '1') {
	$jfnum = intval($paystatus[2] * $csetting['moneybl']);
	updatemembercount($paystatus[1] , array($paymoney => $jfnum), 1, 'AFD', $paystatus[1]);
	notification_add($paystatus[1],'system',lang('plugin/hux_credit','zz_sus'),0,1);
}
showmessage('hux_credit:zz_sus','home.php?mod=spacecp&ac=credit&op=base');
?>