<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_nciaer_postright {}

class plugin_nciaer_postright_forum extends plugin_nciaer_postright {	
	public function viewthread_demo_output() {
		global $_G;
		global $postlist;
		$_msg = $_G["cache"]["plugin"]["nciaer_postright"]["msg"];
		$_re = $_G["cache"]["plugin"]["nciaer_postright"]["re"];
		$_pos = $_G["cache"]["plugin"]["nciaer_postright"]["pos"];
		$_bcolor = $_G["cache"]["plugin"]["nciaer_postright"]["bcolor"] ? $_G["cache"]["plugin"]["nciaer_postright"]["bcolor"] : "#080"; //border color
		$_bradius = $_G["cache"]["plugin"]["nciaer_postright"]["bradius"] ? "border-radius: 8px;" : "";
		$_url = $_G["siteurl"];
		if($_re) {
			//rewrite
			$_url2 .= "<a href='{$_url}thread-{$_G['tid']}-1-1.html' title='{$_G['forum_thread']['subject']}'>{$_url}thread-{$_G['tid']}-1-1.html</a>";	
		} else {
			$_url2 .= "<a href='{$_url}forum.php?mod=viewthread&tid={$_G['tid']}' title='{$_G['forum_thread']['subject']}'>{$_url}forum.php?mod=viewthread&tid={$_G['tid']}</a>";			
		}
		$str .= "<div style = 'border: solid 1px {$_bcolor}; margin: 10px; padding: 10px; text-align: center; line-height: 30px;{$_bradius}'>
		".lang('plugin/nciaer_postright', 'str1')."{$_url2}<br />{$_msg}
				</div>";
		if($postlist) {
			foreach($postlist as $k => &$p) {
				if($p["first"]) {
					if($_pos) {
						$p["message"] = $p["message"].$str;
					} else {
						$p["message"] = $str.$p["message"];
					}
				}
			}	
		}		
	}
}
