<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_plugin_k_misignset extends discuz_table{
	public function __construct() {

		$this->_table = 'plugin_k_misignset';
		$this->_pk    = 'id';

		parent::__construct();
	}

	public function increase_todayq() {
		return DB::query('UPDATE %t SET todayq=todayq+1 WHERE id=1', array($this->_table));
	}
}

?>