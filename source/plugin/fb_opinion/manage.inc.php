<?php

/**
 * manage members feedback opinion
 *
 * $Id: main.inc.php 2013-2-17 bing $
 * 
 * fb_opinion  statusֵ
 * 1 show 
 * 2  ignore
 * 0  check
 * -1 delete just a status 
 */

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit ('Access Denied');
}

$option = $_G['cache']['plugin']['fb_opinion'];
$is_multi = $option['is_multi'];
$is_pic = $option['is_pic'];

$pluginop = !empty ($_GET['pluginop']) ? $_GET['pluginop'] : 'list';
if (!in_array($pluginop, array ('list'))) {
	showmessage('undefined_action');
}
require_once libfile("function/profile");

if ($pluginop == 'list') {

	if (!submitcheck('submit')) {
		$page = intval($_G['page']);
		$page = empty ($page) ? 1 : $page;
		if ($page < 1){
			$page = 1;
		}
		
$searchctrl = '';
$searchctrl = '<span style="float: right; padding-right: 40px;">'
		.'<a href="javascript:;" onclick="$(\'tb_search\').style.display=\'\';$(\'a_search_show\').style.display=\'none\';$(\'a_search_hide\').style.display=\'\';" id="a_search_show" style="display:none">'.cplang('show_search').'</a>'
		.'<a href="javascript:;" onclick="$(\'tb_search\').style.display=\'none\';$(\'a_search_show\').style.display=\'\';$(\'a_search_hide\').style.display=\'none\';" id="a_search_hide">'.cplang('hide_search').'</a>'
		.'</span>';		
showsubmenu('',  array(), $searchctrl);
$adminscript = ADMINSCRIPT;
	$searchlang = array();
	$keys = array('search',  'perpage_10', 'perpage', 'perpage_20', 'perpage_50', 'perpage_100','uid','username');
	foreach ($keys as $key) {
		$searchlang[$key] = cplang($key);
	}
	$searchlang['status'] = lang('plugin/fb_opinion', 'status');

		$perpage = empty ($_GET['perpage']) ? 20 : intval($_GET['perpage']);
		if (!in_array($perpage, array (10,20,50,100))){
			$perpage = 20;
		}
		$perpages = array($perpage => ' selected');
		$start = ($page -1) * $perpage;
		
		$mpurl = ADMINSCRIPT . '?action=plugins&operation=config&do=' . $pluginid . '&identifier=fb_opinion&pmod=manage';
		$mpurl .= '&perpage=' . $perpage;

		$conditions = " 1 ";
                $status = '';
		if(isset($_GET['status']) && $_GET['status']!=='') {
			$status = Intval($_GET['status']);
			$conditions .= " AND status = '".$status."'";
			$mpurl .= '&status=' . $status;
		}
		if(isset($_GET['uid']) && Intval($_GET['uid'])) {
			$uid = Intval($_GET['uid']);
			$conditions .= " AND uid = '".$uid."'";
			$mpurl .= '&uid=' . $uid;
		}
		if(isset($_GET['username']) && trim($_GET['username'])) {
			$username = addslashes(trim($_GET['username']));
			$conditions .= " AND username = '".$username."'";
			$mpurl .= '&username=' . $username;
		}
		$statuss = array("$status" => ' selected');
		$statusopt = '<select name="status"><option value="">--all--</option><option value="0" '.$statuss[0].'>'.lang('plugin/fb_opinion', 'unverify').'</option><option value="1" '.$statuss[1].'>'.lang('plugin/fb_opinion', 'show').'</option><option value="2" '.$statuss[2].'>'.lang('plugin/fb_opinion', 'hide').'</option></select>';
		echo <<<SEARCH
	<form method="get" autocomplete="off" action="$adminscript" id="tb_search">
		<div style="margin-top:8px;">
			<table cellspacing="3" cellpadding="3">
				<tr>
					<th>$searchlang[status]</th><td>$statusopt</td>
					<th>$searchlang[perpage]</th><td><select name="perpage">
						<option value="10"$perpages[10]>$searchlang[perpage_10]</option>
						<option value="20"$perpages[20]>$searchlang[perpage_20]</option>
						<option value="50"$perpages[50]>$searchlang[perpage_50]</option>
						<option value="100"$perpages[100]>$searchlang[perpage_100]</option>
						</select>
						</td>						
				</tr>
				<tr>
					<th>$searchlang[uid]</th><td><input type="text" name="uid" value="$uid"/></td>
					<th>$searchlang[username]</th><td><input type="text" name="username" value="$username"/><input type="hidden" name="action" value="plugins">
						<input type="hidden" name="operation" value="config">
						<input type="hidden" name="do" value="$pluginid">
						<input type="hidden" name="identifier" value="fb_opinion">
						<input type="hidden" name="pmod" value="manage">
						<input type="submit" name="searchsubmit" value="$searchlang[search]" class="btn"></td>
				</tr>
			</table>
		</div>
	</form>
SEARCH;
		$count = DB :: result_first("SELECT count(*) FROM " . DB :: table('fb_opinion'). " WHERE ". $conditions);

		$blogssql = DB :: query("SELECT * FROM " . DB :: table('fb_opinion') . " WHERE ". $conditions. " ORDER BY id DESC LIMIT $start,$perpage");

		showformheader('plugins&operation=config&do=' . $pluginid . '&identifier=fb_opinion&pmod=manage', 'submit');
		showtableheader(lang('plugin/fb_opinion', 'fb_opinion_list'));
		if (!empty ($count)) {
			showsubtitle(array (
				'',
				lang('plugin/fb_opinion', 'name'),
				lang('plugin/fb_opinion', 'username'),
				lang('plugin/fb_opinion', 'email'),
				lang('plugin/fb_opinion', 'phone'),
				lang('plugin/fb_opinion', 'reside'),
				lang('plugin/fb_opinion', 'message'),
				lang('plugin/fb_opinion', 'dateline'),
				lang('plugin/fb_opinion', 'updatetime'),
				lang('plugin/fb_opinion', 'status'),
                                lang('plugin/fb_opinion', 'isreply'),
                                lang('plugin/fb_opinion', 'replynum'). '['. lang('plugin/fb_opinion', 'needreplynum'). ']',
                                lang('plugin/fb_opinion', 'operation'),
			));

			while ($opinionsarr = DB :: fetch($blogssql)) {
				$opinionsarr[dateline] = date("Y-m-d H:i", $opinionsarr[dateline]);
				$opinionsarr[updatetime] = $opinionsarr[updatetime] ? date("Y-m-d H:i", $opinionsarr[updatetime]) : '';
				if($opinionsarr[status] == 0){
					$opinionsarr[status] = lang('plugin/fb_opinion', 'unverify');
				} elseif($opinionsarr[status] == 1){
					$opinionsarr[status] = lang('plugin/fb_opinion', 'show');
				} elseif($opinionsarr[status] == 2){
					$opinionsarr[status] = lang('plugin/fb_opinion', 'hide');
				} elseif($opinionsarr[status] == -1){
					$opinionsarr[status] = lang('plugin/fb_opinion', 'delete');
				}
                                list($opinionsarr[needreplynum], $opinionsarr[replynum]) = get_opinion_reply($opinionsarr[id]);
                                $opinionsarr[isreply] = $opinionsarr[isreply]? lang('plugin/fb_opinion', 'replied'): lang('plugin/fb_opinion', 'noreply');
				$opinionsarr['reside'] = profile_show('residecity', $opinionsarr);
				$opinionsarr['username'] = empty($opinionsarr['username']) ? lang('plugin/fb_opinion', 'youke') : '<a href="home.php?mod=space&uid='. $opinionsarr[uid]. '" target="_blank">'. $opinionsarr[username]. '</a>';				
                                showtablerow('', array ('width=20px','width=100px','width=100px','width=100px','width=100px','width=120px','width=200px','width=100px','width=100px','width=40px','width=100px'), array (
					"<input type=\"checkbox\" class=\"checkbox\" name=\"opinionids[]\" value=\"$opinionsarr[id]\">",
					$opinionsarr[name],
					$opinionsarr[username],
					$opinionsarr[email],
					$opinionsarr[phone],
					$opinionsarr[reside],
					$opinionsarr[message],
					$opinionsarr[dateline],
					$opinionsarr[updatetime],
					$opinionsarr[status],
                                        $opinionsarr[isreply],
                                        $opinionsarr[replynum]. '['. $opinionsarr[needreplynum]. ']',
                                        
                                        '<a href="plugin.php?id=fb_opinion:main&mod=reply&fbid='. $opinionsarr['id']. '" target="_blank">'. lang('plugin/fb_opinion', 'reply'). '</a>',
				));
			}

			$multipage = multi($count, $perpage, $page, $mpurl);
			$optypehtml = '' .
			'<input type="hidden" name="hiddenpage" id="hiddenpage" value="' . $page . '"/>' .
			'<input type="hidden" name="hiddenperpage" id="hiddenperpage" value="' . $perpage . '"/>' .
                        '<input id="optype_status" class="radio" type="radio" value="status" name="optype">'.
                        '<label for="optype_status">'. lang('plugin/fb_opinion','change_status'). '</label>'.
                        $statusopt.
                        '<input id="optype_status" class="radio" type="radio" value="delete" name="optype">'.
                        '<label for="optype_status">'. lang('plugin/fb_opinion','delete'). '</label>'.
			'&nbsp;&nbsp;';
			showsubmit('', '', '', '<input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll(\'prefix\', this.form, \'ids\')" /><label for="chkall">' . lang('plugin/fb_opinion', 'select_all') . '</label>&nbsp;&nbsp;' . $optypehtml . '<input type="submit" class="btn" name="submit" value="' . lang('plugin/fb_opinion', 'submit_url') . '" />', $multipage);
		}else{
			showtablerow('',array(),array (lang('plugin/fb_opinion', 'nodata')));
		}
		showtablefooter();
		showformfooter();

	} else {
                if(!$_POST['optype'] || !in_array($_POST['optype'], array('status', 'delete'))) {
                    cpmsg('article_choose_at_least_one_operation', $mpurl, 'false');
                }
		$perpage = intval($_POST['hiddenperpage']);
		$page = intval($_POST['hiddenpage']);
		$mpurl = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=fb_opinion&pmod=manage';
		$mpurl .= '&perpage=' . $perpage;
		if (!empty ($page)) {
			$mpurl .= '&page=' . $page;
		}
                
		$opinionids = !empty ($_POST['opinionids']) && is_array($_POST['opinionids']) ? $_POST['opinionids'] : cpmsg(lang('plugin/fb_opinion', 'option_choose_at_least_oneoption'), $mpurl, 'false');

		if ($_POST['optype'] == 'status') {
                        if(trim($_POST['status']) == '') {
                            cpmsg(lang('plugin/fb_opinion', 'status_choose_at_least_onestatus'), $mpurl, 'false');
                        }
                        $status = intval($_POST['status']);
			DB::update('fb_opinion', array ('updatetime' => time(),'status' => $status), 'id IN (' . dimplode($opinionids) . ')');
			cpmsg(lang('plugin/fb_opinion', 'status_succeed'), $mpurl, 'succeed');
		}
		if ($_POST['optype'] == 'delete') {
			DB::delete('fb_opinion', 'id IN (' . dimplode($opinionids) . ')');
                        DB::delete('fb_opinion_comment', 'fbid IN (' . dimplode($opinionids) . ')');                        
			cpmsg(lang('plugin/fb_opinion', 'deletesucceed'), $mpurl, 'succeed');
		}
	}
}

function get_opinion_reply($fbid) {
    if(!$fbid) {
        return '';
    }
    $needreplynum = DB::result_first("SELECT COUNT(1) FROM ". DB::table('fb_opinion_comment'). " WHERE fbid='$fbid' AND isreply='0' AND type='1'");
    $replynum = DB::result_first("SELECT COUNT(1) FROM ". DB::table('fb_opinion_comment'). " WHERE fbid='$fbid'");
    return array($needreplynum, $replynum);    
}
?>
