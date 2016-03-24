<?php
/**
 *      [kuozhan] (C)1998-2011 Zoeee Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp.inc.php 20814 2011-06-29 17:51 zoewho $
 */
 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$op = addslashes($_GET['op']);
$lang = $scriptlang['k_spider'];
if($op == 'deleteall') {
	$query = DB::query("TRUNCATE TABLE ".DB::table('k_spider')."");
	ajaxshowheader();
	echo $lang['sp_deleted'];
	ajaxshowfooter();
} elseif ($op == 'delete') {
	DB::delete('k_spider',array('id'=>intval($_GET['spider_id'])));
	ajaxshowheader();
	echo $lang['sp_deleted'];
	ajaxshowfooter();
} else {
	$perpage = 20;
	$page = max(1, intval($_GET['page']));
	$i=1;
	$resultempty = FALSE;
	$srchadd = $searchtext = $extra = '';
	if(!empty($_GET['srchspider'])) {
		$srchspider = addslashes($_GET['srchspider']);
		$extra = '&srchspider='.$srchspider;
		$srchadd = " AND spider like '$srchspider%'";
		$searchtext = $lang['sp_search'].' "'.$srchspider.'" ';
	}
	if(!empty($_GET['srchspip'])) {
		$srchspip = addslashes($_GET['srchspip']);
		$extra .= '&srchspip='.$srchspip;
		$srchadd .= " AND spider_ip like '$srchspip%'";
		$searchtext .= $lang['sp_search'].' "'.$srchspip.'" ';
	}

	if($searchtext) {
		$searchtext = $searchtext.'<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_spider&pmod=admincp">'.$lang['sp_viewall'].'</a>&nbsp';
	}


	showtableheader();
	showformheader('plugins&operation=config&do='.$pluginid.'&identifier=k_spider&pmod=admincp', 'srchsubmit');
	showsubmit('srchsubmit', $lang['sp_search'], $lang['sp_type'].': <select name="srchspider" >
	<option value="">All</option>
	<option value="AltaVista">AltaVista</option>
	<option value="360">360</option>
	<option value="Alexa">Alexa</option>
	<option value="AOL">AOL</option>
	<option value="Ask">Ask</option>
	<option value="Baidu">Baidu</option>
	<option value="Bing">Bing</option>
	<option value="DirectHit">DirectHit</option>
	<option value="Dmoz">Dmoz</option>
	<option value="Excite">Excite</option>Fast
	<option value="Fast">Fast</option>
	<option value="Google">Google</option>
	<option value="HuaweiSymantec">HuaweiSymantec</option>
	<option value="Iask">Iask</option>
	<option value="Infoseek">Infoseek</option>
	<option value="Jike">Jike</option>
	<option value="Lycos">Lycos</option>
	<option value="MJ12">MJ12</option>
	<option value="MSN">MSN</option>
	<option value="NorthernLight">NorthernLight</option>
	<option value="Sogou">Sogou</option>
	<option value="Sohu">Sohu</option>
	<option value="Soso">Soso</option>
	<option value="Tomato">Tomato</option>
	<option value="WebCrawler">WebCrawler</option>
	<option value="Yodao">Yodao</option>
	<option value="Yahoo">Yahoo!</option>
	<option value="Other">Other</option>
	</select>&nbsp;&nbsp;'.$lang['sp_ip'].': <input name="srchspip" value="'.addslashes(stripslashes($_GET['srchspip'])).'" class="txt" />', $searchtext);
	showformfooter();
	if(!$resultempty) {
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('k_spider')." WHERE 1 $srchadd");
		$query = DB::query("SELECT * FROM ".DB::table('k_spider')." WHERE 1 $srchadd ORDER BY id DESC LIMIT ".(($page - 1) * $perpage).",$perpage");

		echo '<tr class="header"><th>'.$lang['sp_type'].'</th><th>'.$lang['sp_ip'].'</th><th>'.$lang['sp_time'].'</th><th>'.$lang['sp_url'].'</th><th><a id="p'.$i.'" onclick="ajaxget(this.href, this.id, \'\');return false" href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_spider&pmod=admincp&op=deleteall">'.$lang['sp_deleteall'].'</a></th><th></th></tr>';
		while($data = DB::fetch($query)) {
			$i++;
			$data['spider_time'] = $data['spider_time'] ? dgmdate($data['spider_time']) : '';
			echo '<tr><td>'.$data['spider'].'</td>'.
				'<td>'.$data['spider_ip'].'</td>'.
				'<td>'.$data['spider_time'].'</td>'.
				'<td><a href='.$data['spider_url'].' target="_blank">'.$data['spider_url'].'</a></td>';
			echo '<td><a id="p'.$i.'" onclick="ajaxget(this.href, this.id, \'\');return false" href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=k_spider&pmod=admincp&op=delete&spider_id='.$data['id'].'">'.$lang['sp_delete'].'</a></td></tr>';
		}	
	}
}
showtablefooter();

echo multi($count, $perpage, $page, ADMINSCRIPT."?action=plugins&operation=config&do=$pluginid&identifier=k_spider&pmod=admincp$extra");
?>