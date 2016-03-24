<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_hux_credit {

	function hux_credit() {
		global $_G;
		$csetting = $_G['cache']['plugin']['hux_credit'];
		$lincolor = $csetting['linkcolor'];
		$pluginname = $csetting['pluginname'];
		if ($csetting['open']) {
			return "<a href='javascript:;' onclick=\"showWindow('hux_credit', 'plugin.php?id=hux_credit:hux_credit&action=buy','get',0);return false;\"><font color='".$lincolor."'>".$pluginname."</font></a>";
		} else {
			return '';
		}
	}

	function global_cpnav_extra1() {
		global $_G;
		$csetting = $_G['cache']['plugin']['hux_credit'];
		$linktype = $csetting['linktype'];
		if ($linktype == '0') {
			return $this->hux_credit();
		} else {
			return '';
		}
	}

	function global_cpnav_extra2() {
		global $_G;
		$csetting = $_G['cache']['plugin']['hux_credit'];
		$linktype = $csetting['linktype'];
		if ($linktype == '1') {
			return $this->hux_credit();
		} else {
			return '';
		}
	}

	function global_usernav_extra3() {
		global $_G;
		$csetting = $_G['cache']['plugin']['hux_credit'];
		$linktype = $csetting['linktype'];
		if ($linktype == '2') {
			return $this->hux_credit();
		} else {
			return '';
		}
	}
	
	function global_nav_extra() {
		global $_G;
		$csetting = $_G['cache']['plugin']['hux_credit'];
		$linktype = $csetting['linktype'];
		if ($linktype == '3') {
			return '<ul><li>'.$this->hux_credit().'</li></ul>';
		} else {
			return '';
		}
	}
}
?>