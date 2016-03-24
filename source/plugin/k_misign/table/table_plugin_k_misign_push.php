<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_plugin_k_misign_push extends discuz_table{
	public function __construct() {

		$this->_table = 'plugin_k_misign_push';
		$this->_pk    = 'pushid';

		parent::__construct();
	}
	
	public function fetch_all_cp($start, $limit) {
		return DB::fetch_all('SELECT * FROM %t ORDER BY dateline DESC LIMIT '.$start.','.$limit, array($this->table), $start, $limit);
	}
	
	public function fetch_by_pushid($pushid) {
		return DB::fetch_first('SELECT * FROM %t WHERE pushid=%d', array($this->table, $pushid));
	}
}

?>