<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_dzapp_nav.php 31511 2014-11-19 17:00:03Z mpage $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_dzapp_nav extends discuz_table {

	public function __construct() {
		$this->_table = 'dzapp_nav';
		$this->_pk    = 'nav_id';

		parent::__construct();
	}

	public function fetch_all_by_displayorder() {
		return DB::fetch_all("SELECT * FROM %t ORDER BY displayorder", array($this->_table), $this->_pk);
	}
}

?>