<?php

/**

������ƣ��Զ��������ͼ

��    ����1.0

��    �ߣ�ҹ����


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