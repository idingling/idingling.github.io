<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if($_GET['formhash'] != FORMHASH) {
	showmessage('undefined_action', NULL);
}

if(!$_G['uid']){
	include template('common/header_ajax');
	echo lang('plugin/k_misign', 'groupontallow');
	include template('common/footer_ajax');
	exit();
}

if(!in_array($_G['groupid'], $setting['groups'])){
	include template('common/header_ajax');
	echo lang('plugin/k_misign', 'groupontallow');
	include template('common/footer_ajax');
	exit();
}
if($_G['uid'] && in_array($_G['uid'],$setting['ban'])){
	include template('common/header_ajax');
	echo lang('plugin/k_misign', 'uidinblack');
	include template('common/footer_ajax');
	exit();
}
if($qiandaodb['time'] > $tdtime){
	include template('common/header_ajax');
	echo lang('plugin/k_misign', 'tdyq');
	include template('common/footer_ajax');
	exit();
}

if($setting['lockopen']){
	while(discuz_process::islocked('k_misign', 5)){
		usleep(100000);
	}
}

if(!$qiandaodb['uid']) {
	C::t("#k_misign#plugin_k_misign")->insert(array('uid' => $_G['uid'], 'time' => $_G['timestamp']));
}

$row = $num + 1;
if($num == '0' || !$num || $num == 0){
	$isfirst = 1;
	C::t("#k_misign#plugin_k_misign_prize")->update_everyday();
}

$ajaxcreditshow['normal']['type'] = $setting['nrcredit'];
$ajaxcreditshow['normal']['number'] = mt_rand($setting['mincredit'],$setting['maxcredit']);

if(($tdtime - $qiandaodb['time']) < 86400)$islasted = 1;
if(!$islasted){//补签记录
	$bqlasttime = gmmktime(0,0,0,dgmdate($qiandaodb['time'], 'n'),dgmdate($qiandaodb['time'], 'j'),dgmdate($qiandaodb['time'], 'Y'));
	if($bqlasttime)C::t("#k_misign#plugin_k_misign_bq")->insert(array('uid' => $_G['uid'], 'lasttime'=> $bqlasttime, 'thistime' => $tdtime, 'bqdays' => $qiandaodb['lasted']));
}

updatemembercount($_G['uid'], array($setting['nrcredit'] => $ajaxcreditshow['normal']['number']));
require_once libfile('function/post');
require_once libfile('function/forum');
if($row <= $extreward_num) {
	list($exacr,$exacz) = explode("|", $extreward[$num]);
	if($exacr && $exacz){
		updatemembercount($_G['uid'], array($exacr => $exacz));
		$ajaxcreditshow['ext']['type'] = $exacr;
		$ajaxcreditshow['ext']['number'] = $exacz;
		if($ajaxcreditshow['ext']['type'] == $ajaxcreditshow['normal']['type']){
			$ajaxcreditshow['normal']['number'] = $ajaxcreditshow['ext']['number'] + $ajaxcreditshow['normal']['number'];
		}
	}
}
$hft = dgmdate($_G['timestamp'], 'Y-m-d H:i',$setting['tos']);

if(memory('check')) memory('set', 'k_misign', $_G['timestamp'], 86400);
if($isfirst || $row == '1') {
	if($stats['todayq'] > $stats['highestq']){
		C::t("#k_misign#plugin_k_misignset")->update(1, array('highestq' => $stats['todayq'], 'yesterdayq' => $stats['todayq'], 'todayq' => 1));
	}else{
		C::t("#k_misign#plugin_k_misignset")->update(1, array('yesterdayq' => $stats['todayq'], 'todayq' => 1));
	}
} else {
	C::t("#k_misign#plugin_k_misignset")->increase_todayq();
}


if($setting['prizestatus']){
	$prizelist = C::t("#k_misign#plugin_k_misign_prize")->fetch_all_by_status();
	$prizenumber = count($prizelist);
	$lucky_range = $made_num = 0;
	if($setting['prizetype'] == 1){//难度升级模式
		$maxpercent = $prizenumber * 1000;
		$luck_num = mt_rand(0, $maxpercent);
		foreach($prizelist as $prize){
			$prob = intval($prize['percent']);
			if($luck_num >=  $made_num && $luck_num < $made_num+$prob){
				$myprize = $prize;
				break;
			}
			$made_num = $made_num + 1000;
		}
	}else{
		foreach($prizelist as $prize){
			$luck_num = mt_rand(0, 1000);
			$prob = intval($prize['percent']);
			if($luck_num >=  0 && $luck_num < $prob){
				$myprize = $prize;
				break;
			}
		}
	}
	if($myprize){
		if($myprize['prizetype'] == 2){//积分奖品处理流程
			updatemembercount($_G['uid'], array($myprize['prizecredittype'] => $myprize['prizecreditnum']));
		}elseif($myprize['prizetype'] == 3){//卡密奖品处理流程
			$kami = C::t("#k_misign#plugin_k_misign_prize_kami")->fetch_by_prizeid($myprize['prizeid']);
			notification_add($_G['uid'], 'k_misign', lang('plugin/k_misign', 'prize_kami_notice').$myprize['prizename'].'(&nbsp;'.$kami['message'].'&nbsp;)');
			C::t("#k_misign#plugin_k_misign_prize_kami")->update($kami['kid'], array('uid' => $_G['uid'], 'username' => $_G['username'], 'usetime' => $_G['timestamp']));
		}else{
			notification_add($_G['uid'], 'k_misign', lang('plugin/k_misign', 'prize_kami_notice').$myprize['prizename']);
		}
		C::t("#k_misign#plugin_k_misign_prize_log")->insert(array('uid' => $_G['uid'], 'username' => $_G['username'], 'prizeid' => $myprize['prizeid'], 'prizename' => $myprize['prizename'], 'dateline' => $_G['timestamp']));
		C::t("#k_misign#plugin_k_misign_prize")->update_by_todaylast($myprize['prizeid'], $myprize['todaylast']-1);
	}
}
if($islasted){
	$lastnum = $qiandaodb['lasted']+1;
}

if($setting['extends']['lastrule'] && $islasted){
	require_once DISCUZ_ROOT.'./source/plugin/k_misign/language/extend_lastrule.'.currentlang().'.php';
	$exlang['lastrule'] = $extendlang;
	$lastreward = C::t("#k_misign#plugin_k_misign_lastrule")->fetch_by_lastday($lastnum);
	$lastreward2 = C::t("#k_misign#plugin_k_misign_lastrule")->fetch_by_lastday($lastnum, true);
	if($lastreward){
		$lastedrewardmsg = lang('plugin/k_misign', 'reward_lasted', array('lasted' => $lastnum));
		$lastreward['reward'] =str_replace(array("\r\n", "\n", "\r"), '/hhf/', $lastreward['reward']);
		$lastreward['rewards'] = explode("/hhf/", $lastreward['reward']);
		unset($lastreward['reward']);
		$lastedrewardcredits = null;
		foreach($lastreward['rewards'] as $k => $v){
			if($k){
				$lastedrewardcredits .= ',&nbsp;';
			}
			list($lastreward['reward'][$k]['type'],$lastreward['reward'][$k]['number']) = explode("|", $v);
			$lastedrewardcredits .= $lastreward['reward'][$k]['number'].'&nbsp;'.$_G['setting']['extcredits'][$lastreward['reward'][$k]['type']]['unit'].$_G['setting']['extcredits'][$lastreward['reward'][$k]['type']]['title'];
			updatemembercount($_G['uid'], array($lastreward['reward'][$k]['type'] => $lastreward['reward'][$k]['number']));
		}
		if($lastreward['rewards']){
			$lastedrewardmsg .= lang('plugin/k_misign', 'reward_lasted_credit', array('credit' => $lastedrewardcredits));
		}
		if($lastreward['relatedmedal']){
			$medallist = C::t("forum_medal")->fetch_all_name_by_available(1);
			$mymedal = C::t("common_member_field_forum")->fetch($_G['uid']);
			$mymedals = explode("\t", $mymedal['medals']);
			foreach($mymedals as $key => $mymedalv){
				if(strpos($mymedalv, '|')){
					$mymedalvarr = explode("|", $mymedalv);
					$mymedalv2 = $mymedalvarr[0];
				}else{
					$mymedalv2 = $mymedalv;
				}
				$mymedallist[$key] = $mymedalv2;
			}
			if(!in_array($lastreward['relatedmedal'],$mymedallist)){
				if($lastreward['relatedmedaldate']){
					$medalarray = array_insert($mymedals,$lastreward['relatedmedal'].'|'.(TIMESTAMP + $lastreward['relatedmedaldate']*86400),0);
				}else{
					$medalarray = array_insert($mymedals,$lastreward['relatedmedal'],0);
				}
				$medalnew = implode("\t", $medalarray);
				C::t("common_member_field_forum")->update($_G['uid'], array('medals' => $medalnew));
				C::t("forum_medallog")->insert(array('uid' =>$_G['uid'], 'medalid' => $lastreward['relatedmedal'], 'dateline' => TIMESTAMP, 'expiration' => (TIMESTAMP + $lastreward['relatedmedaldate']*86400), 'status' => ($lastreward['relatedmedaldate'] ? 1 : 0)));
				$lastedrewardmsg .= lang('plugin/k_misign', 'reward_lasted_medal', array('medal' => $medallist[$lastreward['relatedmedal']]['name']));
			}
		}
		notification_add($_G['uid'], 'system', $lastedrewardmsg, array(), 1);
	}elseif($lastreward2){
		$lastedreward2msg = lang('plugin/k_misign', 'reward_lasted', array('lasted' => $lastnum));
		$lastreward2['reward'] =str_replace(array("\r\n", "\n", "\r"), '/hhf/', $lastreward2['reward']);
		$lastreward2['rewards'] = explode("/hhf/", $lastreward2['reward']);
		unset($lastreward2['reward']);
		$lastedreward2credits = null;
		foreach($lastreward2['rewards'] as $k => $v){
			if($k){
				$lastedreward2credits .= ',&nbsp;';
			}
			list($lastreward2['reward'][$k]['type'],$lastreward2['reward'][$k]['number']) = explode("|", $v);
			$lastedreward2credits .= $lastreward2['reward'][$k]['number'].'&nbsp;'.$_G['setting']['extcredits'][$lastreward2['reward'][$k]['type']]['unit'].$_G['setting']['extcredits'][$lastreward2['reward'][$k]['type']]['title'];
			updatemembercount($_G['uid'], array($lastreward2['reward'][$k]['type'] => $lastreward2['reward'][$k]['number']));
		}
		if($lastreward2['rewards']){
			$lastedreward2msg .= lang('plugin/k_misign', 'reward_lasted_credit', array('credit' => $lastedreward2credits));
		}
		notification_add($_G['uid'], 'system', $lastedreward2msg, array(), 1);
	}
}

if($setting['extends']['totalrule']){
	require_once DISCUZ_ROOT.'./source/plugin/k_misign/language/extend_totalrule.'.currentlang().'.php';
	$exlang['totalrule'] = $extendlang;
	$totalreward = C::t("#k_misign#plugin_k_misign_totalrule")->fetch_by_lastday($qiandaodb['days']+1);
	if($totalreward){
			$totalrewardmsg = lang('plugin/k_misign', 'yljqddays', array('days' => $qiandaodb['days']+1));
			$totalreward['reward'] =str_replace(array("\r\n", "\n", "\r"), '/hhf/', $totalreward['reward']);
			$totalreward['rewards'] = explode("/hhf/", $totalreward['reward']);
			unset($totalreward['reward']);
			$totalrewardcredits = null;
			foreach($totalreward['rewards'] as $k => $v){
				if($k){
					$totalrewardcredits .= ',&nbsp;';
				}
				list($totalreward['reward'][$k]['type'],$totalreward['reward'][$k]['number']) = explode("|", $v);
				$totalrewardcredits .= $totalreward['reward'][$k]['number'].'&nbsp;'.$_G['setting']['extcredits'][$totalreward['reward'][$k]['type']]['unit'].$_G['setting']['extcredits'][$totalreward['reward'][$k]['type']]['title'];
				updatemembercount($_G['uid'], array($totalreward['reward'][$k]['type'] => $totalreward['reward'][$k]['number']));
			}
			if($totalreward['rewards']){
				$totalrewardmsg .= lang('plugin/k_misign', 'reward_lasted_credit', array('credit' => $totalrewardcredits));
			}
			if($totalreward['relatedmedal']){
				$medallist = C::t("forum_medal")->fetch_all_name_by_available(1);
				$mymedal = C::t("common_member_field_forum")->fetch($_G['uid']);
				$mymedals = explode("\t", $mymedal['medals']);
				foreach($mymedals as $key => $mymedalv){
					if(strpos($mymedalv, '|')){
						$mymedalvarr = explode("|", $mymedalv);
						$mymedalv2 = $mymedalvarr[0];
					}else{
						$mymedalv2 = $mymedalv;
					}
					$mymedallist[$key] = $mymedalv2;
				}
				if(!in_array($totalreward['relatedmedal'],$mymedallist)){
					if($totalreward['relatedmedaldate']){
						$medalarray = array_insert($mymedals,$totalreward['relatedmedal'].'|'.(TIMESTAMP + $totalreward['relatedmedaldate']*86400),0);
					}else{
						$medalarray = array_insert($mymedals,$totalreward['relatedmedal'],0);
					}
					$medalnew = implode("\t", $medalarray);
					C::t("common_member_field_forum")->update($_G['uid'], array('medals' => $medalnew));
					C::t("forum_medallog")->insert(array('uid' =>$_G['uid'], 'medalid' => $totalreward['relatedmedal'], 'dateline' => TIMESTAMP, 'expiration' => (TIMESTAMP + $totalreward['relatedmedaldate']*86400), 'status' => ($totalreward['relatedmedaldate'] ? 1 : 0)));
					$totalrewardmsg .= lang('plugin/k_misign', 'reward_lasted_medal', array('medal' => $medallist[$totalreward['relatedmedal']]['name']));
				}
			}
			notification_add($_G['uid'], 'system', $totalrewardmsg, array(), 1);
	}
}

$credit = $ajaxcreditshow['normal']['number'];
if($setting['qdtype'] == '2') {
	$thread = C::t('forum_thread')->fetch($setting['tidnumber']);
	if($num >= 0 && ($row <= $extreward_num) && $exacr && $exacz) {
		$message = lang('plugin/k_misign', 'tsn_extrac', array('time' => $hft, 'row' => $row, 'extrac' => $_G['setting']['extcredits'][$setting['nrcredit']]['title'].' '.$credit.' '.$_G['setting']['extcredits'][$setting['nrcredit']]['unit'], 'extrac2' => $_G['setting']['extcredits'][$exacr]['title'].$exacz.$_G['setting']['extcredits'][$exacr]['unit']));
	} else {
		$message = lang('plugin/k_misign', 'tsn_noextrac', array('time' => $hft, 'extrac' => $_G['setting']['extcredits'][$setting['nrcredit']]['title'].' '.$credit.' '.$_G['setting']['extcredits'][$setting['nrcredit']]['unit']));
	}
	$pid = insertpost(array('fid' => $thread['fid'],'tid' => $setting['tidnumber'],'first' => '0','author' => $_G['username'],'authorid' => $_G['uid'],'subject' => '','dateline' => $_G['timestamp'],'message' => $message,'useip' => $_G['clientip'],'invisible' => '0','anonymous' => '0','usesig' => '0','htmlon' => '0','bbcodeoff' => '0','smileyoff' => '0','parseurloff' => '0','attachment' => '0',));
	C::t('forum_thread')->update($setting['tidnumber'], array('lastposter'=>$_G['username'],'lastpost'=>$_G['timestamp']));
	C::t('forum_thread')->increase($setting['tidnumber'], array('replies'=>1));
	updatepostcredits('+', $_G['uid'], 'reply', $thread['fid']);
	$lastpost = $thread['tid']."\t".addslashes($thread['subject'])."\t".$_G['timestamp']."\t".$_G['username'];
	C::t('forum_forum')->update($thread['fid'], array('lastpost' => $lastpost));
	C::t('forum_forum')->update_forum_counter($thread['fid'], 0, 1, 1);
	$tidnumber = $setting['tidnumber'];
}elseif($setting['qdtype'] == '3') {
	if($isfirst || $stats['qdtidnumber'] == '0') {
		$subject = str_replace(array('{m}','{d}','{y}','{bbname}'), array(dgmdate($_G['timestamp'], 'n',$setting['tos']),dgmdate($_G['timestamp'], 'j',$setting['tos']),dgmdate($_G['timestamp'], 'Y',$setting['tos']),$_G['setting']['bbname']), $setting['title_thread']);
		if($exacr && $exacz) {
			$message = lang('plugin/k_misign', 'tsn_thread').lang('plugin/k_misign', 'tsn_extrac', array('time' => $hft, 'row' => $row, 'extrac' => $_G['setting']['extcredits'][$setting['nrcredit']]['title'].' '.$credit.' '.$_G['setting']['extcredits'][$setting['nrcredit']]['unit'], 'extrac2' => $_G['setting']['extcredits'][$exacr]['title'].$exacz.$_G['setting']['extcredits'][$exacr]['unit']));
		} else {
			$message = lang('plugin/k_misign', 'tsn_thread').lang('plugin/k_misign', 'tsn_noextrac', array('time' => $hft, 'extrac' => $_G['setting']['extcredits'][$setting['nrcredit']]['title'].' '.$credit.' '.$_G['setting']['extcredits'][$setting['nrcredit']]['unit']));
		}
        $thread_param = array(
			'isgroup' => '0',
			'status' => '0',
			'closed' => '1',
			'highlight' => '1',
			'moderated' => '1',
			'attachment' => '0',
			'special' => '0',
			'digest' => '0',
			'displayorder' => '0',
			'lastposter' => $_G['username'],
			'lastpost' => $_G['timestamp'],
			'dateline' => $_G['timestamp'],
			'subject' => $subject,
			'authorid' => $_G['uid'],
			'author' => $_G['username'],
			'sortid' => '0',
			'typeid' => $setting['qdtypeid'],
			'price' => '0',
			'readperm' => '0',
			'posttableid' => '0',
			'fid' => $setting['fidnumber']
		);
		$tid = C::t("forum_thread")->insert($thread_param, true);
		C::t("#k_misign#plugin_k_misignset")->update('1', array('qdtidnumber'=>$tid));
		$pid = insertpost(array('fid' => $setting['fidnumber'],'tid' => $tid,'first' => '1','author' => $_G['username'],'authorid' => $_G['uid'],'subject' => $subject,'dateline' => $_G['timestamp'],'message' => $message,'useip' => $_G['clientip'],'invisible' => '0','anonymous' => '0','usesig' => '0','htmlon' => '0','bbcodeoff' => '0','smileyoff' => '0','parseurloff' => '0','attachment' => '0',));
		$expiration = $_G['timestamp'] + 86400;
		$threadmod_param1 = array(
			'tid' => $tid,
			'uid' => $_G['uid'],
			'username' => $_G['username'],
			'dateline' => $_G['timestamp'],
			'action' => 'EHL',
			'expiration' => $expiration,
			'status' => '1'
		);
		$threadmod_param2 = array(
			'tid' => $tid,
			'uid' => $_G['uid'],
			'username' => $_G['username'],
			'dateline' => $_G['timestamp'],
			'action' => 'CLS',
			'expiration' => '0',
			'status' => '1'
		);
		C::t('forum_threadmod')->insert($threadmod_param1);
		C::t('forum_threadmod')->insert($threadmod_param2);
		updatepostcredits('+', $_G['uid'], 'post', $setting['fidnumber']);
		$lastpost = $tid."\t".addslashes($subject)."\t".$_G['timestamp']."\t.".$_G['username'];
        C::t('forum_forum')->update($setting['fidnumber'], array('lastpost' => $lastpost));
        C::t('forum_forum')->update_forum_counter($setting['fidnumber'], 1, 1, 1);
		$tidnumber = $tid;
	} else {
		$tidnumber = $stats['qdtidnumber'];
		$thread = C::t('forum_thread')->fetch($tidnumber);
		if($num >=1 && ($row <= $extreward_num) && $exacr && $exacz) {
			$message = lang('plugin/k_misign', 'tsn_extrac', array('time' => $hft, 'row' => $row, 'extrac' => $_G['setting']['extcredits'][$setting['nrcredit']]['title'].' '.$credit.' '.$_G['setting']['extcredits'][$setting['nrcredit']]['unit'], 'extrac2' => $_G['setting']['extcredits'][$exacr]['title'].$exacz.$_G['setting']['extcredits'][$exacr]['unit']));
		} else {
			$message = lang('plugin/k_misign', 'tsn_noextrac', array('time' => $hft, 'extrac' => $_G['setting']['extcredits'][$setting['nrcredit']]['title'].' '.$credit.' '.$_G['setting']['extcredits'][$setting['nrcredit']]['unit']));
		}
		$pid = insertpost(array('fid' => $setting['fidnumber'],'tid' => $tidnumber,'first' => '0','author' => $_G['username'],'authorid' => $_G['uid'],'subject' => '','dateline' => $_G['timestamp'],'message' => $message,'useip' => $_G['clientip'],'invisible' => '0','anonymous' => '0','usesig' => '0','htmlon' => '0','bbcodeoff' => '0','smileyoff' => '0','parseurloff' => '0','attachment' => '0',));
		C::t('forum_thread')->update($tidnumber, array('lastposter'=>$_G['username'],'lastpost'=>$_G['timestamp']));
		C::t('forum_thread')->increase($tidnumber, array('replies'=>1));
		updatepostcredits('+', $_G['uid'], 'reply', $setting['fidnumber']);
		$lastpost = $tidnumber."\t".addslashes($thread['subject'])."\t".$_G['timestamp']."\t.".$_G['username'];
		C::t('forum_forum')->update($setting['fidnumber'], array('lastpost' => $lastpost));
		C::t('forum_forum')->update_forum_counter($setting['fidnumber'], 0, 1, 1);
	}
}

C::t("#k_misign#plugin_k_misign")->update_signdata($_G['uid'], $credit, $row, $islasted);
if($setting['lockopen']) discuz_process::unlock('k_misign');

if($inwsq){
	if(defined('IN_MOBILE')){
		include template('diy:k_misign_index', '', 'source/plugin/k_misign/template/'.($setting['styles']['mobile'] ? $setting['styles']['mobile'] : 'mobile_default'));
	}else{
		include template('diy:k_misign_index', '', 'source/plugin/k_misign/template/'.($setting['styles']['pc'] ? $setting['styles']['pc'] : 'default'));
	}
	exit();
}else{
	if($_GET['format'] == 'json'){
		
	}elseif($_GET['format'] == 'global_usernav_extra'){
		include template('common/header_ajax');
		echo '<a href="'.$setting['pluginurl'].'sign" id="k_misign_topb"><img id="fx_checkin_b" src="source/plugin/k_misign/static/mini2.gif" alt="'.lang('plugin/k_misign', 'tdyq').'" style="position:relative;top:5px;height:18px;"></a>';
		include template('common/footer_ajax');
	}elseif($_GET['format'] == 'empty'){
		include template('common/header_ajax');
		echo '';
		include template('common/footer_ajax');
	}else{
		include template('common/header_ajax');
		if($_GET['from'] == 'insign'){
			echo '';
		}elseif($_GET['from'] == 'wsq'){
			echo lang('plugin/k_misign', 'signed');
		}else{
			$lastnum = !$lastnum ? 1 : $lastnum;
			echo '<div class="midaben_con mbm"><a class="midaben_signpanel JD_sign visted" id="JD_sign" href="'.$setting['pluginurl'].'sign" target="_blank"><div class="font">'.lang('plugin/k_misign','signed').'</div><span class="nums">'.lang('plugin/k_misign','row').($lastnum ? $lastnum : '0').lang('plugin/k_misign','days').'</span><div class="fblock"><div class="all">'.($isfirst ? 1 : $stats['todayq']+1).lang('plugin/k_misign','people').'</div><div class="line">'.$row.'</div></div></a>
				<div id="JD_win" class="midaben_win JD_win" style="display:block;">
					<div class="title">
						<h3>
							'.lang('plugin/k_misign','signsuccess').'
						</h3>
						<p class="con">'.($ajaxcreditshow['ext']['type'] == $ajaxcreditshow['normal']['type'] ? lang('plugin/k_misign','getreward', array('reward' => $ajaxcreditshow['normal']['number'].$_G['setting']['extcredits'][$ajaxcreditshow['normal']['type']]['unit'].$_G['setting']['extcredits'][$ajaxcreditshow['normal']['type']]['title'])) : lang('plugin/k_misign','getreward2', array('reward' => $ajaxcreditshow['normal']['number'].$_G['setting']['extcredits'][$ajaxcreditshow['normal']['type']]['unit'].$_G['setting']['extcredits'][$ajaxcreditshow['normal']['type']]['title'], 'reward2' => $ajaxcreditshow['ext']['number'].$_G['setting']['extcredits'][$ajaxcreditshow['ext']['type']]['unit'].$_G['setting']['extcredits'][$ajaxcreditshow['ext']['type']]['title']))).'</p>
					</div>
					<div class="info">'.lang('plugin/k_misign','yljqddays', array('days' => ($qiandaodb['days'] ? $qiandaodb['days'] : 1))).'
					</div>
					<div class="angleA">
					</div>
					<div class="angleB">
					</div>
				</div>
				</div>
			';
		}
		include template('common/footer_ajax');
		exit();
	}
}
?>