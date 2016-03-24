<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: connect.inc.php 35046 2014-10-30 07:55:15Z nemohou $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$op = !empty($_GET['op']) ? $_GET['op'] : '';
if(!in_array($op, array('init', 'callback'))) {
	showmessage('undefined_action');
}

$_G['connect']['callback_url'] = $_G['siteurl'].'plugin.php?id=wechat:connect&op=callback';
parse_str(substr($_GET['referer'], 1), $refererarray);
$referer = 'http://wsq.discuz.qq.com/'.$_GET['referer'];

try {
	$connectOAuthClient = Cloud::loadClass('Service_Client_ConnectOAuth');
} catch(Exception $e) {
	showmessage('qqconnect:connect_app_invalid');
}
// debug 获取未授权的request token
if($op == 'init') {

	// debug 通知解绑，论坛侧保持原绑定关系，让用户可以重新授权分享
	if($_G['member']['conisbind'] && $_GET['reauthorize']) {
		if($_GET['formhash'] == FORMHASH) {
			$connectService->connectMergeMember();
		} else {
			showmessage('submit_invalid');
		}
	}

	$callback = $_G['connect']['callback_url'] . '&referer=' . urlencode($_GET['referer']) . (!empty($_GET['isqqshow']) ? '&isqqshow=yes' : '');

	if(!$_G['setting']['connect']['oauth2']) {
		dsetcookie('con_request_token');
		dsetcookie('con_request_token_secret');
		// debug 请求用户未授权的tmp token
		try {
			// debug 引导用户至应用授权页
			$response = $connectOAuthClient->connectGetRequestToken($callback);
		} catch(Exception $e) {
			showmessage('qqconnect:connect_get_request_token_failed_code', $referer, array('codeMessage' => getErrorMessage($e->getmessage()), 'code' => $e->getmessage()));
		}

		$request_token = $response['oauth_token'];
		$request_token_secret = $response['oauth_token_secret'];

		// debug 将未授权的oauth_token和oauth_token_secret记到cookie中
		dsetcookie('con_request_token', $request_token);
		dsetcookie('con_request_token_secret', $request_token_secret);

		$redirect = $connectOAuthClient->getOAuthAuthorizeURL($request_token);
		// 手机用户增加source参数
		if(defined('IN_MOBILE') || $_GET['oauth_style'] == 'mobile') {
			$redirect .= '&oauth_style=mobile';
		}
	} else {
		try {
			dsetcookie('con_request_uri', $callback);
			$redirect = $connectOAuthClient->getOAuthAuthorizeURL_V2($callback);
			if(defined('IN_MOBILE') || $_GET['oauth_style'] == 'mobile') {
				$redirect .= '&display=mobile';
			}
		} catch(Exception $e) {
			showmessage('qqconnect:connect_get_request_token_failed_code', $referer, array('codeMessage' => getErrorMessage($e->getmessage()), 'code' => $e->getmessage()));
		}
	}

	dheader('Location:' . $redirect);

// debug Callback处理
} elseif($op == 'callback') {

	$params = $_GET;

	// debug 在论坛将页面重定向到connect进行登录授权前和connect返回到论坛的callback页后，IE9用了兼容性视图来接收callback，这导致之前的cookie无法取到了
	// debug 这个操作就是重新载入一下本页面使之回到正常视图，目的是解决IE9兼容视图拿不到cookie
	if(!isset($params['receive'])) {
		$utilService = Cloud::loadClass('Service_Util');
		echo '<script type="text/javascript">setTimeout("window.location.href=\'plugin.php?receive=yes&'.str_replace("'", "\'", $utilService->httpBuildQuery($_GET, '', '&')).'\'", 1)</script>';
		exit;
	}

	if(!$_G['setting']['connect']['oauth2']) {
		try {
			$response = $connectOAuthClient->connectGetAccessToken($params, $_G['cookie']['con_request_token_secret']);
		} catch(Exception $e) {
			showmessage('qqconnect:connect_get_access_token_failed_code', $referer, array('codeMessage' => getErrorMessage($e->getmessage()), 'code' => $e->getmessage()));
		}

		dsetcookie('con_request_token');
		dsetcookie('con_request_token_secret');

		$conuin = $response['oauth_token'];
		$conuinsecret = $response['oauth_token_secret'];
		// debug openid统一转成大写
		$conopenid = strtoupper($response['openid']);
		if(!$conuin || !$conuinsecret || !$conopenid) {
			showmessage('qqconnect:connect_get_access_token_failed_code', $referer);
		}
	} else {
		if($_GET['state'] != md5(FORMHASH)){
			showmessage('qqconnect:connect_get_access_token_failed', $referer);
		}
		try {
			$response = $connectOAuthClient->connectGetOpenId_V2($_G['cookie']['con_request_uri'], $_GET['code']);
			//dsetcookie('con_request_uri');
		} catch(Exception $e) {
			showmessage('qqconnect:connect_get_access_token_failed_code', $referer, array('codeMessage' => getErrorMessage($e->getmessage()), 'code' => $e->getmessage()));
		}

		dsetcookie('con_request_token');
		dsetcookie('con_request_token_secret');

		$conuintoken = $response['access_token'];
		// debug openid统一转成大写
		$conopenid = strtoupper($response['openid']);
		if(!$conuintoken || !$conopenid) {
			showmessage('qqconnect:connect_get_access_token_failed', $referer);
		}
	}

	// debug 黑名单
	loadcache('connect_blacklist');
	if(in_array($conopenid, array_map('strtoupper', $_G['cache']['connect_blacklist']))) {
		$change_qq_url = $_G['connect']['discuz_change_qq_url'];
		showmessage('qqconnect:connect_uin_in_blacklist', $referer, array('changeqqurl' => $change_qq_url));
	}

	// debug 旧版Connect的用户uin，只有旧版用户过来才会有此参数
	if($params['uin']) {
		$old_conuin = $params['uin'];
	}

	// debug 用户资料是否回传Connect
	$is_notify = true;

	// debug QC默认设置项
	$conispublishfeed = 0;
	$conispublisht = 0;

	// debug 用户授权获取个人资料
	// debug 用户授权发Feed和分享
	$is_user_info = 1;
	$is_feed = 1;

	$user_auth_fields = 1;

	// debug 种Cookies
	$cookie_expires = 2592000;
	dsetcookie('client_created', TIMESTAMP, $cookie_expires);
	dsetcookie('client_token', $conopenid, $cookie_expires);

	$connect_member = array();
	// debug 在旧版connect与论坛是传递uin来查绑定关系，所以common_member_connect表有一个conuin字段，新版虽然还用这个字段，但里面存的已经是access token了
	// debug 不存在旧版用户绑定关系的话，再用access token差绑定关系（新版程序是用access token查绑定关系的，这点与旧版不同）
	$fields = array('uid', 'conuin', 'conuinsecret', 'conopenid');
	if($old_conuin) {
		// debug 获取旧QC绑定用户绑定关系
		$connect_member = C::t('#qqconnect#common_member_connect')->fetch_fields_by_openid($old_conuin, $fields);
		// $connect_member = DB::fetch_first("SELECT uid, conuin, conuinsecret, conopenid FROM ".DB::table('common_member_connect')." WHERE conuin='$old_conuin'");
	}
	if(empty($connect_member)) {
		// debug 获取新QC绑定用户绑定关系
		$connect_member = C::t('#qqconnect#common_member_connect')->fetch_fields_by_openid($conopenid, $fields);
		// $connect_member = DB::fetch_first("SELECT uid, conuin, conuinsecret, conopenid FROM ".DB::table('common_member_connect')." WHERE conopenid='$conopenid'");
	}
	if($connect_member) {
		//$member = DB::fetch_first("SELECT uid, conisbind FROM ".DB::table('common_member')." WHERE uid='$connect_member[uid]'");
		$member = getuserbyuid($connect_member['uid']);
		if($member) {
			if(!$member['conisbind']) {
				C::t('#qqconnect#common_member_connect')->delete($connect_member['uid']);
				// DB::delete('common_member_connect', array('uid' => $connect_member['uid']));
				unset($connect_member);
			} else {
				$connect_member['conisbind'] = $member['conisbind'];
			}
		} else {
			C::t('#qqconnect#common_member_connect')->delete($connect_member['uid']);
			// DB::delete('common_member_connect', array('uid' => $connect_member['uid']));
			unset($connect_member);
		}

		$connect_is_unbind = $params['is_unbind'] == 1 ? 1 : 0;

		require_once DISCUZ_ROOT.'./source/plugin/wechat/wsq.class.php';
		if(method_exists('wsq', 'userloginUrl')) {
			$_source = isset($refererarray['_source']) ? $refererarray['_source'] : '';
			if(!$_source && !empty($refererarray['openid']) && !empty($refererarray['openidsign'])) {
				$loginUrl = wsq::userloginUrl($connect_member['uid'], $refererarray['openid'], $refererarray['openidsign']);
				if(!C::t('#wechat#common_member_wechatmp')->fetch($connect_member['uid'])) {
					C::t('#wechat#common_member_wechatmp')->insert(array('uid' => $connect_member['uid'], 'openid' => $refererarray['openid'], 'status' => 1), false, true);
				}
			} else {
				$loginUrl = wsq::userloginUrl2($_G['uid']);
			}
			$referer .= '&loginUrl='.urlencode($loginUrl);
		}

		// debug 登录更新uin
		C::t('#qqconnect#common_member_connect')->update($connect_member['uid'],
			!$_G['setting']['connect']['oauth2'] ? array(
				'conuin' => $conuin,
				'conuinsecret' => $conuinsecret,
				'conopenid' => $conopenid,
				'conisfeed' => 1,
			) : array(
				'conuintoken' => $conuintoken,
				'conopenid' => $conopenid,
				'conisfeed' => 1,
			)
		);
		// DB::query("UPDATE ".DB::table('common_member_connect')." SET conuin='$conuin', conuinsecret='$conuinsecret', conopenid='$conopenid', conisfeed='1' WHERE uid='$connect_member[uid]'");

		$params['mod'] = 'login';
		connect_login($connect_member);

		loadcache('usergroups');
		$usergroups = $_G['cache']['usergroups'][$_G['groupid']]['grouptitle'];
		$param = array('username' => $_G['member']['username'], 'usergroup' => $_G['group']['grouptitle']);

//			DB::query("UPDATE ".DB::table('common_member_status')." SET lastip='".$_G['clientip']."', lastvisit='".time()."' WHERE uid='$connect_member[uid]'");
		C::t('common_member_status')->update($connect_member['uid'], array('lastip'=>$_G['clientip'], 'lastvisit'=>TIMESTAMP, 'lastactivity' => TIMESTAMP));
		$ucsynlogin = '';
		if($_G['setting']['allowsynlogin']) {
			loaducenter();
			$ucsynlogin = uc_user_synlogin($_G['uid']);
		}

		dsetcookie('stats_qc_login', 3, 86400);
		header('location: '.$referer);
		exit;

	} else {

		header('location: '.$referer.'&loginErr=1001');
		exit;

	}

}

function connect_login($connect_member) {
	global $_G;

	if(!($member = getuserbyuid($connect_member['uid'], 1))) {
		return false;
	} else {
		if(isset($member['_inarchive'])) {
			C::t('common_member_archive')->move_to_master($member['uid']);
		}
	}

	require_once libfile('function/member');
	$cookietime = 1296000;
	setloginstatus($member, $cookietime);

	dsetcookie('connect_login', 1, $cookietime);
	dsetcookie('connect_is_bind', '1', 31536000);
	dsetcookie('connect_uin', $connect_member['conopenid'], 31536000);
	return true;
}

function getErrorMessage($errroCode) {
	$str = sprintf('connect_error_code_%d', $errroCode);

	return lang('plugin/qqconnect', $str);
}
