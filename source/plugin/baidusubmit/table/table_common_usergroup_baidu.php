<?php

class table_common_usergroup_baidu extends table_common_usergroup
{

    public function fetch_dict()
    {
        $result = array();
        $query = DB::query('select groupid,grouptitle from %t ', array($this->_table), false, true);
        while ($row = DB::fetch($query)) {
            $result[$row['groupid']] = $row['grouptitle'];
        }
        return $result;
    }

}
