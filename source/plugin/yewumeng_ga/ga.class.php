<?php

/**

插件名称：自定义风格滚动图

版    本：1.0

作    者：夜无梦


**/



if(!defined('IN_DISCUZ')) {

	exit('Access Denied');

}



class plugin_yewumeng_ga_forum {



	function index_side_bottom() {

        global $_G;

        $ye = $_G['cache']['plugin']['yewumeng_ga'];

include template('yewumeng_ga:ga');
return $return;
}
}
?>