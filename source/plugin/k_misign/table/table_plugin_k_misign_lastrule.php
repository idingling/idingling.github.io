<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_plugin_k_misign_lastrule extends discuz_table{
	public function __construct() {

		$this->_table = 'plugin_k_misign_lastrule';
		$this->_pk    = 'ruleid';

		parent::__construct();
	}
	
	public function fetch_all_cp($start, $limit) {
		return DB::fetch_all('SELECT * FROM %t ORDER BY lastday ASC LIMIT %d, %d', array($this->table, $start, $limit), $this->_pk);
	}
	
	public function fetch_all($start, $limit) {
		return DB::fetch_all('SELECT * FROM %t WHERE status=1 ORDER BY lastday ASC LIMIT %d, %d', array($this->table, $start, $limit), 'lastday');
	}
	
	public function fetch_by_ruleid($ruleid) {
		return DB::fetch_first('SELECT * FROM %t WHERE ruleid=%d', array($this->table, $ruleid));
	}

	public function fetch_by_lastday($lastday, $buff = false) {
		if($buff){
			return DB::fetch_first('SELECT * FROM %t WHERE lastday<%d AND buff=1 ORDER BY lastday DESC', array($this->table, $lastday));
		}else{
			return DB::fetch_first('SELECT * FROM %t WHERE lastday=%d', array($this->table, $lastday));
		}
	}
}

?>