<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_plugin_k_misign extends discuz_table{
	public function __construct() {

		$this->_table = 'plugin_k_misign';
		$this->_pk    = 'uid';

		parent::__construct();
	}
	public function getlasttime() {
        return DB::result_first('SELECT time FROM %t ORDER BY time DESC LIMIT 0, 1', array($this->_table));
    }
	
	public function gettodaystar($tdtime){
		return DB::fetch_first("SELECT * FROM %t WHERE time >= %d ORDER BY time ASC", array($this->_table, $tdtime));
	}
	
	public function getcount($field = '', $val = '', $glue = '') {
        $wheresql = !empty($field) ? 'WHERE '.DB::field($field, $val, $glue) : '';
        return DB::result_first('SELECT COUNT(*) FROM %t %i ', array($this->_table, $wheresql));
    }

	public function getsignlist($type, $turn = 'DESC', $start_limit = 0, $pnum = 10, $tdtime = '') {
		$turn = $turn == 'ASC' ? $turn : 'DESC';
        $wheresql = !empty($tdtime) ? 'AND q.time >= '.$tdtime : '';
        return DB::fetch_all("SELECT q.*,m.* FROM %t q, %t m WHERE q.uid=m.uid %i ORDER BY %i %i LIMIT %d, %d", array($this->_table, 'common_member', $wheresql, $type, $turn, $start_limit, $pnum));
    }
	
	public function clearmdays() {
        return DB::query('UPDATE %t SET mdays=0', array($this->_table));
    }

	public function update_signdata($uid, $credit, $row, $islasted) {
        if($islasted){
            return DB::query('UPDATE %t SET days=days+1,mdays=mdays+1,time=%i,reward=reward+%i,lastreward=%i,row=%d,lasted=lasted+1 WHERE uid=%d', array($this->_table, TIMESTAMP, $credit, $credit, $row, $uid));
        }else{
            return DB::query('UPDATE %t SET days=days+1,mdays=mdays+1,time=%i,reward=reward+%i,lastreward=%i,row=%d,lasted=1 WHERE uid=%d', array($this->_table, TIMESTAMP, $credit, $credit, $row, $uid));
        }
    }
	
	public function fetch_by_uid($uid) {
		return DB::fetch_first("SELECT * FROM %t q, %t m WHERE q.uid=m.uid AND q.uid=%d", array($this->table, 'common_member', $uid));
	}
	
	public function fetch_all_by_time($start, $limit, $tdtime) {
		return DB::fetch_all("SELECT * FROM %t q, %t m WHERE q.uid=m.uid AND q.time>%d ORDER BY q.time DESC LIMIT %d, %d", array($this->table, 'common_member', $tdtime, $start, $limit), $this->_pk);
	}
	
	public function fetch_all_by_order($orderby = 'uid', $start, $limit) {
		if(in_array($orderby, array('uid'))){
			$orderbys = 'q.'.$orderby.' ASC';
		}elseif(in_array($orderby, array('days', 'mdays', 'reward', 'time'))){
			$orderbys = 'q.'.$orderby.' DESC';
		}
		return DB::fetch_all("SELECT * FROM %t q, %t m WHERE q.uid=m.uid ORDER BY %i LIMIT %d, %d", array($this->table, 'common_member', $orderbys, $start, $limit), $this->_pk);
	}
		
	public function count_by_time($tdtime) {
		return DB::result_first("SELECT COUNT(*) FROM %t WHERE time >= %d", array($this->table, $tdtime));
	}
}

?>