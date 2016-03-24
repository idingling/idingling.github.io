<?php
/**
 *	开发团队：IT618资讯网
 *	it618_copyright 插件设计：<a href="http://www.cnit618.com" target="_blank" title="为站长提供学习资料">IT618资讯网</a>
 */
if(!defined('IN_DISCUZ')) {
  exit('Access Denied');
}


class plugin_it618_attachdisplay_forum {
	
	function viewthread_posttop_output(){
		global $_G, $postlist,$it618_attachdisplay;

		$it618_attachdisplay_plugin = $_G['cache']['plugin']['it618_attachdisplay'];
		$ad_forums = unserialize($it618_attachdisplay_plugin["ad_forums"]);
		$ad_usergroups = unserialize($it618_attachdisplay_plugin["ad_usergroups"]);
		$postcount_set=$it618_attachdisplay_plugin["ad_postcount"];
		
		$it618_authorid=DB::result_first("SELECT authorid FROM ".DB::table('forum_thread')." WHERE tid=".$_G[tid]);
		$postcount_get=DB::result_first('SELECT count(1) FROM '.DB::table('forum_post').' WHERE tid='.$_G[tid].' and authorid = '.$_G['uid']);

		if(in_array($_G[fid], $ad_forums)){
			
			if(in_array($_G[groupid], $ad_usergroups)||$it618_authorid==$_G['uid']||$postcount_get>=$postcount_set){
				$it618_attachdisplay=null;
			}

			foreach($postlist as $id => $post) {
				if($post['first']!=1)break;
				$left_arr=explode("<ignore_js_op>",$post['message']);
				$tmpmessage=$post['message'];
				if(count($left_arr)>1){
					$n=1;
					foreach($left_arr as $key => $left_arrstr){
						if($n==1){
							$tmpmessage=$left_arrstr;
						}else{
							$tmpmessage.="<ignore_js_op>".$left_arrstr;
						}
						$n=$n+1;
						
						$tmparr=explode("</ignore_js_op>",$left_arrstr);
						if(count($tmparr)>1){

							if(!(in_array($_G[groupid], $ad_usergroups)||$it618_authorid==$_G['uid']||$postcount_get>=$postcount_set)){
								$flag=0;
								if($it618_attachdisplay_plugin["ad_isimage"]==0){
									$tmparr1=explode("zoomfile",$tmparr[0]);
									if(count($tmparr1)>1)$flag=1;
								}
								if($flag==0){
									$tmpmessage = str_replace("<ignore_js_op>".$tmparr[0]."</ignore_js_op>","",$tmpmessage);
								}
							}
						}
					}
				}
				
				$tips="";
				$it618_attachdisplay_block="";
				$n=1;
				if(!(in_array($_G[groupid], $ad_usergroups)||$it618_authorid==$_G['uid']||$postcount_get>=$postcount_set)){
					$query = DB::query("SELECT * FROM ".DB::table('forum_attachment')." where tid=".$_G[tid]." ORDER BY aid desc");
					while($forum_attachment = DB::fetch($query)) {
						$attach = DB::fetch_first("SELECT * FROM ".DB::table('forum_attachment_'.$forum_attachment['tableid'])." where aid=".$forum_attachment['aid']);

						$flag=0;
						if($attach['isimage']!=0){
							if($it618_attachdisplay_plugin["ad_isimage"]==0)$flag=1;
						}
						
						if($flag==0){
							$tips.="<li>".$it618_attachdisplay_plugin["ad_listtitle"]."</li>";
							$filesize=$attach['filesize'] / 1048576 >= 1 ? round(($attach['filesize'] / 1048576), 2).'MB' : round(($attach['filesize'] / 1024)).'KB';
							
							$tips=str_replace("{id}",$n,$tips);
							$tips=str_replace("{filename}",$attach['filename'],$tips);
							$tips=str_replace("{filesize}",$filesize,$tips);
							$tips=str_replace("{downcount}",$forum_attachment['downloads'],$tips);
							
							$n=$n+1;
						}
					}
					
					$ad_title=$it618_attachdisplay_plugin["ad_title"];
					$ad_title=str_replace("{allcount}",$postcount_set,$ad_title);
					$ad_title=str_replace("{okcount}",$postcount_get,$ad_title);
					if($tips!="")include template('it618_attachdisplay:attachdisplay');
				}

				$post['message']=$tmpmessage.$it618_attachdisplay_block;
				$tmpmessage="";
				$postlist[$id] =$post;
				break;
			}
		}
		
	}

}

?>