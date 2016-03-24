<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_qzom_thread{
	var $forum_offset;
	function plugin_qzom_thread() {
		global $_G;
		$set= $_G['cache']['plugin']['qzom_thread'];
		$this->forumlist = (array)unserialize($set['forum_offset']);
	}
}

class plugin_qzom_thread_forum extends plugin_qzom_thread {

  function viewthread_tbg_output($a) {
		global $_G, $postlist;
		if($a['template'] == 'viewthread' && in_array($_G['fid'], $this->forumlist)){
			foreach($postlist as $pid => $post) {
				$postlist[$pid]['message'] = '<table cellspacing="0" cellpadding="0" border="0"><tr><td width="40"><img height="40" src="source/plugin/qzom_thread/image/thread_A.gif" width="40"/></td><td width:=auto background="source/plugin/qzom_thread/image/thread_B.gif"></td><td width="40"><img height="40" src="source/plugin/qzom_thread/image/thread_C.gif" width="40"/></td></tr><tr><td valign="top" background="source/plugin/qzom_thread/image/thread_D.gif"><img src="source/plugin/qzom_thread/image/thread_left.gif" height="80" width="40" vAlign="0" /></td><td background="source/plugin/qzom_thread/image/thread_E.gif"><div class="t_f">'.$post['message'].'</div><br /></td><td valign="top" width="40" background="source/plugin/qzom_thread/image/thread_F.gif"></td></tr><tr><td vAlign="top" width="40"><img height="20" src="source/plugin/qzom_thread/image/thread_G.gif" width="40"/></td><td background="source/plugin/qzom_thread/image/thread_H.gif"></td><td align="right" width="40"><img height="20" src="source/plugin/qzom_thread/image/thread_I.gif" width="40"/></td></tr></table>';
			}
		}
	}
}
?>