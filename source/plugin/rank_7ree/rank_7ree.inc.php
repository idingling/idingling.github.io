<?php
/*
    ID: rank_7ree
	[www.7ree.com] (C)2006-2013 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 17:17 2014/6/10
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$vars_7ree = $_G['cache']['plugin']['rank_7ree'];
		
if(!$vars_7ree['agreement_7ree']) showmessage('rank_7ree:php_lang_agree_7ree');
if(!$vars_7ree['num_7ree']) showmessage('rank_7ree:php_lang_nomum_7ree');

$navtitle = lang('plugin/rank_7ree', 'php_lang_navtitle_7ree');

$colorarray = array('', '#EE1B2E', '#EE5023', '#996600', '#3C9D40', '#2897C5', '#2B65B7', '#8F2A90', '#EC1282');

		$_GET['code'] = !empty($_GET['code']) ? intval($_GET['code']) : $vars_7ree['fenlei_7ree'];
		$_GET['fid_7ree'] = intval($_GET['fid_7ree']);		
		
	if(!in_array($_G['groupid'],unserialize($vars_7ree['gid_7ree']))) showmessage('rank_7ree:php_lang_bangid_7ree');

				
	$vars_7ree['banfid_7ree'] = array_flip(array_flip(array_filter(unserialize($vars_7ree['banfid_7ree']))));	
	$banfid_7ree = $vars_7ree['banfid_7ree'] ? $vars_7ree['banfid_7ree'] : array();
	require_once libfile('function/forumlist');
	$select_fid_7ree = $_GET['fid_7ree'] && !in_array($_GET['fid_7ree'], $vars_7ree['banfid_7ree']) ? (array)$_GET['fid_7ree'] : array();
	$forumlist = forumselect(FALSE, 0, $select_fid_7ree);
	$fid_7ree = intval($_GET[fid_7ree]);

	foreach ($banfid_7ree as $banfid_vars){
		$match_7ree = '/\<option value=\"'.$banfid_vars.'\">(.*?)\<\/option>/';
		$forumlist = preg_replace($match_7ree,'',$forumlist);
	}
	$allfidnum_7ree = substr_count($forumlist,"<option value=");
	$match_7ree = '/\<option value=\"'.$fid_7ree.'\">(.*?)\<\/option>/';
	$forumlist_7ree = preg_replace($match_7ree,'',$forumlist);

    $where_7ree = " WHERE displayorder >= 0 ";
//时间范围条件选择
	$rankcycle_7ree = $_GET['code'] ;
	$ranktime_7ree = gettime_7ree($rankcycle_7ree);
    
	if($ranktime_7ree){
    		$dateline_where_7ree = " AND dateline >".$ranktime_7ree;
	}
	$gid2_7ree = in_array($_G['groupid'],unserialize($vars_7ree['gid2_7ree']));
	if($gid2_7ree && ($_GET['btime_7ree'] || $_GET['etime_7ree'])){
			$bbtime_7ree = $_GET['btime_7ree'] ? strtotime($_GET['btime_7ree']) : 0;
			$eetime_7ree = $_GET['etime_7ree'] ? strtotime($_GET['etime_7ree']) : $_G['timestamp'];			
					
			if($bbtime_7ree>=$eetime_7ree){
				showmessage('rank_7ree:php_lang_badtime_7ree',"plugin.php?id=rank_7ree:rank_7ree&code=6&fid_7ree={$_GET['fid_7ree']}");
			}else{
				$dateline_where_7ree = $bbtime_7ree ? " AND dateline >=".$bbtime_7ree : "";
				$dateline_where_7ree .= " AND dateline <=".$eetime_7ree;					
			}
			
	}
	
	

    
    
//版块范围条件选择(被屏蔽的 && 选择的)
    $banfid_where_7ree = COUNT($banfid_7ree) ? " AND fid NOT IN (".dimplode($banfid_7ree).")" : "";
    $fid_where_7ree = $_GET['fid_7ree'] && !in_array($_GET['fid_7ree'],$banfid_7ree) ? " AND fid = '{$_GET[fid_7ree]}' " : $banfid_where_7ree; 

 
//排序方式条件选择
//	$orderby_7ree = $vars_7ree['order_7ree'] ? " ORDER BY replies DESC ":" ORDER BY views DESC ";
	
	if($vars_7ree['order_7ree']==1){
		$orderby_7ree =	" ORDER BY replies DESC ";
		$orderstring_7ree = lang('plugin/rank_7ree', 'php_lang_order1_7ree');
	}elseif($vars_7ree['order_7ree']==2){
		$orderby_7ree =	" ORDER BY views DESC ";
		$orderstring_7ree = lang('plugin/rank_7ree', 'php_lang_order2_7ree');
	}elseif($vars_7ree['order_7ree']==3){
		$orderby_7ree =	" ORDER BY dateline DESC ";
		$orderstring_7ree = lang('plugin/rank_7ree', 'php_lang_order3_7ree');
	}
	

				$query = DB::query("SELECT * FROM ".DB::table('forum_thread')." {$where_7ree} {$dateline_where_7ree} {$fid_where_7ree} {$orderby_7ree} LIMIT {$vars_7ree['num_7ree']}");
				while($table_7ree = DB::fetch($query)){
					$table_7ree['dateline'] = dgmdate($table_7ree['dateline'],'u');
					$table_7ree['lastpost'] = dgmdate($table_7ree['lastpost'],'u');	
					if($vars_7ree['cutnum_7ree']){
						$table_7ree['subject'] = cutstr($table_7ree['subject'],$vars_7ree['cutnum_7ree'],'...');
					}				
	                $table_7ree['fname'] = DB::result_first("SELECT name FROM ".DB::table('forum_forum')." WHERE fid = '{$table_7ree[fid]}'");
	                 if($table_7ree['highlight'] && $vars_7ree['hightlight_7ree']) {
                		$string = sprintf('%02d', $table_7ree['highlight']);
                		$stylestr = sprintf('%03b', $string[0]);
                		$table_7ree['highlight'] = 'style="';
                		$table_7ree['highlight'] .= $stylestr[0] ? 'font-weight: bold;' : '';
                		$table_7ree['highlight'] .= $stylestr[1] ? 'font-style: italic;' : '';
                		$table_7ree['highlight'] .= $stylestr[2] ? 'text-decoration: underline;' : '';
                		$table_7ree['highlight'] .= $string[1] ? 'color: '.$colorarray[$string[1]] : '';
                		$table_7ree['highlight'] .= '"';
        			} else {
                		$table_7ree['highlight'] = '';
        			}
					$rank_7ree[] = $table_7ree;

				}



include template('rank_7ree:rank_7ree');



		function gettime_7ree($format_7ree){
		global $_G;
		$return_7ree = 0;

		switch ($format_7ree){
		case 1://本日
		  	$return_7ree = mktime(0,0,0,gmdate("m",$_G['timestamp'] + $_G['setting']['timeoffset'] * 3600),gmdate("d",$_G['timestamp'] + $_G['setting']['timeoffset'] * 3600),gmdate("Y",$_G['timestamp'] + $_G['setting']['timeoffset'] * 3600));
		  	break;
		case 2://本周
		  	$return_7ree = mktime(0, 0, 0, gmdate("m",strtotime("last Monday") + $_G['setting']['timeoffset'] * 3600),date("d",strtotime("last Monday") + $_G['setting']['timeoffset'] * 3600),date("Y",strtotime("last Monday") + $_G['setting']['timeoffset'] * 3600));
		  	break;
		case 3://本月
  			$return_7ree = mktime(0,0,0,gmdate("m",$_G['timestamp'] + $_G['setting']['timeoffset'] * 3600),1,gmdate("Y",$_G['timestamp'] + $_G['setting']['timeoffset'] * 3600));
  			break;
		case 4://本季度
			$season = ceil((gmdate("n",$_G['timestamp'] + $_G['setting']['timeoffset'] * 3600))/3);
			$return_7ree = mktime(0, 0, 0,$season*3-3+1,1,date('Y'));
		  	break;
		case 5://本年度
		  	$return_7ree = mktime(0,0,0,1,1,gmdate("Y",$_G['timestamp'] + $_G['setting']['timeoffset'] * 3600));
 		 	break;
		default:
 		 	$return_7ree = 0;
		}

		return $return_7ree;

}

		
?>