<?php
/*
 * 请勿修改本页任何内容，否则后果自负！
 * 作者 土著人宁巴 人工智能实验室 出品（Made By Nimba, Team From AiLab.cn)
 */
$info=array();
$info['name']='nimba_romotepic';
$info['version']='v1.4.1';
require_once DISCUZ_ROOT.'./source/discuz_version.php';
$info['siteversion']=DISCUZ_VERSION;
$info['siterelease']=DISCUZ_RELEASE;
$info['timestamp']=TIMESTAMP;
$info['nowurl']=$_G['siteurl'];
$info['siteurl']='http://www.5477dm.com/';
$info['clienturl']='http://www.5477dm.com/';
$info['siteid']='3F7B28B3-C8FF-EDF6-6EB6-9E69694679B0';
$info['sn']='2015080504i7C1I84kcl';
$info['adminemail']=$_G['setting']['adminemail'];
$infobase=base64_encode(serialize($info));
?>