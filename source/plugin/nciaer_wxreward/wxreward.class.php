<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_nciaer_wxreward {}

class plugin_nciaer_wxreward_forum extends plugin_nciaer_wxreward {
	function  viewthread_modaction() {
		global $_G;
		$msg = $_G["cache"]["plugin"]["nciaer_wxreward"]["msg"];
		
		$rmb1 = $_G["cache"]["plugin"]["nciaer_wxreward"]["rmb1"];
		$rmb1_url = $_G["cache"]["plugin"]["nciaer_wxreward"]["rmb1_url"];
		
		$rmb2 = $_G["cache"]["plugin"]["nciaer_wxreward"]["rmb2"];
		$rmb2_url = $_G["cache"]["plugin"]["nciaer_wxreward"]["rmb2_url"];
		
		$rmb3 = $_G["cache"]["plugin"]["nciaer_wxreward"]["rmb3"];
		$rmb3_url = $_G["cache"]["plugin"]["nciaer_wxreward"]["rmb3_url"];
		
		$rmb4 = $_G["cache"]["plugin"]["nciaer_wxreward"]["rmb4"];
		$rmb4_url = $_G["cache"]["plugin"]["nciaer_wxreward"]["rmb4_url"];
		
		$fids = $_G["cache"]["plugin"]["nciaer_wxreward"]["fids"];
		if(!in_array($_G['fid'],(array)unserialize($fids))) return '';
		
		include template("nciaer_wxreward:index");
		
		return $wx_index;
	}
}