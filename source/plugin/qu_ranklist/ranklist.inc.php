<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      V1.0 QQ179667784
 *		WWW.DZZSK.COM
 *      POWERED BY QINGUI 
 */
 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$discuz = & discuz_core::instance();
$discuz->init();

$langvars=lang('plugin/qu_ranklist');
$vars=$_G['cache']['plugin']['qu_ranklist'];

$navtitle = lang('plugin/qu_ranklist', 'title');
$metakeywords = lang('plugin/qu_ranklist', 'keywords');
$metadescription = lang('plugin/qu_ranklist', 'description');

$qu_listnum = $vars['num'];
$i=1;
if($_GET['type'] == 'credits'){
    $sql = DB::query("SELECT * FROM ".DB::table('common_member')." ORDER BY credits DESC LIMIT $qu_listnum");
	while($result = DB::fetch($sql)){
		$ranklist[] = $result;
		if($result['uid'] == $_G['uid']){
			$now_pos = $i;
		}
		$i++;
	}
}
elseif($_GET['type'] == 'posts'){
    $sql = DB::query("SELECT * FROM ".DB::table('common_member')." as a inner join ".DB::table('common_member_count')." as b on a.uid = b.uid ORDER BY b.posts DESC LIMIT $qu_listnum");
    while($result = DB::fetch($sql)){
        $ranklist[] = $result;
		if($result['uid'] == $_G['uid']){
			$now_posts = $i;
		}
		$i++;
	}
	$myposts = DB::result_first("SELECT posts FROM ".DB::table('common_member_count')." WHERE uid =".$_G['uid']."");
}
elseif($_GET['type'] == 'threads'){
    $sql = DB::query("SELECT * FROM ".DB::table('common_member')." as a inner join ".DB::table('common_member_count')." as b on a.uid = b.uid ORDER BY b.threads DESC LIMIT $qu_listnum");
    while($result = DB::fetch($sql)){
        $ranklist[] = $result;
		if($result['uid'] == $_G['uid']){
			$now_threads = $i;
		}
		$i++;
	}
	$mythreads = DB::result_first("SELECT threads FROM ".DB::table('common_member_count')." WHERE uid =".$_G['uid']."");
}
elseif($_GET['type'] == 'friends'){
    $sql = DB::query("SELECT * FROM ".DB::table('common_member')." as a inner join ".DB::table('common_member_count')." as b on a.uid = b.uid ORDER BY b.friends DESC LIMIT $qu_listnum");
    while($result = DB::fetch($sql)){
		$ranklist[] = $result;
		if($result['uid'] == $_G['uid']){
			$now_friends = $i;
		}
		$i++;
	}
	$myfriends = DB::result_first("SELECT friends FROM ".DB::table('common_member_count')." WHERE uid =".$_G['uid']."");

}
else{
    $sql = DB::query("SELECT * FROM ".DB::table('common_member')." ORDER BY credits DESC LIMIT $qu_listnum");
	while($result = DB::fetch($sql)){
		$ranklist[] = $result;
		if($result['uid'] == $_G['uid']){
			$now_pos = $i;
		}
		$i++;
	}
}

include template('qu_ranklist:index');


?>