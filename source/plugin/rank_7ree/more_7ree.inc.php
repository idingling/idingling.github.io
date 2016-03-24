<?php

/*
	(C)2007-2013 [www.7ree.com]
	This is NOT a freeware, use is subject to license terms
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/



if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

	$pluginid_7ree = dhtmlspecialchars(daddslashes($_GET['identifier']));
	$langdir_7ree = 'plugin/'.$pluginid_7ree;
	
	
//cpmsg('zhiding_7ree:php_lang_timeerror_7ree',"http://addon.discuz.com/?@7ree","succeed");

	$lang_production_7ree = lang($langdir_7ree, 'php_more_lang_production_7ree');
	$lang_free_7ree = lang($langdir_7ree, 'php_more_lang_free_7ree');
	$lang_trade_7ree = lang($langdir_7ree, 'php_more_lang_trade_7ree');
	$lang_shangjia_7ree = lang($langdir_7ree, 'php_more_lang_shangjia_7ree');
	$lang_baojia_7ree = lang($langdir_7ree, 'php_more_lang_baojia_7ree');
	$lang_moreinfo_7ree = lang($langdir_7ree, 'php_more_lang_moreinfo_7ree');
	$lang_service_7ree = lang($langdir_7ree, 'php_more_lang_service_7ree');
	$lang_logo_7ree = lang($langdir_7ree, 'php_more_lang_logo_7ree');
	$lang_logodetail_7ree = lang($langdir_7ree, 'php_more_lang_logodetail_7ree');
	$lang_analysis_7ree = lang($langdir_7ree, 'php_more_lang_analysis_7ree');
	$lang_analysisdetail_7ree = lang($langdir_7ree, 'php_more_lang_analysisdetail_7ree');
	$lang_discuz_7ree = lang($langdir_7ree, 'php_more_lang_discuz_7ree');
	$lang_discuzdetail_7ree = lang($langdir_7ree, 'php_more_lang_discuzdetail_7ree');
	$lang_about_7ree = lang($langdir_7ree, 'php_more_lang_about_7ree');
	$lang_trust_7ree = lang($langdir_7ree, 'php_more_lang_trust_7ree');
	$lang_trustdetail_7ree = lang($langdir_7ree, 'php_more_lang_trustdetail_7ree');
	$lang_detailinfo_7ree = lang($langdir_7ree, 'php_more_lang_detailinfo_7ree');
	$lang_siteweibo_7ree = lang($langdir_7ree, 'php_more_lang_siteweibo_7ree');
	$lang_site_7ree = lang($langdir_7ree, 'php_more_lang_site_7ree');
	$lang_weibo_7ree = lang($langdir_7ree, 'php_more_lang_weibo_7ree');
	$lang_qqweibo_7ree = lang($langdir_7ree, 'php_more_lang_qqweibo_7ree');
	$lang_sinaweibo_7ree = lang($langdir_7ree, 'php_more_lang_sinaweibo_7ree');
	$lang_club_7ree = lang($langdir_7ree, 'php_more_lang_club_7ree');
	$lang_qqqun_7ree = lang($langdir_7ree, 'php_more_lang_qqqun_7ree');
	$lang_qqcopy_7ree = lang($langdir_7ree, 'php_more_lang_qqcopy_7ree');
	$lang_qqtip_7ree = lang($langdir_7ree, 'php_more_lang_qqtip_7ree');
	$lang_document_7ree = lang($langdir_7ree, 'php_more_lang_document_7ree');
	$lang_agreement_7ree = lang($langdir_7ree, 'php_more_lang_agreement_7ree');
	$lang_lawyer_7ree = lang($langdir_7ree, 'php_more_lang_lawyer_7ree');
	$lang_wuxingliang_7ree = lang($langdir_7ree, 'php_more_lang_wuxingliang_7ree');
	$lang_appdy1_7ree = lang($langdir_7ree, 'php_more_lang_appdy1_7ree');
	$lang_appdy2_7ree = lang($langdir_7ree, 'php_more_lang_appdy2_7ree');
	$lang_yu_7ree = lang($langdir_7ree, 'php_more_lang_yu_7ree');
	$lang_cong_7ree = lang($langdir_7ree, 'php_more_lang_cong_7ree');
	$lang_zhongxin_7ree = lang($langdir_7ree, 'php_more_lang_zhongxin_7ree');
	$lang_anzhuang_7ree = lang($langdir_7ree, 'php_more_lang_anzhuang_7ree');
	$lang_gengxin_7ree = lang($langdir_7ree, 'php_more_lang_gengxin_7ree');	
	$lang_dytitle1_7ree = lang($langdir_7ree, 'php_more_lang_dytitle1_7ree');		
	$lang_dytitle2_7ree = lang($langdir_7ree, 'php_more_lang_dytitle2_7ree');		
	$lang_dytitle3_7ree = lang($langdir_7ree, 'php_more_lang_dytitle3_7ree');		
	$lang_dytitle4_7ree = lang($langdir_7ree, 'php_more_lang_dytitle4_7ree');		



	include template($pluginid_7ree.':more_7ree');

	
?>
