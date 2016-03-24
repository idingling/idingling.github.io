<?php

/**
 *      [3800FY Tame!] (C)2011-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: magic_renamecard.php 24357 2013-09-28 02:39:00Z ZDS $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class magic_renamecard {

	var $version = '1.0';
	var $name = '&#x6539;&#x540D;&#x5361;';
	var $description = '&#x53EF;&#x4EE5;&#x4FEE;&#x6539;&#x6211;&#x4EEC;&#x7684;&#x7528;&#x6237;&#x540D;';
	var $price = '20';
	var $weight = '20';
	var $useevent = 1;
	var $targetgroupperm = false;
	var $copyright = '<a href="http://www.seox2.com" target="_blank">seo</a>';
	var $magic = array();
	var $parameters = array();
	function getsetting(&$magic) {
	}

	function setsetting(&$magicnew, &$parameters) {
	}

	function usesubmit() {
		global $_G;
		if(empty($_G['gp_newusername'])) {
			showmessage("&#x4F60;&#x672A;&#x586B;&#x5199;&#x65B0;&#x7684;&#x7528;&#x6237;&#x540D;!");
		}
		$newusername = trim($_G['gp_newusername']);
		if(strlen($newusername) < 3) {
  			showmessage('&#x586B;&#x5199;&#x7684;&#x65B0;&#x7528;&#x6237;&#x540D;&#x957F;&#x5EA6;&#x592A;&#x77ED;&#x4E86;');
		}
		if(strlen($newusername) > 15) {
  			showmessage('&#x586B;&#x5199;&#x7684;&#x65B0;&#x7528;&#x6237;&#x540D;&#x957F;&#x5EA6;&#x592A;&#x957F;&#x4E86;');
		}
		$guestexp = '\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
		$censorexp = '/^('.str_replace(array('\\*', "\r\n", ' '), array('.*', '|', ''), preg_quote(($censoruser = trim($censoruser)), '/')).')$/i'; 
		if(preg_match("/^\s*$|^c:\\con\\con$|[%,\*\"\s\t\<\>\&]|$guestexp/is", $newusername) || ($censoruser && @preg_match($censorexp, $newusername))) {
  			showmessage('profile_username_illegal');
		}

		$query = DB::query("SELECT uid FROM ".DB::table('ucenter_members')." WHERE username ='$newusername'");
			if(DB::num_rows($query)) {
  				showmessage('&#x8BE5;&#x7528;&#x6237;&#x540D;&#x5DF2;&#x7ECF;&#x88AB;&#x62A2;&#x7528;&#x4E86;!');
		} else {

		$tables = array(
			'ucenter_members' => array('id' => 'uid', 'name' => 'username'),

			'common_block' => array('id' => 'uid', 'name' => 'username'),
			'common_invite' => array('id' => 'fuid', 'name' => 'fusername'),
			'common_member' => array('id' => 'uid', 'name' => 'username'),
			'common_member_security' => array('id' => 'uid', 'name' => 'username'),
			'common_mytask' => array('id' => 'uid', 'name' => 'username'),
			'common_report' => array('id' => 'uid', 'name' => 'username'),

			'forum_thread' => array('id' => 'authorid', 'name' => 'author'),
			'forum_post' => array('id' => 'authorid', 'name' => 'author'),
			'forum_activityapply' => array('id' => 'uid', 'name' => 'username'),
			'forum_groupuser' => array('id' => 'uid', 'name' => 'username'),
			'forum_pollvoter' => array('id' => 'uid', 'name' => 'username'),
			'forum_postcomment' => array('id' => 'authorid', 'name' => 'author'),
			'forum_ratelog' => array('id' => 'uid', 'name' => 'username'),

			'home_album' => array('id' => 'uid', 'name' => 'username'),
			'home_blog' => array('id' => 'uid', 'name' => 'username'),
			'home_clickuser' => array('id' => 'uid', 'name' => 'username'),
			'home_docomment' => array('id' => 'uid', 'name' => 'username'),
			'home_doing' => array('id' => 'uid', 'name' => 'username'),
			'home_feed' => array('id' => 'uid', 'name' => 'username'),
			'home_feed_app' => array('id' => 'uid', 'name' => 'username'),
			'home_friend' => array('id' => 'fuid', 'name' => 'fusername'),
			'home_friend_request' => array('id' => 'fuid', 'name' => 'fusername'),
			'home_notification' => array('id' => 'authorid', 'name' => 'author'),
			'home_pic' => array('id' => 'uid', 'name' => 'username'),
			'home_poke' => array('id' => 'fromuid', 'name' => 'fromusername'),
			'home_share' => array('id' => 'uid', 'name' => 'username'),
			'home_show' => array('id' => 'uid', 'name' => 'username'),
			'home_specialuser' => array('id' => 'uid', 'name' => 'username'),
			'home_visitor' => array('id' => 'vuid', 'name' => 'vusername'),

			'portal_article_title' => array('id' => 'uid', 'name' => 'username'),
			'portal_comment' => array('id' => 'uid', 'name' => 'username'),
			'portal_topic' => array('id' => 'uid', 'name' => 'username'),
			'portal_topic_pic' => array('id' => 'uid', 'name' => 'username'),
		);

		foreach($tables as $table => $conf) {
			DB::query("UPDATE ".DB::table($table)." SET `$conf[name]`='$newusername' WHERE `$conf[id]`='$_G[uid]'");
		}


			usemagic($this->magic['magicid'], $this->magic['num']);
			updatemagiclog($this->magic['magicid'], '2', '1', '0', 0, 'uid', $_G['uid']);
			showmessage('&#x4FEE;&#x6539;&#x7528;&#x6237;&#x540D;&#x6210;&#x529F;!', dreferer(), array(), array('showdialog' => 1, 'locationtime' => 1));
		}
	}

	function show() {
		magicshowtype('top');
		magicshowsetting("&#x8BF7;&#x8F93;&#x5165;&#x4F60;&#x7684;&#x65B0;&#x7528;&#x6237;&#x540D;:", 'newusername','', 'text');
		magicshowtype('bottom');
	}


}

?>