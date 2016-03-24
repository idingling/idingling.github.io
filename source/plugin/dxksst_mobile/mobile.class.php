<?php
/**
 * 		Copyright£ºSmartCome
 * 		  WebSite£ºwww.SmartCome.com
 *              QQ:2811931192
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class mobileplugin_dxksst_mobile {
		function discuzcode($value){
		global $_G;
		$dxksst = $_G['cache']['plugin']['dxksst_mobile'];
		$dxksst_forum=unserialize($dxksst['forum']);
		if(!in_array($_G['fid'],$dxksst_forum))return array();
		require_once libfile('function/smart','plugin/dxksst_mobile');
			if($value[caller]=="discuzcode"){
				$_G['discuzcodemessage']=deal_message($_G['discuzcodemessage'],$dxksst[width],$dxksst[height]);
			}			
		}
	
	}
 
 ?>