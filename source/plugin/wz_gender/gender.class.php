<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_wz_gender {
	 
	function global_usernav_extra1(){
     global $_G;
		
      }
} 
//帖子内嵌入点
	class plugin_wz_gender_forum extends plugin_wz_gender{
	function viewthread_wz_gender_output(){
		global $_G,$postlist;
		$male=$_G['cache']['plugin']['wz_gender']['male'];
		$female=$_G['cache']['plugin']['wz_gender']['female'];
		$unknow=$_G['cache']['plugin']['wz_gender']['unknow'];
		$online=$_G['cache']['plugin']['wz_gender']['online'];
		$offline=$_G['cache']['plugin']['wz_gender']['offline'];
		loadcache('plugin');
			if($_G['cache']['plugin']['wz_gender']['radio']){	
			foreach ($postlist as $id=>$post){			
			 if($post['gender']==1) { 
			$postlist[$id]['author']='<span class="y"><img src="source/plugin/wz_gender/image/male.gif" title="'.$male.'"></span>'.$postlist[$id]['author'].'';
            }if($post['gender']==2) {
			$postlist[$id]['author']='<span class="y"><img src="source/plugin/wz_gender/image/female.gif" title="'.$female.'"></span>'.$postlist[$id]['author'].'';
			}if($post['gender']==0) {
			$postlist[$id]['author']='<span class="y"><img src="source/plugin/wz_gender/image/unknow.gif" title="'.$unknow.'"></span>'.$postlist[$id]['author'].'';				
			}if($_G['setting']['vtonlinestatus'] == 2 && $_G[forum_onlineauthors][$post[authorid]] || $_G['setting']['vtonlinestatus'] == 1 && TIMESTAMP - $post['lastactivity'] <= 10800 && !$post['authorinvisible']){
			$postlist[$id]['author']='<span class="y"><img src="source/plugin/wz_gender/image/online.gif" title="'.$online.'" /></span>'.$postlist[$id]['author'].'';	
			} else {
			$postlist[$id]['author']='<span class="y"><img src="source/plugin/wz_gender/image/offline.gif" title="'.$offline.'" /></span>'.$postlist[$id]['author'].'';	
			}
				$return[]="";			
		 }		
		return $return;
        	  	}	
	}
	
	
	//类结束标记
	}
?>