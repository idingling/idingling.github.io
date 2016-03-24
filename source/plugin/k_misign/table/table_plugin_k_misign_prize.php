<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_plugin_k_misign_prize extends discuz_table{
	public function __construct() {

		$this->_table = 'plugin_k_misign_prize';
		$this->_pk    = 'prizeid';

		parent::__construct();
	}

	public function fetch_all_by_status() {
		return DB::fetch_all("SELECT * FROM %t WHERE status='1' && todaylast>0 ORDER BY prizeid DESC", array($this->table), $this->_pk);
	}
	
	public function fetch_all_cp($start, $limit) {
		return DB::fetch_all("SELECT * FROM %t ORDER BY prizeid DESC LIMIT %d, %d", array($this->table, $start, $limit));
	}
	
	public function fetch_by_prizeid($prizeid) {
		return DB::fetch_first("SELECT * FROM %t WHERE prizeid=%d", array($this->table, $prizeid));
	}

	public function update_by_todaylast($prizeid, $todaylast) {
		return DB::query("UPDATE %t SET todaylast=%d WHERE prizeid=%d", array($this->table, $todaylast, $prizeid));
	}
	
	public function update_everyday() {
		return DB::query("UPDATE %t SET todaylast = everyday WHERE auto = 1 AND prizetype IN ('1','2')", array($this->table));
	}
}

?>