<?php

/**
 * Copyright 2001-2099 1314学习网.
 * This is NOT a freeware, use is subject to license terms
 * $Id: mobilehook.class.php 1185 2016-02-09 17:29:08Z zhuge $
 * 应用售后问题：http://www.discuz.1314study.com/services.php?mod=issue
 * 应用售前咨询：QQ 15326940
 * 应用定制开发：QQ 643306797
 * 本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
 * 未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。
 */
/*
 * This is NOT a freeware, use is subject to license terms
 * From www.1314study.com
 */
if(!defined('IN_DISCUZ')) {
exit('3F7B28B3-C8FF-EDF6-6EB6-9E69694679B0');
}
class mobileplugin_addon_baidu_search {
		public function common() {
				global $_G;
				if(CURSCRIPT == 'search'){
						$replace = false;
						$splugin_setting = $_G['cache']['plugin']['addon_baidu_search'];
						if($splugin_setting['touch_radio']){
								if(in_array(CURMODULE, array('forum', 'curforum')) && $splugin_setting['forum_radio']){
										$replace = true;
								}elseif(CURMODULE == 'portal' && $splugin_setting['forum_radio']){
										$replace = true;
								}
								
								if($replace){
										$study_url = $splugin_setting['study_url'] ? $splugin_setting['study_url'] : 'so.1314study.com';
										$study_id = $splugin_setting['study_id'] ? $splugin_setting['study_id'] : '7720323758264071872';
										$q = $_GET['srchtxt'] ? $_GET['srchtxt'] : $_POST['srchtxt'];
										$q = urlencode(diconv($q, CHARSET, 'UTF-8'));
										$url = "http://$study_url/cse/search?s=$study_id&q=$q";
										dheader("Location: $url");
								}
						}
				}
		}
}



//Copyright 2001-2099 1314学习网.
//This is NOT a freeware, use is subject to license terms
//$Id: mobilehook.class.php 1644 2016-02-09 09:29:08Z zhuge $
//应用售后问题：http://www.discuz.1314study.com/services.php?mod=issue
//应用售前咨询：QQ 15326940
//应用定制开发：QQ 643306797
//本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
//未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。