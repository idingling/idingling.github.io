<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$action = empty($_GET['action']) ? '' : dhtmlspecialchars(addslashes($_GET['action']));
$gpformhash = addslashes($_GET['formhash']);
$gppage = addslashes($_GET['page']);
$csetting = $_G['cache']['plugin']['hux_credit'];
$atclass[$action] = "class='a'";
$uid = $_G['uid'];
$username = $_G['username'];
$adminid = $_G['adminid'];
$closemsg = $csetting['closemsg'];
$payhid = $csetting['hid'];
$paypass = $csetting['paypass'];
if ($csetting['open'] == '0') {
	showmessage("$closemsg","index.php");
}

if ($action == 'buy') {
	if(empty($uid)) showmessage('to_login', 'member.php?mod=logging&action=login', array(), array('showmsg' => true, 'login' => 1));
	if(submitcheck('addsubmit')){
		$moneynum = intval(addslashes($_GET['money']));
		$moneymin =  ($csetting['moneymin'] > 1) ? $csetting['moneymin'] : 1;
		if ($moneynum < $moneymin || $moneynum > 99999 || $moneynum == '') {
			showmessage('hux_credit:zzmin_msg','index.php');
		}
		$moneyorderid = dgmdate(TIMESTAMP,'YmdHis').mt_rand(100,999);
		$param = array(
			'orderid' => $moneyorderid,
			'title' => lang('plugin/hux_credit','zzmsg'),
			'price' => $moneynum,
			'paytype' => 'hux_credit',
			'timestamp' => TIMESTAMP,
			'other' => $moneynum,
		);

		ksort($param);
		$params = '';
		foreach($param as $k => $v) {
			$params .= '&'.$k.'='.rawurlencode($v);
		}
		$params .= '&hid='.$payhid.'&uid='.$uid.'&charset='.CHARSET.'&md5hash='.md5(substr($params, 1).$paypass);
		$r = @implode('', file('http://api.k1cn.com/index.php?action=payurl&hid='.$payhid));
		dheader('location:http://'.$r.'/plugin.php?id=hux_api:hux_api&huxac=pay&'.substr($params, 1));
	}
}
include template('hux_credit:index');
?>