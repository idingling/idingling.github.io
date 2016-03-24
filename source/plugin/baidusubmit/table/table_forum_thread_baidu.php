<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_forum_thread_baidu extends table_forum_thread
{

    public function get_thread_by_tidrange($starttid, $endtid, $tableid = 0)
    {
        $sql = 'SELECT fid, tid, readperm, price, status, subject, replies, views, 
            lastpost, posttableid, author, authorid, recommend_add, recommend_sub, 
            digest, displayorder, lastposter, favtimes, sharetimes
            FROM %t WHERE tid<=%d AND tid>=%d ORDER BY tid';
        $query = DB::query($sql, array($this->get_table_name($tableid), $endtid, $starttid), false, true);
        $ret = array();
        while ($row = DB::fetch($query)) {
            if ($row['displayorder'] >= 0) {
                $ret[$row['tid']] = $row;
            }
        }
        return $ret;
    }

    public function get_thread_by_lastpost($start_time, $end_time, $limit = 0, $tableid = 0)
    {
        $sql = 'SELECT fid, tid, readperm, price, status, subject, replies, views,
            lastpost, posttableid, author, authorid, recommend_add, recommend_sub, 
            digest, displayorder, lastposter, favtimes, sharetimes
            FROM %t WHERE isgroup IN (0,1) AND lastpost>=%d AND lastpost<=%d ORDER BY lastpost';
        if ($limit > 0) {
            $sql .= ' LIMIT ' . intval($limit);
        }
        $query = DB::query($sql, array($this->get_table_name($tableid), $start_time, $end_time), false, true);
        $ret = array();
        while ($row = DB::fetch($query)) {
            if ($row['displayorder'] >= 0) {
                $ret[$row['tid']] = $row;
            }
        }
        return $ret;
    }

    public function get_hot_thread($fid)
    {
        $sql = 'select fid,tid,replies,views,dateline,subject from %t '
                . ' where displayorder >= 0 and fid = %d order by replies desc limit 3';
        $query = DB::query($sql, array($this->get_table_name(0), $fid), false, true);
        $ret = array();
        while ($row = DB::fetch($query)) {
            $ret[$row['tid']] = $row;
        }
        return $ret;
    }

}
