<?php

class table_common_member_status_baidu extends table_common_member_status
{

    public function get_uids_by_lastvisit($st, $et, $limit = null)
    {
        $result = array();
        $sql = 'select uid from %t where lastvisit >= %d and lastvisit < %d order by lastvisit asc';
        if ($limit) {
            $sql .= " limit $limit";
        }
        $query = DB::query($sql, array($this->_table, $st, $et), false, true);
        while ($row = DB::fetch($query)) {
            $result[] = $row['uid'];
        }
        return $result;
    }

}
