<?php
if(!defined('IN_DISCUZ')) exit('Access Denied');
class mobileplugin_k_misign {
        function global_header_mobile(){
			global $_G,$show_message;
			require_once libfile('function/core', 'plugin/k_misign');
			$setting['groups'] = unserialize($setting['groups']);
			$setting['ban'] = explode(",",$setting['ban']);
			$setting['width'] = $setting['width'] ? $setting['width'] : 220;
			$setting['bcolor'] = $setting['bcolor'] ? $setting['bcolor'] : '#ff6f3d';
			$setting['hcolor'] = $setting['hcolor'] ? $setting['hcolor'] : '#ff7d49';
			$setting['width'] = $width ? $width : $setting['width'];
			$setting['width2'] = $setting['width']-158;
			
			$stats = C::t("#k_misign#plugin_k_misignset")->fetch(1);
			
			if(defined('IN_k_misign') || $show_message || !$_G['uid'] || !$setting['mobilehookstatus']) return '';
			$tdtime = gmmktime(0,0,0,dgmdate($_G['timestamp'], 'n',$setting['tos']),dgmdate($_G['timestamp'], 'j',$setting['tos']),dgmdate($_G['timestamp'], 'Y',$setting['tos'])) - $setting['tos']*3600;

			$qiandaodb = C::t('#k_misign#plugin_k_misign')->fetch_by_uid($_G['uid']);
			include template("k_misign:hook_indexside");
			return $return;
        }
}
?>