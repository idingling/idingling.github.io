<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_plugin_k_misign_bq extends discuz_table {
	public function __construct() {
		$this->_table = 'plugin_k_misign_bq';
		$this->_pk    = 'bid';

		parent::__construct();
	}

	public function fetch_by_bid($bid) {
		return DB::fetch_first('SELECT * FROM %t WHERE bid=%d ORDER BY bid DESC', array($this->_table, $bid));
	}

	public function fetch_by_time($uid, $bqstarttime) {
		return DB::fetch_first('SELECT * FROM %t WHERE uid=%d AND lasttime > %d ORDER BY bid DESC', array($this->_table, $uid, $bqstarttime));
	}

}

?>