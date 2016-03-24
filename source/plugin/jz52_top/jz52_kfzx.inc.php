<?php
/**  
 *	极致站长资源www.jz52.com原创插件
 *	转载请保留此信息  
 *	极致站长资源QQ群：238148290
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


$charset = $_G['charset'];
$jz52_kfzxdm = $_G['cache']['plugin']['jz52_top']['jz52_kfzxdm'];
$jz52_kfzxwbwx = $_G['cache']['plugin']['jz52_top']['jz52_kfzxwbwx'];
$jz52_qqlist1 = $_G['cache']['plugin']['jz52_top']['jz52_qqlist'];
$jz52_wangwang1 = $_G['cache']['plugin']['jz52_top']['jz52_wangwang'];
$jz52_qqlist = explode("\r\n", $_G['cache']['plugin']['jz52_top']['jz52_qqlist']);
$jz52_wangwang = explode("\r\n", $_G['cache']['plugin']['jz52_top']['jz52_wangwang']);

    $jz52_qqlists = array();
    foreach($jz52_qqlist as $key => $val){
      $myarr = explode("|", $val);
      $jz52_qqlists[$key]['name'] = $myarr[0];
      $jz52_qqlists[$key]['qq'] = $myarr[1];
    }
	
	$jz52_wangwangs = array();
    foreach($jz52_wangwang as $key => $val){
      $myarrw = explode("|", $val);
      $jz52_wangwangs[$key]['name'] = $myarrw[0];
	  $jz52_wangwangs[$key]['zh'] = $myarrw[1];
	  
/**  
if($charset == gbk )
{
$jz52_wangwangs[$key]['zh'] = urlencode( mb_convert_encoding($myarrw[1], "UTF-8", "GBK")); //转换为utf-8
}
else
{
$jz52_wangwangs[$key]['zh'] = urlencode($myarrw[1]);
}    
*/
    }




	
include_once template('jz52_top:kf');


?>