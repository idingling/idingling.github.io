<?php

if(!defined('IN_DISCUZ')) {
   exit('Access Deined');
}

include ("function.inc.php");
$water=new waterFallPlugin();
$fid=strval($_GET['fid']);
$filter=strval($_GET['filter']);
$orderby=strval($_GET['orderby']);

//��ӷ�ҳ
$eachload=$_G['cache']['plugin']['waterfall']['eachload'];
$loadsperpage=$_G['cache']['plugin']['waterfall']['loadsperpage'];
$perpage=$eachload*$loadsperpage;//ÿҳ��ʾ��
$page=intval($_GET['page']);
if($page<1) $page=1;

$query=DB::fetch_first("select count(*) as num from ".DB::table('forum_thread').' as a '.$water->getWhereString($fid,$filter));
$num=$query['num'];
$pagesnum=ceil($num/$perpage);//��ҳ��
if($page>$pagesnum) $page=$pagesnum;	
$mpurl = "plugin.php?id=waterfall:waterfall&fid=$fid&filter=$filter&orderby=$orderby";
$mulpage=multi($num, $perpage, $page, $mpurl);

//������������̳���
$sql="select fid,name from ".DB::table('forum_forum')." where fid in (".$water->getForumIDlistString().")";
$query=DB::query($sql);
$forum=$forums=array();
while($forum = DB::fetch($query))
{
	$forums[]=$forum;
}

//��ѡ���ɸѡ������ʽ
$filters=$water->filterArray;
$orderbys=$water->orderByArray;
//��������
$picwidth=$_G['cache']['plugin']['waterfall']['picwidth'];
$orderbydefault=$_G['cache']['plugin']['waterfall']['orderbydefault'];//Ĭ�ϵ�����ʽ

include template('waterfall:waterfall');

?>