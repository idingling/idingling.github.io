<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_plugin_k_misign_prize_log extends discuz_table{
	public function __construct() {

		$this->_table = 'plugin_k_misign_prize_log';
		$this->_pk    = 'logid';

		parent::__construct();
	}

	public function fetch_all($start, $limit) {
		return DB::fetch_all('SELECT * FROM %t ORDER BY logid DESC LIMIT '.$start.','.$limit, array($this->table), $start, $limit);
	}
	
}

?>