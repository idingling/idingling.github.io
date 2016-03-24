<?php
/*
 * This is NOT a freeware, use is subject to license terms
 * From www.1314study.com
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_zzbuluo_360search {
		public function common() {
				global $_G, $study_article_content;
				if(CURSCRIPT == 'search'){
						if(CURMODULE == 'forum' || CURMODULE == 'curforum'){
								$splugin_setting = $_G['cache']['plugin']['zzbuluo_360search'];
								if($splugin_setting['forum_radio']){
										$site = $splugin_setting['site'] ? $splugin_setting['site'] : '1314study.com';
										$q = urlencode($_GET['srchtxt'] ? $_GET['srchtxt'] : $_POST['srchtxt']);
										$url = "http://www.so.com/s?q=$q&ie=$splugin_setting[encoding]&src=zz_www_so_com&site=$site&rg=$splugin_setting[rg]";
										dheader("Location: $url");
								}
						}
				}
		}
}


class plugin_zzbuluo_360search_forum extends plugin_zzbuluo_360search{
	
		public function viewthread_postbottom() {
				global $_G;
				$return = array();
				$splugin_setting = $_G['cache']['plugin']['zzbuluo_360search'];
				$splugin_lang = lang('plugin/zzbuluo_360search');
				if($splugin_setting['viewthread_radio']){
						if($_G['page'] == 1 && !$_G['inajax']){
								$splugin_setting['site'] = $splugin_setting['site'] ? $splugin_setting['site'] : $splugin_lang['slang_001'];
								$splugin_setting['custom_color'] = $splugin_setting['custom_color'] ? $splugin_setting['custom_color'] : '#3FB30E';
								
								include template('zzbuluo_360search:360search');
								$return[] = $search;
						}
				}
				return $return;
		}

}

