<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_plugin_k_misign_prize_kami extends discuz_table{
	public function __construct() {

		$this->_table = 'plugin_k_misign_prize_kami';
		$this->_pk    = 'kid';

		parent::__construct();
	}

	public function fetch_by_kid($kid) {
		return DB::fetch_first('SELECT * FROM %t WHERE kid=%d', array($this->table, $kid));
	}

	public function fetch_by_prizeid($prizeid) {
		return DB::fetch_first('SELECT * FROM %t WHERE prizeid=%d AND uid=0', array($this->table, $prizeid));
	}

	public function fetch_all_by_status() {
		return DB::fetch_all("SELECT * FROM %t WHERE uid>0 ORDER BY prizeid DESC", array($this->table), $this->_pk);
	}
	
	public function fetch_all_cp($start, $limit) {
		return DB::fetch_all('SELECT p.prizename, k.* FROM %t k LEFT JOIN '.DB::table('plugin_k_misign_prize').' p ON p.prizeid = k.prizeid ORDER BY k.kid DESC LIMIT '.$start.','.$limit, array($this->table), $start, $limit);
	}
	
	public function fetch_all_by_prizeid($prizeid) {
		return DB::fetch_all('SELECT * FROM %t WHERE prizeid=%d', array($this->table, $prizeid));
	}

	public function update_by_uid($kid, $uid) {
		return DB::query('UPDATE %t SET uid=%d WHERE kid=%d', array($this->table, $uid, $kid));
	}
}

?>